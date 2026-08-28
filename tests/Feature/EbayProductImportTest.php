<?php

namespace Tests\Feature;

use App\Models\EbayAccount;
use App\Models\EbayListing;
use App\Models\Product;
use App\Services\EbayListingImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * A shop listed through eBay's own tools is invisible to the Inventory API —
 * it answers 200 with an empty list — so an import driven by that endpoint
 * alone reported "0 inventory items fetched" against hundreds of live
 * products. These cover the Trading API pass that actually sees the shop.
 */
class EbayProductImportTest extends TestCase
{
    use RefreshDatabase;

    private EbayAccount $account;

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
     * Two listings as GetMyeBaySelling really returns them: the first with a
     * seller SKU, the second — like most shop listings — with none at all.
     */
    private function activeListingsXml(int $totalPages = 1): string
    {
        $xml = <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <GetMyeBaySellingResponse xmlns="urn:ebay:apis:eBLBaseComponents">
          <Ack>Success</Ack>
          <ActiveList>
            <PaginationResult>
              <TotalNumberOfPages>TOTAL_PAGES</TotalNumberOfPages>
              <TotalNumberOfEntries>2</TotalNumberOfEntries>
            </PaginationResult>
            <ItemArray>
              <Item>
                <ItemID>110590104029</ItemID>
                <Title>BMW 1 Series 2012-2016 Headlight</Title>
                <SKU>LDN-001</SKU>
                <ConditionID>3000</ConditionID>
                <Quantity>5</Quantity>
                <QuantityAvailable>4</QuantityAvailable>
                <PrimaryCategory><CategoryID>33710</CategoryID></PrimaryCategory>
                <PictureDetails>
                  <GalleryURL>https://i.ebayimg.com/images/g/abc/s-l500.jpg</GalleryURL>
                </PictureDetails>
                <SellingStatus>
                  <CurrentPrice currencyID="GBP">129.99</CurrentPrice>
                  <QuantitySold>1</QuantitySold>
                </SellingStatus>
              </Item>
              <Item>
                <ItemID>110590104030</ItemID>
                <Title>Audi A4 2015 Wing Mirror</Title>
                <ConditionID>1000</ConditionID>
                <Quantity>2</Quantity>
                <PrimaryCategory><CategoryID>33710</CategoryID></PrimaryCategory>
                <SellingStatus>
                  <CurrentPrice currencyID="GBP">59.50</CurrentPrice>
                  <QuantitySold>0</QuantitySold>
                </SellingStatus>
              </Item>
            </ItemArray>
          </ActiveList>
        </GetMyeBaySellingResponse>
        XML;

        return str_replace('TOTAL_PAGES', (string) $totalPages, $xml);
    }

    private function fakeEbay(?string $tradingXml = null): void
    {
        Http::fake([
            // Exactly what the live store answered: empty, not an error.
            'api.ebay.com/sell/inventory/v1/inventory_item*' => Http::response(['inventoryItems' => []]),
            'api.ebay.com/ws/api.dll' => Http::response($tradingXml ?? $this->activeListingsXml()),
            'i.ebayimg.com/*' => Http::response('fake-image-bytes'),
            '*' => Http::response([]),
        ]);
    }

    private function import(): array
    {
        return app(EbayListingImporter::class)->import($this->account);
    }

    public function test_shop_listings_are_imported_even_though_the_inventory_api_is_empty(): void
    {
        $this->fakeEbay();

        $result = $this->import();

        $this->assertSame(2, $result['created']);
        $this->assertSame(2, Product::count());
        $this->assertDatabaseHas('products', ['name' => 'BMW 1 Series 2012-2016 Headlight']);
        $this->assertDatabaseHas('products', ['name' => 'Audi A4 2015 Wing Mirror']);
    }

    public function test_price_quantity_and_condition_come_across(): void
    {
        $this->fakeEbay();
        $this->import();

        $headlight = Product::where('sku', 'LDN-001')->firstOrFail();

        $this->assertSame('129.99', $headlight->selling_price);
        // QuantityAvailable wins where eBay reports it.
        $this->assertEquals(4, $headlight->total_qty);

        $listing = EbayListing::where('sku', 'LDN-001')->firstOrFail();

        $this->assertSame('110590104029', $listing->listing_id);
        $this->assertSame('33710', $listing->ebay_category_id);
        $this->assertSame('USED_EXCELLENT', $listing->condition);
        $this->assertSame('synced', $listing->sync_status);
    }

    /**
     * Most shop listings have no seller SKU. Before, every one of them was
     * dropped for having nothing to key the link on.
     */
    public function test_a_listing_without_a_sku_is_keyed_by_its_ebay_item_id(): void
    {
        $this->fakeEbay();
        $this->import();

        $mirror = Product::where('sku', 'EBAY-110590104030')->firstOrFail();

        $this->assertSame('Audi A4 2015 Wing Mirror', $mirror->name);
        $this->assertSame('59.50', $mirror->selling_price);
        // No QuantityAvailable, so Quantity minus QuantitySold stands in.
        $this->assertEquals(2, $mirror->total_qty);
        $this->assertSame('NEW', EbayListing::where('sku', 'EBAY-110590104030')->firstOrFail()->condition);
    }

    public function test_running_the_import_twice_does_not_duplicate_products(): void
    {
        $this->fakeEbay();

        $this->import();
        $second = $this->import();

        $this->assertSame(2, Product::count());
        $this->assertSame(0, $second['created']);
        $this->assertSame(2, $second['skipped']);
    }

    public function test_every_page_of_a_large_shop_is_fetched(): void
    {
        // Three pages of the same two listings: the second and third are
        // deduped by SKU, but all three pages must actually be requested.
        $this->fakeEbay($this->activeListingsXml(3));

        $this->import();

        $tradingCalls = collect(Http::recorded())
            ->filter(fn (array $exchange) => str_contains($exchange[0]->url(), '/ws/api.dll'))
            ->count();

        $this->assertSame(3, $tradingCalls);
    }

    public function test_a_rejected_trading_call_is_logged_not_thrown(): void
    {
        $this->fakeEbay(<<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <GetMyeBaySellingResponse xmlns="urn:ebay:apis:eBLBaseComponents">
          <Ack>Failure</Ack>
          <Errors>
            <ErrorCode>21917053</ErrorCode>
            <ShortMessage>Insufficient permissions</ShortMessage>
            <LongMessage>The token does not grant this call.</LongMessage>
          </Errors>
        </GetMyeBaySellingResponse>
        XML);

        $result = $this->import();

        $this->assertSame(0, $result['created']);
        $this->assertSame(0, Product::count());
    }
}
