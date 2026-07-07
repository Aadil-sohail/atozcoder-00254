<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EbayOrderImporter
{
    public function __construct(private EbayService $ebay)
    {
    }

    /**
     * Pull recent orders for the store and create a local sale for each one
     * that has not been imported yet.
     *
     * @return array{created: int, skipped: int}
     */
    public function import(EbayAccount $account): array
    {
        $orders = $this->ebay->fetchOrders($account, (int) config('ebay.orders_lookback_days', 30));

        $created = 0;
        $skipped = 0;

        foreach ($orders as $order) {
            $this->importOrder($account, $order) ? $created++ : $skipped++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Create the local sale for one eBay order. Returns false when the order
     * was already imported, is cancelled, or matches no local product.
     */
    private function importOrder(EbayAccount $account, array $order): bool
    {
        $orderId = $order['orderId'] ?? null;

        if (! $orderId || Sale::where('ebay_order_id', $orderId)->exists()) {
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

        DB::transaction(function () use ($account, $order, $orderId, $items) {
            $customer = $this->resolveCustomer($order);

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'invoice_no' => 'EBAY-'.$orderId,
                'sale_date' => substr($order['creationDate'] ?? now()->toDateString(), 0, 10),
                'discount' => 0,
                'total_amount' => collect($items)->sum('subtotal'),
                'ebay_order_id' => $orderId,
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

        Log::info("eBay: order {$orderId} imported as sale EBAY-{$orderId} for store \"{$account->store_name}\"");

        return true;
    }

    /**
     * Map eBay line items onto local products via the listing SKU (falling
     * back to the product SKU, then the listing id). Unmatched lines are
     * skipped with a log entry.
     */
    private function matchLineItems(EbayAccount $account, array $lineItems): array
    {
        $items = [];

        foreach ($lineItems as $line) {
            $sku = $line['sku'] ?? null;
            $quantity = (float) ($line['quantity'] ?? 0);

            $productId = null;

            if ($sku) {
                $productId = EbayListing::where('ebay_account_id', $account->id)->where('sku', $sku)->value('product_id')
                    ?? Product::where('sku', $sku)->value('id');
            }

            if (! $productId && ! empty($line['legacyItemId'])) {
                $productId = EbayListing::where('ebay_account_id', $account->id)
                    ->where('listing_id', $line['legacyItemId'])
                    ->value('product_id');
            }

            if (! $productId || $quantity <= 0) {
                Log::warning('eBay: line item skipped, no matching local product', [
                    'sku' => $sku,
                    'title' => $line['title'] ?? null,
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
