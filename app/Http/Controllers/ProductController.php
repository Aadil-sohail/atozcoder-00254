<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\EbayAccount;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view products')->only(['index', 'show', 'outOfStock']);
        $this->middleware('permission:create products')->only(['create', 'store']);
        $this->middleware('permission:edit products')->only(['edit', 'update']);
        $this->middleware('permission:delete products')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::where(['status' => '1','status'=>'1'])->with(['category', 'ebayListings.ebayAccount'])->orderBy('name')->paginate(10);
        $ebayAccounts = EbayAccount::where('status', '1')->orderBy('store_name')->get();

        return view('products.index', compact('products', 'ebayAccounts'));
    }

    /**
     * Display products whose available stock (total minus sold) has run out.
     */
    public function outOfStock(): View
    {
        $products = Product::where('status', '1')
            ->whereRaw('(total_qty - sold_qty) <= 0')
            ->with('category')
            ->orderBy('name')
            ->paginate(15);

        return view('products.out-of-stock', compact('products'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::where(['status' => '1','status'=>'1'])->orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $images = $this->storeImages($request);

        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'image' => $images === [] ? null : $images,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'size' => $request->size,
            'warranty_months' => $request->warranty_months,
            'warranty_expiry_date' => $request->warranty_months
                ? now()->addMonths((int) $request->warranty_months)->toDateString()
                : null,
            'category_id' => $request->category_id,
            'inserted_by' => auth()->user()->name,
        ]);

        return redirect()->route('products.index')->with('status', 'Product added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::where(['status' => '1','close'=>'1'])->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $images = collect($product->image ?? [])
            ->diff($request->input('delete_images', []))
            ->merge($this->storeImages($request))
            ->values();

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'image' => $images->isEmpty() ? null : $images->all(),
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'size' => $request->size,
            'warranty_months' => $request->warranty_months,
            'warranty_expiry_date' => $request->warranty_months
                ? $product->created_at->copy()->addMonths((int) $request->warranty_months)->toDateString()
                : null,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('status', 'Product updated successfully.');
    }

    /**
     * Disable the specified resource instead of deleting it.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->update([
            'status' => '0',
            'close' => '0',
        ]);

        return redirect()->route('products.index')->with('status', 'Product deleted successfully.');
    }

    /**
     * Move the uploaded images into the public/uploads/products folder and return their relative paths.
     */
    private function storeImages(StoreProductRequest|UpdateProductRequest $request): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }

        return collect($request->file('images'))->map(function ($file) {
            $filename = uniqid('product_').'.'.$file->extension();
            $file->move(public_path('uploads/products'), $filename);

            return 'uploads/products/'.$filename;
        })->all();
    }
}
