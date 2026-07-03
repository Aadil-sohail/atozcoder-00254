<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleReturnRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view returns')->only(['index', 'show', 'lookupInvoice']);
        $this->middleware('permission:create returns')->only(['create', 'store']);
        $this->middleware('permission:delete returns')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $returns = SaleReturn::with('sale.customer', 'items')->latest()->paginate(15);

        return view('returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('returns.create');
    }

    /**
     * Ajax: look up a sale by invoice number and return its items with the
     * remaining returnable quantity for each (original quantity minus quantity
     * already recorded against previous returns).
     */
    public function lookupInvoice(Request $request): JsonResponse
    {
        $invoiceNo = trim((string) $request->input('invoice_no', ''));

        $sale = Sale::with(['customer', 'saleItems.product', 'saleItems.returnItems'])
            ->where('invoice_no', $invoiceNo)
            ->first();

        if (! $sale) {
            return response()->json(['message' => 'No sale found for this invoice number.'], 404);
        }

        $items = $sale->saleItems->map(function (SaleItem $saleItem) {
            $alreadyReturned = (float) $saleItem->returnItems->sum('quantity');
            $remaining = max(0, (float) $saleItem->quantity - $alreadyReturned);

            return [
                'sale_item_id'      => $saleItem->id,
                'product_id'        => $saleItem->product_id,
                'name'              => $saleItem->product->name . ($saleItem->product->sku ? ' (' . $saleItem->product->sku . ')' : ''),
                'quantity'          => (float) $saleItem->quantity,
                'already_returned'  => $alreadyReturned,
                'remaining'         => $remaining,
            ];
        })->values();

        return response()->json([
            'sale_id'    => $sale->id,
            'invoice_no' => $sale->invoice_no,
            'customer'   => $sale->customer->name,
            'sale_date'  => $sale->sale_date,
            'items'      => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleReturnRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $saleReturn = SaleReturn::create([
                'sale_id'     => $request->sale_id,
                'return_date' => $request->return_date,
                'inserted_by' => auth()->user()->name,
            ]);

            foreach ($request->items as $item) {
                $saleItem = SaleItem::findOrFail($item['sale_item_id']);

                SaleReturnItem::create([
                    'sale_return_id' => $saleReturn->id,
                    'sale_item_id'   => $saleItem->id,
                    'product_id'     => $saleItem->product_id,
                    'quantity'       => $item['quantity'],
                    'condition'      => $item['condition'],
                    'inserted_by'    => auth()->user()->name,
                ]);

                if ($item['condition'] === 'good') {
                    Product::where('id', $saleItem->product_id)->increment('total_qty', $item['quantity']);
                    Product::where('id', $saleItem->product_id)->decrement('sold_qty', $item['quantity']);
                }
            }
        });

        return redirect()->route('returns.index')->with('status', 'Return recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleReturn $saleReturn): View
    {
        $saleReturn->load('sale.customer', 'items.product');

        return view('returns.show', compact('saleReturn'));
    }

    /**
     * Remove the specified resource from storage, reversing any stock it added.
     */
    public function destroy(SaleReturn $saleReturn): RedirectResponse
    {
        DB::transaction(function () use ($saleReturn) {
            foreach ($saleReturn->items as $item) {
                if ($item->condition === 'good') {
                    Product::where('id', $item->product_id)->decrement('total_qty', $item->quantity);
                    Product::where('id', $item->product_id)->increment('sold_qty', $item->quantity);
                }
            }

            $saleReturn->delete();
        });

        return redirect()->route('returns.index')->with('status', 'Return deleted successfully.');
    }
}
