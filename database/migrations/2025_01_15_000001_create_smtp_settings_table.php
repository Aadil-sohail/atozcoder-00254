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
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mailer')->default('smtp')->collation('utf8mb4_general_ci');
            $table->string('host')->collation('utf8mb4_general_ci');
            $table->string('username')->collation('utf8mb4_general_ci');
            $table->string('port')->collation('utf8mb4_general_ci');
            $table->string('encryption')->collation('utf8mb4_general_ci');
            $table->string('password')->collation('utf8mb4_general_ci');
            $table->string('from_address')->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
