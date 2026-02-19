<?php

namespace App\Services;

use App\Models\CCARegistration;
use App\Models\RegistrationPayment;

class PaymentLedgerService
{
    /**
     * Calculate total paid amount from active payment rows.
     */
    public function calculatePaidTotal(CCARegistration $registration): float
    {
        return (float) $registration->payments()
            ->where('status', RegistrationPayment::STATUS_ACTIVE)
            ->sum('amount');
    }

    /**
     * Sync denormalized current_paid_amount from payment ledger.
     */
    public function syncCurrentPaidAmount(CCARegistration $registration): void
    {
        $paidTotal = $this->calculatePaidTotal($registration);

        $registration->forceFill([
            'current_paid_amount' => $paidTotal,
        ])->saveQuietly();
    }

    /**
     * Build payment summary for UI.
     *
     * @return array{full_amount: float, paid_total: float, balance: float, status: string}
     */
    public function summary(CCARegistration $registration): array
    {
        $fullAmount = (float) ($registration->full_amount ?? 0);
        $paidTotal = $this->calculatePaidTotal($registration);
        $balance = $fullAmount - $paidTotal;

        if ($fullAmount <= 0) {
            $status = $paidTotal > 0 ? 'paid' : 'unpaid';
        } elseif ($paidTotal <= 0) {
            $status = 'unpaid';
        } elseif ($paidTotal < $fullAmount) {
            $status = 'partial';
        } elseif ($paidTotal == $fullAmount) {
            $status = 'paid';
        } else {
            $status = 'overpaid';
        }

        return [
            'full_amount' => $fullAmount,
            'paid_total' => $paidTotal,
            'balance' => $balance,
            'status' => $status,
        ];
    }
}
