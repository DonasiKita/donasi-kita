<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign; // <--- WAJIB IMPORT MODEL
use App\Models\Donation; // <--- WAJIB IMPORT MODEL
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Dashboard admin utama
     */
    public function dashboard(): View
    {
        // Hitung statistik untuk Dashboard Utama
        $totalCampaigns = Campaign::count();
        $totalDonations = Donation::where('payment_status', 'paid')->sum('amount');
        $totalDonors = Donation::where('payment_status', 'paid')->count();

        return view('admin.dashboard', compact('totalCampaigns', 'totalDonations', 'totalDonors'));
    }

    /**
     * Halaman Kelola Donasi
     * Method ini yang dipanggil saat membuka menu "Donasi"
     */
    public function donations(): View
    {
        // 1. Ambil daftar donasi utama (Pagination untuk tabel besar)
        $donations = Donation::with('campaign')->latest()->paginate(10);

        // 2. Hitung Statistik (Angka-angka di kartu atas)
        $totalCampaigns = Campaign::count();
        $totalDonations = Donation::where('payment_status', 'paid')->count(); // Jumlah Transaksi
        $totalAmount = Donation::where('payment_status', 'paid')->sum('amount'); // Total Uang
        $totalDonors = Donation::where('payment_status', 'paid')->count(); // Total Donatur

        // 3. Ambil Donasi Terbaru (Widget kecil, misal 5 data terakhir)
        $recentDonations = Donation::with('campaign')
            ->latest()
            ->limit(5)
            ->get();

        // 4. Ambil Progress Kampanye (Widget Progress Bar) <--- INI PERBAIKANNYA
        $campaignProgress = Campaign::latest()
            ->limit(5)
            ->get();

        // 5. Kirim SEMUA variabel ke view
        return view('admin.donation.index', compact(
            'donations',
            'totalCampaigns',
            'totalDonations',
            'totalAmount',
            'totalDonors',
            'recentDonations',
            'campaignProgress' // <--- Variabel ini wajib ada agar tidak error
        ));
    }

    /**
     * Halaman BGDN admin
     */
    public function bgdn(): View
    {
        return view('admin.bgdn');
    }
}
