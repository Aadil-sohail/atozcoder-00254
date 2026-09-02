<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Models\Sale;
use App\Services\EbayOrderImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * The live store sold hundreds of items and not one order came through: its
 * listings carry no seller SKU, so every order arrived with sku: null and an
 * item id that matched nothing here, those listings never having been picked
 * on the import screen. These cover the order sync pulling the listing in for
 * itself instead of dropping the sale.
 */
class EbayOrderSyncTest extends TestCase
{
    use RefreshDatabase;

    private EbayAccount $account;

    /** @var list<array<string, mixed>> what the Fulfillment search returns */
    private array $orders = [];

    /** What GetItem answers with. */
    private string $getItemXml = '';

    /** What the Trading API's GetOrders fallback answers with. */
    private string $getOrdersXml = '';

    protected function setUp(): void
    {
        parent::setUp();

        config(['ebay.sandbox' => false, 'ebay.client_id' => 'x', 'ebay.client_secret' => 'y']);

        $this->account = EbayAccount::create([
            'store_name' => 'ldnautoparts',
            'ebay_username' => 'ldnautoparts',
            'marketplace_id' => 'EBAY_GB',
            'access_token' => 'valid-token',
            'access_token_expires_at' => now()->addHour(),
            'refresh_token' => 'refresh-token',
            'refresh_token_expires_at' => now()->addYear(),
        ]);
    }

    /**
     * One order, as the Fulfillment API reports it.
     *
     * @param  list<array<string, mixed>>  $lineItems
     * @return array<string, mixed>
     */
    private function order(string $orderId, array $lineItems): array
    {
        return [
            'orderId' => $orderId,
            'legacyOrderId' => $orderId,
            'creationDate' => '2026-08-30T10:12:00.000Z',
            'buyer' => ['username' => 'partsbuyer'],
            'cancelStatus' => ['cancelState' => 'NONE_REQUESTED'],
            'lineItems' => $lineItems,
            'fulfillmentStartInstructions' => [[
                'shippingStep' => ['shipTo' => [
                    'fullName' => 'A Buyer',
                    'email' => 'buyer@example.com',
                    'contactAddress' => [
                        'addressLine1' => '1 High St',
                        'city' => 'London',
                        'postalCode' => 'E1 6AN',
                        'countryCode' => 'GB',
                    ],
                ]],
            ]],
        ];
    }

    /**
     * A line item for a listing with no seller SKU, exactly as the live orders
     * arrive: sku null, the item id the only handle on what was sold.
     *
     * @return array<string, mixed>
     */
    private function lineItem(string $itemId, string $title, float $quantity = 1, string $cost = '59.50', ?string $sku = null): array
    {
        return [
            'lineItemId' => '1',
            'legacyItemId' => $itemId,
            'sku' => $sku,
            'title' => $title,
            'quantity' => $quantity,
            'lineItemCost' => ['value' => $cost],
        ];
    }

    private function getItemXml(string $itemId, string $title, ?string $sku = null): string
    {
        $skuNode = $sku === null ? '' : "<SKU>{$sku}</SKU>";

        return <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <GetItemResponse xmlns="urn:ebay:apis:eBLBaseComponents">
          <Ack>Success</Ack>
          <Item>
            <ItemID>{$itemId}</ItemID>
            <Title>{$title}</Title>
            {$skuNode}
            <ConditionID>1000</ConditionID>
            <Quantity>3</Quantity>
            <PrimaryCategory><CategoryID>33710</CategoryID></PrimaryCategory>
            <PictureDetails><PictureURL>https://i.ebayimg.com/images/g/abc/s-l500.jpg</PictureURL></PictureDetails>
            <SellingStatus><CurrentPrice currencyID="GBP">59.50</CurrentPrice><QuantitySold>1</QuantitySold></SellingStatus>
            <Description>&lt;p&gt;Genuine part&lt;/p&gt;</Description>
            <ItemSpecifics>
              <NameValueList><Name>Manufacturer Warranty</Name><Value>1 Year</Value></NameValueList>
            </ItemSpecifics>
          </Item>
        </GetItemResponse>
        XML;
    }

    /**
     * Both Trading calls the sync makes — GetOrders and GetItem — post to the
     * same URL, so they are told apart by the call-name header eBay requires.
     *
     * @param  list<array<string, mixed>>  $orders
     */
    private function fakeEbay(array $orders, string $getItemXml, ?string $getOrdersXml = null): void
    {
        $this->orders = $orders;
        $this->getItemXml = $getItemXml;
        $this->getOrdersXml = $getOrdersXml ?? $this->legacyOrdersXml([]);

        // Registered once: the answers come off the properties above, so a
        // test can change what eBay says between two syncs.
        Http::fake(function (Request $request) {
            if (str_contains($request->url(), '/sell/fulfillment/v1/order')) {
                return Http::response(['orders' => $this->orders, 'total' => count($this->orders)]);
            }

            if (str_contains($request->url(), '/ws/api.dll')) {
                return $this->tradingCall($request) === 'GetItem'
                    ? Http::response($this->getItemXml)
                    : Http::response($this->getOrdersXml);
            }

            return Http::response('fake-image-bytes');
        });
    }

    /**
     * The Trading API's GetOrders answer, which knows an order only by its
     * legacy id.
     *
     * @param  list<array{id: string, item_id: string, title: string}>  $orders
     */
    private function legacyOrdersXml(array $orders): string
    {
        $nodes = '';

        foreach ($orders as $order) {
            $nodes .= <<<XML
            <Order>
              <OrderID>{$order['id']}</OrderID>
              <OrderStatus>Completed</OrderStatus>
              <CreatedTime>2026-08-30T10:12:00.000Z</CreatedTime>
              <BuyerUserID>partsbuyer</BuyerUserID>
              <CheckoutStatus><Status>Complete</Status></CheckoutStatus>
              <TransactionArray>
                <Transaction>
                  <QuantityPurchased>1</QuantityPurchased>
                  <TransactionPrice currencyID="GBP">59.50</TransactionPrice>
                  <Buyer><Email>buyer@example.com</Email></Buyer>
                  <Item>
                    <ItemID>{$order['item_id']}</ItemID>
                    <Title>{$order['title']}</Title>
                  </Item>
                </Transaction>
              </TransactionArray>
              <ShippingAddress><Name>A Buyer</Name><CityName>London</CityName><Country>GB</Country></ShippingAddress>
            </Order>
            XML;
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'
            ."<GetOrdersResponse xmlns=\"urn:ebay:apis:eBLBaseComponents\"><Ack>Success</Ack><OrderArray>{$nodes}</OrderArray></GetOrdersResponse>";
    }

    private function tradingCall(Request $request): string
    {
        return $request->header('X-EBAY-API-CALL-NAME')[0] ?? '';
    }

    private function getItemCallCount(): int
    {
        return collect(Http::recorded())
            ->filter(fn (array $exchange) => str_contains($exchange[0]->url(), '/ws/api.dll')
                && $this->tradingCall($exchange[0]) === 'GetItem')
            ->count();
    }

    /**
     * @return array{created: int, skipped: int}
     */
    private function sync(): array
    {
        return app(EbayOrderImporter::class)->import($this->account);
    }

    public function test_an_order_for_a_listing_nobody_imported_still_becomes_a_sale(): void
    {
        $this->fakeEbay(
            [$this->order('20-15093-30637', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')])],
            $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'),
        );

        $result = $this->sync();

        $this->assertSame(1, $result['created']);

        // The listing was pulled in and linked, keyed by its item id because
        // the seller never gave it a SKU.
        $product = Product::where('sku', 'EBAY-110590104030')->firstOrFail();
        $this->assertSame('Audi A4 2015 Wing Mirror', $product->name);
        $this->assertEquals(1, $product->sold_qty);

        $listing = EbayListing::where('listing_id', '110590104030')->firstOrFail();
        $this->assertSame($product->id, $listing->product_id);
        $this->assertSame($this->account->id, $listing->ebay_account_id);

        $sale = Sale::where('ebay_order_id', '20-15093-30637')->firstOrFail();
        $this->assertSame('EBAY-20-15093-30637', $sale->invoice_no);
        $this->assertEquals(59.50, $sale->total_amount);
        $this->assertSame($product->id, $sale->saleItems()->firstOrFail()->product_id);
    }

    /**
     * A part that sells over and over is the normal case, not a duplicate:
     * every order is its own sale, all of them against the one product.
     */
    public function test_the_same_product_selling_in_several_orders_is_a_sale_each_time(): void
    {
        $this->fakeEbay([
            $this->order('20-15093-30637', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')]),
            $this->order('03-15123-68672', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror', 2, '119.00')]),
        ], $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'));

        $result = $this->sync();

        $this->assertSame(2, $result['created']);
        $this->assertSame(2, Sale::count());

        // One product, one listing link, its sold quantity the sum of both.
        $product = Product::firstOrFail();
        $this->assertSame(1, Product::count());
        $this->assertSame(1, EbayListing::count());
        $this->assertEquals(3, $product->sold_qty);

        // Each order kept its own line and its own money.
        $first = Sale::where('ebay_order_id', '20-15093-30637')->firstOrFail();
        $second = Sale::where('ebay_order_id', '03-15123-68672')->firstOrFail();

        $this->assertEquals(59.50, $first->total_amount);
        $this->assertEquals(119.00, $second->total_amount);
        $this->assertEquals(1, $first->saleItems()->firstOrFail()->quantity);
        $this->assertEquals(2, $second->saleItems()->firstOrFail()->quantity);
        $this->assertSame($product->id, $first->saleItems()->firstOrFail()->product_id);
        $this->assertSame($product->id, $second->saleItems()->firstOrFail()->product_id);

        // The listing itself was only fetched from eBay once.
        $this->assertSame(1, $this->getItemCallCount());
    }

    /**
     * And the same part twice inside one order stays two lines, not one
     * mistaken for a repeat of the other.
     */
    public function test_the_same_product_twice_in_one_order_is_two_lines(): void
    {
        $this->fakeEbay([
            $this->order('20-15093-30637', [
                $this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror'),
                $this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror', 2, '119.00'),
            ]),
        ], $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'));

        $this->assertSame(1, $this->sync()['created']);

        $sale = Sale::firstOrFail();

        $this->assertSame(2, $sale->saleItems()->count());
        $this->assertEquals(178.50, $sale->total_amount);
        $this->assertEquals(3, Product::firstOrFail()->sold_qty);
    }

    public function test_a_listing_already_here_is_reused_rather_than_fetched_again(): void
    {
        $product = Product::create([
            'name' => 'Audi A4 2015 Wing Mirror',
            'sku' => 'LDN-009',
            'category_id' => Category::create(['name' => 'Mirrors', 'inserted_by' => 'Test'])->id,
            'selling_price' => '59.50',
            'total_qty' => 4,
            'sold_qty' => 0,
            'inserted_by' => 'Test',
        ]);

        EbayListing::create([
            'product_id' => $product->id,
            'ebay_account_id' => $this->account->id,
            'sku' => 'LDN-009',
            'listing_id' => '110590104030',
            'condition' => 'NEW',
            'sync_status' => 'synced',
        ]);

        $this->fakeEbay(
            [$this->order('20-15093-30637', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')])],
            $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'),
        );

        $this->assertSame(1, $this->sync()['created']);
        $this->assertSame(1, Product::count());
        $this->assertEquals(1, $product->refresh()->sold_qty);
        $this->assertSame(0, $this->getItemCallCount());
    }

    public function test_an_item_ebay_cannot_identify_is_skipped_without_breaking_the_run(): void
    {
        $this->fakeEbay([
            $this->order('20-15093-30637', [$this->lineItem('', 'Mystery part')]),
            $this->order('03-15123-68672', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')]),
        ], $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'));

        $result = $this->sync();

        $this->assertSame(1, $result['created']);
        $this->assertSame(1, $result['skipped']);
        $this->assertDatabaseMissing('sales', ['ebay_order_id' => '20-15093-30637']);
        $this->assertDatabaseHas('sales', ['ebay_order_id' => '03-15123-68672']);
    }

    public function test_an_order_already_imported_is_not_imported_twice(): void
    {
        $this->fakeEbay(
            [$this->order('20-15093-30637', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')])],
            $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'),
        );

        $this->sync();
        $second = $this->sync();

        $this->assertSame(0, $second['created']);
        $this->assertSame(1, Sale::count());
        $this->assertEquals(1, Product::firstOrFail()->sold_qty);
    }

    /**
     * The Fulfillment API keys an order by its own orderId and reports the
     * legacy id alongside; the Trading fallback knows only the legacy one.
     * Keyed by the wrong one, the same order coming back from the other source
     * would be imported a second time.
     */
    public function test_an_order_seen_by_both_apis_is_imported_only_once(): void
    {
        $fulfillmentOrder = $this->order('17-09717-33189', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')]);
        $fulfillmentOrder['legacyOrderId'] = '110590104030-10000011957210';

        $this->fakeEbay([$fulfillmentOrder], $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'));

        $this->assertSame(1, $this->sync()['created']);

        // Keyed by the id both APIs agree on.
        $this->assertDatabaseHas('sales', ['ebay_order_id' => '110590104030-10000011957210']);

        // Now the Fulfillment search no longer returns it and only the Trading
        // fallback does, under that legacy id.
        $this->orders = [];
        $this->getOrdersXml = $this->legacyOrdersXml([[
            'id' => '110590104030-10000011957210',
            'item_id' => '110590104030',
            'title' => 'Audi A4 2015 Wing Mirror',
        ]]);

        $this->assertSame(0, $this->sync()['created']);
        $this->assertSame(1, Sale::count());
        $this->assertEquals(1, Product::firstOrFail()->sold_qty);
    }

    /**
     * The reverse: imported from the Trading fallback first, then the same
     * order turns up in the Fulfillment search under its own orderId.
     */
    public function test_an_order_imported_from_the_trading_fallback_is_not_reimported(): void
    {
        $this->fakeEbay([], $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'), $this->legacyOrdersXml([[
            'id' => '110590104030-10000011957210',
            'item_id' => '110590104030',
            'title' => 'Audi A4 2015 Wing Mirror',
        ]]));

        $this->assertSame(1, $this->sync()['created']);

        $fulfillmentOrder = $this->order('17-09717-33189', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')]);
        $fulfillmentOrder['legacyOrderId'] = '110590104030-10000011957210';

        $this->orders = [$fulfillmentOrder];
        $this->getOrdersXml = $this->legacyOrdersXml([]);

        $this->assertSame(0, $this->sync()['created']);
        $this->assertSame(1, Sale::count());
        $this->assertEquals(1, Product::firstOrFail()->sold_qty);
    }

    /**
     * A second click, or the scheduled sync landing on a manual one, must not
     * have two passes importing the same orders side by side.
     */
    public function test_a_second_sync_for_the_same_store_is_refused_while_one_is_running(): void
    {
        $this->fakeEbay(
            [$this->order('20-15093-30637', [$this->lineItem('110590104030', 'Audi A4 2015 Wing Mirror')])],
            $this->getItemXml('110590104030', 'Audi A4 2015 Wing Mirror'),
        );

        $held = Cache::lock("ebay:orders:{$this->account->id}", 900);
        $this->assertTrue($held->get());

        try {
            $this->sync();
            $this->fail('A second sync should not have been allowed to start.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('already running', $e->getMessage());
        } finally {
            $held->release();
        }

        $this->assertSame(0, Sale::count());

        // Released, the next run goes ahead as normal.
        $this->assertSame(1, $this->sync()['created']);
    }
}
