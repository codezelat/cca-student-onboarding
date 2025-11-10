<?php

namespace App\Console\Commands;

use App\Models\Admin;
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

        // Get admin credentials
        $admin = Admin::first();
        if (!$admin) {
            $this->error('No admin found in database');
            return;
        }

        $this->info("Admin Email: {$admin->email}");
        $this->info("Admin Active: " . ($admin->is_active ? 'Yes' : 'No'));

        // Test password verification
        $password = 'CCATav#67gR'; // From migration
        $passwordValid = Hash::check($password, $admin->password);
        $this->info("Password valid: " . ($passwordValid ? 'Yes' : 'No'));

        // Test admin guard login
        if ($passwordValid && $admin->is_active) {
            Auth::guard('admin')->login($admin);
            $this->info("Admin guard check after login: " . (Auth::guard('admin')->check() ? 'Yes' : 'No'));
            $this->info("Admin user ID: " . (Auth::guard('admin')->id() ?? 'null'));
            $this->info("Admin user email: " . (Auth::guard('admin')->user()->email ?? 'null'));
        }

        $this->info('Test completed.');
    }
}
