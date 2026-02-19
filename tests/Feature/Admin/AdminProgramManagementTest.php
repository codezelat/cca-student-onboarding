<?php

namespace Tests\Feature\Admin;

use App\Models\Program;
use App\Models\User;
use Database\Seeders\ProgramSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProgramManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_program_seeder_populates_catalog(): void
    {
        $this->seed(ProgramSeeder::class);

        $this->assertDatabaseHas('programs', [
            'code' => 'CCA-FS25',
            'name' => 'Full Stack Developer Career Accelerator',
        ]);
    }

    public function test_admin_can_create_program_and_add_intake_window(): void
    {
        $admin = $this->createAdminUser();

        $createResponse = $this->actingAs($admin, 'admin')->post(route('admin.programs.store'), [
            'code' => 'CCA-TS27',
            'name' => 'Test Specialist Accelerator',
            'year_label' => '2027',
            'duration_label' => '6 Months',
            'base_price' => '120000.00',
            'currency' => 'LKR',
            'display_order' => 10,
            'is_active' => '1',
        ]);

        $program = Program::where('code', 'CCA-TS27')->firstOrFail();

        $createResponse->assertRedirect(route('admin.programs.edit', $program->id));

        $intakeResponse = $this->actingAs($admin, 'admin')->post(route('admin.programs.intakes.store', $program->id), [
            'window_name' => 'April 2027 Intake',
            'opens_at' => '2027-02-01 00:00:00',
            'closes_at' => '2027-03-01 23:59:59',
            'price_override' => '110000.00',
            'is_active' => '1',
        ]);

        $intakeResponse->assertRedirect(route('admin.programs.edit', $program->id));
        $this->assertDatabaseHas('program_intake_windows', [
            'program_id' => $program->id,
            'window_name' => 'April 2027 Intake',
            'price_override' => '110000.00',
            'is_active' => 1,
        ]);
    }

    public function test_intake_windows_cannot_overlap_when_active(): void
    {
        $admin = $this->createAdminUser();

        $program = Program::create([
            'code' => 'CCA-QX27',
            'name' => 'QA Expert Accelerator',
            'year_label' => '2027',
            'duration_label' => '6 Months',
            'base_price' => 100000,
            'currency' => 'LKR',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.programs.intakes.store', $program->id), [
            'window_name' => 'Batch 1',
            'opens_at' => '2027-01-01 00:00:00',
            'closes_at' => '2027-01-31 23:59:59',
            'is_active' => '1',
        ])->assertRedirect(route('admin.programs.edit', $program->id));

        $response = $this->actingAs($admin, 'admin')->from(route('admin.programs.edit', $program->id))
            ->post(route('admin.programs.intakes.store', $program->id), [
                'window_name' => 'Batch 2',
                'opens_at' => '2027-01-15 00:00:00',
                'closes_at' => '2027-02-15 23:59:59',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.programs.edit', $program->id));
        $response->assertSessionHasErrors('opens_at');
    }

    private function createAdminUser(): User
    {
        Role::findOrCreate('admin');

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }
}
