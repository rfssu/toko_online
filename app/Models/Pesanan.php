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

    protected $casts = [
        'tanggal_pickup' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
        $this->status = self::STATUS_CO;
        $this->payment_type = $paymentType;
        $this->save();
    }

    /**
     * Generate QR code for pickup verification (SVG for web)
     */
    public function getQrCodeAttribute()
    {
        // Generate encrypted data for QR code
        $data = encrypt([
            'kode' => $this->kode,
            'id' => $this->id,
            'total' => $this->total,
            'timestamp' => now()->timestamp
        ]);

        // Generate QR code as SVG
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->margin(1)
            ->generate($data);
    }

    /**
     * Generate QR code for email (SVG for compatibility - works in Gmail/Outlook)
     */
    public function getQrCodeEmailAttribute()
    {
        // Generate encrypted data for QR code
        $data = encrypt([
            'kode' => $this->kode,
            'id' => $this->id,
            'total' => $this->total,
            'timestamp' => now()->timestamp
        ]);

        // Generate QR code as SVG (no Imagick needed, works in modern email clients)
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->margin(1)
            ->generate($data);
    }
}
