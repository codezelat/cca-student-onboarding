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
        Schema::table('cca_registrations', function (Blueprint $table) {
            // Change passport_photo and payment_slip from string to json
            // to support the new R2 storage format with metadata
            $table->json('passport_photo')->change();
            $table->json('payment_slip')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cca_registrations', function (Blueprint $table) {
            // Revert back to string
            $table->string('passport_photo')->change();
            $table->string('payment_slip')->change();
        });
    }
};
