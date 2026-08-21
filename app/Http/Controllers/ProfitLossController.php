<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Support\ServerTable;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use stdClass;

/**
 * Profit & loss per product.
 *
 * One row per product: what a unit costs, what it actually sold for on
 * average, how many units left the shelf, and the money made or lost on them.
 *
 * Three things the numbers have to respect, which is why they are built in SQL
 * rather than added up in PHP:
 *
 *  - A product is rarely sold at one price. The sale line carries the price it
 *    went out at, so the average is revenue over units rather than the price
 *    sitting on the product record.
 *  - Returned units were never really sold, so they are taken out entirely:
 *    quantity is net of sale_items.returned_qty for revenue and for cost of
 *    goods alike, and a line returned in full drops out of the report rather
 *    than sitting in it as a sale worth nothing.
 *  - A discount is recorded once for a whole sale, not per line. It is shared
 *    back across that sale's kept lines in proportion to what they are worth,
 *    so a discounted invoice lowers the margin of everything on it instead of
 *    quietly inflating profit. Sharing it over what is left after a return is
 *    what ReturnController::refreshSaleTotal() does too, so a sale's lines
 *    here always add back up to its sales.total_amount.
 *
 * Cost is products.cost_price, the only cost the system records; there is no
 * per-purchase cost history to average over, so it is the unit cost charged
 * against every unit sold.
 */
class ProfitLossController extends Controller
{
    private const DISCOUNT_FACTOR = 'GREATEST(0, COALESCE(1 - COALESCE(sales.discount, 0) / NULLIF(sale_totals.gross, 0), 1))';

    /** Units that were really sold, i.e. the ones that did not come back. */
    private const NET_QTY = '(sale_items.quantity - sale_items.returned_qty)';

    public function __construct()
    {
        $this->middleware('permission:view sales')->only(['index', 'data', 'breakdown']);
    }

    /**
     * The report page: KPI totals for the current filters plus the filter form
     * itself. The rows arrive from data() a page at a time.
     */
    public function index(Request $request): View
    {
        $totals = $this->totals($request);

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $subcategories = Subcategory::orderBy('name')->get(['id', 'name', 'category_id']);

        return view('profit-loss.index', compact('totals', 'categories', 'subcategories'));
    }

    /**
     * Rows for the profit & loss grid. The filter form's values ride along
     * with every draw, so filtering and paging stay in step.
     */
    public function data(Request $request): JsonResponse
    {
        return ServerTable::make($request, $this->query($request), [
            'product' => 'products.name',
            'category' => 'categories.name',
            // Not columns in the grid, but still worth finding a product by.
            'sku' => ['search' => 'products.sku'],
            'subcategory' => ['search' => 'subcategories.name'],
            // Sortable only: each of these is a SELECT alias, and MySQL takes
            // an alias in ORDER BY but not in WHERE.
            'cost_price' => ['order' => 'products.cost_price'],
            'avg_price' => ['order' => 'avg_selling_price'],
            'qty_net' => ['order' => 'qty_net'],
            'revenue' => ['order' => 'revenue'],
            'cogs' => ['order' => 'cogs'],
            'profit' => ['order' => 'profit'],
            'margin' => ['order' => 'margin'],
        ], fn (Product $product) => [
            'product' => view('profit-loss.partials.cells.product', compact('product'))->render(),
            'category' => e($product->category_name ?? '—'),
            'cost_price' => view('profit-loss.partials.cells.cost-price', compact('product'))->render(),
            'avg_price' => view('profit-loss.partials.cells.avg-price', compact('product'))->render(),
            'qty_net' => view('profit-loss.partials.cells.qty', compact('product'))->render(),
            'revenue' => number_format((float) $product->revenue, 2),
            'cogs' => number_format((float) $product->cogs, 2),
            'profit' => view('profit-loss.partials.cells.profit', compact('product'))->render(),
            'margin' => view('profit-loss.partials.cells.margin', compact('product'))->render(),
            'actions' => view('profit-loss.partials.cells.actions', compact('product'))->render(),
        ]);
    }

    /**
     * Every sale line for one product, so a row can be opened to see the
     * individual sales its average was built from.
     *
     * Kept in step with the grid: same period, same netting of returns, and
     * fully returned lines left out, so these lines add up to the row above.
     */
    public function breakdown(Request $request, Product $product): JsonResponse
    {
        $cost = (float) $product->cost_price;

        $netRevenue = self::NET_QTY.' * sale_items.selling_price * '.self::DISCOUNT_FACTOR;

        $lines = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->leftJoinSub($this->saleTotals(), 'sale_totals', 'sale_totals.sale_id', '=', 'sale_items.sale_id')
            ->where('sale_items.product_id', $product->id)
            ->whereRaw(self::NET_QTY.' > 0')
            ->select('sales.id as sale_id', 'sales.invoice_no', 'sales.sale_date', 'customers.name as customer_name',
                'sale_items.selling_price')
            ->selectRaw(self::NET_QTY.' as qty_net')
            ->selectRaw($netRevenue.' as revenue')
            ->selectRaw(self::NET_QTY.' * ? as cogs', [$cost])
            ->selectRaw($netRevenue.' - '.self::NET_QTY.' * ? as profit', [$cost])
            ->when($request->filled('date_from'), fn ($q) => $q->where('sales.sale_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->where('sales.sale_date', '<=', $request->date_to))
            ->orderByDesc('sales.sale_date')
            ->orderByDesc('sales.id')
            ->get();

        return response()->json([
            'html' => view('profit-loss.partials.breakdown', compact('product', 'lines'))->render(),
        ]);
    }

    /**
     * KPI totals for the filtered report.
     *
     * Summed from the grid's own query, so the tiles can never disagree with
     * the rows underneath them: the report query becomes a derived table and
     * its already-computed columns are added up once, in SQL.
     */
    private function totals(Request $request): stdClass
    {
        return DB::query()->fromSub($this->query($request), 'report')->selectRaw(
            'COUNT(*) as products,
             COALESCE(SUM(qty_net), 0) as units,
             COALESCE(SUM(revenue), 0) as revenue,
             COALESCE(SUM(cogs), 0) as cogs,
             COALESCE(SUM(profit), 0) as profit,
             COUNT(CASE WHEN profit < 0 THEN 1 END) as loss_makers'
        )->first();
    }

    /**
     * One row per product, with that product's sales folded in.
     *
     * The sales are aggregated per product first and then joined, rather than
     * joined and then grouped: a grouped query would break the row counts that
     * paging depends on, and this keeps the result exactly one row per product.
     */
    private function query(Request $request): Builder
    {
        // Parenthesised: it is divided by revenue further down, and division
        // binds tighter than the subtraction it is made of.
        $profit = '(COALESCE(sold.revenue, 0) - COALESCE(sold.qty_net, 0) * COALESCE(products.cost_price, 0))';

        $query = Product::query()
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->leftJoinSub($this->salesByProduct($request), 'sold', 'sold.product_id', '=', 'products.id')
            ->select('products.*', 'categories.name as category_name', 'subcategories.name as subcategory_name')
            ->selectRaw('COALESCE(sold.qty_net, 0) as qty_net')
            ->selectRaw('COALESCE(sold.sale_count, 0) as sale_count')
            ->selectRaw('COALESCE(sold.revenue, 0) as revenue')
            ->selectRaw('sold.min_price as min_price')
            ->selectRaw('sold.max_price as max_price')
            ->selectRaw('sold.last_sold_at as last_sold_at')
            // The cost of the units actually sold, at the product's unit cost.
            ->selectRaw('COALESCE(sold.qty_net, 0) * COALESCE(products.cost_price, 0) as cogs')
            ->selectRaw($profit.' as profit')
            // What a unit really went out at, which a mix of prices makes
            // different from products.selling_price.
            ->selectRaw('COALESCE(sold.revenue, 0) / NULLIF(sold.qty_net, 0) as avg_selling_price')
            ->selectRaw($profit.' / NULLIF(sold.revenue, 0) * 100 as margin')
            ->when($request->filled('category_id'), fn ($q) => $q->where('products.category_id', $request->category_id))
            ->when($request->filled('subcategory_id'), fn ($q) => $q->where('products.subcategory_id', $request->subcategory_id));

        // A product that never sold in the period carries nothing but zeroes,
        // so it stays out of the report unless it is asked for.
        if ($request->input('show') !== 'all') {
            $query->whereNotNull('sold.product_id');
        }

        // Winners or losers only — what the two headline tiles drill into.
        if ($request->input('result') === 'profit') {
            $query->whereRaw($profit.' > 0');
        } elseif ($request->input('result') === 'loss') {
            $query->whereRaw($profit.' < 0');
        }

        return $query;
    }

    /**
     * Sales rolled up per product over the filtered date range.
     *
     * A line the customer sent back in full is dropped outright rather than
     * carried as a row of zeroes: it earned nothing and cost nothing, so it
     * has no place in a profit report, and leaving it in would still have it
     * counted as a sale.
     */
    private function salesByProduct(Request $request): QueryBuilder
    {
        return DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->leftJoinSub($this->saleTotals(), 'sale_totals', 'sale_totals.sale_id', '=', 'sale_items.sale_id')
            ->whereRaw(self::NET_QTY.' > 0')
            ->when($request->filled('date_from'), fn ($q) => $q->where('sales.sale_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->where('sales.sale_date', '<=', $request->date_to))
            ->groupBy('sale_items.product_id')
            ->select('sale_items.product_id')
            ->selectRaw('SUM('.self::NET_QTY.') as qty_net')
            ->selectRaw('SUM('.self::NET_QTY.' * sale_items.selling_price * '.self::DISCOUNT_FACTOR.') as revenue')
            ->selectRaw('COUNT(DISTINCT sale_items.sale_id) as sale_count')
            ->selectRaw('MIN(sale_items.selling_price) as min_price')
            ->selectRaw('MAX(sale_items.selling_price) as max_price')
            ->selectRaw('MAX(sales.sale_date) as last_sold_at');
    }

    /**
     * What each sale's kept lines add up to before its discount — the
     * denominator that discount is shared out over.
     *
     * Net of returns rather than sale_items.subtotal, which is never rewritten
     * when a return comes in. This is the same figure ReturnController's
     * refreshSaleTotal() takes the discount off, so a sale's lines here always
     * add back up to the sales.total_amount the returns module left behind.
     */
    private function saleTotals(): QueryBuilder
    {
        return DB::table('sale_items')
            ->groupBy('sale_items.sale_id')
            ->select('sale_items.sale_id')
            ->selectRaw('SUM('.self::NET_QTY.' * sale_items.selling_price) as gross');
    }
}
