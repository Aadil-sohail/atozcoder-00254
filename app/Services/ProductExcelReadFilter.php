<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Bounds which cells PhpSpreadsheet actually parses while loading a file.
 *
 * A supplier spreadsheet can declare a "used range" far larger than its
 * visible data — a single stray formatted cell or floating image is enough —
 * which makes an unbounded read try to materialize millions of phantom cells
 * and exhaust memory. Capping the read window keeps memory bounded no matter
 * what dimensions the file itself claims.
 */
class ProductExcelReadFilter implements IReadFilter
{
    private const MAX_ROW = 5000;

    private const MAX_COLUMN_INDEX = 40; // column AN

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
    {
        return $row <= self::MAX_ROW && Coordinate::columnIndexFromString($columnAddress) <= self::MAX_COLUMN_INDEX;
    }
}
