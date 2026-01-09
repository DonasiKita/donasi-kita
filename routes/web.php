<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Models
use App\Models\Campaign;

// Import Controllers Public
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonationController;

// Import Controllers Admin
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. AREA PUBLIK (BISA DIAKSES TANPA LOGIN)
// ====================================================

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('home');
})->name('home');

// Halaman Daftar Semua Kampanye
// (Tombol "Mulai Donasi" di home akan mengarah ke sini)
Route::get('/campaigns', function () {
    // Ambil kampanye yang aktif, urutkan terbaru, pagination 9 per halaman
    $campaigns = Campaign::where('is_active', true)
        ->latest()
        ->paginate(9);

    return view('campaigns.index', compact('campaigns'));
})->name('campaigns.index');

// Halaman Detail Kampanye
Route::get('/campaigns/{id}', function ($id) {
    // Ambil detail kampanye beserta 10 donasi terakhir yang SUKSES (paid)
    $campaign = Campaign::with(['donations' => function($query) {
        $query->where('payment_status', 'paid')
              ->latest()
              ->limit(10);
    }])->findOrFail($id);

    return view('campaigns.show', compact('campaign'));
})->name('campaigns.show');

// Group Route Donasi (Form, Simpan, Bayar)
Route::prefix('donation')->name('donation.')->group(function () {

    // Form Input Nominal & Data Diri (Step 1)
    Route::get('/create', [DonationController::class, 'create'])->name('create');

    // Proses Simpan ke Database (Step 2)
    Route::post('/store', [DonationController::class, 'store'])->name('store');

    // Halaman Pembayaran / Snap Midtrans (Step 3)
    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');

    // Halaman Sukses setelah bayar
    Route::get('/success', [DonationController::class, 'success'])->name('success');
});

// Route Default Laravel Auth (Opsional, biasanya untuk redirect setelah login user biasa)
Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');


// ====================================================
// 2. AREA ADMIN (WAJIB LOGIN & ROLE ADMIN)
// ====================================================

Route::prefix('admin')->name('admin.')->group(function () {

    // Halaman Login Admin
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Group Middleware (Hanya bisa diakses jika sudah login sebagai admin)
    Route::middleware(['auth', 'admin'])->group(function () {

        // Dashboard Utama
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Kelola Donasi (Riwayat & Widget)
        Route::get('donations', [AdminController::class, 'donations'])->name('donations.index');

        // Kelola Kampanye (CRUD: Create, Read, Update, Delete)
        Route::resource('campaigns', AdminCampaignController::class);

        // Halaman Tambahan (BGDN)
        Route::get('bgdn', [AdminController::class, 'bgdn'])->name('bgdn');
    });
});

// ====================================================
// 3. FALLBACK (JIKA HALAMAN TIDAK DITEMUKAN)
// ====================================================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
