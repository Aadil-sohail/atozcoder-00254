<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view inventories')->only(['index', 'category', 'show']);
        $this->middleware('permission:create inventories')->only('store');
    }

    /**
     * Display stock grouped by category.
     */
    public function index(): View
    {
        $categories = Category::query()
            ->where('status', '1')
            ->withCount(['products as products_count' => fn ($q) => $q->where('status', '1')])
            ->addSelect(['available_stock' => Product::selectRaw('COALESCE(SUM(total_qty - sold_qty), 0)')
                ->whereColumn('category_id', 'categories.id')
                ->where('status', '1'),
            ])
            ->orderBy('name')
            ->paginate(15);

        $products = Product::where(['status' => '1'])->orderBy('name')->get();

        return view('inventory.index', compact('categories', 'products'));
    }

    /**
     * Display the products of one category with their available stock.
     */
    public function category(Category $category): View
    {
        $products = Product::where('category_id', $category->id)
            ->where('status', '1')
            ->orderBy('name')
            ->paginate(15);

        return view('inventory.category', compact('category', 'products'));
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
