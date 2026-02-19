<?php

namespace Tests\Feature\Admin;

use App\Models\CCARegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRegistrationRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_soft_deletes_registration_and_can_restore_it(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $deleteResponse = $this->actingAs($admin, 'admin')
            ->delete(route('admin.registrations.destroy', $registration->id));

        $deleteResponse->assertRedirect(route('admin.dashboard'));
        $this->assertSoftDeleted('cca_registrations', ['id' => $registration->id]);

        $restoreResponse = $this->actingAs($admin, 'admin')
            ->patch(route('admin.registrations.restore', $registration->id));

        $restoreResponse->assertRedirect(route('admin.dashboard', ['scope' => 'trashed']));
        $this->assertDatabaseHas('cca_registrations', [
            'id' => $registration->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_can_force_delete_only_after_soft_delete(): void
    {
        $admin = $this->createAdminUser();
        $registration = $this->createRegistration();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.registrations.destroy', $registration->id));

        $forceResponse = $this->actingAs($admin, 'admin')
            ->delete(route('admin.registrations.force-delete', $registration->id));

        $forceResponse->assertRedirect(route('admin.dashboard', ['scope' => 'trashed']));
        $this->assertDatabaseMissing('cca_registrations', ['id' => $registration->id]);
    }

    private function createAdminUser(): User
    {
        Role::findOrCreate('admin');

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    private function createRegistration(): CCARegistration
    {
        return CCARegistration::create([
            'program_id' => 'CCA-FS25',
            'program_name' => 'Full Stack Developer Career Accelerator',
            'program_year' => '2025',
            'program_duration' => '6 Months',
            'full_name' => 'Recovery Test',
            'name_with_initials' => 'R. Test',
            'gender' => 'male',
            'date_of_birth' => '2000-01-01',
            'nic_number' => '200000000099',
            'passport_number' => null,
            'nationality' => 'Sri Lankan',
            'country_of_birth' => 'Sri Lanka',
            'country_of_residence' => 'Sri Lanka',
            'permanent_address' => '123 Test Street',
            'postal_code' => '10000',
            'country' => 'Sri Lanka',
            'district' => 'Colombo',
            'province' => 'Western',
            'email_address' => 'recovery-test@example.com',
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
            'current_paid_amount' => 0,
        ]);
    }
}
