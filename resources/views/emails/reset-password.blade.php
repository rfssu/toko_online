<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Toko Online Khas Jogja</title>
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

        .email-body p {
            margin: 16px 0;
            font-size: 16px;
        }

        .reset-button {
            display: inline-block;
            margin: 30px 0;
            padding: 16px 40px;
            background-color: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
        }

        .reset-button:hover {
            background-color: #ea580c;
        }

        .button-container {
            text-align: center;
        }

        .alert-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-box p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
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
            <h1>🔐 Reset Password</h1>
        </div>

        {{-- Body --}}
        <div class="email-body">
            <p>Halo,</p>

            <p>
                Kami menerima permintaan untuk mereset password akun Anda di <strong>Toko Online Khas Jogja</strong>.
            </p>

            <p>
                Klik tombol di bawah untuk membuat password baru:
            </p>

            {{-- Reset Button --}}
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Reset Password Saya
                </a>
            </div>

            {{-- Alternative Link --}}
            <p style="font-size: 14px; color: #6b7280;">
                Atau copy dan paste link berikut ke browser Anda:<br>
                <a href="{{ $resetUrl }}" style="color: #f59e0b; word-break: break-all;">{{ $resetUrl }}</a>
            </p>

            <div class="divider"></div>

            {{-- Alert Box --}}
            <div class="alert-box">
                <p>
                    ⚠️ <strong>Penting:</strong> Link ini hanya berlaku selama <strong>60 menit</strong> dan hanya dapat
                    digunakan sekali.
                </p>
            </div>

            {{-- Security Notice --}}
            <p style="font-size: 14px; color: #6b7280;">
                Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
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