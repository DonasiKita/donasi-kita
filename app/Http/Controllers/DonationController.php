<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    protected $midtransService;

    public function __construct()
    {
        $this->midtransService = new MidtransService();
    }

    /**
     * Show donation form
     */
    public function create(Request $request)
    {
        $campaignId = $request->query('campaign_id');

        if (!$campaignId) {
            return redirect('/')->with('error', 'Pilih kampanye terlebih dahulu');
        }

        $campaign = Campaign::find($campaignId);

        if (!$campaign || !$campaign->is_active) {
            return redirect('/')->with('error', 'Kampanye tidak ditemukan atau tidak aktif');
        }

        return view('donation.create', compact('campaign'));
    }

    /**
     * Process donation (Step 1: Create donation record)
     */
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:100',
            'amount' => 'required|integer|min:1000',
            'note' => 'nullable|string|max:500',
        ]);

        $campaign = Campaign::find($request->campaign_id);

        if (!$campaign->is_active) {
            return back()->with('error', 'Kampanye ini tidak aktif')->withInput();
        }

        // Generate unique order ID
        $orderId = $this->midtransService->generateOrderId();

        // Create donation record
        $donation = Donation::create([
            'campaign_id' => $request->campaign_id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'amount' => $request->amount,
            'note' => $request->note,
            'midtrans_order_id' => $orderId,
            'payment_status' => 'pending',
        ]);

        // Prepare customer details for Midtrans
        $customerDetails = [
            'first_name' => substr($request->donor_name, 0, 50),
            'email' => $request->donor_email,
        ];

        // Prepare transaction data
        $transactionData = $this->midtransService->prepareTransactionData(
            $orderId,
            $request->amount,
            $customerDetails
        );

        // Get Snap Token from Midtrans
        $midtransResponse = $this->midtransService->createTransaction($transactionData);

        if (!$midtransResponse['success']) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $midtransResponse['message'])->withInput();
        }

        // Update donation with snap token
        $donation->update(['midtrans_snap_token' => $midtransResponse['snap_token']]);

        // Redirect to payment page with donation data
        return redirect()->route('donation.payment', $donation->id)
            ->with('success', 'Donasi berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    /**
     * Show payment page (Step 2: Midtrans Snap)
     */
    public function payment($donationId)
    {
        $donation = Donation::with('campaign')->findOrFail($donationId);

        if ($donation->payment_status !== 'pending') {
            return redirect()->route('donation.status', $donation->midtrans_order_id)
                ->with('info', 'Status pembayaran: ' . $donation->status_text);
        }

        return view('donation.payment', compact('donation'));
    }

    /**
     * Check payment status
     */
    public function status($orderId)
    {
        $donation = Donation::where('midtrans_order_id', $orderId)
            ->with('campaign')
            ->firstOrFail();

        return view('donation.status', compact('donation'));
    }

    /**
     * Success page
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            $donation = Donation::where('midtrans_order_id', $orderId)->first();
        } else {
            $donation = null;
        }

        return view('donation.success', compact('donation'));
    }

    /**
     * Failed page
     */
    public function failed(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            $donation = Donation::where('midtrans_order_id', $orderId)->first();
        } else {
            $donation = null;
        }

        return view('donation.failed', compact('donation'));
    }

    /**
     * Midtrans webhook handler
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        // Verify signature (optional but recommended for production)
        // $signatureKey = $payload['signature_key'] ?? '';
        // if (!$this->midtransService->verifySignature(
        //     $payload['order_id'],
        //     $payload['status_code'],
        //     $payload['gross_amount'],
        //     $signatureKey
        // )) {
        //     return response()->json(['error' => 'Invalid signature'], 403);
        // }

        // Find donation
        $donation = Donation::where('midtrans_order_id', $payload['order_id'])->first();

        if (!$donation) {
            return response()->json(['error' => 'Donation not found'], 404);
        }

        // Update payment status based on Midtrans response
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        $status = 'pending';

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $status = 'success';
            }
        } elseif ($transactionStatus == 'settlement') {
            $status = 'success';
        } elseif ($transactionStatus == 'pending') {
            $status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $status = 'failed';
        }

        // Update donation
        $donation->updatePaymentStatus($status, $payload);

        return response()->json(['status' => 'OK']);
    }
}
