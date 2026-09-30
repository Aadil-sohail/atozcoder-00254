<?php

namespace App\Jobs;

use App\Models\EbayAccount;
use App\Services\EbayListingImporter;
use App\Support\EbayImportStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Step one of "Import from eBay", off the request: pull everything the store
 * has listed into the pick list.
 */
class StageEbayListings implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 1800;

    public function __construct(public int $accountId)
    {
    }

    public function handle(EbayListingImporter $importer): void
    {
        $account = EbayAccount::find($this->accountId);

        if (! $account) {
            EbayImportStatus::forget($this->accountId);

            return;
        }

        $staged = $importer->stage($account);

        $staged === 0
            ? EbayImportStatus::put($account->id, 'done', "Nothing is listed on \"{$account->store_name}\" right now, so there is nothing to import.")
            : EbayImportStatus::put($account->id, 'ready', "{$staged} ".str('listing')->plural($staged)." from \"{$account->store_name}\" ".($staged === 1 ? 'is' : 'are').' ready — choose which to import.');
    }

    /**
     * Also reached on a timeout, which handle() never gets to catch.
     */
    public function failed(Throwable $e): void
    {
        $name = EbayAccount::find($this->accountId)?->store_name ?? "#{$this->accountId}";

        Log::error("eBay: fetching listings for store \"{$name}\" failed: {$e->getMessage()}");

        EbayImportStatus::put($this->accountId, 'failed', "Fetching listings from \"{$name}\" failed — {$e->getMessage()}");
    }
}
