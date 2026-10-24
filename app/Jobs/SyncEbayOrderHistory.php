<?php

namespace App\Jobs;

use App\Models\EbayAccount;
use App\Services\EbayOrderImporter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

/**
 * After products are imported from a store, pull every order eBay still keeps
 * for it (config ebay.orders_history_days), not just the regular sync's
 * window. Orders whose products are here become sales; the rest keep waiting.
 */
class SyncEbayOrderHistory implements ShouldQueue
{
    use Queueable;

    /** The regular sync holds the same per-store lock, so a clash is retried. */
    public int $tries = 3;

    public int $backoff = 300;

    public int $timeout = 1800;

    public function __construct(public int $accountId)
    {
    }

    public function handle(EbayOrderImporter $importer): void
    {
        $account = EbayAccount::find($this->accountId);

        if (! $account) {
            return;
        }

        $days = (int) config('ebay.orders_history_days', 730);

        try {
            $result = $importer->import($account, $days);
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'already running')) {
                $this->release($this->backoff);

                return;
            }

            throw $e;
        }

        Log::info("eBay: order history ({$days} days) for store \"{$account->store_name}\" — {$result['created']} new sales, {$result['skipped']} already here or waiting");
    }

    public function failed(Throwable $e): void
    {
        Log::error("eBay: order history sync for store #{$this->accountId} failed: {$e->getMessage()}");
    }
}
