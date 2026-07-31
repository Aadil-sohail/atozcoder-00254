<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleItem;
use App\Support\ServerTable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    /**
     * Warranty expiry is not stored: it is the sale date plus the product's
     * warranty length, so it is computed in SQL wherever it is filtered,
     * sorted or displayed.
     */
    private const EXPIRY_SQL = 'DATE_ADD(sales.sale_date, INTERVAL products.warranty_months MONTH)';

    public function __construct()
    {
        $this->middleware('permission:view sales')->only(['index', 'data']);
    }

    /**
     * List sold products that carry a warranty, with expiry computed from the sale date.
     */
    public function index(Request $request): View
    {
        $recordCount = $this->query($request)->count();

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::whereNotNull('warranty_months')->orderBy('name')->get(['id', 'name']);

        return view('warranties.index', compact('recordCount', 'customers', 'products'));
    }

    /**
     * Rows for the warranties grid. The filter form's values ride along with
     * every draw, so filtering and paging stay in step with one another.
     */
    public function data(Request $request): JsonResponse
    {
        return ServerTable::make($request, $this->query($request), [
            'invoice_no' => 'sales.invoice_no',
            'customer_name' => 'customers.name',
            'product_name' => 'products.name',
            'sale_date' => 'sales.sale_date',
            // Sortable only — all three are computed in the SELECT, and MySQL
            // will not accept an alias in a WHERE clause.
            'qty' => ['order' => 'remaining_qty'],
            'expiry' => ['order' => 'warranty_expiry'],
            // Days left is just the expiry seen from today, so it sorts on it.
            'days_left' => ['order' => 'warranty_expiry'],
        ], function (SaleItem $item) {
            $expiry = Carbon::parse($item->warranty_expiry)->startOfDay();
            $daysLeft = (int) now()->startOfDay()->diffInDays($expiry, false);
            $fullyReturned = $item->returned_qty >= $item->quantity;
            $cell = compact('item', 'expiry', 'daysLeft', 'fullyReturned');

            return [
                'invoice_no' => view('warranties.partials.cells.invoice', compact('item'))->render(),
                'customer_name' => e($item->customer_name ?? '—'),
                'product_name' => e($item->product_name ?? '—'),
                'sale_date' => e(Carbon::parse($item->sale_date)->format('M d, Y')),
                'qty' => view('warranties.partials.cells.qty', compact('item'))->render(),
                'status' => view('warranties.partials.cells.status', $cell)->render(),
                'expiry' => e($expiry->format('M d, Y')),
                'days_left' => view('warranties.partials.cells.days-left', $cell)->render(),
            ];
        });
    }

    /**
     * Sold line items carrying a warranty, narrowed by the filter form.
     */
    private function query(Request $request)
    {
        return SaleItem::query()
            ->select('sale_items.*', 'customers.name as customer_name', 'products.name as product_name',
                'sales.invoice_no', 'sales.sale_date')
            ->selectRaw(self::EXPIRY_SQL.' as warranty_expiry')
            ->selectRaw('(sale_items.quantity - sale_items.returned_qty) as remaining_qty')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->whereNotNull('products.warranty_months')
            ->when($request->filled('customer_id'), fn ($q) => $q->where('sales.customer_id', $request->customer_id))
            ->when($request->filled('product_id'), fn ($q) => $q->where('sale_items.product_id', $request->product_id))
            ->when($request->filled('expiry_from'), fn ($q) => $q->whereRaw(self::EXPIRY_SQL.' >= ?', [$request->expiry_from]))
            ->when($request->filled('expiry_to'), fn ($q) => $q->whereRaw(self::EXPIRY_SQL.' <= ?', [$request->expiry_to]));
    }
}
