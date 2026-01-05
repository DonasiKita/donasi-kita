<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation; // PENTING: Import Model Donation
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        // 1. Ambil data donasi dari database
        // 'with' digunakan untuk mengambil relasi campaign agar query lebih cepat (eager loading)
        // 'latest' untuk mengurutkan dari yang terbaru
        $donations = Donation::with('campaign')->latest()->paginate(10);

        // 2. Return view sambil membawa data $donations
        // Pastikan nama folder view Anda benar (admin.donation.index atau admin.donations.index)
        return view('admin.donation.index', compact('donations'));
    }
}
