<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminAccountController extends Controller
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    /**
     * Show active and deleted admin accounts.
     */
    public function index(): View
    {
        $activeAdmins = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->orderBy('name')
            ->get();

        $deletedAdmins = User::onlyTrashed()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->orderByDesc('deleted_at')
            ->get();

        return view('admin.accounts.index', [
            'activeAdmins' => $activeAdmins,
            'deletedAdmins' => $deletedAdmins,
            'activeAdminCount' => $activeAdmins->count(),
            'currentAdminId' => Auth::guard('admin')->id(),
        ]);
    }

    /**
     * Create a new admin account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Role::findOrCreate('admin');

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('admin');

        $this->activityLogger->log('admin_account.created', [
            'category' => 'account',
            'subject' => $user,
            'subject_type' => 'user',
            'message' => 'Admin account created.',
            'after' => $this->adminSnapshot($user),
        ]);

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Admin account created successfully.');
    }

    /**
     * Soft-delete an admin account.
     */
    public function destroy(int $userId): RedirectResponse
    {
        $admin = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->findOrFail($userId);
        $before = $this->adminSnapshot($admin);

        $activeAdminsCount = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->count();

        if ($activeAdminsCount <= 1) {
            $this->activityLogger->log('admin_account.deactivate.blocked', [
                'category' => 'account',
                'status' => 'failed',
                'subject' => $admin,
                'subject_type' => 'user',
                'message' => 'Attempted to deactivate the last admin account.',
                'before' => $before,
            ]);

            return redirect()
                ->route('admin.accounts.index')
                ->withErrors(['account' => 'Cannot deactivate the last admin account.']);
        }

        $isSelf = (int) Auth::guard('admin')->id() === (int) $admin->id;
        $admin->delete();
        $afterModel = User::withTrashed()->find($admin->id);

        $this->activityLogger->log('admin_account.deactivated', [
            'category' => 'account',
            'subject_type' => 'user',
            'subject_id' => $before['id'],
            'subject_label' => $before['email'],
            'message' => $isSelf
                ? 'Admin self-deactivated account.'
                : 'Admin account deactivated.',
            'before' => $before,
            'after' => $afterModel ? $this->adminSnapshot($afterModel) : null,
            'meta' => [
                'self_action' => $isSelf,
            ],
        ]);

        if ($isSelf) {
            Auth::guard('admin')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('success', 'Your admin account was deactivated.');
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Admin account deactivated successfully.');
    }

    /**
     * Restore a soft-deleted admin account.
     */
    public function restore(int $userId): RedirectResponse
    {
        $admin = User::onlyTrashed()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->findOrFail($userId);
        $before = $this->adminSnapshot($admin);

        $admin->restore();

        $this->activityLogger->log('admin_account.restored', [
            'category' => 'account',
            'subject' => $admin,
            'subject_type' => 'user',
            'message' => 'Admin account restored.',
            'before' => $before,
            'after' => $this->adminSnapshot($admin->refresh()),
        ]);

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Admin account restored successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function adminSnapshot(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'deleted_at' => $user->deleted_at?->toDateTimeString(),
        ];
    }
}
