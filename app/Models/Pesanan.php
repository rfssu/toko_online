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
        'snap_token',
        'payment_type',
        'pic'
    ];

    public const STATUS_PENDING = 'pending_payment';
    public const STATUS_CO = 'co';
    public const STATUS_PICKUP = 'pickup';

    public const STATUS = [
        self::STATUS_PENDING => 'Menunggu Pembayaran',
        self::STATUS_CO => 'Sudah Dibayar & Siap Dipickup',
        self::STATUS_PICKUP => 'Selesai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function toPic()
    {
        return $this->belongsTo(User::class, 'pic', 'id');
    }

    public function pesanan_detail()
    {
        return $this->hasMany(PesananDetail::class);
    }
    public function getStatusValAttribute()
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getTotalAttribute()
    {
        return $this->pesanan_detail->sum('total');
    }

    public function getItemCount()
    {
        return $this->pesanan_detail()->sum('jumlah');
    }
    public function markAsPaid(string $paymentType): void
    {
        if ($this->status === self::STATUS_CO) {
            return;
        }

        $this->update([
            'status' => self::STATUS_CO,
            'payment_type' => $paymentType,
        ]);
    }
}
