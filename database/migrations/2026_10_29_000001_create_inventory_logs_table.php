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
        // One row per stock movement, written by App\Services\ProductStock.
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // The stock entry a log row was written for, so a product's history
            // can show the entries that have no log row yet.
            $table->foreignId('inventory_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30)->index();
            // Signed: positive when stock came in, negative when it went out.
            $table->decimal('quantity', 10, 2);
            // Available stock (total - sold) right after the movement.
            $table->decimal('balance_after', 10, 2)->nullable();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sale_return_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ebay_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note')->nullable();
            $table->string('inserted_by', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
