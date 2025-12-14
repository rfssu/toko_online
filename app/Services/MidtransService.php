<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production');

        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create Snap transaction and get token
     */
    public function createTransaction($params)
    {
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return [
                'success' => true,
                'snap_token' => $snapToken
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Create Transaction Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans Get Status Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify notification signature
     */
    public function verifyNotification($notification)
    {
        try {
            $notif = new \Midtrans\Notification();

            // Verify signature
            $signatureKey = hash('sha512', $notif->order_id . $notif->status_code . $notif->gross_amount . $this->serverKey);

            if ($signatureKey != $notif->signature_key) {
                return false;
            }

            return $notif;
        } catch (\Exception $e) {
            Log::error('Midtrans Verify Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client key for frontend
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }
}
