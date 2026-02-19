<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CCARegistrationController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminProgramController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sitemap.xml', function () {
    $lastModified = now()->toAtomString();

    $urls = collect(config('sitemap.static', []))
        ->map(function (array $page) use ($lastModified) {
            $path = $page['path'] ?? '/';

            return [
                'loc' => url($path),
                'lastmod' => $page['lastmod'] ?? $lastModified,
                'changefreq' => $page['changefreq'] ?? 'monthly',
                'priority' => number_format((float) ($page['priority'] ?? 0.5), 1, '.', ''),
            ];
        });

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
});

// CCA Student Registration Routes (Public - No Authentication Required)
Route::get('/cca-register', [CCARegistrationController::class, 'create'])->name('cca.register');
Route::post('/cca-register', [CCARegistrationController::class, 'store'])->name('cca.register.store');

// CSRF Token Route for AJAX refresh
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Backward-compatible dashboard route used by auth flows/tests
Route::middleware('auth')->get('/dashboard', function () {
    return Auth::guard('admin')->check()
        ? redirect()->route('admin.dashboard')
        : redirect('/');
})->name('dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Admin Routes (Protected by admin.auth middleware)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [AdminDashboardController::class, 'show'])->name('registrations.show');
        Route::get('/registrations/{id}/edit', [AdminDashboardController::class, 'edit'])->name('registrations.edit');
        Route::put('/registrations/{id}', [AdminDashboardController::class, 'update'])->name('registrations.update');
        Route::delete('/registrations/{id}', [AdminDashboardController::class, 'destroy'])->name('registrations.destroy');
        Route::patch('/registrations/{id}/restore', [AdminDashboardController::class, 'restore'])->name('registrations.restore');
        Route::delete('/registrations/{id}/force', [AdminDashboardController::class, 'forceDelete'])->name('registrations.force-delete');
        Route::get('/export', [AdminDashboardController::class, 'export'])->name('export');

        // Program Management
        Route::get('/programs', [AdminProgramController::class, 'index'])->name('programs.index');
        Route::get('/programs/create', [AdminProgramController::class, 'create'])->name('programs.create');
        Route::post('/programs', [AdminProgramController::class, 'store'])->name('programs.store');
        Route::get('/programs/{id}/edit', [AdminProgramController::class, 'edit'])->name('programs.edit');
        Route::put('/programs/{id}', [AdminProgramController::class, 'update'])->name('programs.update');
        Route::patch('/programs/{id}/toggle', [AdminProgramController::class, 'toggle'])->name('programs.toggle');
        Route::post('/programs/{id}/intakes', [AdminProgramController::class, 'storeIntake'])->name('programs.intakes.store');
        Route::get('/programs/{id}/intakes/{intake}/edit', [AdminProgramController::class, 'editIntake'])->name('programs.intakes.edit');
        Route::put('/programs/{id}/intakes/{intake}', [AdminProgramController::class, 'updateIntake'])->name('programs.intakes.update');
        Route::patch('/programs/{id}/intakes/{intake}/toggle', [AdminProgramController::class, 'toggleIntake'])->name('programs.intakes.toggle');

        // Admin Account Management
        Route::get('/accounts', [AdminAccountController::class, 'index'])->name('accounts.index');
        Route::post('/accounts', [AdminAccountController::class, 'store'])->name('accounts.store');
        Route::delete('/accounts/{id}', [AdminAccountController::class, 'destroy'])->name('accounts.destroy');
        Route::patch('/accounts/{id}/restore', [AdminAccountController::class, 'restore'])->name('accounts.restore');

        // Payment Ledger Management
        Route::get('/registrations/{id}/payments', [AdminPaymentController::class, 'index'])->name('registrations.payments.index');
        Route::post('/registrations/{id}/payments', [AdminPaymentController::class, 'store'])->name('registrations.payments.store');
        Route::get('/registrations/{id}/payments/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('registrations.payments.edit');
        Route::put('/registrations/{id}/payments/{payment}', [AdminPaymentController::class, 'update'])->name('registrations.payments.update');
        Route::patch('/registrations/{id}/payments/{payment}/void', [AdminPaymentController::class, 'void'])->name('registrations.payments.void');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Admin Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
