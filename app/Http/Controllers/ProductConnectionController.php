<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductConnectionRequest;
use App\Models\EbayAccount;
use App\Models\Product;
use App\Models\ProductConnection;
use App\Models\ProductConnectionItem;
use App\Services\ProductStock;
use App\Support\ServerTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Connections: the same physical part, listed on several stores, told that it
 * is one product.
 */
class ProductConnectionController extends Controller
{
    protected $filter = ['status' => '1', 'close' => '1'];

    public function __construct()
    {
        $this->middleware('permission:view connections')->only(['index', 'data']);
        $this->middleware('permission:create connections')->only(['create', 'store']);
        $this->middleware('permission:edit connections')->only(['edit', 'update']);
        $this->middleware('permission:delete connections')->only('destroy');
        $this->middleware('permission:create connections|edit connections')->only('storeProducts');
    }

    public function index(): View
    {
        $connectionCount = ProductConnection::where('status', '1')->count();
        $ebayAccounts = EbayAccount::where($this->filter)->orderBy('store_name')->get();

        return view('connections.index', compact('connectionCount', 'ebayAccounts'));
    }

    /**
     * Rows for the connections grid.
     *
     * The master product is joined so its price and stock can be sorted in
     * SQL; the members are eager-loaded, since joining a hasMany would give
     * one row per member and break the paging counts.
     */
    public function data(Request $request): JsonResponse
    {
        $query = ProductConnection::query()
            ->where('product_connections.status', '1')
            ->leftJoin('products as master', 'master.id', '=', 'product_connections.master_product_id')
            ->select('product_connections.*', 'master.name as master_name', 'master.selling_price as master_selling_price', 'master.cost_price as master_cost_price')
            ->selectRaw('(master.total_qty - master.sold_qty) as available_stock')
            ->selectSub(
                ProductConnectionItem::selectRaw('COUNT(*)')
                    ->whereColumn('product_connection_id', 'product_connections.id'),
                'items_count'
            )
            ->with(['masterProduct', 'items.product.ebayListings.ebayAccount', 'items.stores']);

        return ServerTable::make($request, $query, [
            'name' => 'product_connections.name',
            'master_name' => 'master.name',
            // Sortable only: MySQL takes a SELECT alias in ORDER BY, never in
            // the WHERE clause a search would build.
            'items_count' => ['order' => 'items_count'],
            'master_selling_price' => ['order' => 'master_selling_price'],
            'available_stock' => ['order' => 'available_stock'],
        ], fn (ProductConnection $connection) => [
            'name' => view('connections.partials.cells.name', compact('connection'))->render(),
            'items_count' => view('connections.partials.cells.products', compact('connection'))->render(),
            'stores' => view('connections.partials.cells.stores', compact('connection'))->render(),
            'master_selling_price' => view('connections.partials.cells.prices', compact('connection'))->render(),
            'available_stock' => view('connections.partials.cells.stock', compact('connection'))->render(),
            'actions' => view('connections.partials.cells.actions', compact('connection'))->render(),
        ]);
    }

    public function create(): View
    {
        return view('connections.form', [
            'connection' => null,
            'ebayAccounts' => EbayAccount::where($this->filter)->orderBy('store_name')->get(),
        ]);
    }

    public function edit(ProductConnection $connection): View
    {
        $connection->load(['items.product.ebayListings.ebayAccount', 'items.stores']);

        return view('connections.form', [
            'connection' => $connection,
            'ebayAccounts' => EbayAccount::where($this->filter)->orderBy('store_name')->get(),
        ]);
    }

    /**
     * The products listed on one store, for the picker on the form.
     *
     * Products already in another connection come back flagged rather than
     * hidden, so the form can say why one cannot be picked instead of leaving
     * the user hunting for a product that silently is not there.
     */
    public function storeProducts(Request $request): JsonResponse
    {
        $request->validate([
            'store' => ['nullable', 'integer', 'exists:ebay_accounts,id'],
            'connection' => ['nullable', 'integer', 'exists:product_connections,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $store = (int) $request->input('store');
        $search = trim((string) $request->input('q'));

        $products = Product::query()
            ->where('products.status', '1')
            ->when($store, fn ($query) => $query->whereHas(
                'ebayListings',
                fn ($listings) => $listings->where('ebay_account_id', $store)
            ))
            ->when($search !== '', fn ($query) => $query->where(fn ($match) => $match
                ->where('products.name', 'like', "%{$search}%")
                ->orWhere('products.sku', 'like', "%{$search}%")
                ->orWhereHas('ebayListings', fn ($listings) => $listings->where('listing_id', 'like', "%{$search}%"))))
            // Every listing, not just the asked-for store's: the form needs to
            // know which stores a product already covers on its own.
            // The connection comes along too: availableStock() reads through it
            // and would otherwise ask once per product.
            ->with(['ebayListings', 'productConnection.masterProduct'])
            ->orderBy('products.name')
            ->limit(300)
            ->get();

        // One query for the lot, rather than asking per product whether it is
        // spoken for.
        $taken = ProductConnectionItem::with('connection')
            ->whereIn('product_id', $products->pluck('id'))
            ->when($request->input('connection'), fn ($query, $id) => $query->where('product_connection_id', '!=', $id))
            ->get()
            ->keyBy('product_id');

        return response()->json([
            'products' => $products->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'listing_id' => ($store
                    ? $product->ebayListings->firstWhere('ebay_account_id', $store)
                    : $product->ebayListings->first())?->listing_id,
                // Store ids this product is listed on itself.
                'stores' => $product->ebayListings->pluck('ebay_account_id')->filter()->unique()->values(),
                'cost_price' => $product->cost_price,
                'selling_price' => $product->selling_price,
                'stock' => $product->availableStock(),
                'connected_to' => $taken->get($product->id)?->connection?->name,
            ])->values(),
        ]);
    }

    public function store(StoreProductConnectionRequest $request): RedirectResponse
    {
        $connection = DB::transaction(function () use ($request) {
            $master = Product::findOrFail($request->master_product_id);

            $connection = ProductConnection::create([
                'name' => $request->input('name') ?: $master->name,
                'master_product_id' => $master->id,
                'inserted_by' => auth()->user()->name,
            ]);

            $this->saveItems($connection, $request->input('items'));

            return $connection;
        });

        ProductStock::forget();

        return redirect()->route('connections.index')
            ->with('status', "Connection \"{$connection->name}\" created. Its products now count as one.");
    }

    public function update(StoreProductConnectionRequest $request, ProductConnection $connection): RedirectResponse
    {
        DB::transaction(function () use ($request, $connection) {
            $master = Product::findOrFail($request->master_product_id);

            $connection->update([
                'name' => $request->input('name') ?: $master->name,
                'master_product_id' => $master->id,
            ]);

            // Members carry nothing but the link itself, so replacing them
            // outright is simpler than working out what moved. Each one is
            // deleted through the model so its store links go with it.
            $connection->items->each->delete();

            $this->saveItems($connection, $request->input('items'));
        });

        ProductStock::forget();

        return redirect()->route('connections.index')
            ->with('status', "Connection \"{$connection->name}\" updated.");
    }

    /**
     * Break the connection. Deleted outright rather than disabled: its members
     * have to be free to join another one, and a disabled row would still hold
     * them.
     */
    public function destroy(ProductConnection $connection): RedirectResponse
    {
        $name = $connection->name;

        $connection->delete();

        ProductStock::forget();

        return redirect()->route('connections.index')
            ->with('status', "Connection \"{$name}\" removed. Its products are separate again.");
    }

    /**
     * @param  list<array{product_id: int|string, ebay_account_ids?: list<int|string>|null}>  $items
     */
    private function saveItems(ProductConnection $connection, array $items): void
    {
        foreach ($items as $item) {
            $member = $connection->items()->create(['product_id' => $item['product_id']]);

            $member->stores()->sync(array_filter($item['ebay_account_ids'] ?? []));
        }
    }
}
