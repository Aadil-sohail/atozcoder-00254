<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view sales')->only('index');
    }

    /**
     * List sold products that carry a warranty, with expiry computed from the sale date.
     */
    public function index(Request $request): View
    {
        $expirySql = 'DATE_ADD(sales.sale_date, INTERVAL products.warranty_months MONTH)';

        $warranties = SaleItem::query()
            ->select('sale_items.*')
            ->selectRaw("{$expirySql} as warranty_expiry")
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereNotNull('products.warranty_months')
            ->when($request->filled('customer_id'), fn ($q) => $q->where('sales.customer_id', $request->customer_id))
            ->when($request->filled('product_id'), fn ($q) => $q->where('sale_items.product_id', $request->product_id))
            ->when($request->filled('expiry_from'), fn ($q) => $q->whereRaw("{$expirySql} >= ?", [$request->expiry_from]))
            ->when($request->filled('expiry_to'), fn ($q) => $q->whereRaw("{$expirySql} <= ?", [$request->expiry_to]))
            ->with(['sale.customer', 'product'])
            ->orderByRaw("{$expirySql} asc")
            ->get();

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::whereNotNull('warranty_months')->orderBy('name')->get(['id', 'name']);

        return view('warranties.index', compact('warranties', 'customers', 'products'));
    }
}
