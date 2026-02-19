<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CCARegistration;
use App\Models\RegistrationPayment;
use App\Services\ActivityLogger;
use App\Services\PaymentLedgerService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function __construct(
        private readonly PaymentLedgerService $paymentLedgerService,
        private readonly ActivityLogger $activityLogger
    ) {}

    /**
     * Display payment ledger for a registration.
     */
    public function index(int $registrationId): View
    {
        $registration = CCARegistration::with('payments')->findOrFail($registrationId);
        $summary = $this->paymentLedgerService->summary($registration);

        return view('admin.payments.index', [
            'registration' => $registration,
            'payments' => $registration->payments,
            'summary' => $summary,
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    /**
     * Show edit form for a single payment row.
     */
    public function edit(int $registrationId, int $paymentId): View
    {
        $registration = CCARegistration::findOrFail($registrationId);
        $payment = $this->findPayment($registration, $paymentId);
        $summary = $this->paymentLedgerService->summary($registration);

        return view('admin.payments.edit', [
            'registration' => $registration,
            'payment' => $payment,
            'summary' => $summary,
            'paymentMethods' => $this->paymentMethodsForEdit($payment),
        ]);
    }

    /**
     * Add a new payment row.
     */
    public function store(Request $request, int $registrationId): RedirectResponse
    {
        $registration = CCARegistration::findOrFail($registrationId);
        $validated = $this->validatePaymentData($request, $this->paymentMethods());
        $adminId = Auth::guard('admin')->id();
        $createdPayment = null;

        try {
            DB::transaction(function () use ($registration, $validated, $adminId, &$createdPayment): void {
                // Serialize numbering per registration.
                CCARegistration::whereKey($registration->id)->lockForUpdate()->first();

                $nextNo = ((int) RegistrationPayment::where('cca_registration_id', $registration->id)
                    ->max('payment_no')) + 1;

                $createdPayment = RegistrationPayment::create([
                    'cca_registration_id' => $registration->id,
                    'payment_no' => $nextNo,
                    'payment_date' => $validated['payment_date'],
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'receipt_reference' => $validated['receipt_reference'] ?? null,
                    'note' => $validated['note'] ?? null,
                    'status' => RegistrationPayment::STATUS_ACTIVE,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]);
            });
        } catch (QueryException $e) {
            $this->activityLogger->log('payment.create.failed', [
                'category' => 'payment',
                'status' => 'failed',
                'subject' => $registration,
                'message' => 'Failed to add payment entry.',
                'after' => $validated,
            ]);

            return back()
                ->withErrors(['amount' => 'Could not add the payment entry. Please try again.'])
                ->withInput();
        }

        $updatedRegistration = $registration->fresh();
        $this->paymentLedgerService->syncCurrentPaidAmount($updatedRegistration);

        if ($createdPayment instanceof RegistrationPayment) {
            $this->activityLogger->log('payment.created', [
                'category' => 'payment',
                'subject' => $createdPayment,
                'subject_type' => 'registration_payment',
                'subject_label' => sprintf(
                    '%s - Payment #%d',
                    $updatedRegistration?->register_id ?? ('Registration #' . $registration->id),
                    $createdPayment->payment_no
                ),
                'message' => 'Payment added to ledger.',
                'after' => $this->paymentSnapshot($createdPayment),
                'meta' => [
                    'registration_id' => $registration->id,
                    'registration_register_id' => $updatedRegistration?->register_id,
                    'current_paid_amount' => $updatedRegistration?->current_paid_amount,
                ],
            ]);
        }

        return redirect()
            ->route('admin.registrations.payments.index', $registration->id)
            ->with('success', 'Payment added successfully.');
    }

    /**
     * Update payment row.
     */
    public function update(Request $request, int $registrationId, int $paymentId): RedirectResponse
    {
        $registration = CCARegistration::findOrFail($registrationId);
        $payment = $this->findPayment($registration, $paymentId);
        $before = $this->paymentSnapshot($payment);
        $validated = $this->validatePaymentData($request, $this->paymentMethodsForEdit($payment));

        if ($payment->status === RegistrationPayment::STATUS_VOID) {
            $this->activityLogger->log('payment.update.blocked', [
                'category' => 'payment',
                'status' => 'failed',
                'subject' => $payment,
                'subject_type' => 'registration_payment',
                'message' => 'Attempted to edit a void payment.',
                'before' => $before,
            ]);

            return back()->withErrors(['amount' => 'Void payments cannot be edited.']);
        }

        $payment->update([
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'receipt_reference' => $validated['receipt_reference'] ?? null,
            'note' => $validated['note'] ?? null,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        $updatedRegistration = $registration->fresh();
        $this->paymentLedgerService->syncCurrentPaidAmount($updatedRegistration);
        $after = $this->paymentSnapshot($payment->refresh());

        $this->activityLogger->log('payment.updated', [
            'category' => 'payment',
            'subject' => $payment,
            'subject_type' => 'registration_payment',
            'subject_label' => sprintf(
                '%s - Payment #%d',
                $updatedRegistration?->register_id ?? ('Registration #' . $registration->id),
                $payment->payment_no
            ),
            'message' => 'Payment updated.',
            'before' => $before,
            'after' => $after,
            'meta' => [
                'registration_id' => $registration->id,
                'current_paid_amount' => $updatedRegistration?->current_paid_amount,
            ],
        ]);

        return redirect()
            ->route('admin.registrations.payments.index', $registration->id)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Void a payment row while keeping audit trail.
     */
    public function void(Request $request, int $registrationId, int $paymentId): RedirectResponse
    {
        $registration = CCARegistration::findOrFail($registrationId);
        $payment = $this->findPayment($registration, $paymentId);
        $before = $this->paymentSnapshot($payment);

        $validated = $request->validate([
            'void_reason' => 'required|string|max:500',
        ]);

        if ($payment->status === RegistrationPayment::STATUS_VOID) {
            $this->activityLogger->log('payment.void.skipped', [
                'category' => 'payment',
                'subject' => $payment,
                'subject_type' => 'registration_payment',
                'message' => 'Void skipped because payment was already void.',
                'before' => $before,
            ]);

            return back()->with('success', 'Payment is already void.');
        }

        $payment->update([
            'status' => RegistrationPayment::STATUS_VOID,
            'void_reason' => $validated['void_reason'],
            'voided_at' => now(),
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        $updatedRegistration = $registration->fresh();
        $this->paymentLedgerService->syncCurrentPaidAmount($updatedRegistration);
        $after = $this->paymentSnapshot($payment->refresh());

        $this->activityLogger->log('payment.voided', [
            'category' => 'payment',
            'subject' => $payment,
            'subject_type' => 'registration_payment',
            'subject_label' => sprintf(
                '%s - Payment #%d',
                $updatedRegistration?->register_id ?? ('Registration #' . $registration->id),
                $payment->payment_no
            ),
            'message' => 'Payment marked as void.',
            'before' => $before,
            'after' => $after,
            'meta' => [
                'registration_id' => $registration->id,
                'current_paid_amount' => $updatedRegistration?->current_paid_amount,
            ],
        ]);

        return redirect()
            ->route('admin.registrations.payments.index', $registration->id)
            ->with('success', 'Payment voided successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function paymentSnapshot(RegistrationPayment $payment): array
    {
        return [
            'id' => $payment->id,
            'cca_registration_id' => $payment->cca_registration_id,
            'payment_no' => $payment->payment_no,
            'payment_date' => $payment->payment_date?->format('Y-m-d'),
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'receipt_reference' => $payment->receipt_reference,
            'note' => $payment->note,
            'status' => $payment->status,
            'void_reason' => $payment->void_reason,
            'voided_at' => $payment->voided_at?->toDateTimeString(),
        ];
    }

    /**
     * Resolve payment by registration ownership.
     */
    private function findPayment(CCARegistration $registration, int $paymentId): RegistrationPayment
    {
        return RegistrationPayment::where('cca_registration_id', $registration->id)
            ->where('id', $paymentId)
            ->firstOrFail();
    }

    /**
     * Validate payment create/update payload.
     *
     * @return array<string, mixed>
     */
    private function validatePaymentData(Request $request, array $allowedMethods): array
    {
        return $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|gt:0',
            'payment_method' => ['required', 'string', Rule::in($allowedMethods)],
            'receipt_reference' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:2000',
        ]);
    }

    /**
     * Fixed payment methods for consistent reporting.
     *
     * @return array<int, string>
     */
    private function paymentMethods(): array
    {
        return [
            'bank_transfer',
            'cash',
            'card',
            'online_gateway',
            'cheque',
            'other',
        ];
    }

    /**
     * Allow editing rows that were backfilled with legacy methods.
     *
     * @return array<int, string>
     */
    private function paymentMethodsForEdit(RegistrationPayment $payment): array
    {
        $methods = $this->paymentMethods();

        if (
            $payment->payment_method !== null
            && ! in_array($payment->payment_method, $methods, true)
        ) {
            $methods[] = $payment->payment_method;
        }

        return $methods;
    }
}
