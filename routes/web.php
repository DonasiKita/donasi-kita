<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Models\Campaign;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public Homepage
// Mengembalikan view 'home' yang berisi script fetch('/api/')
Route::get('/', function () {
    return view('home');
})->name('home');

// Rute ini tetap dipertahankan jika Anda ingin akses via Controller HTML
Route::get('/home', [HomeController::class, 'index'])->name('homepage');

// 2. Campaign Public Routes
Route::get('/campaigns', function () {
    $campaigns = Campaign::where('is_active', true)
        ->latest()
        ->paginate(9);
    return view('campaigns.index', compact('campaigns'));
})->name('campaigns.index');

Route::get('/campaigns/{id}', function ($id) {
    $campaign = Campaign::with(['donations' => function($query) {
        $query->where('payment_status', 'success')
              ->latest()
              ->limit(10);
    }])->findOrFail($id);

    return view('campaigns.show', compact('campaign'));
})->name('campaigns.show');

// 3. Donation Routes (Public / User Side)
Route::prefix('donation')->name('donation.')->group(function () {
    Route::get('/create', [DonationController::class, 'create'])->name('create');
    Route::post('/store', [DonationController::class, 'store'])->name('store');
    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');
    Route::get('/status/{orderId}', [DonationController::class, 'status'])->name('status');
    Route::get('/success', [DonationController::class, 'success'])->name('success');
    Route::get('/failed', [DonationController::class, 'failed'])->name('failed');
    Route::post('/webhook/midtrans', [DonationController::class, 'webhook'])->name('webhook');
});

// 4. Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Menggunakan alias AdminCampaignController untuk menghindari bentrok
        Route::resource('campaigns', AdminCampaignController::class)->except(['show']);
        Route::get('campaigns/{campaign}', [AdminCampaignController::class, 'show'])->name('campaigns.show');

        // Perbaikan: Menampilkan daftar donasi di panel admin
        Route::get('donations', [AdminDonationController::class, 'index'])->name('donations.index');
    });
});

// 5. Fallback Route (404)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
