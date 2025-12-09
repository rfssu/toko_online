<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang; // <-- Panggil Model Barang

class HomeController extends Controller
{
    public function index()
    {
        // 1. AMBIL DATA DARI TABEL 'BARANGS'
        $barangs = Barang::all(); 

        // 2. RETURN KE VIEW DI DALAM FOLDER BUYER
        // Perhatikan tanda titik (.) sebagai pemisah folder
        return view('buyer.home', [
            'data_barang' => $barangs
        ]);
    }
}