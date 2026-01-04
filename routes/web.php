<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Models\Campaign;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Alternatif: menggunakan controller
Route::get('/home', [HomeController::class, 'index'])->name('homepage');

// Campaign Public Routes
Route::get('/campaigns', function () {
    $campaigns = Campaign::where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->paginate(9);
    return view('campaigns.index', compact('campaigns'));
})->name('campaigns.index');

Route::get('/campaigns/{id}', function ($id) {
    $campaign = Campaign::with(['donations' => function($query) {
        $query->where('payment_status', 'success')
              ->orderBy('created_at', 'desc')
              ->limit(10);
    }])->findOrFail($id);

    return view('campaigns.show', compact('campaign'));
})->name('campaigns.show');

// Donation Routes
Route::prefix('donation')->name('donation.')->group(function () {
    Route::get('/create', [DonationController::class, 'create'])->name('create');
    Route::post('/store', [DonationController::class, 'store'])->name('store');
    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');
    Route::get('/status/{orderId}', [DonationController::class, 'status'])->name('status');
    Route::get('/success', [DonationController::class, 'success'])->name('success');
    Route::get('/failed', [DonationController::class, 'failed'])->name('failed');
    Route::post('/webhook/midtrans', [DonationController::class, 'webhook'])->name('webhook');
});

// API Routes for donation status check
Route::get('/api/donations/status/{orderId}', function ($orderId) {
    $donation = \App\Models\Donation::where('midtrans_order_id', $orderId)->first();

    if (!$donation) {
        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'order_id' => $donation->midtrans_order_id,
            'status' => $donation->payment_status,
            'amount' => $donation->amount,
            'donor_name' => $donation->donor_name,
            'campaign_title' => $donation->campaign->title ?? '',
        ]
    ]);
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('campaigns', CampaignController::class)->except(['show']);
        Route::get('campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    });
});

// Fallback - 404 Custom Page
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
