<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CCARegistration;
use App\Models\RegistrationPayment;
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
    public function __construct(private readonly PaymentLedgerService $paymentLedgerService)
    {
    }

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

        try {
            DB::transaction(function () use ($registration, $validated, $adminId): void {
                // Serialize numbering per registration.
                CCARegistration::whereKey($registration->id)->lockForUpdate()->first();

                $nextNo = ((int) RegistrationPayment::where('cca_registration_id', $registration->id)
                    ->max('payment_no')) + 1;

                RegistrationPayment::create([
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
            return back()
                ->withErrors(['amount' => 'Could not add the payment entry. Please try again.'])
                ->withInput();
        }

        $this->paymentLedgerService->syncCurrentPaidAmount($registration->fresh());

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
        $validated = $this->validatePaymentData($request, $this->paymentMethodsForEdit($payment));

        if ($payment->status === RegistrationPayment::STATUS_VOID) {
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

        $this->paymentLedgerService->syncCurrentPaidAmount($registration->fresh());

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

        $validated = $request->validate([
            'void_reason' => 'required|string|max:500',
        ]);

        if ($payment->status === RegistrationPayment::STATUS_VOID) {
            return back()->with('success', 'Payment is already void.');
        }

        $payment->update([
            'status' => RegistrationPayment::STATUS_VOID,
            'void_reason' => $validated['void_reason'],
            'voided_at' => now(),
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        $this->paymentLedgerService->syncCurrentPaidAmount($registration->fresh());

        return redirect()
            ->route('admin.registrations.payments.index', $registration->id)
            ->with('success', 'Payment voided successfully.');
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
