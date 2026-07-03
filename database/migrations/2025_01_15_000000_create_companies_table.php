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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->collation('utf8mb4_general_ci');
            $table->string('company_email')->collation('utf8mb4_general_ci');
            $table->string('company_phone')->collation('utf8mb4_general_ci');
            $table->string('company_mobile')->collation('utf8mb4_general_ci');
            $table->text('company_address')->collation('utf8mb4_general_ci');
            $table->string('company_logo')->nullable()->collation('utf8mb4_general_ci');
            $table->string('fav_icon')->nullable()->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
