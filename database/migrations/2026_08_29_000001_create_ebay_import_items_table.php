<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Staging area for "Import from eBay": everything the store has listed is
     * parked here first so the user can pick what they actually want. Rows are
     * deleted as soon as a selection is saved or discarded — nothing here is
     * meant to outlive the import screen it feeds.
     */
    public function up(): void
    {
        Schema::create('ebay_import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebay_account_id')->constrained()->cascadeOnDelete();
            $table->string('sku', 100);
            $table->string('title');
            $table->text('description')->nullable();
            // eBay's own image URLs, downloaded only if the row is selected.
            $table->json('image_urls')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('ebay_category_id', 20)->nullable();
            $table->string('listing_id', 50)->nullable();
            $table->string('offer_id', 50)->nullable();
            $table->string('condition', 40)->default('NEW');
            // Already in the software, so the screen can say so up front.
            $table->boolean('already_in_software')->default(false);
            $table->timestamps();

            $table->unique(['ebay_account_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebay_import_items');
    }
};
