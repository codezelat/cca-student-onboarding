<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('year_label', 20);
            $table->string('duration_label', 50);
            $table->decimal('base_price', 12, 2)->default(0);
            $table->char('currency', 3)->default('LKR');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });

        // Backfill unique program definitions from existing registration snapshots.
        $existingPrograms = DB::table('cca_registrations')
            ->selectRaw('program_id, MIN(program_name) as program_name, MIN(program_year) as program_year, MIN(program_duration) as program_duration')
            ->whereNotNull('program_id')
            ->groupBy('program_id')
            ->orderBy('program_id')
            ->get();

        if ($existingPrograms->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];

        foreach ($existingPrograms as $index => $program) {
            $rows[] = [
                'code' => (string) $program->program_id,
                'name' => (string) ($program->program_name ?: $program->program_id),
                'year_label' => (string) ($program->program_year ?: 'N/A'),
                'duration_label' => (string) ($program->program_duration ?: 'N/A'),
                'base_price' => 0,
                'currency' => 'LKR',
                'is_active' => false,
                'display_order' => 1000 + $index,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('programs')->insert($rows);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
