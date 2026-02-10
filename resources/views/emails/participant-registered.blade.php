<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f7; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); overflow: hidden; }
        .header { background: #1a1a2e; color: #fff; padding: 24px 32px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #333; line-height: 1.7; }
        .password-box { background: #f0f4ff; border: 2px dashed #4f46e5; border-radius: 8px; padding: 16px 24px; text-align: center; margin: 20px 0; }
        .password-box .label { font-size: 13px; color: #666; margin-bottom: 4px; }
        .password-box .password { font-size: 24px; font-weight: bold; color: #4f46e5; letter-spacing: 2px; font-family: monospace; }
        .warning { background: #fff7ed; border-left: 4px solid #f59e0b; padding: 12px 16px; margin: 16px 0; font-size: 14px; color: #92400e; border-radius: 4px; }
        .footer { padding: 20px 32px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; }
        .btn { display: inline-block; background: #4f46e5; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BDI Yogyakarta</h1>
        </div>
        <div class="body">
            <p>Halo <strong>{{ $participantName }}</strong>,</p>
            <p>Pendaftaran akun peserta Anda di <strong>Balai Diklat Industri Yogyakarta</strong> telah berhasil!</p>
            
            <p>Berikut adalah password akun Anda:</p>
            
            <div class="password-box">
                <div class="label">Password Anda</div>
                <div class="password">{{ $password }}</div>
            </div>

            <div class="warning">
                ⚠️ Harap simpan password ini dengan aman. Anda disarankan untuk mengingat password ini saat login ke akun peserta Anda.
            </div>

            <p>Silakan login menggunakan email dan password di atas melalui tombol berikut:</p>

            <p style="text-align: center;">
                <a href="{{ route('participant.login') }}" class="btn">Login Sekarang</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Balai Diklat Industri Yogyakarta. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
