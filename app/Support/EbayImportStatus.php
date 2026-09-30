<?php

namespace App\Support;

use App\Models\EbayAccount;
use Illuminate\Support\Facades\Cache;

/**
 * Where a store's background "Import from eBay" has got to.
 *
 * The fetch and the import both run on the queue, so the products page learns
 * how they are doing from here — the web request and the queue worker share
 * the cache. One entry per store:
 *
 *  - fetching   listings are being pulled into the pick list
 *  - ready      the pick list is waiting for the user
 *  - importing  the ticked listings are being turned into products
 *  - done       finished; the message says what happened
 *  - failed     stopped; the message says why
 */
class EbayImportStatus
{
    /**
     * A job longer than this is assumed dead (worker killed, never started),
     * so the store is no longer blocked from starting another import.
     */
    private const STALE_AFTER_MINUTES = 35;

    public static function get(int $accountId): ?array
    {
        return Cache::get(self::key($accountId));
    }

    public static function put(int $accountId, string $state, ?string $message = null): void
    {
        Cache::forever(self::key($accountId), [
            'state' => $state,
            'message' => $message,
            'at' => now()->timestamp,
        ]);
    }

    public static function forget(int $accountId): void
    {
        Cache::forget(self::key($accountId));
    }

    /**
     * True while a fetch or an import for this store is still running.
     */
    public static function busy(int $accountId): bool
    {
        $status = self::get($accountId);

        return $status
            && in_array($status['state'], ['fetching', 'importing'], true)
            && $status['at'] > now()->subMinutes(self::STALE_AFTER_MINUTES)->timestamp;
    }
    
    public static function runningAccountId(): ?int
    {
        return EbayAccount::pluck('id')->first(fn (int $id) => self::busy($id));
    }

    private static function key(int $accountId): string
    {
        return "ebay.import.{$accountId}";
    }
}
