<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Support\ServerTable;
use App\Services\ProductStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view inventories')->only(['index', 'data', 'category', 'categoryData', 'show']);
        $this->middleware('permission:create inventories')->only('store');
    }

    /**
     * Display stock grouped by category.
     */
    public function index(): View
    {
        $filter=['status'=>'1','close'=>'1'];
        $categoryCount = Category::where($filter)->count();
        $products = Product::where($filter)
            ->with(['ebayListings.ebayAccount', 'productConnection.items.stores'])
            ->orderBy('name')->get();

        return view('inventory.index', compact('categoryCount', 'products'));
    }

    /**
     * Rows for the stock-by-category grid.
     *
     * The product count and available stock are correlated sub-queries rather
     * than aggregates over a join, so one row comes back per category however
     * many products it holds — which is what keeps the paging counts honest.
     */
    public function data(Request $request): JsonResponse
    {
        $query = Category::query()
            ->where('categories.status', '1')
            ->select('categories.*')
            ->selectSub(
                Product::selectRaw('COUNT(*)')
                    ->whereColumn('category_id', 'categories.id')
                    ->where('status', '1')
                    ->withoutConnectionDuplicates(),
                'products_count'
            )
            ->selectSub(
                Product::selectRaw('COALESCE(SUM(total_qty - sold_qty), 0)')
                    ->whereColumn('category_id', 'categories.id')
                    ->where('status', '1')
                    ->withoutConnectionDuplicates(),
                'available_stock'
            );

        return ServerTable::make($request, $query, [
            'name' => 'categories.name',
            // Sortable only: MySQL takes a SELECT alias in ORDER BY but not in
            // the WHERE clause a search would build.
            'products_count' => ['order' => 'products_count'],
            'available_stock' => ['order' => 'available_stock'],
        ], fn (Category $category) => [
            'name' => view('inventory.partials.cells.category', compact('category'))->render(),
            'products_count' => (string) $category->products_count,
            'available_stock' => view('inventory.partials.cells.stock', [
                'available' => $category->available_stock,
            ])->render(),
            'actions' => view('inventory.partials.cells.category-actions', compact('category'))->render(),
        ]);
    }

    /**
     * Display the products of one category with their available stock.
     */
    public function category(Category $category): View
    {
        $productCount = Product::where('category_id', $category->id)->where('status', '1')
            ->withoutConnectionDuplicates()->count();

        return view('inventory.category', compact('category', 'productCount'));
    }

    /**
     * Rows for one category's products, with stock computed in SQL so the
     * column sorts on the real number rather than the formatted text.
     */
    public function categoryData(Request $request, Category $category): JsonResponse
    {
        $query = Product::query()
            ->where('category_id', $category->id)
            ->where('status', '1')
            // One row per part, the same as the products screen shows.
            ->withoutConnectionDuplicates()
            // For the store column, as on the products screen.
            ->with([
                'ebayListings.ebayAccount',
                'productConnection.items.stores',
            ])
            ->select('products.*')
            ->selectRaw('(products.total_qty - products.sold_qty) as available_stock');

        return ServerTable::make($request, $query, [
            'name' => 'products.name',
            'available_stock' => ['order' => 'available_stock'],
        ], fn (Product $product) => [
            'name' => e($product->name),
            'stores' => view('products.partials.cells.stores', compact('product'))->render(),
            'available_stock' => view('inventory.partials.cells.stock', [
                'available' => $product->available_stock,
            ])->render(),
            'actions' => view('inventory.partials.cells.product-actions', compact('product'))->render(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $entry = Inventory::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'inserted_by' => auth()->user()->name,
                ]);

                ProductStock::received($item['product_id'], $item['quantity'], [
                    'inventory_id' => $entry->id,
                    'note' => 'Added from Add Stock',
                ]);
            }
        });

        return redirect()->route('inventories.index')->with('status', 'Stock added successfully.');
    }

    /**
     * Display the inventory log of the given product: every stock movement,
     * newest first.
     *
     * Stock entries with no log row of their own (those added before the log
     * existed) are listed too, so the history is complete without copying
     * them over.
     *
     * A connected product shares one stock with the rest of its connection,
     * so its page shows the movements of every member — the same list
     * whichever member is opened.
     */
    public function show(Product $product): View
    {
        $product->load('productConnection.items.product');

        $members = $product->productConnection?->items->pluck('product')->filter()->keyBy('id')
            ?? collect([$product->id => $product]);

        $logs = InventoryLog::whereIn('product_id', $members->keys())
            ->with(['sale', 'ebayAccount', 'product'])
            ->get();

        $unlogged = Inventory::whereIn('product_id', $members->keys())
            ->whereNotExists(fn ($logged) => $logged
                ->selectRaw('1')
                ->from('inventory_logs')
                ->whereColumn('inventory_logs.inventory_id', 'inventories.id'))
            ->get()
            ->map(fn (Inventory $entry) => InventoryLog::fromInventory($entry)
                ->setRelation('product', $members->get($entry->product_id)));

        $logs = $logs->concat($unlogged)
            ->sortByDesc(fn (InventoryLog $log) => [$log->created_at?->timestamp ?? 0, $log->id ?? 0])
            ->values();

        $connected = $members->count() > 1;

        return view('inventory.show', compact('product', 'logs', 'connected'));
    }
}
