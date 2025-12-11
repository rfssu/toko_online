<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use App\Models\PesananDetail;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $pesanans = Pesanan::where('user_id', Auth::user()->id)
            ->whereIn('status', ['checkout', 'siap_pickup'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('history', compact('pesanans'));
    }
    public function detail($id)
    {
        $pesanan = Pesanan::where('id', $id)->first();
        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();
        return view('history.detail', compact('pesanan', 'pesanan_details'));
    }
}
