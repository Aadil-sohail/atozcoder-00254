<?php

namespace App\Jobs;

use App\Models\EbayListing;
use App\Services\EbayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use Throwable;

class SyncProductToEbay implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 120;

    public function __construct(public int $listingId)
    {
    }

    public function handle(EbayService $ebay): void
    {
        $listing = EbayListing::with(['product', 'ebayAccount'])->find($this->listingId);

        if (! $listing || ! $listing->product || ! $listing->ebayAccount) {
            return;
        }

        $listing->update(['sync_status' => 'syncing']);

        try {
            $ebay->syncListing($listing);
        } catch (Throwable $e) {
            $listing->update([
                'sync_status' => 'failed',
                'last_error' => Str::limit($e->getMessage(), 2000),
            ]);
        }
    }
}
