<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationController extends Controller
{
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
        $validated = $request->validate([
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:100',
            'amount' => 'required|numeric|min:1000',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'payment_method' => 'required|in:qris,bank_transfer,gopay,shopeepay',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create donation record
            $donation = Donation::create([
                'campaign_id' => $campaignId,
                'donor_name' => $validated['donor_name'],
                'donor_email' => $validated['donor_email'],
                'amount' => $validated['amount'],
                'message' => $validated['message'] ?? null,
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'user_id' => null, // Guest donation
            ]);

            // 2. Create pending payment record
            $payment = Payment::create([
                'payment_id' => 'DONASI-' . Str::upper(Str::random(10)) . '-' . time(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'donation_id' => $donation->id,
                'response_data' => null,
            ]);

            // 3. Update campaign current amount (temporary, will be confirmed after payment)
            // $campaign = Campaign::find($campaignId);
            // $campaign->increment('current_amount', $validated['amount']);

            DB::commit();

            // 4. Redirect to payment page (Midtrans integration later)
            return redirect()->route('donation.success', $donation->id)
                ->with('success', 'Donasi berhasil diajukan! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show donation success page
     */
    public function success($donationId)
    {
        $donation = Donation::with(['campaign', 'payment'])->findOrFail($donationId);

        // Generate payment instructions based on payment method
        $paymentInstructions = $this->getPaymentInstructions($donation->payment);

        return view('donation.success', compact('donation', 'paymentInstructions'));
    }

    /**
     * Get payment instructions (dummy for now, will integrate with Midtrans)
     */
    private function getPaymentInstructions($payment)
    {
        $instructions = [];

        switch ($payment->payment_method) {
            case 'qris':
                $instructions = [
                    'title' => 'QRIS Payment',
                    'steps' => [
                        'Buka aplikasi mobile banking/e-wallet Anda',
                        'Pilih menu Scan/Pay QRIS',
                        'Scan QR code di bawah ini',
                        'Konfirmasi pembayaran',
                    ],
                    'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($payment->payment_id),
                ];
                break;

            case 'bank_transfer':
                $instructions = [
                    'title' => 'Transfer Bank',
                    'steps' => [
                        'Transfer ke rekening: BCA 123-456-7890 (DONASIKITA)',
                        'Masukkan nominal: Rp ' . number_format($payment->amount, 0, ',', '.'),
                        'Gunakan kode unik: ' . substr($payment->payment_id, -3),
                        'Konfirmasi via WhatsApp ke 081234567890',
                    ],
                    'account' => 'BCA 123-456-7890 a.n DonasiKita',
                ];
                break;

            default:
                $instructions = [
                    'title' => 'Pembayaran ' . ucfirst($payment->payment_method),
                    'steps' => [
                        'Pembayaran akan diproses melalui Midtrans',
                        'Anda akan diarahkan ke halaman pembayaran',
                        'Ikuti instruksi di halaman tersebut',
                    ],
                ];
        }

        return $instructions;
    }
}
