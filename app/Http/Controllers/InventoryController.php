<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Support\ServerTable;
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
        $products = Product::where($filter)->orderBy('name')->get();

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
                    ->where('status', '1'),
                'products_count'
            )
            ->selectSub(
                Product::selectRaw('COALESCE(SUM(total_qty - sold_qty), 0)')
                    ->whereColumn('category_id', 'categories.id')
                    ->where('status', '1'),
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
        $productCount = Product::where('category_id', $category->id)->where('status', '1')->count();

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
            ->select('products.*')
            ->selectRaw('(products.total_qty - products.sold_qty) as available_stock');

        return ServerTable::make($request, $query, [
            'name' => 'products.name',
            'available_stock' => ['order' => 'available_stock'],
        ], fn (Product $product) => [
            'name' => e($product->name),
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
        $entries = Inventory::where('product_id', $product->id)->latest()->get();

        return view('inventory.show', compact('product', 'entries'));
    }
}
