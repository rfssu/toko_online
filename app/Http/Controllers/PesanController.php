<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;

class PesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $barang = Barang::where('id', $id)->first();

        return view('pesan.index', compact('barang'));
    }

    public function addToCartAjax(Request $request)
    {
        try {
            $request->validate([
                'barang_id' => 'required|exists:barangs,id',
                'jumlah' => 'integer|min:1'
            ]);
            $barang = Barang::findOrFail($request->barang_id);
            $jumlah = $request->jumlah ?? 1;
            // Validasi stok
            if ($jumlah > $barang->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }
            // Get or create keranjang (status='keranjang')
            $pesanan = Pesanan::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'status' => 'keranjang'
                ],
                [
                    'kode' => mt_rand(100000, 999999)
                ]
            );
            // Check if item already in cart
            $pesananDetail = PesananDetail::where('pesanan_id', $pesanan->id)
                ->where('barang_id', $barang->id)
                ->first();
            if ($pesananDetail) {
                // Update quantity (harga sudah tersimpan, hanya update jumlah)
                $pesananDetail->jumlah += $jumlah;
                $pesananDetail->save();
            } else {
                // Create new item (simpan harga per unit)
                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah,
                    'harga' => $barang->harga
                ]);
            }
            // Total akan di-calculate otomatis via getTotalAttribute()
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $pesanan->getItemCount(),
                'cart_total' => $pesanan->total  // Use attribute instead
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function pesan(Request $request, $id)
    {
        $barang = Barang::where('id', $id)->first();
        $tanggal = Carbon::now();

        //validasi jika melebihi stok
        if ($request->jumlah_pesan > $barang->stok) {
            Alert::error('Jumlah pesanan melebihi stok barang', 'Error');
            return redirect('pesan/' . $id);
        }
        //validasi
        $cek_pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //menyimpan data pemesanan
        if (empty($cek_pesanan)) {
            $pesanan = new Pesanan;
            $pesanan->user_id = Auth::user()->id;
            $pesanan->tanggal = $tanggal;
            $pesanan->status = 0;
            $pesanan->jumlah_harga = 0;
            $pesanan->kode = mt_rand(100, 999);
            $pesanan->save();
        }


        //simpan data pesanan detail
        $pesanan_baru = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //cek pesanan detail
        $cek_pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();
        if (empty($cek_pesanan_detail)) {
            $pesanan_detail = new PesananDetail;
            $pesanan_detail->barang_id = $barang->id;
            $pesanan_detail->pesanan_id = $pesanan_baru->id;
            $pesanan_detail->jumlah = $request->jumlah_pesan;
            $pesanan_detail->jumlah_harga = $barang->harga * $request->jumlah_pesan;
            $pesanan_detail->save();
        } else {
            $pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();
            $pesanan_detail->jumlah = $pesanan_detail->jumlah + $request->jumlah_pesan;

            //harga sekarang
            $harga_pesanan_detail_baru = $barang->harga * $request->jumlah_pesan;
            $pesanan_detail->jumlah_harga = $pesanan_detail->jumlah_harga + $harga_pesanan_detail_baru;
            $pesanan_detail->update();
        }
        //jumlah total
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $pesanan->jumlah_harga = $pesanan->jumlah_harga + $barang->harga * $request->jumlah_pesan;
        $pesanan->update();

        Alert::success('Pesanan Berhasil Masuk Keranjang', 'Success');

        return redirect('check-out');
    }

    public function check_out()
    {
        // Query cart: keranjang OR pending_payment (waiting for payment)
        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->whereIn('status', ['keranjang', 'pending_payment'])
            ->first();

        // DEBUG: Log what we found
        \Log::info('Checkout Debug', [
            'user_id' => Auth::user()->id,
            'pesanan_found' => $pesanan ? true : false,
            'pesanan_id' => $pesanan ? $pesanan->id : null,
            'pesanan_status' => $pesanan ? $pesanan->status : null,
        ]);

        $pesanan_details = [];
        if ($pesanan) {
            $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();

            // DEBUG: Log details
            \Log::info('Checkout Details', [
                'pesanan_detail_count' => $pesanan_details->count(),
                'details' => $pesanan_details->pluck('id', 'barang_id')->toArray()
            ]);
        }

        return view('pesan.keranjang', compact('pesanan', 'pesanan_details'));
    }

    public function delete($id)
    {
        $pesanan_detail = PesananDetail::where('id', $id)->first();

        // Delete item (total will be recalculated automatically)
        $pesanan_detail->delete();

        Alert::success('Item berhasil dihapus dari keranjang', 'Hapus');
        return redirect('check-out');
    }

    public function konfirmasi(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

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

        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->where('status', 'keranjang')
            ->first();

        if (!$pesanan) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }
            Alert::error('Keranjang kosong', 'Error');
            return redirect('/');
        }

        // Update status ke pending_payment
        $pesanan->status = 'pending_payment';
        $pesanan->save();

        // Create Midtrans Snap token
        try {
            $midtransService = new \App\Services\MidtransService();

            // Build transaction params
            $itemDetails = [];
            foreach ($pesanan->pesanan_detail as $detail) {
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

            $result = $midtransService->createTransaction($params);

            if (!$result['success']) {
                \Log::error('Snap Token Creation Failed', ['message' => $result['message']]);

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

            \Log::info('Snap Token Created', [
                'pesanan_id' => $pesanan->id,
                'token_length' => strlen($result['snap_token'])
            ]);

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
            \Log::error('Konfirmasi Exception', ['error' => $e->getMessage()]);

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
