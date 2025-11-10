<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestAdminLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-admin-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin login functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Admin Login...');

        // Get admin user
        $admin = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->first();
        
        if (!$admin) {
            $this->error('No admin user found in database');
            return;
        }

        $this->info("Admin Email: {$admin->email}");
        $this->info("Admin Name: {$admin->name}");
        $this->info("Has admin role: " . ($admin->hasRole('admin') ? 'Yes' : 'No'));

        // Test password verification
        $password = 'password'; // From seeder
        $passwordValid = Hash::check($password, $admin->password);
        $this->info("Password valid: " . ($passwordValid ? 'Yes' : 'No'));

        // Test admin guard login
        if ($passwordValid) {
            Auth::guard('admin')->login($admin);
            $this->info("Admin guard check after login: " . (Auth::guard('admin')->check() ? 'Yes' : 'No'));
            $this->info("Admin user ID: " . (Auth::guard('admin')->id() ?? 'null'));
            $this->info("Admin user email: " . (Auth::guard('admin')->user()->email ?? 'null'));
        }

        $this->info('Test completed.');
    }
}
