<?php

namespace App\Services;

use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExcelImporter
{
    
    private const HEADER_KEYWORDS = [
        'listing_id' => ['ebay listing id', 'listing id', 'ebay item id', 'item id', 'item number', 'ebay id'],
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
     * Import a supplier sheet into the system, creating products and stock
     * entries for each row. Rows whose chassis number doesn't match an active
     * sub category are held back for later import.
     *
     */
    public function import(string $filePath, EbayAccount $account, string $insertedBy): array
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

        DB::transaction(function () use ($spreadsheet, $subcategories, $account, $insertedBy, &$created, &$restocked, &$pending) {
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                foreach ($this->parseSheet($sheet) as $row) {
                    $subcategory = $subcategories[$this->normalize($row['chassis'])] ?? null;

                    if (! $subcategory) {
                        $pending[] = $row;

                        continue;
                    }

                    match ($this->importRow($row, $subcategory, $account, $insertedBy)) {
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
     * @param  list<array{chassis: string, listing_id: string, name: string, variant: ?string, price: float, quantity: float}>  $rows
     * @param  array<string, Subcategory>  $subcategoryByChassis  keyed by chassis number, in any spelling
     * @return array{created: int, restocked: int}
     */
    public function importPending(array $rows, array $subcategoryByChassis, EbayAccount $account, string $insertedBy): array
    {
        $lookup = [];

        foreach ($subcategoryByChassis as $chassis => $subcategory) {
            $lookup[$this->normalize((string) $chassis)] = $subcategory;
        }

        $created = 0;
        $restocked = 0;

        DB::transaction(function () use ($rows, $lookup, $account, $insertedBy, &$created, &$restocked) {
            foreach ($rows as $row) {
                $subcategory = $lookup[$this->normalize($row['chassis'])] ?? null;

                if (! $subcategory) {
                    continue;
                }

                match ($this->importRow($row, $subcategory, $account, $insertedBy)) {
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
     * it. The listing id and variant describe an individual row, so they are
     * never inherited — a row without its own listing id cannot identify a
     * product.
     *
     * @return list<array{chassis: string, listing_id: string, name: string, variant: ?string, price: float, quantity: float}>
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

            $listingId = $this->toIdentifier($cells[$columns['listing_id']] ?? null);
            $variant = $columns['variant'] === null
                ? ''
                : trim((string) ($cells[$columns['variant']] ?? ''));

            $price = $this->toFloat($cells[$columns['price']] ?? null);
            $quantity = $this->toFloat($cells[$columns['quantity']] ?? null);

            if ($listingId === '' || $price === null || $quantity === null || $quantity <= 0 || ! $lastChassis || ! $lastName) {
                continue;
            }

            $rows[] = [
                'chassis' => $lastChassis,
                'listing_id' => $listingId,
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
     * @return array{header_row: int, listing_id: int, chassis: int, name: int, variant: ?int, price: int, quantity: int}|null
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
     * one matched by its eBay listing id. Either way an inventory entry is
     * recorded and the product's running total is kept in sync, mirroring how
     * stock intake works elsewhere in the app.
     *
     * The listing id is matched across every store rather than just $account:
     * it is unique on eBay, so a product already linked under one store must
     * not be duplicated because the import was run against another.
     *
     * @param  array{chassis: string, listing_id: string, name: string, variant: ?string, price: float, quantity: float}  $row
     * @return 'created'|'restocked'
     */
    private function importRow(array $row, Subcategory $subcategory, EbayAccount $account, string $insertedBy): string
    {
        $listing = EbayListing::where('listing_id', $row['listing_id'])->first();
        $product = $listing?->product;

        if ($listing && ! $product) {
            // Link left behind by a product deleted outside the app: drop it so
            // the listing id can be imported as a fresh product below.
            $listing->delete();
        }

        if (! $product) {
            $product = Product::create([
                'name' => $row['name'],
                'variant' => $row['variant'],
                'cost_price' => $row['price'],
                'category_id' => $subcategory->category_id,
                'subcategory_id' => $subcategory->id,
                'inserted_by' => $insertedBy,
            ]);

            $this->linkListing($product, $account, $row['listing_id'], $insertedBy);
        }

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $row['quantity'],
            'inserted_by' => $insertedBy,
        ]);

        $product->increment('total_qty', $row['quantity']);

        return $product->wasRecentlyCreated ? 'created' : 'restocked';
    }

   
    private function linkListing(Product $product, EbayAccount $account, string $listingId, string $insertedBy): void
    {
        EbayListing::create([
            'product_id' => $product->id,
            'ebay_account_id' => $account->id,
            'sku' => 'PRD-'.$product->id,
            'listing_id' => $listingId,
            'sync_status' => 'synced',
            'inserted_by' => $insertedBy,
        ]);
    }

    /**
     * Read an identifier cell as plain text. A listing id is all digits, so a
     * spreadsheet hands it over as a number — casting that straight to a string
     * would leave "1105862345.0" or exponent notation in place of the id.
     */
    private function toIdentifier(mixed $value): string
    {
        if (is_float($value) || is_int($value)) {
            return rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.');
        }

        return trim((string) $value);
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
