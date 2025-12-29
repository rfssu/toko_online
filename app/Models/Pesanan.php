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
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_READY = 'ready';
    public const STATUS_CO = 'ready'; // Alias for backward compatibility
    public const STATUS_PICKUP = 'pickup';

    public const STATUS = [
        self::STATUS_PENDING => 'Menunggu Pembayaran',
        self::STATUS_PREPARING => 'Sedang Disiapkan',
        self::STATUS_READY => 'Siap Dipickup',
        'co' => 'Siap Dipickup', // Legacy support
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
        $this->status = self::STATUS_PREPARING;
        $this->payment_type = $paymentType;
        $this->save();
    }

    /**
     * Mark order as preparing (after payment)
     */
    public function markAsPreparing(): void
    {
        $this->status = self::STATUS_PREPARING;
        $this->save();
    }

    /**
     * Mark order as ready for pickup (send email notification)
     */
    public function markAsReady(): void
    {
        $this->status = self::STATUS_READY;
        $this->save();

        // Send ready for pickup email
        $this->sendReadyForPickupEmail();
    }

    /**
     * Send ready for pickup email notification
     */
    protected function sendReadyForPickupEmail(): void
    {
        try {
            \Illuminate\Support\Facades\Mail::send('emails.order-ready-pickup', [
                'pesanan' => $this->load(['pesanan_detail.barang', 'user'])
            ], function ($message) {
                $message->to($this->user->email);
                $message->subject('Pesanan Siap Dipickup! #' . $this->kode . ' - Toko Online Khas Jogja');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Ready for pickup email failed: ' . $e->getMessage());
        }
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
