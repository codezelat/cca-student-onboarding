<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    /**
     * Show the admin login form
     */
    public function showLoginForm(): View
    {
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password) || ! $user->hasRole('admin')) {
            $this->activityLogger->log('admin.login.failed', [
                'category' => 'auth',
                'status' => 'failed',
                'subject_type' => 'auth_session',
                'subject_label' => (string) $request->email,
                'message' => 'Failed admin login attempt.',
                'meta' => [
                    'email' => (string) $request->email,
                    'reason' => 'invalid_credentials_or_role',
                ],
            ]);

            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        RateLimiter::clear($request->throttleKey());

        // Log in the user using the admin guard
        Auth::guard('admin')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        $this->activityLogger->log('admin.login.success', [
            'category' => 'auth',
            'subject' => $user,
            'subject_type' => 'user',
            'message' => 'Admin logged in successfully.',
            'meta' => [
                'remember' => $request->boolean('remember'),
            ],
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $actor = Auth::guard('admin')->user();

        $this->activityLogger->log('admin.logout', [
            'category' => 'auth',
            'actor' => $actor,
            'subject' => $actor,
            'subject_type' => 'user',
            'message' => 'Admin logged out.',
        ]);

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
