<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCampaigns = Campaign::count();
        $totalDonations = Donation::where('payment_status', 'success')->count();
        $totalAmount = Donation::where('payment_status', 'success')->sum('amount');

        $recentDonations = Donation::with('campaign')
            ->where('payment_status', 'success')
            ->latest()
            ->limit(10)
            ->get();

        $campaignProgress = Campaign::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalCampaigns',
            'totalDonations',
            'totalAmount',
            'recentDonations',
            'campaignProgress'
        ));
    }
}
