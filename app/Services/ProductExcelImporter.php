<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExcelImporter
{
    /**
     * Header keywords used to locate each column, matched case-insensitively
     * against every cell in the header row. Supplier sheets label and order
     * their columns differently, so we match on keywords rather than a fixed
     * layout.
     *
     * The two identifying columns play different roles: the chassis number
     * names the sub category a row belongs to (and through it the category),
     * while the product SKU identifies the product itself and is what an
     * existing product is matched on.
     */
    private const HEADER_KEYWORDS = [
        'sku' => ['product sku', 'sku'],
        'chassis' => ['chassis', 'sub category', 'subcategory'],
        'name' => ['product name', 'name'],
        'variant' => ['variant'],
        'price' => ['price'],
        'quantity' => ['quantity', 'qty'],
    ];

    /**
     * Columns a sheet is allowed to leave out; every other keyword above has
     * to be present for the sheet to be recognised.
     */
    private const OPTIONAL_COLUMNS = ['variant'];

    /**
     * Only the first few rows of a sheet are scanned for the header row.
     */
    private const MAX_HEADER_SEARCH_ROWS = 15;

    /**
     * Import products and stock from a spreadsheet on disk. Existing products
     * (matched by product SKU) are restocked via a new inventory entry;
     * unknown SKUs are created fresh with an opening stock.
     *
     * A row's chassis number names the sub category the product is filed
     * under, and the category that sub category belongs to is applied along
     * with it. Rows whose chassis number matches no sub category are not
     * imported and not guessed at — they are handed back as "pending" so the
     * user can pick a sub category for them and import them in a second pass
     * (see importPending).
     *
     * The file is uploaded to the server in small chunks and reassembled
     * before this runs (see ChunkedUploadStore), so it is always a plain
     * path on disk rather than an UploadedFile tied to a single request.
     *
     * @return array{created: int, restocked: int, pending: list<array{chassis: string, sku: string, name: string, variant: ?string, price: float, quantity: float}>}
     */
    public function import(string $filePath, string $insertedBy): array
    {
        // Supplier sheets often embed a product photo per row (see the "picture"
        // column in the sample invoice); we only read cell values, so skip
        // images/styles on load to avoid exhausting PHP's memory limit.
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new ProductExcelReadFilter);
        $spreadsheet = $reader->load($filePath);

        $subcategories = $this->subcategoriesByName();

        $created = 0;
        $restocked = 0;
        $pending = [];

        DB::transaction(function () use ($spreadsheet, $subcategories, $insertedBy, &$created, &$restocked, &$pending) {
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($this->parseSheet($sheet) as $row) {
                    $subcategory = $subcategories[$this->normalize($row['chassis'])] ?? null;

                    if (! $subcategory) {
                        $pending[] = $row;

                        continue;
                    }

                    match ($this->importRow($row, $subcategory, $insertedBy)) {
                        'created' => $created++,
                        default => $restocked++,
                    };
                }
            }
        });

        return [
            'created' => $created,
            'restocked' => $restocked,
            'pending' => $pending,
        ];
    }

    /**
     * Import rows that import() held back, now that a sub category has been
     * chosen for each chassis number. Rows whose chassis number was left
     * unassigned are simply not imported.
     *
     * @param  list<array{chassis: string, sku: string, name: string, variant: ?string, price: float, quantity: float}>  $rows
     * @param  array<string, Subcategory>  $subcategoryByChassis  keyed by chassis number, in any spelling
     * @return array{created: int, restocked: int}
     */
    public function importPending(array $rows, array $subcategoryByChassis, string $insertedBy): array
    {
        $lookup = [];

        foreach ($subcategoryByChassis as $chassis => $subcategory) {
            $lookup[$this->normalize((string) $chassis)] = $subcategory;
        }

        $created = 0;
        $restocked = 0;

        DB::transaction(function () use ($rows, $lookup, $insertedBy, &$created, &$restocked) {
            foreach ($rows as $row) {
                $subcategory = $lookup[$this->normalize($row['chassis'])] ?? null;

                if (! $subcategory) {
                    continue;
                }

                match ($this->importRow($row, $subcategory, $insertedBy)) {
                    'created' => $created++,
                    default => $restocked++,
                };
            }
        });

        return ['created' => $created, 'restocked' => $restocked];
    }

    /**
     * Active sub categories keyed by their normalized name, which is what the
     * chassis number column maps onto. Names are only unique within a
     * category, so when the same name exists under two categories the oldest
     * one wins — deterministically, rather than by whichever row the query
     * happened to return last.
     *
     * @return array<string, Subcategory>
     */
    private function subcategoriesByName(): array
    {
        return Subcategory::where(['status' => '1', 'close' => '1'])
            ->orderByDesc('id')
            ->get()
            ->keyBy(fn (Subcategory $subcategory) => $this->normalize($subcategory->name))
            ->all();
    }

    /**
     * Read a worksheet into a flat list of import rows.
     *
     * Supplier sheets commonly merge the chassis/name cells across the rows
     * that carry the price and quantity (see the sample proforma invoice), so
     * a blank chassis/name cell inherits the last non-blank value seen above
     * it. The SKU and variant describe an individual row, so they are never
     * inherited — a row without its own SKU cannot identify a product.
     *
     * @return list<array{chassis: string, sku: string, name: string, variant: ?string, price: float, quantity: float}>
     */
    private function parseSheet(Worksheet $sheet): array
    {
        $grid = $sheet->toArray(null, true, true, false);

        $columns = $this->detectColumns($grid);

        if ($columns === null) {
            return [];
        }

        $rows = [];
        $lastChassis = null;
        $lastName = null;

        foreach ($grid as $index => $cells) {
            if ($index <= $columns['header_row']) {
                continue;
            }

            $chassis = trim((string) ($cells[$columns['chassis']] ?? ''));
            $name = trim((string) ($cells[$columns['name']] ?? ''));
            $lastChassis = $chassis !== '' ? $chassis : $lastChassis;
            $lastName = $name !== '' ? $name : $lastName;

            $sku = trim((string) ($cells[$columns['sku']] ?? ''));
            $variant = $columns['variant'] === null
                ? ''
                : trim((string) ($cells[$columns['variant']] ?? ''));

            $price = $this->toFloat($cells[$columns['price']] ?? null);
            $quantity = $this->toFloat($cells[$columns['quantity']] ?? null);

            if ($sku === '' || $price === null || $quantity === null || $quantity <= 0 || ! $lastChassis || ! $lastName) {
                continue;
            }

            $rows[] = [
                'chassis' => $lastChassis,
                'sku' => $sku,
                'name' => $lastName,
                'variant' => $variant === '' ? null : $variant,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }

        return $rows;
    }

    /**
     * Scan the top of the sheet for a row containing all the required headers
     * and return the 0-indexed column position of each, plus the row they were
     * found on. Optional columns come back as null when absent. Returns null
     * if the sheet doesn't match.
     *
     * @param  list<array<int, mixed>>  $grid
     * @return array{header_row: int, sku: int, chassis: int, name: int, variant: ?int, price: int, quantity: int}|null
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

            $missing = array_diff(array_keys(self::HEADER_KEYWORDS), array_keys($found), self::OPTIONAL_COLUMNS);

            if ($missing === []) {
                return ['header_row' => $rowIndex, 'variant' => null, ...$found];
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
     * @param  array{chassis: string, sku: string, name: string, variant: ?string, price: float, quantity: float}  $row
     * @return 'created'|'restocked'
     */
    private function importRow(array $row, Subcategory $subcategory, string $insertedBy): string
    {
        $product = Product::where('sku', $row['sku'])->first();

        if (! $product) {
            $product = Product::create([
                'name' => $row['name'],
                'sku' => $row['sku'],
                'variant' => $row['variant'],
                'selling_price' => $row['price'],
                'category_id' => $subcategory->category_id,
                'subcategory_id' => $subcategory->id,
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
     * Reduce a name to a comparable form, so that trailing spaces, double
     * spaces or a different capitalisation in the sheet still match the sub
     * category as it was typed into the system.
     */
    private function normalize(string $value): string
    {
        return mb_strtolower((string) preg_replace('/\s+/', ' ', trim($value)));
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
