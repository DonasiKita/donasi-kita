<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class MidtransService
{
    public function __construct()
    {
        // Setup Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Snap Token for payment
     */
    public function createTransaction(array $transactionData)
    {
        try {
            $snapToken = Snap::getSnapToken($transactionData);
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create transaction data for Midtrans
     */
    public function prepareTransactionData($orderId, $amount, $customerDetails, $itemDetails = null)
    {
        // Default item details
        if (!$itemDetails) {
            $itemDetails = [
                [
                    'id' => 'donation',
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Donasi Sosial'
                ]
            ];
        }

        $transactionData = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24
            ]
        ];

        return $transactionData;
    }

    /**
     * Generate unique order ID
     */
    public function generateOrderId()
    {
        return 'DONASI-' . strtoupper(Str::random(8)) . '-' . time();
    }

    /**
     * Verify webhook signature
     */
    public function verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)
    {
        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }
}
