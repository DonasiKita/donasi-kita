<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CampaignController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('homepage');

// Donation routes
Route::prefix('donation')->group(function () {
    Route::get('/create', [DonationController::class, 'create'])->name('donation.create');
    Route::get('/success', [DonationController::class, 'success'])->name('donation.success');
});

// Admin authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Campaign management
        Route::resource('campaigns', CampaignController::class)->except(['show']);
        Route::get('campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');

        // Donation management (future implementation)
        Route::get('donations', function () {
            return view('admin.donations.index');
        })->name('donations.index');
    });
});

// Fallback route
Route::fallback(function () {
    return view('welcome');
});
