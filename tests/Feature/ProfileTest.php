<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user, 'admin')
            ->get('/admin/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user, 'admin')
            ->patch('/admin/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user, 'admin')
            ->patch('/admin/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = $this->createAdminUser();
        $this->createAdminUser();

        $response = $this
            ->actingAs($user, 'admin')
            ->delete('/admin/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest('admin');
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_last_admin_cannot_delete_their_account(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user, 'admin')
            ->from('/admin/profile')
            ->delete('/admin/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->fresh());
        $this->assertAuthenticated('admin');
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user, 'admin')
            ->from('/admin/profile')
            ->delete('/admin/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->fresh());
    }

    private function createAdminUser(): User
    {
        Role::findOrCreate('admin');

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }
}
