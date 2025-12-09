<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // 1. Definisikan Nama Tabel (Jika beda dengan nama model)
    // Contoh: Jika di database namanya 'tb_produk', tulis di bawah.
    protected $table = 'barangs'; 

    // 2. Definisikan Primary Key (Jika bukan 'id')
    protected $primaryKey = 'id';

    // 3. Kolom mana saja yang boleh diisi/diedit
    // Masukkan nama-nama kolom sesuai database Anda
    protected $fillable = [
        'nama_barang',
        'harga',
        'stok',
        'gambar',
        'keterangan',
    ];
}