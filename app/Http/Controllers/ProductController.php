<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\EbayAccount;
use App\Models\Product;
use App\Models\Subcategory;
use App\Services\ChunkedUploadStore;
use App\Services\ProductExcelImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    protected $filter = ['status' => '1', 'close' => '1'];

    public function __construct()
    {
        $this->middleware('permission:view products')->only(['index', 'show', 'outOfStock']);
        $this->middleware('permission:create products')->only(['create', 'store', 'importChunk', 'importFinalize']);
        $this->middleware('permission:edit products')->only(['edit', 'update']);
        $this->middleware('permission:delete products')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::where('status', '1')->with(['category', 'ebayListings.ebayAccount'])->orderBy('name')->get();
        $ebayAccounts = EbayAccount::where('status', '1')->orderBy('store_name')->get();

        return view('products.index', compact('products', 'ebayAccounts'));
    }

    /**
     * Receive one chunk of a spreadsheet being uploaded for import and
     * append it to the file being assembled for this upload_id. Uploading
     * in small chunks means a large supplier catalog never needs a single
     * oversized HTTP request that could exceed the host's upload limits.
     */
    public function importChunk(Request $request, ChunkedUploadStore $uploads): JsonResponse
    {
        $request->validate([
            'upload_id' => ['required', 'uuid'],
            'chunk' => ['required', 'file', 'max:5120'],
        ]);

        $uploads->appendChunk($request->upload_id, $request->file('chunk'));

        return response()->json(['ok' => true]);
    }

    /**
     * Assemble the uploaded chunks into a spreadsheet and import it. Rows
     * matching an existing product SKU are restocked; unknown SKUs are created
     * fresh, filed under the sub category (and its category) named by the
     * row's chassis number.
     */
    public function importFinalize(Request $request, ChunkedUploadStore $uploads, ProductExcelImporter $importer): JsonResponse
    {
        $request->validate([
            'upload_id' => ['required', 'uuid'],
            'filename' => ['required', 'string', 'max:255'],
        ]);

        try {
            $path = $uploads->finalize($request->upload_id, $request->filename);
            $result = $importer->import($path, auth()->user()->name);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => 'Import failed: '.$e->getMessage()], 422);
        } finally {
            $uploads->cleanup($request->upload_id);
        }

        if ($result['created'] === 0 && $result['restocked'] === 0) {
            return response()->json(['message' => 'No matching rows were found in the file. Make sure it has columns for chassis number, product name, product SKU, price and quantity.'], 422);
        }

        $message = "{$result['created']} new ".Str::plural('product', $result['created']).' created, '
            ."{$result['restocked']} existing ".Str::plural('product', $result['restocked']).' restocked.';

        if ($result['subcategories_created'] !== []) {
            $count = count($result['subcategories_created']);
            $message .= " {$count} new ".Str::plural('sub category', $count)
                .' added under "Excel Imports": '.$this->listNames($result['subcategories_created']).'.';
        }

        return response()->json(['message' => $message]);
    }

    /**
     * Render a list of names for a status message, capped so a large import
     * cannot produce a wall of text.
     *
     * @param  list<string>  $names
     */
    private function listNames(array $names): string
    {
        $shown = array_slice($names, 0, 5);
        $list = implode(', ', $shown);

        return count($names) > count($shown)
            ? $list.' and '.(count($names) - count($shown)).' more'
            : $list;
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
            ->get();

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
        $categories = Category::where($this->filter)->orderBy('name')->get();
        $Subcategories = Subcategory::where($this->filter)->orderBy('name')->get();

        return view('products.create', compact('categories', 'Subcategories'));
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
            'variant' => $request->variant,
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
            'subcategory_id' => $request->subcategory_id,
            'inserted_by' => auth()->user()->name,
        ]);

        return redirect()->route('products.index')->with('status', 'Product added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::where($this->filter)->orderBy('name')->get();
        $Subcategories = Subcategory::where($this->filter)->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'Subcategories'));
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
            'variant' => $request->variant,
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
            'subcategory_id' => $request->subcategory_id,
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
