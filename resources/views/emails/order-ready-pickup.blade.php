<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Siap Diambil - Toko Online Khas Jogja</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .email-body {
            padding: 40px 30px;
            color: #374151;
            line-height: 1.6;
        }

        .alert-success {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-success p {
            margin: 8px 0;
            font-size: 16px;
            color: #065f46;
        }

        .order-info {
            background-color: #f9fafb;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }

        .order-info p {
            margin: 8px 0;
            font-size: 14px;
        }

        .pickup-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
        }

        .pickup-box h3 {
            margin-top: 0;
            color: #92400e;
            font-size: 20px;
        }

        .pickup-box p {
            margin: 12px 0;
            font-size: 15px;
            line-height: 1.8;
        }

        .pickup-box strong {
            color: #92400e;
        }

        .map-link {
            display: inline-block;
            background-color: #f59e0b;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }

        .map-link:hover {
            background-color: #ea580c;
        }

        .contact-info {
            background-color: #ecfdf5;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border: 1px solid #10b981;
        }

        .contact-info p {
            margin: 8px 0;
            font-size: 14px;
        }

        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        {{-- Header --}}
        <div class="email-header">
            <h1>🎉 Pesanan Siap Diambil!</h1>
        </div>

        {{-- Body --}}
        <div class="email-body">
            <p>Halo <strong>{{ $pesanan->user->name }}</strong>,</p>

            {{-- Success Alert --}}
            <div class="alert-success">
                <p style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                    ✅ Kabar Baik! Pesanan Anda sudah siap!
                </p>
                <p>
                    Pesanan <strong>#{{ $pesanan->kode }}</strong> telah disiapkan dan siap untuk diambil di toko kami.
                </p>
            </div>

            {{-- Order Info --}}
            <div class="order-info">
                <p><strong>Nomor Pesanan:</strong> #{{ $pesanan->kode }}</p>
                <p><strong>Total Item:</strong> {{ $pesanan->pesanan_detail->count() }} produk</p>
                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
            </div>

            {{-- Pickup Instructions --}}
            <div class="pickup-box">
                <h3>📍 Silahkan Ambil Pesanan Anda</h3>

                <p><strong>Alamat Toko:</strong><br>
                    Jl. Malioboro No. 123, Yogyakarta 55271</p>

                <p><strong>Jam Operasional:</strong><br>
                    🕐 Senin - Sabtu: 09:00 - 17:00 WIB<br>
                    🚫 Minggu & Hari Libur: Tutup</p>

                <p><strong>Yang Perlu Dibawa:</strong><br>
                    📱 Nomor pesanan: <strong>#{{ $pesanan->kode }}</strong><br>
                    🆔 KTP atau identitas diri</p>

                <p
                    style="color: #ea580c; font-weight: bold; margin-top: 20px; padding: 15px; background-color: white; border-radius: 6px;">
                    ⚠️ Harap ambil pesanan dalam waktu 3 hari. Setelah lewat dari batas waktu, pesanan akan dibatalkan.
                </p>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="https://goo.gl/maps/example" class="map-link">
                        📍 Lihat Lokasi di Maps
                    </a>
                </div>
            </div>

            {{-- View QR Code Notice --}}
            <div
                style="text-align: center; margin: 30px 0; padding: 20px; background-color: #d1fae5; border-radius: 8px;">
                <h3 style="color: #065f46; margin-top: 0;">🎫 QR Code Pickup</h3>
                <p style="font-size: 14px; color: #666; margin-bottom: 10px;">
                    QR code tersedia di halaman riwayat pesanan Anda
                </p>
                <p style="font-size: 14px; margin-top: 15px;">
                    <a href="{{ url('/history/' . $pesanan->id) }}"
                        style="display: inline-block; background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                        Lihat QR Code
                    </a>
                </p>
                <p style="font-size: 12px; color: #666; margin-top: 10px;">
                    Tunjukkan QR code saat pickup untuk proses lebih cepat
                </p>
            </div>

            <div class="divider"></div>

            {{-- Contact Info --}}
            <div class="contact-info">
                <p><strong>💬 Ada Pertanyaan?</strong></p>
                <p>Hubungi kami jika ada yang ingin ditanyakan:</p>
                <p>📞 WhatsApp: <strong>0812-3456-7890</strong></p>
                <p>📧 Email: <strong>support@tokoonlinejogja.com</strong></p>
            </div>

            <p style="margin-top: 30px; font-size: 16px;">
                Terima kasih telah berbelanja di <strong>Toko Online Khas Jogja</strong>!<br>
                Kami tunggu kedatangan Anda 😊
            </p>
        </div>

        {{-- Footer --}}
        <div class="email-footer">
            <p>
                Email ini dikirim otomatis, mohon tidak membalas email ini.
            </p>
            <p style="margin-top: 10px;">
                &copy; {{ date('Y') }} Toko Online Khas Jogja. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>