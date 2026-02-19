<?php

namespace Tests\Feature\Admin;

use App\Models\CCARegistration;
use App\Models\RegistrationPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPaymentLedgerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_first_payment_when_no_existing_paid_amount(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $response = $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-19',
                'amount' => '25000.00',
                'payment_method' => 'bank_transfer',
                'receipt_reference' => 'TXN-001',
                'note' => 'Initial installment',
            ]
        );

        $response->assertRedirect(route('admin.registrations.payments.index', $registration->id));
        $this->assertDatabaseHas('registration_payments', [
            'cca_registration_id' => $registration->id,
            'payment_no' => 1,
            'payment_method' => 'bank_transfer',
            'status' => RegistrationPayment::STATUS_ACTIVE,
        ]);

        $registration->refresh();
        $this->assertEquals(25000.00, (float) $registration->current_paid_amount);
    }

    public function test_payment_numbers_increase_without_limit_for_each_new_entry(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        foreach ([10000, 15000, 20000] as $index => $amount) {
            $this->actingAs($admin, 'admin')->post(
                route('admin.registrations.payments.store', $registration->id),
                [
                    'payment_date' => '2026-02-' . str_pad((string) (10 + $index), 2, '0', STR_PAD_LEFT),
                    'amount' => (string) $amount,
                    'payment_method' => 'bank_transfer',
                ]
            )->assertRedirect(route('admin.registrations.payments.index', $registration->id));
        }

        $this->assertDatabaseHas('registration_payments', [
            'cca_registration_id' => $registration->id,
            'payment_no' => 3,
            'amount' => '20000.00',
        ]);

        $registration->refresh();
        $this->assertEquals(45000.00, (float) $registration->current_paid_amount);
    }

    public function test_admin_can_edit_payment_and_totals_resync(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-19',
                'amount' => '15000.00',
                'payment_method' => 'cash',
            ]
        );

        $payment = RegistrationPayment::where('cca_registration_id', $registration->id)->firstOrFail();

        $response = $this->actingAs($admin, 'admin')->put(
            route('admin.registrations.payments.update', [$registration->id, $payment->id]),
            [
                'payment_date' => '2026-02-20',
                'amount' => '21000.00',
                'payment_method' => 'card',
                'receipt_reference' => 'CARD-777',
                'note' => 'Adjusted amount',
            ]
        );

        $response->assertRedirect(route('admin.registrations.payments.index', $registration->id));
        $this->assertDatabaseHas('registration_payments', [
            'id' => $payment->id,
            'amount' => '21000.00',
            'payment_method' => 'card',
        ]);

        $registration->refresh();
        $this->assertEquals(21000.00, (float) $registration->current_paid_amount);
    }

    public function test_voiding_payment_keeps_history_and_recalculates_total(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-18',
                'amount' => '30000.00',
                'payment_method' => 'bank_transfer',
            ]
        );

        $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-19',
                'amount' => '5000.00',
                'payment_method' => 'cash',
            ]
        );

        $firstPayment = RegistrationPayment::where('cca_registration_id', $registration->id)
            ->where('payment_no', 1)
            ->firstOrFail();

        $response = $this->actingAs($admin, 'admin')->patch(
            route('admin.registrations.payments.void', [$registration->id, $firstPayment->id]),
            ['void_reason' => 'Incorrect transfer record']
        );

        $response->assertRedirect(route('admin.registrations.payments.index', $registration->id));
        $this->assertDatabaseHas('registration_payments', [
            'id' => $firstPayment->id,
            'status' => RegistrationPayment::STATUS_VOID,
            'void_reason' => 'Incorrect transfer record',
        ]);

        $registration->refresh();
        $this->assertEquals(5000.00, (float) $registration->current_paid_amount);
    }

    public function test_new_payments_continue_from_two_when_legacy_payment_one_exists(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration(12000.00);

        RegistrationPayment::create([
            'cca_registration_id' => $registration->id,
            'payment_no' => 1,
            'payment_date' => '2026-02-10',
            'amount' => '12000.00',
            'payment_method' => 'legacy',
            'status' => RegistrationPayment::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-19',
                'amount' => '8000.00',
                'payment_method' => 'cash',
            ]
        );

        $response->assertRedirect(route('admin.registrations.payments.index', $registration->id));
        $this->assertDatabaseHas('registration_payments', [
            'cca_registration_id' => $registration->id,
            'payment_no' => 2,
            'amount' => '8000.00',
            'payment_method' => 'cash',
        ]);

        $registration->refresh();
        $this->assertEquals(20000.00, (float) $registration->current_paid_amount);
    }

    public function test_admin_can_update_legacy_payment_method_without_validation_error(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration(15000.00);

        $payment = RegistrationPayment::create([
            'cca_registration_id' => $registration->id,
            'payment_no' => 1,
            'payment_date' => '2026-02-11',
            'amount' => '15000.00',
            'payment_method' => 'legacy',
            'status' => RegistrationPayment::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($admin, 'admin')->put(
            route('admin.registrations.payments.update', [$registration->id, $payment->id]),
            [
                'payment_date' => '2026-02-20',
                'amount' => '18000.00',
                'payment_method' => 'legacy',
                'note' => 'Updated imported record',
            ]
        );

        $response->assertRedirect(route('admin.registrations.payments.index', $registration->id));
        $this->assertDatabaseHas('registration_payments', [
            'id' => $payment->id,
            'payment_method' => 'legacy',
            'amount' => '18000.00',
        ]);

        $registration->refresh();
        $this->assertEquals(18000.00, (float) $registration->current_paid_amount);
    }

    private function createAdminUser(): User
    {
        Role::findOrCreate('admin');

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    private function createRegistration(?float $currentPaidAmount = null): CCARegistration
    {
        return CCARegistration::create([
            'program_id' => 'CCA-FS25',
            'program_name' => 'Full Stack Developer Career Accelerator',
            'program_year' => '2025',
            'program_duration' => '6 Months',
            'full_name' => 'John Payment',
            'name_with_initials' => 'J. Payment',
            'gender' => 'male',
            'date_of_birth' => '2000-01-01',
            'nic_number' => '200000000001',
            'passport_number' => null,
            'nationality' => 'Sri Lankan',
            'country_of_birth' => 'Sri Lanka',
            'country_of_residence' => 'Sri Lanka',
            'permanent_address' => '123 Test Street',
            'postal_code' => '10000',
            'country' => 'Sri Lanka',
            'district' => 'Colombo',
            'province' => 'Western',
            'email_address' => 'payment-test@example.com',
            'whatsapp_number' => '+94770000000',
            'home_contact_number' => null,
            'guardian_contact_name' => 'Guardian',
            'guardian_contact_number' => '+94771111111',
            'highest_qualification' => 'degree',
            'qualification_other_details' => null,
            'qualification_status' => 'completed',
            'qualification_completed_date' => '2024-01-01',
            'qualification_expected_completion_date' => null,
            'academic_qualification_documents' => [
                ['path' => 'legacy/doc.pdf', 'url' => 'https://example.com/doc.pdf'],
            ],
            'nic_documents' => null,
            'passport_documents' => null,
            'passport_photo' => ['path' => 'legacy/photo.jpg', 'url' => 'https://example.com/photo.jpg'],
            'payment_slip' => ['path' => 'legacy/slip.pdf', 'url' => 'https://example.com/slip.pdf'],
            'terms_accepted' => true,
            'tags' => ['General Rate'],
            'full_amount' => 100000,
            'current_paid_amount' => $currentPaidAmount,
        ]);
    }
}
