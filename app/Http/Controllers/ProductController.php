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
use App\Support\ServerTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    protected $filter = ['status' => '1', 'close' => '1'];

    public function __construct()
    {
        $this->middleware('permission:view products')->only(['index', 'data', 'show', 'outOfStock']);
        $this->middleware('permission:create products')->only(['create', 'store', 'importChunk', 'importFinalize', 'importResolve']);
        $this->middleware('permission:edit products')->only(['edit', 'update']);
        $this->middleware('permission:delete products')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // The rows themselves are fetched a page at a time by data() below, so
        // only the count the header shows is needed here.
        $productCount = Product::where($this->filter)->count();
        $ebayAccounts = EbayAccount::where($this->filter)->orderBy('store_name')->get();
        // Offered in the import modal for rows whose chassis number matched no
        // sub category, so they can be assigned one without leaving the page.
        $subcategories = Subcategory::where($this->filter)->with('category')->orderBy('name')->get();

        return view('products.index', compact('productCount', 'ebayAccounts', 'subcategories'));
    }

    /**
     * Rows for the products grid, one page at a time.
     *
     * The category is joined rather than eager-loaded so it can be sorted and
     * searched in SQL alongside the product's own columns; the eBay listings
     * stay an eager-load because a product can have several and joining them
     * would multiply the rows.
     *
     * The grid shows eBay listing ids where it used to show SKUs, so they are
     * also pulled in as a scalar subquery — one row per product, listing ids
     * joined — which keeps that column sortable and searchable in SQL.
     */
    public function data(Request $request): JsonResponse
    {
        $hasEbayAccounts = EbayAccount::where($this->filter)->exists();

        $listingIds = '(select group_concat(ebay_listings.listing_id)
            from ebay_listings where ebay_listings.product_id = products.id)';

        $query = Product::query()
            ->where('products.status', '1')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->with('ebayListings.ebayAccount')
            ->select('products.*', 'categories.name as category_name')
            ->selectRaw($listingIds.' as listing_ids');

        return ServerTable::make($request, $query, [
            'name' => 'products.name',
            // Ordered on the alias and searched on the subquery itself: MySQL
            // takes an alias in ORDER BY but not in WHERE.
            'listing_ids' => ['order' => 'listing_ids', 'search' => DB::raw($listingIds)],
            'category_name' => 'categories.name',
            'size' => 'products.size',
            'cost_price' => 'products.cost_price',
            'selling_price' => 'products.selling_price',
            // No longer a column in the grid, but products added before the
            // switch to listing ids still carry a SKU worth finding them by.
            'sku' => ['search' => 'products.sku'],
        ], fn (Product $product) => [
            'select' => '<input type="checkbox" class="form-check-input product-select" value="'.$product->id.'">',
            'image' => view('products.partials.cells.image', compact('product'))->render(),
            'name' => view('products.partials.cells.name', compact('product'))->render(),
            'listing_ids' => view('products.partials.cells.listing-ids', compact('product'))->render(),
            'category_name' => e($product->category_name ?? '—'),
            'size' => e($product->size ?? '—'),
            'cost_price' => number_format((float) $product->cost_price, 2),
            'selling_price' => number_format((float) $product->selling_price, 2),
            'ebay' => view('products.partials.cells.ebay', compact('product'))->render(),
            // Passed explicitly: an arrow function only captures variables it
            // mentions by name, and compact() hides $hasEbayAccounts in a string.
            'actions' => view('products.partials.cells.actions', [
                'product' => $product,
                'hasEbayAccounts' => $hasEbayAccounts,
            ])->render(),
        ]);
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
     * Assemble the uploaded chunks into a spreadsheet and import it. Rows are
     * identified by their eBay listing id: one already known to the software is
     * restocked, an unknown one is created fresh and linked to the chosen store
     * under that listing id, filed under the sub category (and its category)
     * named by the row's chassis number.
     */
    public function importFinalize(Request $request, ChunkedUploadStore $uploads, ProductExcelImporter $importer): JsonResponse
    {
        $request->validate([
            'upload_id' => ['required', 'uuid'],
            'filename' => ['required', 'string', 'max:255'],
            'ebay_account_id' => ['required', 'integer', 'exists:ebay_accounts,id'],
        ]);

        $account = EbayAccount::findOrFail($request->ebay_account_id);

        try {
            $path = $uploads->finalize($request->upload_id, $request->filename);
            $result = $importer->import($path, $account, auth()->user()->name);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => 'Import failed: '.$e->getMessage()], 422);
        } finally {
            $uploads->cleanup($request->upload_id);
        }

        if ($result['created'] === 0 && $result['restocked'] === 0 && $result['pending'] === []) {
            return response()->json(['message' => 'No matching rows were found in the file. Make sure it has columns for chassis number, product name, eBay listing ID, price and quantity.'], 422);
        }

        return response()->json([
            'message' => $this->importSummary($result['created'], $result['restocked']),
            'pending' => $this->stashPending($request->upload_id, $account, $result['pending']),
        ]);
    }

    /**
     * Import the rows held back by importFinalize, against the sub category
     * the user picked for each chassis number.
     */
    public function importResolve(Request $request, ProductExcelImporter $importer): JsonResponse
    {
        $validated = $request->validate([
            'upload_id' => ['required', 'uuid'],
            'mappings' => ['required', 'array', 'min:1'],
            'mappings.*.chassis' => ['required', 'string'],
            'mappings.*.subcategory_id' => [
                'required',
                Rule::exists('subcategories', 'id')->where('status', '1')->where('close', '1'),
            ],
        ]);

        // The store picked for the upload was stashed with the rows, so this
        // pass links to the same one without trusting the browser for it.
        $stash = Cache::get($this->pendingCacheKey($validated['upload_id']));
        $rows = $stash['rows'] ?? null;
        $account = $stash ? EbayAccount::find($stash['ebay_account_id']) : null;

        if (! $rows || ! $account) {
            return response()->json(['message' => 'This import is no longer available — please upload the file again.'], 422);
        }

        $subcategories = Subcategory::whereIn('id', array_column($validated['mappings'], 'subcategory_id'))
            ->get()
            ->keyBy('id');

        $subcategoryByChassis = [];

        foreach ($validated['mappings'] as $mapping) {
            $subcategoryByChassis[$mapping['chassis']] = $subcategories[$mapping['subcategory_id']];
        }

        try {
            $result = $importer->importPending($rows, $subcategoryByChassis, $account, auth()->user()->name);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => 'Import failed: '.$e->getMessage()], 422);
        }

        Cache::forget($this->pendingCacheKey($validated['upload_id']));

        return response()->json(['message' => $this->importSummary($result['created'], $result['restocked'])]);
    }

    /**
     * Hold the rows that still need a sub category server-side for the
     * follow-up request, and return them grouped by chassis number for the
     * modal to render. Keeping the parsed rows here rather than round-tripping
     * them through the browser means the prices and quantities that get
     * imported are the ones that were actually in the file.
     */
    private function stashPending(string $uploadId, EbayAccount $account, array $pending): array
    {
        if ($pending === []) {
            return [];
        }

        Cache::put(
            $this->pendingCacheKey($uploadId),
            ['ebay_account_id' => $account->id, 'rows' => $pending],
            now()->addMinutes(30),
        );

        $groups = [];

        foreach ($pending as $row) {
            // Grouped on the normalized spelling so "F20" and "f20 " ask for a
            // sub category once, while the group still shows it as written.
            $key = mb_strtolower((string) preg_replace('/\s+/', ' ', trim($row['chassis'])));

            $groups[$key] ??= ['chassis' => $row['chassis'], 'products' => []];
            $groups[$key]['products'][] = [
                'name' => $row['name'],
                'listing_id' => $row['listing_id'],
                'variant' => $row['variant'],
                'quantity' => $row['quantity'],
            ];
        }

        return array_values($groups);
    }

    private function pendingCacheKey(string $uploadId): string
    {
        return 'product-import-pending:'.auth()->id().':'.$uploadId;
    }

    /**
     * One-line summary of what an import pass did.
     */
    private function importSummary(int $created, int $restocked): string
    {
        return "{$created} new ".Str::plural('product', $created).' created, '
            ."{$restocked} existing ".Str::plural('product', $restocked).' restocked.';
    }

    /**
     * Display products whose available stock (total minus sold) has run out.
     */
    public function outOfStock(): View
    {
        $products = Product::where($this->filter)
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
