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
            $table->json('tags')->nullable()->after('terms_accepted');
            $table->decimal('current_paid_amount', 10, 2)->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cca_registrations', function (Blueprint $table) {
            $table->dropColumn(['tags', 'current_paid_amount']);
        });
    }
};
