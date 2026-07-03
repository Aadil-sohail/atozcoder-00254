<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ebay_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebay_account_id')->constrained()->cascadeOnDelete();
            $table->string('sku', 50);
            $table->string('offer_id', 50)->nullable();
            $table->string('listing_id', 50)->nullable();
            $table->string('ebay_category_id', 20)->nullable();
            $table->string('condition', 40)->default('NEW');
            $table->enum('sync_status', ['pending', 'syncing', 'synced', 'failed'])->default('pending');
            $table->text('last_error')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->string('inserted_by', 50)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'ebay_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebay_listings');
    }
};
