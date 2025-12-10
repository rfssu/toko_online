<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Barang; // <-- Panggil Model Barang

class HomeController extends Controller
{
    public function index()
    {
        // 1. AMBIL DATA BEST SELLER (simulasi: 8 produk pertama)
        // TODO: Nanti bisa diganti dengan query berdasarkan jumlah penjualan
        $bestSellers = Barang::take(8)->get();

        // 2. AMBIL PRODUK BARU (8 produk terbaru)
        $newProducts = Barang::latest()->take(8)->get();

        // 3. RETURN KE VIEW DI DALAM FOLDER BUYER
        return view('buyer.home', [
            'best_sellers' => $bestSellers,
            'new_products' => $newProducts
        ]);
    }

    public function produk(Request $request)
    {
        // AMBIL QUERY SEARCH DARI REQUEST
        $search = $request->input('search');

        // QUERY BARANG DENGAN FILTER SEARCH
        $barangs = Barang::query();

        if ($search) {
            $barangs->where('nama_barang', 'LIKE', '%' . $search . '%');
        }

        $barangs = $barangs->get();

        return view('buyer.produk', [
            'data_barang' => $barangs,
            'search' => $search
        ]);
    }

    public function tentang()
    {
        // RETURN VIEW TENTANG KAMI
        return view('buyer.tentang');
    }

    public function profile()
    {
        // 1. AMBIL DATA USER YANG SEDANG LOGIN
        $user = Auth::user();

        // 2. HITUNG JUMLAH PESANAN USER
        $totalOrders = $user->pesanan()->count();
        $completedOrders = $user->pesanan()->where('status', 'selesai')->count();
        $processingOrders = $user->pesanan()->where('status', 'diproses')->count();

        // 3. AMBIL 3 PESANAN TERBARU
        $recentOrders = $user->pesanan()->latest()->take(3)->get();

        // 4. KIRIM DATA KE VIEW
        return view('buyer.profile', [
            'user' => $user,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'processingOrders' => $processingOrders,
            'recentOrders' => $recentOrders
        ]);
    }

    public function updateProfile(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',

        ]);
        // 2. AMBIL USER YANG SEDANG LOGIN
        $user = Auth::user();
        // 3. UPDATE DATA USER
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        $user->save();
        // 4. REDIRECT KEMBALI DENGAN PESAN SUKSES
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
    public function updatePassword(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        // 2. AMBIL USER YANG SEDANG LOGIN
        $user = Auth::user();
        // 3. CEK PASSWORD LAMA
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }
        // 4. UPDATE PASSWORD BARU
        $user->password = Hash::make($request->new_password);
        $user->save();
        // 5. REDIRECT DENGAN PESAN SUKSES
        return redirect()->route('profile')->with('success', 'Password berhasil diubah!');
    }
}