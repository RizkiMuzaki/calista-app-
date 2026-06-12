<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi Calista Mobile</title>
    <style>
        body { font-family: 'Nunito', 'Segoe UI', Geneva, Verdana, sans-serif; background-color: #F8F9FA; margin: 0; padding: 20px; color: #1E1B4B; }
        .container { max-width: 500px; background-color: #FFFFFF; border-radius: 24px; padding: 40px; margin: 0 auto; border: 2px solid #E2E8F0; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); }
        .logo { font-size: 28px; font-weight: 900; color: #EC4899; text-align: center; margin-bottom: 24px; letter-spacing: 1px; }
        .title { font-size: 22px; font-weight: 800; text-align: center; color: #0F172A; margin-bottom: 12px; }
        .text { font-size: 15px; color: #475569; text-align: center; line-height: 1.6; margin-bottom: 30px; }
        .otp-box { background: linear-gradient(135deg, #A78BFA 0%, #7C3AED 100%); padding: 18px; border-radius: 18px; text-align: center; font-size: 32px; font-weight: 900; letter-spacing: 6px; color: #FFFFFF; width: 200px; margin: 0 auto 30px auto; }
        .footer { font-size: 12px; color: #94A3B8; text-align: center; margin-top: 40px; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">CALISTA</div>
        <div class="title">Atur Ulang Kata Sandi</div>
        <div class="text">
            Halo Ayah & Bunda!<br>
            Kami menerima permintaan untuk mengatur ulang kata sandi akun Calista Mobile Anda. Silakan masukkan kode OTP di bawah ini untuk melanjutkan perubahan sandi:
        </div>
        <div class="otp-box">{{ $otp }}</div>
        <div class="text" style="font-size: 13px; color: #94A3B8; margin-bottom: 0;">
            Kode OTP ini hanya berlaku selama 10 menit. Jika Anda tidak meminta pengaturan ulang kata sandi ini, abaikan email ini.
        </div>
        <div class="footer">
            Calista Mobile &copy; 2026<br>
            Aplikasi Belajar Interaktif Anak Masa Kini.
        </div>
    </div>
</body>
</html>
