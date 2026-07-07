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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('ebay_order_id', 50)->nullable()->unique()->after('invoice_no');
            $table->foreignId('ebay_account_id')->nullable()->after('ebay_order_id')
                ->constrained('ebay_accounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ebay_account_id');
            $table->dropColumn('ebay_order_id');
        });
    }
};
