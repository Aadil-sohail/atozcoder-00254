<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One product is often listed on more than one store at once, so a member
     * of a connection needs a list of stores rather than the single one it
     * started with.
     */
    public function up(): void
    {
        Schema::create('connection_item_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_connection_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebay_account_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['product_connection_item_id', 'ebay_account_id'], 'connection_item_store_unique');
        });

        // Carry over whatever the single-store column already held.
        if (Schema::hasColumn('product_connection_items', 'ebay_account_id')) {
            $rows = DB::table('product_connection_items')
                ->whereNotNull('ebay_account_id')
                ->get(['id', 'ebay_account_id']);

            foreach ($rows as $row) {
                DB::table('connection_item_stores')->insert([
                    'product_connection_item_id' => $row->id,
                    'ebay_account_id' => $row->ebay_account_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('product_connection_items', function (Blueprint $table) {
                $table->dropConstrainedForeignId('ebay_account_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_connection_items', function (Blueprint $table) {
            $table->foreignId('ebay_account_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::dropIfExists('connection_item_stores');
    }
};
