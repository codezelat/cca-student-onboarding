<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CCARegistrationController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Admin\AdminDashboardController;
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

// File Upload API Routes (Public for registration form)
Route::prefix('api')->group(function () {
    Route::post('/upload-file', [FileUploadController::class, 'upload'])->name('api.upload.file');
    Route::post('/delete-file', [FileUploadController::class, 'delete'])->name('api.delete.file');
});

// Admin Routes (Protected by admin.auth middleware)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrations/{id}', [AdminDashboardController::class, 'show'])->name('registrations.show');
    Route::get('/registrations/{id}/edit', [AdminDashboardController::class, 'edit'])->name('registrations.edit');
    Route::put('/registrations/{id}', [AdminDashboardController::class, 'update'])->name('registrations.update');
    Route::delete('/registrations/{id}', [AdminDashboardController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/export', [AdminDashboardController::class, 'export'])->name('export');
    
    // Admin Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
