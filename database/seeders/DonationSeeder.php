<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = Campaign::all();
        $users = User::all();

        $donorNames = ['Budi Santoso', 'Sari Dewi', 'Ahmad Fauzi', 'Lisa Permata',
                      'Rudi Hartono', 'Diana Putri', 'Hendra Kurniawan', 'Maya Sari'];

        foreach ($campaigns as $campaign) {
            // Create 5-10 donations per campaign
            $donationCount = rand(5, 10);

            for ($i = 0; $i < $donationCount; $i++) {
                $status = ['pending', 'success', 'failed'][rand(0, 2)];

                Donation::create([
                    'campaign_id' => $campaign->id,
                    'user_id' => rand(0, 1) ? $users->random()->id : null,
                    'donor_name' => $donorNames[array_rand($donorNames)],
                    'donor_email' => strtolower(str_replace(' ', '.', $donorNames[array_rand($donorNames)])) . '@example.com',
                    'amount' => [50000, 100000, 200000, 500000, 1000000][rand(0, 4)],
                    'note' => rand(0, 1) ? 'Semoga bermanfaat untuk yang membutuhkan.' : null,
                    'payment_status' => $status,
                    'midtrans_order_id' => 'DONASI-' . strtoupper(uniqid()),
                    'midtrans_transaction_id' => $status === 'success' ? 'TRX-' . strtoupper(uniqid()) : null,
                ]);
            }
        }

        // Update campaign current amounts based on successful donations
        foreach ($campaigns as $campaign) {
            $total = $campaign->donations()
                ->where('payment_status', 'success')
                ->sum('amount');

            $campaign->update(['current_amount' => $total]);
        }
    }
}
