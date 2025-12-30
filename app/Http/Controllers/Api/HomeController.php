<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCampaigns = Campaign::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'slug' => $campaign->slug,
                    'description' => substr($campaign->description, 0, 100) . '...',
                    'target_amount' => (int) $campaign->target_amount,
                    'current_amount' => (int) $campaign->current_amount,
                    'progress_percentage' => $campaign->target_amount > 0 ?
                        min(100, ($campaign->current_amount / $campaign->target_amount) * 100) : 0,
                    'image_url' => $campaign->image_url,
                    'created_at' => $campaign->created_at->format('d M Y'),
                ];
            });

        $totalDonations = Campaign::sum('current_amount');
        $totalCampaigns = Campaign::where('is_active', true)->count();

        return $this->success([
            'featured_campaigns' => $featuredCampaigns,
            'statistics' => [
                'total_donations' => (int) $totalDonations,
                'total_campaigns' => $totalCampaigns,
                'total_donors' => \App\Models\Donation::where('payment_status', 'success')
                    ->distinct('donor_email')
                    ->count(),
            ]
        ], 'Berhasil mengambil data home');
    }

    public function about()
    {
        return $this->success([
            'name' => 'DonasiKita',
            'description' => 'Platform crowdfunding dan donasi online berbasis cloud untuk membantu sesama dengan cara yang mudah, transparan, dan terpercaya.',
            'mission' => 'Menyediakan platform yang aman dan transparan untuk menggalang dana bagi yang membutuhkan.',
            'vision' => 'Menjadi platform donasi terdepan di Indonesia yang menghubungkan para donatur dengan penerima manfaat secara efisien.',
            'contact' => [
                'email' => 'admin@donasikita.id',
                'phone' => '+62 812-3456-7890',
                'address' => 'Jl. Contoh No. 123, Jakarta, Indonesia'
            ]
        ], 'Berhasil mengambil data about');
    }
}
