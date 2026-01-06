<?php

namespace App\Http\Controllers; // <--- Perhatikan namespace harus ada \Api

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // 1. Ambil Data Kampanye Unggulan
            $featuredCampaigns = Campaign::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
                ->map(function ($campaign) {
                    $progress = $campaign->target_amount > 0
                        ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100)
                        : 0;

                    return [
                        'id' => $campaign->id,
                        'title' => $campaign->title,
                        'description' => $campaign->description,
                        'target_amount' => (int) $campaign->target_amount,
                        'current_amount' => (int) $campaign->current_amount,
                        'progress_percentage' => round($progress, 2),
                        'image_url' => $campaign->image_url,
                    ];
                });

            // 2. Hitung Statistik (Menyesuaikan variabel yang diminta JavaScript Anda)
            $statistics = [
                // Mengambil SUM amount untuk 'total_donations' agar angka besar muncul
                'total_donations' => (int) Donation::where('payment_status', 'success')->sum('amount'),
                'total_campaigns' => (int) Campaign::where('is_active', true)->count(),
                'total_donors'    => (int) Donation::where('payment_status', 'success')
                                        ->distinct('donor_email')
                                        ->count('donor_email'),
            ];

            // 3. Kembalikan format JSON sesuai permintaan script home.blade.php
            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $statistics,
                    'featured_campaigns' => $featuredCampaigns
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
}
