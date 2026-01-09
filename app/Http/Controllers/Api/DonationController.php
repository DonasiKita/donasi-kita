<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
// use App\Models\Payment; // Opsional: Jika Anda ingin memisahkan tabel payment, tapi biasanya disatukan di tabel donations untuk simplifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class DonationController extends Controller
{
    /**
     * Konfigurasi Midtrans di Constructor
     */
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Show donation form (guest)
     */
    public function create($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        return view('donation.create', compact('campaign'));
    }

    /**
     * Process donation (guest)
     */
    public function store(Request $request, $campaignId)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:100',
            'amount' => 'required|numeric|min:1000',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean', // Diubah ke nullable agar tidak error jika checkbox tidak dicentang
        ]);

        DB::beginTransaction();

        try {
            // 2. Generate Order ID Unik
            // Format: DON-{CampaignID}-{Timestamp}-{Random}
            $orderId = 'DON-' . $campaignId . '-' . time() . '-' . Str::random(5);

            // 3. Simpan Record Donasi (Status awal: Pending)
            $donation = Donation::create([
                'campaign_id' => $campaignId,
                'user_id' => auth()->id() ?? null, // Support logged in user or guest
                'midtrans_order_id' => $orderId,   // Pastikan kolom ini ada di database
                'donor_name' => $validated['donor_name'],
                'donor_email' => $validated['donor_email'],
                'amount' => $validated['amount'],
                'message' => $validated['message'] ?? null,
                'is_anonymous' => $request->has('is_anonymous'),
                'payment_status' => 'pending',     // pending, paid, failed
            ]);

            // 4. Siapkan Parameter untuk Midtrans Snap
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $validated['amount'],
                ],
                'customer_details' => [
                    'first_name' => $validated['donor_name'],
                    'email' => $validated['donor_email'],
                ],
                'item_details' => [
                    [
                        'id' => 'CAMPAIGN-' . $campaignId,
                        'price' => (int) $validated['amount'],
                        'quantity' => 1,
                        'name' => 'Donasi Kampanye ID #' . $campaignId,
                    ]
                ]
            ];

            // 5. Minta Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 6. Simpan Snap Token ke Database
            $donation->update(['snap_token' => $snapToken]);

            DB::commit();

            // 7. Redirect ke halaman pembayaran (Success Page dengan tombol Pay)
            return redirect()->route('donation.payment', $donation->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses donasi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Halaman Pembayaran (Menampilkan Tombol Pay Midtrans)
     * Menggantikan function success() lama
     */
    public function payment($donationId)
    {
        $donation = Donation::with('campaign')->findOrFail($donationId);

        // Jika sudah dibayar, jangan tampilkan tombol bayar lagi
        if ($donation->payment_status == 'paid') {
            return redirect()->route('campaign.show', $donation->campaign_id)
                ->with('success', 'Donasi ini sudah berhasil dibayar sebelumnya.');
        }

        return view('donation.payment', compact('donation'));
    }

    /**
     * Callback/Webhook dari Midtrans (Wajib ada untuk update status otomatis)
     * Route: POST /api/midtrans-callback
     */
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $donation = Donation::where('midtrans_order_id', $request->order_id)->first();

            if ($donation) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $donation->update(['payment_status' => 'paid']);

                    // Update total donasi kampanye
                    $campaign = Campaign::find($donation->campaign_id);
                    $campaign->increment('current_amount', $donation->amount);
                    $campaign->increment('backer_count'); // Opsional: Tambah jumlah donatur
                } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $donation->update(['payment_status' => 'failed']);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }
}
