<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Customer;
use App\Models\EbayAccount;
use App\Models\Product;
use App\Models\Sale;
use App\Support\ServerTable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view sales')->only(['index', 'data', 'show']);
        $this->middleware('permission:create sales')->only(['create', 'store']);
        $this->middleware('permission:delete sales')->only('destroy');
    }

    public function index(): View
    {
        // Rows come from data() a page at a time; only the header count is needed here.
        $saleCount = Sale::count();

        // Orders are pulled one store at a time, so the header needs the list
        // to offer — exactly as the product import screen does.
        $ebayAccounts = EbayAccount::where(['status' => '1', 'close' => '1'])->orderBy('store_name')->get();

        return view('sales.index', compact('saleCount', 'ebayAccounts'));
    }

    /**
     * Rows for the sales grid. The customer is joined so the column can be
     * sorted and searched in SQL rather than after the fact.
     */
    public function data(Request $request): JsonResponse
    {
        $query = Sale::query()
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->select('sales.*', 'customers.name as customer_name');

        return ServerTable::make($request, $query, [
            'invoice_no' => 'sales.invoice_no',
            'customer_name' => 'customers.name',
            'sale_date' => 'sales.sale_date',
            'discount' => 'sales.discount',
            'total_amount' => 'sales.total_amount',
        ], fn (Sale $sale) => [
            'invoice_no' => view('sales.partials.cells.invoice', compact('sale'))->render(),
            'customer_name' => e($sale->customer_name ?? '—'),
            'sale_date' => e(Carbon::parse($sale->sale_date)->format('M d, Y')),
            'discount' => number_format((float) $sale->discount, 2),
            'total_amount' => number_format((float) $sale->total_amount, 2),
            'actions' => view('sales.partials.cells.actions', compact('sale'))->render(),
        ]);
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get(['id', 'name', 'sku', 'selling_price', 'total_qty', 'sold_qty']);
        $nextInvoice = $this->generateInvoiceNo();

        return view('sales.create', compact('customers', 'products', 'nextInvoice'));
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        $items = $request->input('items');
        $discount = (float) $request->input('discount', 0);

        $subtotal = array_reduce($items, function ($carry, $item) {
            return $carry + ($item['quantity'] * $item['selling_price']);
        }, 0);

        $total = max(0, $subtotal - $discount);

        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'invoice_no' => $request->invoice_no,
            'sale_date' => $request->sale_date,
            'discount' => $discount,
            'total_amount' => $total,
            'inserted_by' => auth()->user()->name,
        ]);

        foreach ($items as $item) {
            $sale->saleItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'selling_price' => $item['selling_price'],
                'subtotal' => $item['quantity'] * $item['selling_price'],
                'inserted_by' => auth()->user()->name,
            ]);

            Product::where('id', $item['product_id'])
                ->increment('sold_qty', $item['quantity']);
        }

        return redirect()->route('sales.index')->with('status', 'Sale #'.$sale->invoice_no.' created successfully.');
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
        $prefix = 'INV-'.date('Ymd').'-';
        $last = Sale::where('invoice_no', 'like', $prefix.'%')->max('invoice_no');

        if ($last) {
            $seq = (int) substr($last, strlen($prefix)) + 1;
        } else {
            $seq = 1;
        }

        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
