<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile', [
            'user' => Auth::guard('admin')->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $before = $user ? $this->profileSnapshot($user) : null;
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user && $before) {
            $this->activityLogger->log('admin_profile.updated', [
                'category' => 'profile',
                'subject' => $user,
                'subject_type' => 'user',
                'message' => 'Admin profile updated.',
                'before' => $before,
                'after' => $this->profileSnapshot($user->refresh()),
            ]);
        }

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:admin'],
        ]);

        $user = Auth::guard('admin')->user();
        $before = $user ? $this->profileSnapshot($user) : null;

        $activeAdminsCount = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->count();

        if ($user && $user->hasRole('admin') && $activeAdminsCount <= 1) {
            if ($before) {
                $this->activityLogger->log('admin_profile.delete.blocked', [
                    'category' => 'profile',
                    'status' => 'failed',
                    'subject' => $user,
                    'subject_type' => 'user',
                    'message' => 'Profile deletion blocked for last admin account.',
                    'before' => $before,
                ]);
            }

            return Redirect::route('admin.profile.edit')
                ->withErrors([
                    'password' => 'You cannot delete the last admin account. Create another admin account first.',
                ], 'userDeletion');
        }

        if ($before) {
            $this->activityLogger->log('admin_profile.deleted', [
                'category' => 'profile',
                'subject_type' => 'user',
                'subject_id' => $before['id'],
                'subject_label' => $before['email'],
                'message' => 'Admin profile deleted by owner.',
                'before' => $before,
            ]);
        }

        Auth::guard('admin')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the authenticated admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::guard('admin')->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->activityLogger->log('admin_profile.password_updated', [
            'category' => 'profile',
            'subject' => $user,
            'subject_type' => 'user',
            'message' => 'Admin password updated.',
            'meta' => [
                'password_changed' => true,
            ],
        ]);

        return Redirect::route('admin.profile.edit')->with('status', 'password-updated');
    }

    /**
     * @return array<string, mixed>
     */
    private function profileSnapshot(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'deleted_at' => $user->deleted_at?->toDateTimeString(),
        ];
    }
}
