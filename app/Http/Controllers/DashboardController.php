<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use stdClass;

/**
 * Builds the dashboard overview.
 *
 * Every widget below is a single aggregate query — counts, sums and buckets are
 * folded into one round trip with conditional aggregation rather than pulling
 * rows into PHP. Widgets the user has no permission to see are never queried.
 */
class DashboardController extends Controller
{
    /** Units on hand at or below this level flag a product as running low. */
    private const LOW_STOCK = 5;

    /** Months plotted on the revenue trend (also scopes the category / top-product widgets). */
    private const TREND_MONTHS = 12;

    public function index(): View
    {
        $user = auth()->user();

        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $prevStart = $monthStart->copy()->subMonth();
        $trendStart = $monthStart->copy()->subMonths(self::TREND_MONTHS - 1);

        $canSales = $user->can('view sales');
        $canProducts = $user->can('view products');

        return view('dashboard', [
            'monthLabel' => $monthStart->format('F Y'),
            'today' => $today,
            'sales' => $canSales ? $this->salesSummary($today, $monthStart, $prevStart) : null,
            'trend' => $canSales ? $this->revenueTrend($trendStart) : null,
            'categories' => $canSales ? $this->revenueByCategory($trendStart) : null,
            'topProducts' => $canSales ? $this->topProducts($trendStart) : null,
            'recentSales' => $canSales ? $this->recentSales() : null,
            'returns' => $user->can('view returns') ? $this->returnsSummary($monthStart) : null,
            'stock' => $canProducts ? $this->stockSummary() : null,
            'lowStock' => $canProducts ? $this->lowStockProducts() : null,
            'customers' => $user->can('view customers') ? $this->customersSummary($monthStart) : null,
            'ebay' => $user->can('view ebay stores') ? $this->ebaySummary() : null,
        ]);
    }

    /**
     * All-time, today, this-month and last-month sale figures in one pass,
     * plus the month-over-month deltas the KPI tiles render.
     */
    private function salesSummary(Carbon $today, Carbon $monthStart, Carbon $prevStart): stdClass
    {
        [$d, $m, $p] = [$today->toDateString(), $monthStart->toDateString(), $prevStart->toDateString()];

        $row = DB::table('sales')->selectRaw(
            'COUNT(*) as orders,
             COALESCE(SUM(total_amount), 0) as revenue,
             COUNT(CASE WHEN sale_date = ? THEN 1 END) as today_orders,
             COALESCE(SUM(CASE WHEN sale_date = ? THEN total_amount END), 0) as today_revenue,
             COUNT(CASE WHEN sale_date >= ? THEN 1 END) as month_orders,
             COALESCE(SUM(CASE WHEN sale_date >= ? THEN total_amount END), 0) as month_revenue,
             COUNT(CASE WHEN sale_date >= ? AND sale_date < ? THEN 1 END) as prev_orders,
             COALESCE(SUM(CASE WHEN sale_date >= ? AND sale_date < ? THEN total_amount END), 0) as prev_revenue,
             COUNT(CASE WHEN ebay_order_id IS NOT NULL AND sale_date >= ? THEN 1 END) as month_ebay_orders',
            [$d, $d, $m, $m, $p, $m, $p, $m, $m]
        )->first();

        $row->revenue_delta = $this->percentChange($row->month_revenue, $row->prev_revenue);
        $row->orders_delta = $this->percentChange($row->month_orders, $row->prev_orders);

        return $row;
    }

    /**
     * Revenue per month for the trend line, padded so months without a single
     * sale still plot as zero instead of collapsing the x-axis.
     *
     * @return list<array{label: string, revenue: float}>
     */
    private function revenueTrend(Carbon $from): array
    {
        $byMonth = DB::table('sales')
            ->selectRaw("DATE_FORMAT(sale_date, '%Y-%m') as ym, SUM(total_amount) as revenue")
            ->where('sale_date', '>=', $from->toDateString())
            ->groupBy('ym')
            ->pluck('revenue', 'ym');

        return array_map(function (int $offset) use ($from, $byMonth) {
            $month = $from->copy()->addMonths($offset);

            return [
                'label' => $month->format('M y'),
                'revenue' => (float) ($byMonth[$month->format('Y-m')] ?? 0),
            ];
        }, range(0, self::TREND_MONTHS - 1));
    }

    /**
     * Revenue share per category. Six colour slots exist, so the five biggest
     * keep their identity and the tail folds into a single "Other" segment.
     *
     * @return list<array{name: string, revenue: float}>
     */
    private function revenueByCategory(Carbon $from): array
    {
        $rows = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->where('sales.sale_date', '>=', $from->toDateString())
            ->selectRaw("COALESCE(categories.name, 'Uncategorised') as name, SUM(sale_items.subtotal) as revenue")
            ->groupBy('name')
            ->orderByDesc('revenue')
            ->get();

        $slices = $rows->take(5)
            ->map(fn ($row) => ['name' => $row->name, 'revenue' => (float) $row->revenue])
            ->values();

        if ($rows->count() > 5) {
            $slices->push(['name' => 'Other', 'revenue' => (float) $rows->skip(5)->sum('revenue')]);
        }

        return $slices->all();
    }

    /**
     * Best sellers by units moved over the trend window.
     */
    private function topProducts(Carbon $from): Collection
    {
        return DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->where('sales.sale_date', '>=', $from->toDateString())
            ->selectRaw('products.id, products.name, SUM(sale_items.quantity) as units, SUM(sale_items.subtotal) as revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('units')
            ->limit(5)
            ->get();
    }

    private function recentSales(): EloquentCollection
    {
        return Sale::with('customer:id,name')
            ->latest('id')
            ->limit(6)
            ->get(['id', 'customer_id', 'invoice_no', 'sale_date', 'total_amount', 'ebay_order_id']);
    }

    /**
     * Catalogue size, stock on hand and the two stock alert buckets in one pass.
     * Stock is clamped at zero so an oversold product cannot subtract from the
     * inventory valuation.
     */
    private function stockSummary(): stdClass
    {
        return DB::table('products')
            ->selectRaw(
                'COUNT(*) as products,
                 COALESCE(SUM(GREATEST(total_qty - sold_qty, 0)), 0) as units,
                 COALESCE(SUM(GREATEST(total_qty - sold_qty, 0) * COALESCE(cost_price, 0)), 0) as cost_value,
                 COALESCE(SUM(GREATEST(total_qty - sold_qty, 0) * COALESCE(selling_price, 0)), 0) as retail_value,
                 COUNT(CASE WHEN (total_qty - sold_qty) <= 0 THEN 1 END) as out_of_stock,
                 COUNT(CASE WHEN (total_qty - sold_qty) > 0 AND (total_qty - sold_qty) <= ? THEN 1 END) as low_stock',
                [self::LOW_STOCK]
            )
            ->where('status', '1')
            ->first();
    }

    /**
     * The products closest to running out — the ones worth restocking today.
     */
    private function lowStockProducts(): Collection
    {
        return DB::table('products')
            ->selectRaw('id, name, sku, (total_qty - sold_qty) as stock')
            ->where('status', '1')
            ->whereRaw('(total_qty - sold_qty) <= ?', [self::LOW_STOCK])
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(6)
            ->get();
    }

    private function returnsSummary(Carbon $monthStart): stdClass
    {
        return DB::table('sale_returns')
            ->selectRaw('COUNT(*) as total, COUNT(CASE WHEN return_date >= ? THEN 1 END) as month', [$monthStart->toDateString()])
            ->first();
    }

    private function customersSummary(Carbon $monthStart): stdClass
    {
        return DB::table('customers')
            ->selectRaw('COUNT(*) as total, COUNT(CASE WHEN created_at >= ? THEN 1 END) as new_this_month', [$monthStart->toDateTimeString()])
            ->first();
    }

    private function ebaySummary(): stdClass
    {
        $summary = DB::table('ebay_listings')->selectRaw(
            "COUNT(*) as total,
             COUNT(CASE WHEN sync_status = 'synced' THEN 1 END) as synced,
             COUNT(CASE WHEN sync_status = 'failed' THEN 1 END) as failed,
             COUNT(CASE WHEN sync_status IN ('pending', 'syncing') THEN 1 END) as pending"
        )->first();

        $summary->stores = DB::table('ebay_accounts')->where('status', '1')->count();

        return $summary;
    }

    /**
     * Month-over-month change, or null when there is no prior period to compare
     * against — a jump from zero is not a meaningful percentage.
     */
    private function percentChange(float|int $current, float|int $previous): ?float
    {
        return $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : null;
    }
}
