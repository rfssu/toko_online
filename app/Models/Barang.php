<?php

namespace App\Models;

use App\Traits\Fileable;
use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory, Validatable, Fileable;

    protected $table = 'barangs';

    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_barang',
        'harga',
        'stok',
        'gambar',
        'keterangan',
    ];

    public function rules($scenario = null)
    {
        $scenarios = [
            null => [
                'nama_barang' => 'required',
                'harga' => 'required',
                'stok_fisik' => 'required',
            ]
        ];

        $rules = $scenarios[$scenario] ?? $scenarios[null];
        return $rules;
    }

    public function labels()
    {
        return [
            'nama_barang' => 'Nama Barang',
            'harga' => 'Harga',
            'stok_fisik' => 'Stok',
            'keterangan' => 'Keterangan'
        ];
    }

    // Relationship to order details (for sales tracking)
    public function pesanan_detail()
    {
        return $this->hasMany(PesananDetail::class, 'barang_id');
    }


    public function getStokReadyAttribute()
    {
        $terjual = $this->pesanan_detail()
            ->whereHas('pesanan', function ($q) {
                $q->whereIn('status', [
                    Pesanan::STATUS_CO,
                    Pesanan::STATUS_PICKUP,
                ]);
            })
            ->sum('jumlah');

        return $this->stok - $terjual;
    }

    public function getStokFisikAttribute()
    {
        $terjual = $this->pesanan_detail()
            ->whereHas('pesanan', function ($q) {
                $q->whereIn('status', [
                    Pesanan::STATUS_PICKUP,
                ]);
            })
            ->sum('jumlah');

        return $this->stok - $terjual;
    }

    public function getStokBookingAttribute()
    {
        $terjual = $this->pesanan_detail()
            ->whereHas('pesanan', function ($q) {
                $q->whereIn('status', [
                    Pesanan::STATUS_CO,
                ]);
            })
            ->sum('jumlah');

        return $terjual;
    }
}
