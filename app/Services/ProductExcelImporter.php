<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExcelImporter
{
    /**
     * Header keywords used to locate the SKU, name, price and quantity
     * columns, matched case-insensitively against each cell in the header
     * row. Supplier sheets label these columns differently (e.g. "Chassis
     * number" for SKU), so we match on keywords rather than a fixed layout.
     */
    private const HEADER_KEYWORDS = [
        'sku' => ['chassis', 'sku'],
        'name' => ['product name', 'name'],
        'price' => ['price'],
        'quantity' => ['quantity', 'qty'],
    ];

    /**
     * Only the first few rows of a sheet are scanned for the header row.
     */
    private const MAX_HEADER_SEARCH_ROWS = 15;

    /**
     * Import products and stock from a spreadsheet on disk into the given
     * category. Existing products (matched by SKU) are restocked via a new
     * inventory entry; unknown SKUs are created fresh with an opening stock.
     *
     * The file is uploaded to the server in small chunks and reassembled
     * before this runs (see ChunkedUploadStore), so it is always a plain
     * path on disk rather than an UploadedFile tied to a single request.
     *
     * @return array{created: int, restocked: int, skipped: int}
     */
    public function import(string $filePath, Category $category, string $insertedBy): array
    {
        // Supplier sheets often embed a product photo per row (see the "picture"
        // column in the sample invoice); we only read cell values, so skip
        // images/styles on load to avoid exhausting PHP's memory limit.
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new ProductExcelReadFilter());
        $spreadsheet = $reader->load($filePath);

        $created = 0;
        $restocked = 0;
        $skipped = 0;

        DB::transaction(function () use ($spreadsheet, $category, $insertedBy, &$created, &$restocked, &$skipped) {
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($this->parseSheet($sheet) as $row) {
                    match ($this->importRow($row, $category, $insertedBy)) {
                        'created' => $created++,
                        'restocked' => $restocked++,
                        default => $skipped++,
                    };
                }
            }
        });

        return ['created' => $created, 'restocked' => $restocked, 'skipped' => $skipped];
    }

    /**
     * Read a worksheet into a flat list of {sku, name, price, quantity} rows.
     *
     * Supplier sheets commonly merge the SKU/name cells across the row that
     * carries the price and quantity (see the sample proforma invoice), so
     * a blank SKU/name cell inherits the last non-blank value seen above it.
     *
     * @return list<array{sku: string, name: string, price: float, quantity: float}>
     */
    private function parseSheet(Worksheet $sheet): array
    {
        $grid = $sheet->toArray(null, true, true, false);

        $columns = $this->detectColumns($grid);

        if ($columns === null) {
            return [];
        }

        $rows = [];
        $lastSku = null;
        $lastName = null;

        foreach ($grid as $index => $cells) {
            if ($index <= $columns['header_row']) {
                continue;
            }

            $sku = trim((string) ($cells[$columns['sku']] ?? ''));
            $name = trim((string) ($cells[$columns['name']] ?? ''));
            $lastSku = $sku !== '' ? $sku : $lastSku;
            $lastName = $name !== '' ? $name : $lastName;

            $price = $this->toFloat($cells[$columns['price']] ?? null);
            $quantity = $this->toFloat($cells[$columns['quantity']] ?? null);

            if ($price === null || $quantity === null || $quantity <= 0 || ! $lastSku || ! $lastName) {
                continue;
            }

            $rows[] = ['sku' => $lastSku, 'name' => $lastName, 'price' => $price, 'quantity' => $quantity];
        }

        return $rows;
    }

    /**
     * Scan the top of the sheet for a row containing all four required
     * headers and return the 0-indexed column position of each, plus the
     * row they were found on. Returns null if the sheet doesn't match.
     *
     * @param  list<array<int, mixed>>  $grid
     * @return array{header_row: int, sku: int, name: int, price: int, quantity: int}|null
     */
    private function detectColumns(array $grid): ?array
    {
        foreach (array_slice($grid, 0, self::MAX_HEADER_SEARCH_ROWS, true) as $rowIndex => $cells) {
            $found = [];

            foreach ($cells as $colIndex => $value) {
                $text = strtolower(trim((string) $value));

                if ($text === '') {
                    continue;
                }

                foreach (self::HEADER_KEYWORDS as $field => $keywords) {
                    if (isset($found[$field])) {
                        continue;
                    }

                    foreach ($keywords as $keyword) {
                        if (str_contains($text, $keyword)) {
                            $found[$field] = $colIndex;

                            break;
                        }
                    }
                }
            }

            if (count($found) === count(self::HEADER_KEYWORDS)) {
                return ['header_row' => $rowIndex, ...$found];
            }
        }

        return null;
    }

    /**
     * Create a fresh product with opening stock, or add stock to an existing
     * one matched by SKU. Either way an inventory entry is recorded and the
     * product's running total is kept in sync, mirroring how stock intake
     * works elsewhere in the app.
     *
     * @param  array{sku: string, name: string, price: float, quantity: float}  $row
     * @return 'created'|'restocked'
     */
    private function importRow(array $row, Category $category, string $insertedBy): string
    {
        $product = Product::where('sku', $row['sku'])->first();

        if (! $product) {
            $product = Product::create([
                'name' => $row['name'],
                'sku' => $row['sku'],
                'selling_price' => $row['price'],
                'category_id' => $category->id,
                'inserted_by' => $insertedBy,
            ]);
        }

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $row['quantity'],
            'inserted_by' => $insertedBy,
        ]);

        $product->increment('total_qty', $row['quantity']);

        return $product->wasRecentlyCreated ? 'created' : 'restocked';
    }

    /**
     * Parse a cell value into a float, tolerating thousands separators.
     * Returns null for blank or non-numeric cells.
     */
    private function toFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $cleaned = str_replace([',', ' '], '', (string) $value);

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }
}
