<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Barang;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct()
    {
        $this->middleware('auth')->except(['notification']);

        try {
            $this->midtransService = new MidtransService();
        } catch (\Error $e) {
            // Midtrans package not installed
            Log::error('Midtrans Package Not Installed: ' . $e->getMessage());
        }
    }

    /**
     * Show payment page with Snap
     */
    public function index($pesanan_id)
    {
        // Check if Midtrans package installed
        if (!$this->midtransService) {
            Alert::error('Midtrans package belum terinstall. Jalankan: composer require midtrans/midtrans-php', 'Error');
            return redirect()->route('checkout');
        }

        $pesanan = Pesanan::with('pesanan_detail.barang', 'user')->findOrFail($pesanan_id);

        // Verify ownership
        if ($pesanan->user_id != Auth::id()) {
            abort(403);
        }

        // Build transaction params for Midtrans
        $itemDetails = [];
        foreach ($pesanan->pesanan_detail as $detail) {
            // Skip if product deleted
            if (!$detail->barang) {
                continue;
            }

            $itemDetails[] = [
                'id' => $detail->barang->id,
                'price' => $detail->barang->harga,
                'quantity' => $detail->jumlah,
                'name' => $detail->barang->nama_barang,
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $pesanan->kode,
                'gross_amount' => $pesanan->total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $pesanan->user->name,
                'email' => $pesanan->user->email,
                'phone' => $pesanan->user->no_hp ?? '08123456789',
            ],
        ];

        // Get Snap token
        $result = $this->midtransService->createTransaction($params);

        if (!$result['success']) {

            Alert::error('Gagal membuat transaksi: ' . ($result['message'] ?? 'Unknown error'), 'Error');
            return redirect()->route('checkout');
        }

        // Save snap token
        $pesanan->snap_token = $result['snap_token'];
        $pesanan->save();

        // Show payment page with Snap popup
        return view('pesan.payment', [
            'pesanan' => $pesanan,
            'snapToken' => $result['snap_token'],
            'clientKey' => $this->midtransService->getClientKey(),
        ]);
    }

    /**
     * Handle notification from Midtrans
     */
    public function notification(Request $request)
    {
        $notif = $this->midtransService->verifyNotification($request->all());

        if (!$notif) {
            return response()->json(['status' => 'error'], 403);
        }

        $pesanan = Pesanan::where('kode', $notif->order_id)->first();

        if (!$pesanan) {
            return response()->json(['status' => 'error'], 404);
        }

        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status;

        if (
            $transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')
        ) {
            $pesanan->markAsPaid($notif->payment_type);

            foreach ($pesanan->pesanan_detail as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->decrement('stok', $detail->jumlah);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Update order to paid status and reduce stock
     */
    private function updateOrderPaid($pesanan, $paymentType)
    {
        if ($pesanan->status == 'checkout') {
            // Already processed
            return;
        }

        $pesanan->status = 'checkout';
        $pesanan->payment_type = $paymentType;
        $pesanan->save();

        // Reduce stock
        foreach ($pesanan->pesanan_detail as $detail) {
            $barang = Barang::find($detail->barang_id);
            if ($barang) {
                $barang->stok -= $detail->jumlah;
                $barang->save();
            }
        }
    }

    /**
     * Check payment status (for ajax polling)
     */
    public function checkStatus($pesanan_id)
    {
        $pesanan = Pesanan::findOrFail($pesanan_id);

        // Verify ownership
        if ($pesanan->user_id != Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $pesanan->status,
            'status_label' => $pesanan->status_val
        ]);
    }

    public function updateStatus(Request $request)
    {
        $pesanan = Pesanan::where('kode', $request->order_id)->first();

        if (!$pesanan || $pesanan->user_id != Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $pesanan->markAsPaid($request->payment_type);

        // Send order confirmation email
        try {
            // Eager load with null-safe check
            $pesanan->load(['pesanan_detail.barang', 'user']);

            Mail::send('emails.order-confirmation', [
                'pesanan' => $pesanan
            ], function ($message) use ($pesanan) {
                $message->to($pesanan->user->email);
                $message->subject('Konfirmasi Pesanan #' . $pesanan->kode . ' - Toko Online Khas Jogja');
            });
        } catch (\Exception $e) {
            // Log error but don't fail the payment
            Log::error('Order confirmation email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }
}
