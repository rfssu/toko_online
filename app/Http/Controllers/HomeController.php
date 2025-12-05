<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Pesanan;





class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $barangs = Barang::paginate(200);
        $query = $request->get('search');
    $barangs = Barang::where('nama_barang', 'like', '%' . $query . '%')->paginate(10);
        return view('home',compact('barangs'));
    }
}
