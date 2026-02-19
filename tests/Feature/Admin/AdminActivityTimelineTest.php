<?php

namespace Tests\Feature\Admin;

use App\Models\AdminActivityLog;
use App\Models\CCARegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminActivityTimelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_action_is_written_to_admin_activity_log(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $response = $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-19',
                'amount' => '25000.00',
                'payment_method' => 'bank_transfer',
                'receipt_reference' => 'AUD-001',
            ]
        );

        $response->assertRedirect(route('admin.registrations.payments.index', $registration->id));

        $log = AdminActivityLog::query()->where('action', 'payment.created')->first();

        $this->assertNotNull($log);
        $this->assertSame('payment', $log->category);
        $this->assertSame('success', $log->status);
        $this->assertSame($admin->id, $log->actor_user_id);
        $this->assertSame('registration_payment', $log->subject_type);
        $this->assertIsArray($log->after_data);
        $this->assertSame(1, $log->after_data['payment_no']);
    }

    public function test_admin_can_open_activity_timeline_and_export_csv(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $this->actingAs($admin, 'admin')->post(
            route('admin.registrations.payments.store', $registration->id),
            [
                'payment_date' => '2026-02-19',
                'amount' => '12000.00',
                'payment_method' => 'cash',
            ]
        );

        $indexResponse = $this->actingAs($admin, 'admin')->get(route('admin.activity.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Activity Timeline');

        $exportResponse = $this->actingAs($admin, 'admin')->get(route('admin.activity.export'));
        $exportResponse->assertOk();
        $this->assertStringContainsString('text/csv', (string) $exportResponse->headers->get('content-type'));
        $this->assertStringContainsString('Date Time', $exportResponse->streamedContent());
    }

    public function test_failed_admin_login_is_logged(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'wrong-admin@example.com',
            'password' => 'not-the-password',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('email');

        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'admin.login.failed',
            'status' => 'failed',
            'subject_label' => 'wrong-admin@example.com',
        ]);
    }

    public function test_blocking_last_admin_deactivation_is_logged(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin, 'admin')
            ->from(route('admin.accounts.index'))
            ->delete(route('admin.accounts.destroy', $admin->id));

        $response->assertRedirect(route('admin.accounts.index'));
        $response->assertSessionHasErrors('account');

        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'admin_account.deactivate.blocked',
            'status' => 'failed',
            'actor_user_id' => $admin->id,
            'subject_id' => $admin->id,
        ]);
    }

    private function createAdminUser(?string $email = null): User
    {
        Role::findOrCreate('admin');

        $user = User::factory()->create([
            'email' => $email ?? fake()->unique()->safeEmail(),
        ]);
        $user->assignRole('admin');

        return $user;
    }

    private function createRegistration(): CCARegistration
    {
        $seed = (string) fake()->unique()->numberBetween(1000, 9999);

        return CCARegistration::create([
            'program_id' => 'CCA-FS25',
            'program_name' => 'Full Stack Developer Career Accelerator',
            'program_year' => '2025',
            'program_duration' => '6 Months',
            'full_name' => 'Activity Test ' . $seed,
            'name_with_initials' => 'A. Test',
            'gender' => 'male',
            'date_of_birth' => '2000-01-01',
            'nic_number' => '2000000' . $seed,
            'passport_number' => null,
            'nationality' => 'Sri Lankan',
            'country_of_birth' => 'Sri Lanka',
            'country_of_residence' => 'Sri Lanka',
            'permanent_address' => '123 Test Street',
            'postal_code' => '10000',
            'country' => 'Sri Lanka',
            'district' => 'Colombo',
            'province' => 'Western',
            'email_address' => 'activity-test-' . $seed . '@example.com',
            'whatsapp_number' => '+94770' . $seed,
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
            'current_paid_amount' => 0,
        ]);
    }
}

