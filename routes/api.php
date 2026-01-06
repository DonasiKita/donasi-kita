<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Pastikan Controller ini berada di dalam folder app/Http/Controllers/Api/
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| File ini diakses melalui URL: http://20.6.9.146/api/
*/

// 1. Endpoint Utama untuk Statistik Home (Menghapus angka 0 di dashboard)
// Ini yang dipanggil oleh fetch('/api/') di home.blade.php
Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);

// 2. Rute Kampanye
Route::prefix('campaigns')->group(function () {
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/featured', [CampaignController::class, 'featured']);
    Route::get('/active', [CampaignController::class, 'active']);
    Route::get('/{campaign:slug}', [CampaignController::class, 'show']);
});

// 3. Rute Donasi & Webhook
Route::prefix('donations')->group(function () {
    Route::post('/', [DonationController::class, 'store']);
    Route::get('/{orderId}/status', [DonationController::class, 'checkStatus']);
    Route::post('/webhook/midtrans', [DonationController::class, 'webhook']);
});

// 4. Rute Terproteksi (Opsi masa depan)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
