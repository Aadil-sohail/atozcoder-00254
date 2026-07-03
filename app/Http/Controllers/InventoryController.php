<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view inventories')->only(['index', 'show']);
        $this->middleware('permission:create inventories')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $inventories = Inventory::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('COUNT(*) as entries_count')
            ->selectRaw('MAX(created_at) as last_added_at')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('last_added_at')
            ->paginate(10);

        $products = Product::where(['status' => '1'])->orderBy('name')->get();

        return view('inventory.index', compact('inventories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                Inventory::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'inserted_by' => auth()->user()->name,
                ]);

                Product::where('id', $item['product_id'])->increment('total_qty', $item['quantity']);
            }
        });

        return redirect()->route('inventories.index')->with('status', 'Stock added successfully.');
    }

    /**
     * Display all stock entries for the given product.
     */
    public function show(Product $product): View
    {
        $entries = Inventory::where('product_id', $product->id)->latest()->paginate(15);

        return view('inventory.show', compact('product', 'entries'));
    }
}
