<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cca_registrations', function (Blueprint $table) {
            $table->string('register_id', 20)->unique()->nullable()->after('id');
        });

        // Generate register IDs for existing records without model scopes.
        $registrations = DB::table('cca_registrations')
            ->select('id')
            ->whereNull('register_id')
            ->get();

        foreach ($registrations as $registration) {
            DB::table('cca_registrations')
                ->where('id', $registration->id)
                ->update([
                    'register_id' => 'cca-A' . str_pad((string) $registration->id, 5, '0', STR_PAD_LEFT),
                ]);
        }

        // Make register_id not nullable after generating IDs
        Schema::table('cca_registrations', function (Blueprint $table) {
            $table->string('register_id', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cca_registrations', function (Blueprint $table) {
            $table->dropColumn('register_id');
        });
    }
};
