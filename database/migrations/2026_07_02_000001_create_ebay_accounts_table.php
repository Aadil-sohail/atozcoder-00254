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
        Schema::create('ebay_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('store_name', 100);
            $table->string('ebay_username', 100)->nullable();
            $table->string('marketplace_id', 20)->default('EBAY_US');
            $table->text('access_token')->nullable();
            $table->timestamp('access_token_expires_at')->nullable();
            $table->text('refresh_token');
            $table->timestamp('refresh_token_expires_at')->nullable();
            $table->string('fulfillment_policy_id', 50)->nullable();
            $table->string('payment_policy_id', 50)->nullable();
            $table->string('return_policy_id', 50)->nullable();
            $table->string('merchant_location_key', 50)->nullable();
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
        Schema::dropIfExists('ebay_accounts');
    }
};
