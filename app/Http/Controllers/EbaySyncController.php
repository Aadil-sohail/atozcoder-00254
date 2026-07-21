<?php

namespace App\Http\Controllers;

use App\Jobs\SyncProductToEbay;
use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Services\EbayListingImporter;
use App\Services\EbayOrderImporter;
use App\Services\EbayReturnImporter;
use App\Services\EbayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
     * Pull new orders from every connected store and create local sales.
     */
    public function syncOrders(EbayOrderImporter $importer): RedirectResponse
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

        return back()->with('status', "{$created} new eBay ".Str::plural('order', $created).' imported.');
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
     * Pull listings from the chosen store and create local products for any
     * that are on eBay but not in the software yet.
     */
    public function syncProducts(Request $request, EbayListingImporter $importer): RedirectResponse
    {
        $request->validate([
            'ebay_account_id' => ['required', 'integer', 'exists:ebay_accounts,id'],
        ]);

        $account = EbayAccount::findOrFail($request->ebay_account_id);

        // Each item can need a follow-up offers call, so allow generous time.
        set_time_limit(300);

        try {
            $result = $importer->import($account);
        } catch (Throwable $e) {
            return back()->with('error', "{$account->store_name} — {$e->getMessage()}");
        }

        $message = "{$result['created']} new ".Str::plural('product', $result['created'])." imported from \"{$account->store_name}\".";

        if ($result['linked'] > 0) {
            $message .= " {$result['linked']} existing ".Str::plural('product', $result['linked']).' linked.';
        }

        return back()->with('status', $message);
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
