<?php

namespace App\Http\Controllers;

use App\Jobs\ImportEbayListings;
use App\Jobs\StageEbayListings;
use App\Jobs\SyncProductToEbay;
use App\Models\EbayAccount;
use App\Models\EbayImportItem;
use App\Models\EbayListing;
use App\Models\Product;
use App\Services\EbayListingImporter;
use App\Services\EbayOrderImporter;
use App\Services\EbayReturnImporter;
use App\Services\EbayService;
use App\Support\EbayImportStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class EbaySyncController extends Controller
{
    /**
     * Batches up to this size sync immediately during the request; larger
     * batches are pushed to the queue.
     */
    private const INLINE_SYNC_LIMIT = 5;

    public function __construct(private EbayService $ebay)
    {
        $this->middleware('permission:sync ebay products');
    }

    /**
     * Sync the selected products to the chosen eBay store.
     */
    public function sync(Request $request): RedirectResponse
    {
        $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'ebay_account_id' => ['required', 'integer', 'exists:ebay_accounts,id'],
            'condition' => ['required', 'string', 'in:'.implode(',', array_keys(config('ebay.conditions')))],
            'ebay_category_id' => ['nullable', 'string', 'max:20'],
        ]);

        $account = EbayAccount::findOrFail($request->ebay_account_id);

        if (! $account->isFullyConfigured()) {
            return back()->with('error', "Store \"{$account->store_name}\" is not fully set up yet. Open eBay Stores and finish its setup first.");
        }

        $products = Product::whereIn('id', $request->product_ids)->get();
        $inline = $products->count() <= self::INLINE_SYNC_LIMIT;
        $synced = 0;
        $failures = [];

        if ($inline) {
            set_time_limit(60 + $products->count() * 60);
        }

        foreach ($products as $product) {
            $listing = EbayListing::firstOrNew([
                'product_id' => $product->id,
                'ebay_account_id' => $account->id,
            ]);

            // eBay requires a unique SKU per seller account; fall back to the product id.
            $listing->sku = $listing->sku ?: ($product->sku ?: 'PRD-'.$product->id);
            $listing->condition = $request->condition;

            if ($request->filled('ebay_category_id')) {
                $listing->ebay_category_id = $request->ebay_category_id;
            }

            $listing->sync_status = 'pending';
            $listing->last_error = null;
            $listing->inserted_by = $listing->inserted_by ?: auth()->user()->name;
            $listing->save();

            if (! $inline) {
                SyncProductToEbay::dispatch($listing->id);

                continue;
            }

            // Sync right now so the outcome is visible immediately.
            $listing->setRelation('product', $product);
            $listing->load('ebayAccount');
            $listing->update(['sync_status' => 'syncing']);

            try {
                $this->ebay->syncListing($listing);
                $synced++;
            } catch (Throwable $e) {
                $listing->update([
                    'sync_status' => 'failed',
                    'last_error' => Str::limit($e->getMessage(), 2000),
                ]);

                $failures[] = "{$product->name} — {$e->getMessage()}";
            }
        }

        if (! $inline) {
            $count = $products->count();

            return back()->with('status', "{$count} ".Str::plural('product', $count)." queued for sync to \"{$account->store_name}\". Refresh in a moment to see the result.");
        }

        if ($synced > 0) {
            session()->flash('status', "{$synced} ".Str::plural('product', $synced)." synced to \"{$account->store_name}\".");
        }

        if ($failures !== []) {
            session()->flash('error', implode(' • ', $failures));
        }

        return back();
    }

    /**
     * Pull new orders from the chosen store — or from every connected store
     * when none is named — and create local sales.
     */
    public function syncOrders(Request $request, EbayOrderImporter $importer): RedirectResponse
    {
        $request->validate([
            'ebay_account_id' => ['nullable', 'integer', 'exists:ebay_accounts,id'],
        ]);

        $accounts = EbayAccount::where('status', '1')
            ->when($request->ebay_account_id, fn ($query, $id) => $query->whereKey($id))
            ->get();

        if ($accounts->isEmpty()) {
            return back()->with('error', 'No connected eBay stores.');
        }

        // An order for something never imported fetches that listing from eBay
        // before the sale can be recorded, so allow generous time.
        set_time_limit(300);

        $created = 0;
        $errors = [];

        foreach ($accounts as $account) {
            try {
                $created += $importer->import($account)['created'];
            } catch (Throwable $e) {
                $errors[] = "{$account->store_name} — {$e->getMessage()}";
            }
        }

        if ($errors !== []) {
            session()->flash('error', implode(' • ', $errors));
        }

        $from = $accounts->count() === 1 ? " from \"{$accounts->first()->store_name}\"" : '';

        return back()->with('status', $created === 0
            ? "No new orders{$from} to import."
            : "{$created} new eBay ".Str::plural('order', $created)." imported{$from}.");
    }

    /**
     * Pull completed return requests from every connected store and record
     * them as local sale returns (restocking sellable items).
     */
    public function syncReturns(EbayReturnImporter $importer): RedirectResponse
    {
        $accounts = EbayAccount::where('status', '1')->get();

        if ($accounts->isEmpty()) {
            return back()->with('error', 'No connected eBay stores.');
        }

        $created = 0;
        $errors = [];

        foreach ($accounts as $account) {
            try {
                $created += $importer->import($account)['created'];
            } catch (Throwable $e) {
                $errors[] = "{$account->store_name} — {$e->getMessage()}";
            }
        }

        if ($errors !== []) {
            session()->flash('error', implode(' • ', $errors));
        }

        return back()->with('status', "{$created} new eBay ".Str::plural('return', $created).' imported.');
    }

    /**
     * Step one of the import: queue a fetch of everything the chosen store has
     * listed into the staging table. The products page shows its progress and
     * links to the pick list once it is ready. No product is created here.
     */
    public function syncProducts(Request $request): RedirectResponse
    {
        $request->validate([
            'ebay_account_id' => ['required', 'integer', 'exists:ebay_accounts,id'],
        ]);

        $account = EbayAccount::findOrFail($request->ebay_account_id);

        if ($blocked = $this->importAlreadyRunning()) {
            return $blocked;
        }

        EbayImportStatus::put($account->id, 'fetching');
        StageEbayListings::dispatch($account->id);

        return redirect()->route('products.index')
            ->with('status', "Fetching listings from \"{$account->store_name}\" in the background. You can keep working — this page shows when they are ready to pick.");
    }

    /**
     * Only one eBay fetch or import runs at a time, across every store. Sends
     * the user back to the products page with an alert naming the one that is
     * running, or returns null when the way is clear.
     */
    private function importAlreadyRunning(): ?RedirectResponse
    {
        $runningId = EbayImportStatus::runningAccountId();

        if ($runningId === null) {
            return null;
        }

        $store = EbayAccount::find($runningId)?->store_name ?? 'another store';

        return redirect()->route('products.index')
            ->with('import_blocked', "An import from \"{$store}\" is already running. Only one eBay import can run at a time — wait for it to finish, then try again.");
    }

    /**
     * Where every store's background import has got to, for the products page
     * to poll while one is running.
     */
    public function importStatus(): JsonResponse
    {
        $statuses = EbayAccount::pluck('id')
            ->mapWithKeys(fn ($id) => [$id => EbayImportStatus::get($id)['state'] ?? null])
            ->filter();

        return response()->json($statuses);
    }

    /**
     * Step two: the staged listings, for the user to tick.
     */
    public function importSelection(EbayAccount $ebayAccount): View|RedirectResponse
    {
        if (EbayImportStatus::busy($ebayAccount->id)) {
            return redirect()->route('products.index')
                ->with('warning', "\"{$ebayAccount->store_name}\" is still being fetched or imported. Wait for it to finish.");
        }

        $items = EbayImportItem::where('ebay_account_id', $ebayAccount->id)
            ->orderBy('title')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('warning', "Nothing is waiting to be imported from \"{$ebayAccount->store_name}\". Run Import from eBay again.");
        }

        return view('ebay.import', ['account' => $ebayAccount, 'items' => $items]);
    }

    /**
     * Step three: create products for the ticked rows, then empty the staging
     * table for this store whether or not everything was picked.
     */
    public function importSelected(Request $request, EbayAccount $ebayAccount): RedirectResponse
    {
        $request->validate([
            'item_ids' => ['required', 'array', 'min:1'],
            'item_ids.*' => ['integer'],
        ], [
            'item_ids.required' => 'Tick at least one product to import, or click Discard to throw the list away.',
        ]);

        if ($blocked = $this->importAlreadyRunning()) {
            return $blocked;
        }

        $count = count($request->item_ids);

        // Each product fetches its details and downloads its pictures, which
        // for a big selection is minutes — so it runs on the queue.
        EbayImportStatus::put($ebayAccount->id, 'importing', "Importing {$count} ".Str::plural('product', $count)." from \"{$ebayAccount->store_name}\"…");
        ImportEbayListings::dispatch($ebayAccount->id, array_map('intval', $request->item_ids));

        return redirect()->route('products.index')
            ->with('status', "Importing {$count} ".Str::plural('product', $count)." from \"{$ebayAccount->store_name}\" in the background. They appear here as soon as it finishes.");
    }

    /**
     * Throw the staged list away without importing any of it.
     */
    public function importDiscard(EbayAccount $ebayAccount, EbayListingImporter $importer): RedirectResponse
    {
        if (EbayImportStatus::busy($ebayAccount->id)) {
            return redirect()->route('products.index')
                ->with('warning', "An import from \"{$ebayAccount->store_name}\" is running, so there is nothing to discard yet.");
        }

        EbayImportStatus::forget($ebayAccount->id);

        $discarded = $importer->clearStaging($ebayAccount);

        return redirect()->route('products.index')
            ->with('status', "Import discarded. {$discarded} eBay ".Str::plural('listing', $discarded).' left untouched.');
    }

    /**
     * End the eBay listing and unlink the product from the store.
     */
    public function destroy(EbayListing $ebayListing): RedirectResponse
    {
        $productName = $ebayListing->product->name ?? 'Product';

        try {
            $this->ebay->endListing($ebayListing);
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        $ebayListing->delete();

        return back()->with('status', "\"{$productName}\" removed from eBay.");
    }
}
