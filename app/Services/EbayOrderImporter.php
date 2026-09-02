<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class EbayOrderImporter
{
    /**
     * Products already resolved during this run, keyed by eBay item id. The
     * same listing routinely sells across many orders and every miss costs a
     * call to eBay, so each id is looked up once per sync.
     *
     * @var array<string, int|null>
     */
    private array $resolved = [];

    public function __construct(
        private EbayService $ebay,
        private EbayListingImporter $listings,
    ) {}

    /**
     * Pull recent orders for the store and create a local sale for each one
     * that has not been imported yet.
     *
     * Only one sync per store runs at a time. A slow first run invites a
     * second click, and the scheduled sync can land on top of a manual one:
     * two passes reading the same orders would each see them as new.
     *
     * @return array{created: int, skipped: int}
     */
    public function import(EbayAccount $account): array
    {
        $lock = Cache::lock("ebay:orders:{$account->id}", 900);

        if (! $lock->get()) {
            throw new RuntimeException('a sync for this store is already running, give it a moment to finish.');
        }

        try {
            $orders = $this->ebay->fetchOrders($account, (int) config('ebay.orders_lookback_days', 30));

            $created = 0;
            $skipped = 0;

            foreach ($orders as $order) {
                $this->importOrder($account, $order) ? $created++ : $skipped++;
            }

            return ['created' => $created, 'skipped' => $skipped];
        } finally {
            $lock->release();
        }
    }

    /**
     * Create the local sale for one eBay order. Returns false when the order
     * was already imported, is cancelled, or matches no local product.
     */
    private function importOrder(EbayAccount $account, array $order): bool
    {
        $orderId = $order['orderId'] ?? null;

        if (! $orderId) {
            return false;
        }

        // The same order reaches us under two different ids: the Fulfillment
        // API reports its own orderId, the Trading API only the legacy one.
        // The legacy id is the one both agree on, so a sale is keyed by that
        // wherever eBay gives it. Keyed by the other, the same order arriving
        // from the other source later would look brand new.
        $legacyId = $order['legacyOrderId'] ?? null;
        $ids = array_values(array_unique(array_filter([$orderId, $legacyId])));
        $key = $legacyId ?: $orderId;

        // Both ids are checked, not just the key, so an order imported before
        // this ran, under whichever id arrived first, still counts as here.
        if (Sale::whereIn('ebay_order_id', $ids)->exists()) {
            return false;
        }

        if (($order['cancelStatus']['cancelState'] ?? 'NONE_REQUESTED') === 'CANCELED') {
            return false;
        }

        $items = $this->matchLineItems($account, $order['lineItems'] ?? []);

        if ($items === []) {
            Log::warning("eBay: order {$orderId} skipped, no line item matches a local product");

            return false;
        }

        try {
            DB::transaction(function () use ($account, $order, $key, $items) {
                $customer = $this->resolveCustomer($order);

                $sale = Sale::create([
                    'customer_id' => $customer->id,
                    'invoice_no' => 'EBAY-'.$key,
                    'sale_date' => substr($order['creationDate'] ?? now()->toDateString(), 0, 10),
                    'discount' => 0,
                    'total_amount' => collect($items)->sum('subtotal'),
                    'ebay_order_id' => $key,
                    'ebay_account_id' => $account->id,
                    'inserted_by' => 'eBay Sync',
                ]);

                foreach ($items as $item) {
                    $sale->saleItems()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'selling_price' => $item['selling_price'],
                        'subtotal' => $item['subtotal'],
                        'inserted_by' => 'eBay Sync',
                    ]);

                    Product::where('id', $item['product_id'])->increment('sold_qty', $item['quantity']);
                }
            });
        } catch (QueryException $e) {
            // sales.ebay_order_id and sales.invoice_no are both unique, so a
            // second run that somehow got this far is stopped by the database
            // rather than doubling the sale and its sold quantities. The
            // transaction rolled back, so nothing was half written: this is
            // just an order that turned out to be here already.
            if (! $this->isDuplicate($e)) {
                throw $e;
            }

            Log::info("eBay: order {$key} was already imported by another run for store \"{$account->store_name}\"");

            return false;
        }

        Log::info("eBay: order {$key} imported as sale EBAY-{$key} for store \"{$account->store_name}\"");

        return true;
    }

    /**
     * Whether a write failed because the row is already there, rather than for
     * some other reason worth surfacing.
     */
    private function isDuplicate(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062 || str_starts_with((string) $e->getCode(), '23');
    }

    /**
     * Map eBay line items onto local products. A listing that is not here yet
     * is imported from eBay on the spot, so an order is only ever skipped when
     * eBay itself cannot identify the item.
     */
    private function matchLineItems(EbayAccount $account, array $lineItems): array
    {
        $items = [];

        foreach ($lineItems as $line) {
            $quantity = (float) ($line['quantity'] ?? 0);
            $productId = $this->resolveProduct($account, $line);

            if (! $productId || $quantity <= 0) {
                Log::warning('eBay: line item skipped, no matching local product', [
                    'sku' => $line['sku'] ?? null,
                    'item_id' => $line['legacyItemId'] ?? null,
                    'title' => $line['title'] ?? null,
                    'quantity' => $quantity,
                ]);

                continue;
            }

            $lineTotal = (float) ($line['lineItemCost']['value'] ?? 0);

            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'selling_price' => round($lineTotal / $quantity, 2),
                'subtotal' => round($lineTotal, 2),
            ];
        }

        return $items;
    }

    /**
     * The local product one eBay line item is for.
     *
     * Everything eBay gives us is tried before giving up, because a store's
     * listings frequently carry no SKU at all — orders then arrive with
     * sku: null and the item id as the only handle on what was sold:
     *
     *  1. the SKU, against this store's links and then the products table;
     *  2. the item id, against this store's links and any other store's;
     *  3. the synthetic "EBAY-{item id}" SKU a listing with no SKU of its own
     *     is imported under, for a product whose store link went missing;
     *  4. failing all of that, the listing is fetched from eBay and imported.
     */
    private function resolveProduct(EbayAccount $account, array $line): ?int
    {
        $sku = $line['sku'] ?? null;
        $itemId = trim((string) ($line['legacyItemId'] ?? ''));

        if ($sku) {
            $productId = EbayListing::where('ebay_account_id', $account->id)->where('sku', $sku)->value('product_id')
                ?? Product::where('sku', $sku)->value('id');

            if ($productId) {
                return (int) $productId;
            }
        }

        if ($itemId === '') {
            return null;
        }

        if (array_key_exists($itemId, $this->resolved)) {
            return $this->resolved[$itemId];
        }

        $productId = EbayListing::where('listing_id', $itemId)
            ->orderByRaw('ebay_account_id = ? desc', [$account->id])
            ->value('product_id')
            ?? Product::where('sku', 'EBAY-'.$itemId)->value('id');

        if (! $productId) {
            $productId = $this->listings->importByListingId($account, $itemId);
        }

        return $this->resolved[$itemId] = $productId ? (int) $productId : null;
    }

    /**
     * Find or create the local customer for the order's buyer. Buyers are
     * keyed by their eBay username so repeat orders reuse the same customer.
     */
    private function resolveCustomer(array $order): Customer
    {
        $username = $order['buyer']['username'] ?? 'unknown-buyer';
        $shipTo = data_get($order, 'fulfillmentStartInstructions.0.shippingStep.shipTo', []);
        $email = $shipTo['email'] ?? null;

        if ($email && ($existing = Customer::where('email', $email)->first())) {
            return $existing;
        }

        $address = $shipTo['contactAddress'] ?? [];
        $addressText = collect([
            $address['addressLine1'] ?? null,
            $address['addressLine2'] ?? null,
            $address['city'] ?? null,
            $address['stateOrProvince'] ?? null,
            $address['postalCode'] ?? null,
            $address['countryCode'] ?? null,
        ])->filter()->implode(', ');

        return Customer::firstOrCreate(
            ['name' => ($shipTo['fullName'] ?? $username).' ('.$username.')'],
            [
                'email' => $email,
                'phone' => Str::limit($shipTo['primaryPhone']['phoneNumber'] ?? '', 30, '') ?: null,
                'address' => $addressText ?: null,
                'inserted_by' => 'eBay Sync',
            ]
        );
    }
}
