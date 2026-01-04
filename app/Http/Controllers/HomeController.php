<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Featured campaigns - dengan progress percentage
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
                        'is_active' => $campaign->is_active,
                    ];
                });

            // Statistics - pastikan query benar
            $totalCampaigns = Campaign::where('is_active', true)->count();
            $totalDonations = Donation::where('payment_status', 'success')->count();
            $totalAmount = Donation::where('payment_status', 'success')->sum('amount');

            // Hitung total donatur unik (berdasarkan email)
            $totalDonors = Donation::where('payment_status', 'success')
                ->distinct('donor_email')
                ->count('donor_email');

            return view('home', compact(
                'featuredCampaigns',
                'totalCampaigns',
                'totalDonations',
                'totalAmount',
                'totalDonors'
            ));

        } catch (\Exception $e) {
            // Fallback jika ada error
            return view('home', [
                'featuredCampaigns' => collect(),
                'totalCampaigns' => 0,
                'totalDonations' => 0,
                'totalAmount' => 0,
                'totalDonors' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }
}
