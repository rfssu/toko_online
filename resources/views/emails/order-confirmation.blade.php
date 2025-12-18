<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Toko Online Khas Jogja</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
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

        .order-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .order-info p {
            margin: 8px 0;
            font-size: 14px;
        }

        .order-info strong {
            color: #92400e;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-size: 14px;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background-color: #fef3c7;
            font-weight: bold;
            color: #92400e;
        }

        .pickup-box {
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .pickup-box h3 {
            margin-top: 0;
            color: #047857;
            font-size: 18px;
        }

        .pickup-box p {
            margin: 8px 0;
            font-size: 14px;
        }

        .contact-info {
            background-color: #f9fafb;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
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
            <h1>✅ Pesanan Berhasil!</h1>
        </div>

        {{-- Body --}}
        <div class="email-body">
            <p>Halo <strong>{{ $pesanan->user->name }}</strong>,</p>

            <p>
                Terima kasih atas pesanan Anda! Pembayaran telah berhasil kami terima dan pesanan Anda sedang disiapkan.
            </p>

            {{-- Order Info --}}
            <div class="order-info">
                <p><strong>Nomor Pesanan:</strong> #{{ $pesanan->kode }}</p>
                <p><strong>Tanggal Pesanan:</strong> {{ $pesanan->created_at->format('d M Y, H:i') }} WIB</p>
                <p><strong>Status:</strong> {{ $pesanan->status_val }}</p>
                <p><strong>Metode Pembayaran:</strong>
                    {{ $pesanan->payment_type ? ucfirst(str_replace('_', ' ', $pesanan->payment_type)) : 'Midtrans' }}
                </p>
            </div>

            <h3>Detail Pesanan</h3>

            {{-- Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Harga</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan->pesanan_detail as $detail)
                        <tr>
                            <td>{{ $detail->barang->nama_barang }}</td>
                            <td style="text-align: center;">{{ $detail->jumlah }}</td>
                            <td style="text-align: right;">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td style="text-align: right;">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total Pembayaran</td>
                        <td style="text-align: right;">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Pickup Instructions --}}
            <div class="pickup-box">
                <h3>📍 Instruksi Pengambilan</h3>
                <p><strong>Alamat Toko:</strong><br>
                    Jl. Malioboro No. 123, Yogyakarta 55271</p>

                <p><strong>Jam Operasional:</strong><br>
                    Senin - Sabtu: 09:00 - 17:00 WIB<br>
                    Minggu & Hari Libur: Tutup</p>

                <p><strong>Yang Perlu Dibawa:</strong><br>
                    • Nomor pesanan: <strong>#{{ $pesanan->kode }}</strong><br>
                    • KTP/identitas diri</p>

                <p style="color: #ea580c; font-weight: bold; margin-top: 15px;">
                    ⏰ Anda akan menerima email lagi ketika pesanan sudah siap untuk diambil!
                </p>
            </div>

            <div class="divider"></div>

            {{-- Contact Info --}}
            <div class="contact-info">
                <p><strong>Butuh Bantuan?</strong></p>
                <p>📞 WhatsApp: 0812-3456-7890</p>
                <p>📧 Email: support@tokoonlinejogja.com</p>
            </div>

            <p style="margin-top: 30px;">
                Terima kasih telah berbelanja di <strong>Toko Online Khas Jogja</strong>!
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