<?php

namespace App\Console\Commands;

use App\Models\EbayAccount;
use App\Services\EbayOrderImporter;
use Illuminate\Console\Command;
use Throwable;

class SyncEbayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebay:sync-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import new eBay orders as local sales and update product sold quantities';

    /**
     * Execute the console command.
     */
    public function handle(EbayOrderImporter $importer): int
    {
        $accounts = EbayAccount::where('status', '1')->get();

        if ($accounts->isEmpty()) {
            $this->info('No connected eBay stores.');

            return self::SUCCESS;
        }

        $failed = false;

        foreach ($accounts as $account) {
            try {
                $result = $importer->import($account);
                $this->info("{$account->store_name}: {$result['created']} new ".str('sale')->plural($result['created']).", {$result['skipped']} already imported or skipped.");
            } catch (Throwable $e) {
                $this->error("{$account->store_name}: {$e->getMessage()}");
                $failed = true;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
