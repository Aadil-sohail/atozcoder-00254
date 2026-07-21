<?php

namespace App\Services;

use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EbayReturnImporter
{
    /**
     * Return states where the item is actually on its way back to the seller
     * or the case was settled. Requests that are still open (RETURN_REQUESTED,
     * ITEM_SHIPPED), rejected or escalated are skipped until they progress;
     * they are picked up by a later sync once they reach one of these states.
     */
    private const COMPLETED_STATES = [
        'ITEM_DELIVERED',
        'REFUND_INITIATED',
        'REFUND_SENT',
        'CLOSED',
    ];

    /**
     * eBay return reasons implying the item comes back unsellable. These are
     * recorded with condition "bad" so the stock is NOT restored; everything
     * else (wrong size, no longer needed, ...) restocks as "good".
     */
    private const FAULTY_REASONS = [
        'ARRIVED_DAMAGED',
        'DEFECTIVE_ITEM',
        'MISSING_PARTS',
        'EXPIRED_ITEM',
        'FAKE_OR_COUNTERFEIT',
    ];

    public function __construct(private EbayService $ebay)
    {
    }

    /**
     * Pull recent return requests for the store and record a local sale return
     * for each completed one that has not been imported yet.
     *
     * @return array{created: int, skipped: int}
     */
    public function import(EbayAccount $account): array
    {
        $returns = $this->ebay->fetchReturns($account, (int) config('ebay.returns_lookback_days', 30));

        $created = 0;
        $skipped = 0;

        foreach ($returns as $return) {
            $this->importReturn($account, $return) ? $created++ : $skipped++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Record the local sale return for one eBay return. Returns false when it
     * was already imported, is still in progress, was closed without a refund,
     * or cannot be matched to an imported eBay sale.
     */
    private function importReturn(EbayAccount $account, array $return): bool
    {
        $returnId = $return['returnId'] ?? null;

        if (! $returnId || SaleReturn::where('ebay_return_id', $returnId)->exists()) {
            return false;
        }

        if (! in_array($return['state'] ?? '', self::COMPLETED_STATES, true)) {
            return false;
        }

        // CLOSED covers both "refunded" and "request dropped/expired": only a
        // refund that was actually paid means the item came back.
        if (($return['state'] ?? '') === 'CLOSED'
            && (float) data_get($return, 'sellerTotalRefund.actualRefundAmount.value', 0) <= 0) {
            return false;
        }

        $sale = Sale::with('saleItems')->where('ebay_order_id', $return['orderId'] ?? '')->first();

        if (! $sale) {
            Log::warning("eBay: return {$returnId} skipped, its order was never imported as a sale", [
                'order_id' => $return['orderId'] ?? null,
            ]);

            return false;
        }

        $saleItem = $this->matchSaleItem($account, $sale, data_get($return, 'creationInfo.item', []));

        if (! $saleItem) {
            Log::warning("eBay: return {$returnId} skipped, no sale item matches the returned listing", [
                'invoice_no' => $sale->invoice_no,
            ]);

            return false;
        }

        $remaining = max(0, (float) $saleItem->quantity - (float) $saleItem->returned_qty);
        $quantity = min((float) data_get($return, 'creationInfo.item.returnQuantity', 1), $remaining);

        if ($quantity <= 0) {
            Log::warning("eBay: return {$returnId} skipped, sale item already fully returned", [
                'invoice_no' => $sale->invoice_no,
            ]);

            return false;
        }

        $condition = in_array(data_get($return, 'creationInfo.reason'), self::FAULTY_REASONS, true) ? 'bad' : 'good';

        DB::transaction(function () use ($return, $returnId, $sale, $saleItem, $quantity, $condition) {
            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'return_date' => substr(data_get($return, 'creationInfo.creationDate.value', now()->toDateString()), 0, 10),
                'ebay_return_id' => $returnId,
                'inserted_by' => 'eBay Sync',
            ]);

            $saleReturn->items()->create([
                'sale_item_id' => $saleItem->id,
                'product_id' => $saleItem->product_id,
                'quantity' => $quantity,
                'condition' => $condition,
                'inserted_by' => 'eBay Sync',
            ]);

            $saleItem->increment('returned_qty', $quantity);

            // Same restock rule as manual returns: only sellable items go
            // back into stock (see ReturnController::store()).
            if ($condition === 'good') {
                Product::where('id', $saleItem->product_id)->decrement('sold_qty', $quantity);
            }

            $this->refreshSaleTotal($sale);
        });

        Log::info("eBay: return {$returnId} imported against sale {$sale->invoice_no} ({$quantity} x product #{$saleItem->product_id}, condition {$condition})");

        return true;
    }

    /**
     * Find the sale item the return refers to. The return summary carries the
     * eBay listing id (creationInfo.item.itemId), which maps to a local
     * product via the ebay_listings table. Single-line orders are matched
     * directly even when no listing record exists anymore.
     */
    private function matchSaleItem(EbayAccount $account, Sale $sale, array $item): ?SaleItem
    {
        if (! empty($item['itemId'])) {
            $productId = EbayListing::where('ebay_account_id', $account->id)
                ->where('listing_id', $item['itemId'])
                ->value('product_id');

            if ($productId && ($saleItem = $sale->saleItems->firstWhere('product_id', $productId))) {
                return $saleItem;
            }
        }

        return $sale->saleItems->count() === 1 ? $sale->saleItems->first() : null;
    }

    /**
     * Recalculate the sale total from the net (sold minus returned) value of
     * its items, matching ReturnController::refreshSaleTotal().
     */
    private function refreshSaleTotal(Sale $sale): void
    {
        $netValue = (float) $sale->saleItems()
            ->selectRaw('COALESCE(SUM((quantity - returned_qty) * selling_price), 0) as net')
            ->value('net');

        $sale->update([
            'total_amount' => max(0, round($netValue, 2) - (float) $sale->discount),
        ]);
    }
}
