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
                'stok' => 'required',
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
            'stok' => 'Stok',
            'keterangan' => 'Keterangan'
        ];
    }
}
