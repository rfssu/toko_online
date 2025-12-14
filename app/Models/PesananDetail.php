<?php

namespace App\Models;

use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    use HasFactory, Validatable;

    protected $fillable = [
        'user_id',
        'pesanan_id',
        'barang_id',
        'jumlah',
        'harga'
    ];

    public function rules($scenario = null)
    {
        $scenarios = [
            null => [
                'barang_id' => 'required',
                'jumlah' => 'required',
                'user_id' => 'required',
            ]
        ];

        $rules = $scenarios[$scenario] ?? $scenarios[null];
        return $rules;
    }

    public function labels()
    {
        return [
            'barang_id' => 'Barang',
            'jumlah' => 'Jumlah',
            'user_id' => 'User',
        ];
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }

    public function getTotalAttribute()
    {
        return $this->harga * $this->jumlah;
    }
    public function getTotalKeranjangAttribute()
    {
        return $this->barang->harga * $this->jumlah;
    }
}
