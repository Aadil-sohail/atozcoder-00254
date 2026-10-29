<?php

namespace App\Http\Controllers;

use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Services\ProductStock;
use App\Support\ServerTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    private array $filter = ['status' => '1', 'close' => '1'];

    public function __construct()
    {
        $this->middleware('permission:view stock adjustments')->only(['index', 'data']);
        $this->middleware('permission:create stock adjustments')->only(['products', 'store']);
        $this->middleware('permission:edit stock adjustments')->only('update');
    }

    public function index(): View
    {
        $adjustmentCount = StockAdjustment::where($this->filter)->count();
        $ebayAccounts = EbayAccount::where($this->filter)->orderBy('store_name')->get();

        return view('stock-adjustments.index', compact('adjustmentCount', 'ebayAccounts'));
    }

    /**
     * Rows for the adjustments grid. Product and store are single-row joins,
     * so there is still exactly one row per adjustment.
     */
    public function data(Request $request): JsonResponse
    {
        $query = StockAdjustment::query()
            ->where('stock_adjustments.status', '1')
            ->where('stock_adjustments.close', '1')
            ->leftJoin('products', 'products.id', '=', 'stock_adjustments.product_id')
            ->leftJoin('ebay_accounts', 'ebay_accounts.id', '=', 'stock_adjustments.ebay_account_id')
            ->select('stock_adjustments.*', 'products.name as product_name', 'products.sku as product_sku', 'ebay_accounts.store_name');

        return ServerTable::make($request, $query, [
            'created_at' => 'stock_adjustments.created_at',
            'store_name' => 'ebay_accounts.store_name',
            'product_name' => 'products.name',
            'sku' => ['search' => 'products.sku'],
            'type' => 'stock_adjustments.type',
            'change' => 'stock_adjustments.change',
            'stock' => ['order' => 'stock_adjustments.stock_after'],
            'pushed' => ['order' => 'stock_adjustments.pushed_listings'],
            'reason' => 'stock_adjustments.reason',
            'inserted_by' => 'stock_adjustments.inserted_by',
        ], fn (StockAdjustment $adjustment) => [
            'created_at' => e($adjustment->created_at->format('M d, Y H:i')),
            'store_name' => e($adjustment->store_name ?? '—'),
            'product_name' => view('stock-adjustments.partials.cells.product', compact('adjustment'))->render(),
            'type' => view('stock-adjustments.partials.cells.type', compact('adjustment'))->render(),
            'change' => view('stock-adjustments.partials.cells.change', compact('adjustment'))->render(),
            'stock' => e(number_format((float) $adjustment->stock_before, 2).' → '.number_format((float) $adjustment->stock_after, 2)),
            'pushed' => view('stock-adjustments.partials.cells.pushed', compact('adjustment'))->render(),
            'reason' => e($adjustment->reason ?? '—'),
            'inserted_by' => e($adjustment->inserted_by ?? '—'),
            'actions' => view('stock-adjustments.partials.cells.actions', compact('adjustment'))->render(),
        ]);
    }

    /**
     * The products listed on one store, with their current stock, for the
     * product picker. Searched as the user types, so a large store is never
     * sent whole.
     */
    public function products(Request $request): JsonResponse
    {
        $request->validate([
            'store' => ['required', 'integer', 'exists:ebay_accounts,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $store = (int) $request->input('store');
        $search = trim((string) $request->input('q'));

        $products = Product::query()
            ->where('products.status', '1')
            ->where('products.close', '1')
            ->whereHas('ebayListings', fn ($listings) => $listings->where('ebay_account_id', $store))
            ->when($search !== '', fn ($query) => $query->where(fn ($match) => $match
                ->where('products.name', 'like', "%{$search}%")
                ->orWhere('products.sku', 'like', "%{$search}%")
                ->orWhereHas('ebayListings', fn ($listings) => $listings->where('listing_id', 'like', "%{$search}%"))))
            // availableStock() reads through the connection.
            ->with(['productConnection.masterProduct', 'ebayListings' => fn ($listings) => $listings->where('ebay_account_id', $store)])
            ->orderBy('products.name')
            ->limit(50)
            ->get();

        return response()->json([
            'results' => $products->map(fn (Product $product) => [
                'id' => $product->id,
                'text' => $product->name.($product->sku ? ' ('.$product->sku.')' : ''),
                'listing_id' => $product->ebayListings->first()?->listing_id,
                'stock' => $product->availableStock(),
            ])->values(),
        ]);
    }

    /**
     * Apply an adjustment and send the new figure to every eBay listing of
     * the product. The form only submits once the user has confirmed that.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ebay_account_id' => ['required', 'integer', Rule::exists('ebay_accounts', 'id')->where('status', '1')->where('close', '1')],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in(array_keys(StockAdjustment::TYPES))],
            'quantity' => ['required', 'numeric', $request->input('type') === 'set' ? 'min:0' : 'gt:0', 'max:99999999'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        // Only products picked from the chosen store's list can be adjusted.
        $listed = EbayListing::where('ebay_account_id', $data['ebay_account_id'])
            ->where('product_id', $data['product_id'])
            ->exists();

        if (! $listed) {
            throw ValidationException::withMessages(['product_id' => 'This product is not listed on the selected store.']);
        }

        $adjustment = DB::transaction(function () use ($data) {
            // Locked so two adjustments at once cannot both start from the
            // same "before" figure.
            $keeper = Product::lockForUpdate()->findOrFail(ProductStock::keeperId($data['product_id']));
            $before = (float) $keeper->total_qty - (float) $keeper->sold_qty;
            $quantity = (float) $data['quantity'];

            $change = match ($data['type']) {
                'increase' => $quantity,
                'decrease' => -$quantity,
                'set' => $quantity - $before,
            };

            if ($data['type'] === 'decrease' && $quantity > $before) {
                throw ValidationException::withMessages([
                    'quantity' => 'Only '.number_format(max(0, $before), 2).' in stock, so it cannot be decreased by '.number_format($quantity, 2).'.',
                ]);
            }

            if ($change == 0) {
                throw ValidationException::withMessages(['quantity' => 'The stock is already '.number_format($before, 2).'.']);
            }

            $adjustment = StockAdjustment::create([
                'product_id' => $data['product_id'],
                'ebay_account_id' => $data['ebay_account_id'],
                'type' => $data['type'],
                'quantity' => $quantity,
                'change' => $change,
                'stock_before' => $before,
                'stock_after' => $before + $change,
                'reason' => $data['reason'] ?? null,
                'inserted_by' => auth()->user()->name,
            ]);

            ProductStock::adjusted($data['product_id'], $change, [
                'ebay_account_id' => $data['ebay_account_id'],
                'note' => 'Adjustment #'.$adjustment->id.($adjustment->reason ? ': '.$adjustment->reason : ''),
            ]);

            return $adjustment;
        });

        return $this->pushAndRedirect($adjustment, 'Stock adjusted to '.number_format((float) $adjustment->stock_after, 2).'.');
    }

    /**
     * Change an adjustment's type, quantity or reason.
     *
     * Only the difference from what it applied before reaches the stock: an
     * increase of 1 edited to 3 adds 2 more. "Set stock to" is measured
     * against the stock the adjustment was made on, so the row still reads
     * before → after. The difference is logged and pushed like any other
     * adjustment; the form confirms that before it submits.
     */
    public function update(Request $request, StockAdjustment $adjustment): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(StockAdjustment::TYPES))],
            'quantity' => ['required', 'numeric', $request->input('type') === 'set' ? 'min:0' : 'gt:0', 'max:99999999'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $difference = DB::transaction(function () use ($data, $adjustment) {
            $keeper = Product::lockForUpdate()->findOrFail(ProductStock::keeperId($adjustment->product_id));
            $current = (float) $keeper->total_qty - (float) $keeper->sold_qty;
            $before = (float) $adjustment->stock_before;
            $quantity = (float) $data['quantity'];

            $change = match ($data['type']) {
                'increase' => $quantity,
                'decrease' => -$quantity,
                'set' => $quantity - $before,
            };

            if ($change == 0) {
                throw ValidationException::withMessages(['quantity' => 'That leaves the stock where it started ('.number_format($before, 2).'), so there is nothing to adjust.']);
            }

            $difference = $change - (float) $adjustment->change;

            if ($current + $difference < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Only '.number_format(max(0, $current), 2).' in stock now, so this edit would take it below zero.',
                ]);
            }

            $reason = $data['reason'] ?? null;

            $adjustment->update([
                'type' => $data['type'],
                'quantity' => $quantity,
                'change' => $change,
                'stock_after' => $before + $change,
                'reason' => $reason,
            ]);

            if ($difference != 0) {
                ProductStock::adjusted($adjustment->product_id, $difference, [
                    'ebay_account_id' => $adjustment->ebay_account_id,
                    'note' => 'Adjustment #'.$adjustment->id.' edited'.($reason ? ': '.$reason : ''),
                ]);
            }

            return $difference;
        });

        return $this->pushAndRedirect($adjustment, 'Adjustment #'.$adjustment->id.' updated.', $difference != 0);
    }

    /**
     * Push the moved stock now rather than when the request ends, so the
     * message can say how it went. Failures are logged by EbayStockSync, not
     * thrown.
     */
    private function pushAndRedirect(StockAdjustment $adjustment, string $message, bool $moved = true): RedirectResponse
    {
        // Only the reason changed: nothing to send, and the row keeps what
        // it said about the last push.
        if (! $moved) {
            return redirect()->route('stock-adjustments.index')->with('status', $message);
        }

        $pushing = config('ebay.push_stock', true);
        $pushed = ProductStock::flush();

        $adjustment->update(['pushed_listings' => $pushing ? $pushed : null]);

        if (! $pushing) {
            return redirect()->route('stock-adjustments.index')
                ->with('warning', $message.' Pushing stock to eBay is turned off (EBAY_PUSH_STOCK).');
        }

        if ($pushed === 0) {
            return redirect()->route('stock-adjustments.index')
                ->with('warning', $message.' No eBay listing was updated — see the log for details.');
        }

        return redirect()->route('stock-adjustments.index')
            ->with('status', $message.' Pushed to '.$pushed.' eBay '.str('listing')->plural($pushed).'.');
    }
}
