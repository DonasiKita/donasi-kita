<?php

namespace App\Http\Controllers\Api; // <--- WAJIB ADA \Api

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DonationController extends Controller
{
    // Cek Status Donasi untuk JavaScript (JSON)
    public function checkStatus($orderId): JsonResponse
    {
        $donation = Donation::where('midtrans_order_id', $orderId)->first();

        if (!$donation) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $donation->payment_status,
                'amount' => $donation->amount
            ]
        ]);
    }

    // Webhook Midtrans (JSON)
    public function webhook(Request $request): JsonResponse
    {
        // ... (Gunakan logika update status yang Anda berikan tadi)
        return response()->json(['status' => 'OK']);
    }
}
