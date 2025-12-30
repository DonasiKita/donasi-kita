<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);

// Campaign routes
Route::prefix('campaigns')->group(function () {
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/featured', [CampaignController::class, 'featured']);
    Route::get('/active', [CampaignController::class, 'active']);
    Route::get('/{campaign:slug}', [CampaignController::class, 'show']);
});

// Donation routes
Route::prefix('donations')->group(function () {
    Route::post('/', [DonationController::class, 'store']);
    Route::get('/{orderId}/status', [DonationController::class, 'checkStatus']);
    Route::post('/webhook/midtrans', [DonationController::class, 'webhook']);
});

// Protected routes (for future use with Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
