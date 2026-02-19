<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminAccountController extends Controller
{
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

        $activeAdminsCount = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->count();

        if ($activeAdminsCount <= 1) {
            return redirect()
                ->route('admin.accounts.index')
                ->withErrors(['account' => 'Cannot deactivate the last admin account.']);
        }

        $isSelf = (int) Auth::guard('admin')->id() === (int) $admin->id;
        $admin->delete();

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

        $admin->restore();

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Admin account restored successfully.');
    }
}
