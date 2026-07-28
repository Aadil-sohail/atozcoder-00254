<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view sales')->only(['index', 'show']);
        $this->middleware('permission:create sales')->only(['create', 'store']);
        $this->middleware('permission:delete sales')->only('destroy');
    }

    public function index(): View
    {
        $sales = Sale::with('customer')->latest()->get();

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get(['id', 'name', 'sku', 'selling_price', 'total_qty', 'sold_qty']);
        $nextInvoice = $this->generateInvoiceNo();

        return view('sales.create', compact('customers', 'products', 'nextInvoice'));
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        $items    = $request->input('items');
        $discount = (float) $request->input('discount', 0);

        $subtotal = array_reduce($items, function ($carry, $item) {
            return $carry + ($item['quantity'] * $item['selling_price']);
        }, 0);

        $total = max(0, $subtotal - $discount);

        $sale = Sale::create([
            'customer_id'  => $request->customer_id,
            'invoice_no'   => $request->invoice_no,
            'sale_date'    => $request->sale_date,
            'discount'     => $discount,
            'total_amount' => $total,
            'inserted_by'  => auth()->user()->name,
        ]);

        foreach ($items as $item) {
            $sale->saleItems()->create([
                'product_id'    => $item['product_id'],
                'quantity'      => $item['quantity'],
                'selling_price' => $item['selling_price'],
                'subtotal'      => $item['quantity'] * $item['selling_price'],
                'inserted_by'   => auth()->user()->name,
            ]);

            Product::where('id', $item['product_id'])
                ->increment('sold_qty', $item['quantity']);
        }

        return redirect()->route('sales.index')->with('status', 'Sale #' . $sale->invoice_no . ' created successfully.');
    }

    public function show(Sale $sale): View
    {
        $sale->load('customer', 'saleItems.product');

        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('status', 'Sale deleted successfully.');
    }

    private function generateInvoiceNo(): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $last   = Sale::where('invoice_no', 'like', $prefix . '%')->max('invoice_no');

        if ($last) {
            $seq = (int) substr($last, strlen($prefix)) + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
