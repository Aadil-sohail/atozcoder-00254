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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // The store the product was picked from.
            $table->foreignId('ebay_account_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['increase', 'decrease', 'set']);
            // What was typed in; for "set" that is the new stock figure.
            $table->decimal('quantity', 10, 2);
            // Signed difference actually applied to the stock.
            $table->decimal('change', 10, 2);
            $table->decimal('stock_before', 10, 2);
            $table->decimal('stock_after', 10, 2);
            // eBay listings the new figure was sent to; null when pushing is off.
            $table->unsignedInteger('pushed_listings')->nullable();
            $table->string('reason')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('close', ['1', '0'])->default('1');
            $table->string('inserted_by', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
