<?php

namespace App\Support;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Server-side data source for a DataTables grid.
 *
 * A DataTables table normally ships every row to the browser and filters,
 * sorts and pages them there, which stops scaling the moment a table holds
 * more than a few thousand rows. Pointing the grid at one of these endpoints
 * instead ("serverSide: true") keeps the work in SQL: only the rows for the
 * page being viewed are ever queried or rendered.
 *
 * Usage from a controller — the query decides what is visible, the column map
 * decides what can be searched and sorted, and the callback renders one row:
 *
 *     return ServerTable::make($request, $query, [
 *         'name' => 'products.name',            // key sent to DataTables => sortable/searchable DB column
 *         'category_name' => 'categories.name', // joined columns work the same way
 *         'stock' => ['order' => 'available_stock'], // sortable only — see below
 *         'actions' => null,                    // present but neither sorted nor searched
 *     ], fn (Product $product) => [
 *         'name' => e($product->name),
 *         'category_name' => e($product->category_name),
 *         'actions' => view('products.partials.cells.actions', compact('product'))->render(),
 *     ]);
 *
 * Cell values are returned as HTML, so anything interpolated from the database
 * must be escaped — with e() for plain text, or by rendering a Blade partial.
 *
 * A column defined as a plain string is both sortable and searchable. Give it
 * ['order' => …] or ['search' => …] instead to allow only one of the two —
 * which a SELECT alias such as a computed total needs, since MySQL accepts an
 * alias in ORDER BY but not in WHERE. A 'search' entry may also be a DB::raw()
 * expression, so a value selected as a subquery can still be searched:
 *
 *     'listing_ids' => ['order' => 'listing_ids', 'search' => DB::raw($subquery)],
 *
 * Two rules for the query passed in:
 *  - it must not multiply rows (a hasMany join would break the row counts);
 *    join one-to-one tables, and eager-load the rest with with().
 *  - only columns named in the map can be ordered or searched, which is what
 *    keeps a crafted request from reaching arbitrary SQL.
 */
class ServerTable
{
    /**
     * Build the JSON payload DataTables expects for one draw.
     *
     * @param  Builder  $query  rows the user is allowed to see, before paging
     * @param  array<string, string|array{order?: string, search?: string|Expression}|null>  $columns  DataTables column key => DB column
     * @param  Closure  $row  receives one model, returns that row's cells keyed by column key
     */
    public static function make(Request $request, Builder $query, array $columns, Closure $row): JsonResponse
    {
        [$orderable, $searchable] = self::resolveColumns($columns);

        $recordsTotal = (clone $query)->count();

        self::applySearch($request, $query, $searchable);

        $recordsFiltered = (clone $query)->count();

        self::applyOrder($request, $query, $orderable);
        self::applyPaging($request, $query);

        return response()->json([
            // Echoed back so DataTables can discard responses that arrive out
            // of order — a slow page 1 landing after a fast page 2.
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $query->get()->map($row)->values(),
        ]);
    }

    /**
     * Split the column map into what may be ordered and what may be searched.
     *
     * A string allows both. ['order' => …] / ['search' => …] allow just one,
     * which is how a SELECT alias stays sortable without being dropped into a
     * WHERE clause that the database would reject.
     *
     * @param  array<string, string|array{order?: string, search?: string|Expression}|null>  $columns
     * @return array{0: array<string, string>, 1: array<string, string|Expression>}
     */
    private static function resolveColumns(array $columns): array
    {
        $orderable = [];
        $searchable = [];

        foreach ($columns as $key => $definition) {
            if (is_string($definition)) {
                $orderable[$key] = $definition;
                $searchable[$key] = $definition;

                continue;
            }

            if (is_array($definition)) {
                isset($definition['order']) && $orderable[$key] = $definition['order'];
                isset($definition['search']) && $searchable[$key] = $definition['search'];
            }
        }

        return [$orderable, $searchable];
    }

    /**
     * Apply the search box to every searchable column at once, wrapped so the
     * OR group cannot leak past the query's own constraints.
     *
     * @param  array<string, string|Expression>  $sortable
     */
    private static function applySearch(Request $request, Builder $query, array $sortable): void
    {
        $term = trim((string) $request->input('search.value', ''));

        if ($term === '') {
            return;
        }

        // A column the grid marked as non-searchable stays out of the OR group.
        $searchable = array_filter(
            $sortable,
            fn (string $key) => self::columnFlag($request, $key, 'searchable') !== false,
            ARRAY_FILTER_USE_KEY
        );

        if ($searchable === []) {
            return;
        }

        // Escaped so a literal % or _ typed into the search box is matched as
        // itself rather than as a wildcard.
        $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $term).'%';

        $query->where(function (Builder $query) use ($searchable, $pattern) {
            foreach ($searchable as $column) {
                $query->orWhere($column, 'like', $pattern);
            }
        });
    }

    /**
     * Order by the columns the grid asked for, ignoring anything not in the
     * column map so the ORDER BY can only ever name a known column.
     *
     * @param  array<string, string>  $sortable
     */
    private static function applyOrder(Request $request, Builder $query, array $sortable): void
    {
        $ordered = false;

        foreach ((array) $request->input('order', []) as $order) {
            $key = $request->input("columns.{$order['column']}.data");
            $column = $sortable[$key] ?? null;

            if ($column === null || self::columnFlag($request, $key, 'orderable') === false) {
                continue;
            }

            $query->orderBy($column, ($order['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc');
            $ordered = true;
        }

        if (! $ordered) {
            // Paging is only stable with a deterministic order, so fall back to
            // the first sortable column rather than leaving it to the database.
            $query->orderBy(reset($sortable) ?: 'id');
        }
    }

    private static function applyPaging(Request $request, Builder $query): void
    {
        $length = (int) $request->input('length', 10);

        // DataTables sends -1 for "show all", which means no limit at all.
        if ($length > 0) {
            $query->skip(max(0, (int) $request->input('start', 0)))->take($length);
        }
    }

    /**
     * Read one of DataTables' per-column booleans, which arrive as the strings
     * "true"/"false". Returns null when the grid didn't send the column.
     */
    private static function columnFlag(Request $request, string $key, string $flag): ?bool
    {
        foreach ((array) $request->input('columns', []) as $column) {
            if (($column['data'] ?? null) === $key) {
                return filter_var($column[$flag] ?? true, FILTER_VALIDATE_BOOL);
            }
        }

        return null;
    }
}
