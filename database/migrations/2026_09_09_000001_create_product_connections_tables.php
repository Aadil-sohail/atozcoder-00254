<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The same physical part is listed separately on every store, so the same
     * part arrives here as one product per store with nothing tying them
     * together. A connection is that missing identifier: it says "these rows
     * are one product", names the member whose prices and stock speak for all
     * of them, and lets a sale on any store draw from the one pile of stock.
     */
    public function up(): void
    {
        Schema::create('product_connections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            // The member the connection shows prices and stock from. Every
            // stock movement on any member is applied to this one, so the
            // figure the stores are given is the same figure everywhere.
            $table->foreignId('master_product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('close', ['1', '0'])->default('1');
            $table->string('inserted_by', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('product_connection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_connection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // The store this member is listed on. Null covers a product that
            // is only here, not on eBay at all.
            $table->foreignId('ebay_account_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // One connection per product: two would leave its stock with two
            // owners and no way to say which is right.
            $table->unique('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_connection_items');
        Schema::dropIfExists('product_connections');
    }
};
