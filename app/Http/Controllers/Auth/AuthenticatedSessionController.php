<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Supports both regular users and admin authentication.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try to authenticate the user
        $user = User::where('email', $request->email)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            // Check if user has admin role
            if ($user->hasRole('admin')) {
                // Log in the user using the admin guard
                Auth::guard('admin')->login($user, $request->boolean('remember'));
                $request->session()->regenerateToken();
                
                return redirect()->route('admin.dashboard');
            }
            
            // Regular user login (if needed in the future)
            Auth::guard('web')->login($user, $request->boolean('remember'));
            $request->session()->regenerateToken();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        // If authentication failed, throw validation error
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Destroy an authenticated session.
     * Handles both regular users and admins.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Check which guard is authenticated and logout accordingly
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
