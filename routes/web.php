<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CCARegistrationController;
use App\Http\Controllers\Api\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// CCA Student Registration Routes (Public - No Authentication Required)
Route::get('/cca-register', [CCARegistrationController::class, 'create'])->name('cca.register');
Route::post('/cca-register', [CCARegistrationController::class, 'store'])->name('cca.register.store');

// File Upload API Routes (Public for registration form)
Route::prefix('api')->group(function () {
    Route::post('/upload-file', [FileUploadController::class, 'upload'])->name('api.upload.file');
    Route::post('/delete-file', [FileUploadController::class, 'delete'])->name('api.delete.file');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
