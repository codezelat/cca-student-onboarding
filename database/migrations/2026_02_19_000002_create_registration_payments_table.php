<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cca_registration_id')
                ->constrained('cca_registrations')
                ->cascadeOnDelete();
            $table->unsignedInteger('payment_no');
            $table->date('payment_date');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 50);
            $table->string('receipt_reference')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['active', 'void'])->default('active');
            $table->text('void_reason')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['cca_registration_id', 'payment_no'], 'registration_payments_reg_no_unique');
            $table->index(['cca_registration_id', 'status'], 'registration_payments_reg_status_index');
            $table->index('payment_date');
        });

        // Backfill legacy current_paid_amount as Payment #1 for existing rows.
        DB::table('cca_registrations')
            ->whereNotNull('current_paid_amount')
            ->where('current_paid_amount', '>', 0)
            ->orderBy('id')
            ->chunkById(500, function ($registrations): void {
                $now = now();
                $rows = [];

                foreach ($registrations as $registration) {
                    $sourceDate = $registration->updated_at ?? $registration->created_at ?? $now;
                    $paymentDate = Carbon::parse($sourceDate)->toDateString();

                    $rows[] = [
                        'cca_registration_id' => $registration->id,
                        'payment_no' => 1,
                        'payment_date' => $paymentDate,
                        'amount' => $registration->current_paid_amount,
                        'payment_method' => 'legacy',
                        'receipt_reference' => null,
                        'note' => 'Auto-created from existing current_paid_amount during payment ledger migration.',
                        'status' => 'active',
                        'void_reason' => null,
                        'voided_at' => null,
                        'created_by' => null,
                        'updated_by' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (!empty($rows)) {
                    DB::table('registration_payments')->insert($rows);
                }
            }, 'id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_payments');
    }
};
