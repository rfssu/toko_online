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
                    'tanggal' => now(),
                    'kode' => mt_rand(100, 999)
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
        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->where('status', 'keranjang')
            ->first();

        $pesanan_details = [];
        if ($pesanan) {
            $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();
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

    public function konfirmasi()
    {
        $user = User::where('id', Auth::user()->id)->first();

        // Validasi user profile
        if (empty($user->alamat) || empty($user->no_hp)) {
            Alert::error('Silahkan lengkapi profil Anda terlebih dahulu', 'Error');
            return redirect('profile');
        }
        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->where('status', 'keranjang')
            ->first();
        if (!$pesanan) {
            Alert::error('Keranjang kosong', 'Error');
            return redirect('/');
        }
        // Update status ke checkout
        $pesanan->status = 'checkout';
        $pesanan->save();
        // Kurangi stok
        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();
        foreach ($pesanan_details as $detail) {
            $barang = Barang::find($detail->barang_id);
            $barang->stok -= $detail->jumlah;
            $barang->save();
        }
        Alert::success('Pesanan berhasil dikonfirmasi! Silahkan tunggu pemberitahuan siap pickup', 'Success');

        return redirect()->route('history');
    }
}
