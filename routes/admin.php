<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\CampaignController;

Route::prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('admin.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('admin.login.submit');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');


    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        // BGDN
        Route::get('/bgdn', [AdminController::class, 'bgdn'])
            ->name('admin.bgdn');

        // Campaigns (UNTUK SIDEBAR)
        Route::get('/campaigns', [CampaignController::class, 'index'])
            ->name('admin.campaigns.index');

        // Donations (UNTUK SIDEBAR)
        Route::get('/donations', [DonationController::class, 'index'])
            ->name('admin.donations.index');
    });
});
