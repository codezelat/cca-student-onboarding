<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_new_admin_account(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.accounts.store'), [
            'name' => 'Second Admin',
            'email' => 'second-admin@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.accounts.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Second Admin',
            'email' => 'second-admin@example.com',
        ]);

        $created = User::where('email', 'second-admin@example.com')->firstOrFail();
        $this->assertTrue($created->hasRole('admin'));
    }

    public function test_cannot_deactivate_last_admin_account_from_accounts_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin, 'admin')
            ->from(route('admin.accounts.index'))
            ->delete(route('admin.accounts.destroy', $admin->id));

        $response->assertRedirect(route('admin.accounts.index'));
        $response->assertSessionHasErrors('account');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_can_deactivate_and_restore_another_admin_account(): void
    {
        $admin = $this->createAdminUser();
        $secondAdmin = $this->createAdminUser('restore-admin@example.com');

        $deactivateResponse = $this->actingAs($admin, 'admin')
            ->delete(route('admin.accounts.destroy', $secondAdmin->id));

        $deactivateResponse->assertRedirect(route('admin.accounts.index'));
        $this->assertSoftDeleted('users', ['id' => $secondAdmin->id]);

        $restoreResponse = $this->actingAs($admin, 'admin')
            ->patch(route('admin.accounts.restore', $secondAdmin->id));

        $restoreResponse->assertRedirect(route('admin.accounts.index'));
        $this->assertDatabaseHas('users', [
            'id' => $secondAdmin->id,
            'deleted_at' => null,
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
}
