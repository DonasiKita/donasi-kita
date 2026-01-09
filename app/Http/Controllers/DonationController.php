<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
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

    // ==========================================
    // BAGIAN WEB (VIEW)
    // ==========================================

    /**
     * Menampilkan Form Donasi
     * Perbaikan: Mengambil ID dari ?campaign_id=1 menggunakan Request
     */
    public function create(Request $request)
    {
        // Ambil ID dari URL (Query String)
        $campaignId = $request->query('campaign_id');

        // Validasi jika ID kosong
        if (!$campaignId) {
            return redirect('/')->with('error', 'Kampanye tidak valid atau tidak ditemukan.');
        }

        $campaign = Campaign::findOrFail($campaignId);
        return view('donation.create', compact('campaign'));
    }

    /**
     * Memproses Donasi
     * Perbaikan: Mengambil ID dari <input type="hidden"> di form
     */
    public function store(Request $request)
    {
        // Validasi Input (Termasuk campaign_id dari hidden input)
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id', // Pastikan ID ada di database
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:100',
            'amount' => 'required|numeric|min:1000',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $campaignId = $validated['campaign_id'];

        DB::beginTransaction();

        try {
            // Generate Order ID
            $orderId = 'DON-' . $campaignId . '-' . time() . '-' . Str::random(5);

            // Simpan Data Donasi
            $donation = Donation::create([
                'campaign_id' => $campaignId,
                'user_id' => auth()->id() ?? null,
                'midtrans_order_id' => $orderId,
                'donor_name' => $validated['donor_name'],
                'donor_email' => $validated['donor_email'],
                'amount' => $validated['amount'],
                'message' => $validated['message'] ?? null,
                'is_anonymous' => $request->has('is_anonymous'),
                'payment_status' => 'pending',
                'snap_token' => null // Nanti diupdate
            ]);

            // Siapkan Parameter Midtrans
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

            // Request Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Update Token ke Database
            $donation->update(['snap_token' => $snapToken]);

            DB::commit();

            // Redirect ke Halaman Pembayaran
            return redirect()->route('donation.payment', $donation->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses donasi: ' . $e->getMessage())->withInput();
        }
    }

    public function payment($donationId)
    {
        $donation = Donation::with('campaign')->findOrFail($donationId);

        // Jika sudah lunas, langsung ke halaman sukses
        if ($donation->payment_status == 'paid') {
            return redirect()->route('donation.success', ['order_id' => $donation->midtrans_order_id]);
        }

        return view('donation.payment', compact('donation'));
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect('/')->with('error', 'Order ID tidak ditemukan.');
        }

        $donation = Donation::where('midtrans_order_id', $orderId)->with('campaign')->firstOrFail();

        return view('donation.success', compact('donation'));
    }

    // ==========================================
    // BAGIAN API (JSON / Webhook)
    // ==========================================

    /**
     * Cek Status untuk JavaScript di halaman Payment
     */
    public function checkStatus($orderId): JsonResponse
    {
        $donation = Donation::where('midtrans_order_id', $orderId)->first();

        if (!$donation) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $donation->payment_status, // pending, paid, failed
                'amount' => $donation->amount
            ]
        ]);
    }

    /**
     * Webhook/Callback dari Midtrans (Otomatis)
     */
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $donation = Donation::where('midtrans_order_id', $request->order_id)->first();

            if ($donation) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    // Update status jadi PAID
                    $donation->update(['payment_status' => 'paid']);

                    // Update Saldo Kampanye
                    $campaign = Campaign::find($donation->campaign_id);
                    if($campaign) {
                        $campaign->increment('current_amount', $donation->amount);
                        $campaign->increment('backer_count'); // Opsional: Tambah jumlah donatur
                    }

                } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $donation->update(['payment_status' => 'failed']);
                }
            }
        }
        return response()->json(['status' => 'success']);
    }
}
