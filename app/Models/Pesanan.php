<?php

namespace App\Models;

use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory, Validatable;

    protected $fillable = [
        'kode',
        'user_id',
        'tanggal_pickup',
        'status',
    ];

    public const STATUS = [
        'co' => 'Check Out',
        'pickup' => 'Pickup',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function pesanan_detail()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id', 'id');
    }

    public function getItemCount()
    {
        return $this->pesanan_detail()->sum('jumlah');
    }

    public function getStatusValAttribute()
    {
        return self::STATUS[$this->status];
    }

    public function getTotalAttribute()
    {
        return $this->pesanan_detail->sum('total');
    }
}
