<?php

namespace App\Jobs;

use App\Models\EbayAccount;
use App\Services\EbayListingImporter;
use App\Support\EbayImportStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Step two of "Import from eBay", off the request: turn the listings the user
 * ticked into products. Slow because each one fetches its details and
 * downloads its pictures.
 */
class ImportEbayListings implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 1800;

    /**
     * @param  list<int>  $itemIds  ebay_import_items ids the user ticked
     */
    public function __construct(public int $accountId, public array $itemIds)
    {
    }

    public function handle(EbayListingImporter $importer): void
    {
        $account = EbayAccount::find($this->accountId);

        if (! $account) {
            EbayImportStatus::forget($this->accountId);

            return;
        }

        $result = $importer->commit($account, $this->itemIds);

        $message = "{$result['created']} new ".Str::plural('product', $result['created'])." imported from \"{$account->store_name}\".";

        if ($result['linked'] > 0) {
            $message .= " {$result['linked']} existing ".Str::plural('product', $result['linked']).' linked.';
        }

        if ($result['skipped'] > 0) {
            $message .= " {$result['skipped']} already here, so ".($result['skipped'] === 1 ? 'it was' : 'they were').' skipped.';
        }

        // The products are here now, so their past sales can be recorded.
        if ($result['created'] + $result['linked'] > 0) {
            SyncEbayOrderHistory::dispatch($account->id);

            $message .= ' Their past sales are being fetched in the background.';
        }

        EbayImportStatus::put($account->id, 'done', $message);
    }

    /**
     * Also reached on a timeout, which handle() never gets to catch.
     */
    public function failed(Throwable $e): void
    {
        $name = EbayAccount::find($this->accountId)?->store_name ?? "#{$this->accountId}";

        Log::error("eBay: importing listings for store \"{$name}\" failed: {$e->getMessage()}");

        EbayImportStatus::put($this->accountId, 'failed', "Importing from \"{$name}\" failed — {$e->getMessage()}");
    }
}
