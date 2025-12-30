<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

// Route untuk admin panel
Route::prefix('admin')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [Controller::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Route khusus untuk bgdn
        Route::get('/bgdn', [AdminController::class, 'bgdn'])->name('admin.bgdn');
    });
});
