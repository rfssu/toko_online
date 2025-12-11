<?php
namespace App\Http\Controllers;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Requests\PesananRequest;
class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display list of orders waiting for pickup (status='checkout')
     */
    public function index()
    {
        $pesanans = Pesanan::with(['user', 'pesanan_detail.barang'])
            ->whereIn('status', ['checkout', 'siap_pickup'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('seller.pages.pesanans.index', compact('pesanans'));
    }
    /**
     * Mark order as ready for pickup
     */
    public function markReady($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status != 'checkout') {
            return back()->with('error', 'Pesanan tidak dalam status checkout');
        }
        $pesanan->status = 'siap_pickup';
        $pesanan->save();
        return back()->with('success', 'Pesanan berhasil ditandai siap pickup');
    }
    /**
     * View order details
     */
    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'pesanan_detail.barang'])->findOrFail($id);
        return view('seller.pages.pesanans.show', compact('pesanan'));
    }
}