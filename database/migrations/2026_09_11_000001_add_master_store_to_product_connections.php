<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remember which store the price and stock were taken from, not just which
     * product.
     *
     * The form asks store by store, and the same product row can serve two of
     * them — so the product alone cannot say which row was marked, and the
     * choice was lost the moment the form was reopened.
     */
    public function up(): void
    {
        Schema::table('product_connections', function (Blueprint $table) {
            $table->foreignId('master_ebay_account_id')->nullable()->after('master_product_id')
                ->constrained('ebay_accounts')->nullOnDelete();
        });

        // Anything saved before this: the store the master product is listed on.
        $connections = DB::table('product_connections')->get(['id', 'master_product_id']);

        foreach ($connections as $connection) {
            $storeId = DB::table('ebay_listings')
                ->where('product_id', $connection->master_product_id)
                ->value('ebay_account_id');

            if ($storeId) {
                DB::table('product_connections')
                    ->where('id', $connection->id)
                    ->update(['master_ebay_account_id' => $storeId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('product_connections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('master_ebay_account_id');
        });
    }
};
