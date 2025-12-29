<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Services\MidtransService;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;

class PesanController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index($id)
    {
        $barang = Barang::where('id', $id)->first();

        return view('pesan.index', compact('barang'));
    }

    public function addToCartAjax(Request $request)
    {
        $params = $request->all();
        $params['user_id'] = $this->user->id;

        $model = PesananDetail::where('barang_id', $params['barang_id'])
            ->where('user_id', $params['user_id'])
            ->whereNull('pesanan_id')->first() ?? new PesananDetail;

        if ($model->id) {
            $params['jumlah'] += $model->jumlah;
        }

        if (empty($params['jumlah'])) {
            $model->delete();
            return redirect()->back()->with('success', 'Barang berhasil diupdate!');
        } else {
            AutoFill::fill($model, params: $params);
            $model->saveOrFail();
            return redirect()->back()->with('success', 'Barang berhasil diupdate!');
        }
    }

    public function check_out()
    {
        $pesanan_details = $this->user->barang_keranjang;
        $user = $this->user;

        return view('pesan.keranjang', get_defined_vars());
    }

    public function delete($id)
    {
        $pesanan_detail = PesananDetail::where('id', $id)->first() ?? null;

        // Delete item (total will be recalculated automatically)
        $pesanan_detail->delete();

        Alert::success('Item berhasil dihapus dari keranjang', 'Hapus');
        return redirect('check-out');
    }

    public function konfirmasi(Request $request)
    {
        $params = $request->all();
        $user = $this->user;

        // Validasi user profile
        if (empty($user->alamat) || empty($user->no_hp)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silahkan lengkapi profil Anda terlebih dahulu'
                ], 400);
            }
            Alert::error('Silahkan lengkapi profil Anda terlebih dahulu', 'Error');
            return redirect('profile');
        }

        $pesanan_details = $user->barang_keranjang;

        if ($pesanan_details->isEmpty()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }
            Alert::error('Keranjang kosong', 'Error');
            return redirect('/');
        }

        // Create pesanan with pickup time
        $kode = 'ORD-' . date('ymd') . '-' . uniqid();
        $pesanan = Pesanan::lockForUpdate()->create([
            'kode' => $kode,
            'user_id' => $user->id,
            'status' => Pesanan::STATUS_PENDING,
            'tanggal_pickup' => $request->input('pickup_time'), // Save selected pickup time
        ]);

        // Move items from cart to pesanan_detail
        foreach ($pesanan_details as $detail) {
            $detail->update([
                'pesanan_id' => $pesanan->id,
            ]);
        }

        // Get Midtrans snap token
        try {
            $midtransService = new MidtransService();

            // Build transaction params
            $itemDetails = [];
            foreach ($pesanan->pesanan_detail as $detail) {
                // Use data from pesanan_detail (not barang) to handle deleted products
                $itemDetails[] = [
                    'id' => $detail->id,
                    'price' => (int) $detail->harga,
                    'quantity' => (int) $detail->jumlah,
                    'name' => $detail->barang?->nama_barang ?? 'Produk (ID: ' . $detail->id . ')',
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $pesanan->kode,
                    'gross_amount' => (int) $pesanan->total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->no_hp ?? '08123456789',
                ],
            ];

            $result = $midtransService->createTransaction($params);

            if (!$result['success']) {

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat token pembayaran: ' . $result['message']
                    ], 500);
                }

                Alert::error('Gagal membuat token pembayaran', 'Error');
                return redirect()->route('checkout');
            }

            // Save snap token
            $pesanan->snap_token = $result['snap_token'];
            $pesanan->save();

            // Return AJAX response with snap token
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'snap_token' => $result['snap_token'],
                    'client_key' => $midtransService->getClientKey(),
                ]);
            }

            // Fallback redirect (shouldn't happen)
            return redirect()->route('payment.index', $pesanan->id);
        } catch (\Exception $e) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            Alert::error('Terjadi kesalahan sistem', 'Error');
            return redirect()->route('checkout');
        }
    }
}
