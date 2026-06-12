<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Calista Mobile</title>
    <style>
        body { font-family: 'Nunito', 'Segoe UI', Geneva, Verdana, sans-serif; background-color: #FFF5F7; margin: 0; padding: 20px; color: #1E1B4B; }
        .container { max-width: 500px; background-color: #FFFFFF; border-radius: 24px; padding: 35px; margin: 0 auto; border: 2px solid #FCE7F3; box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.05); }
        .logo-container { text-align: center; margin-bottom: 24px; }
        .logo-img { max-height: 50px; width: auto; }
        .title { font-size: 22px; font-weight: 800; text-align: center; color: #0F172A; margin-bottom: 16px; }
        .text { font-size: 15px; color: #475569; text-align: center; line-height: 1.6; margin-bottom: 25px; }
        .tips-container { background-color: #FAF5FF; border: 1.5px dashed #E9D5FF; border-radius: 18px; padding: 20px; margin-bottom: 30px; }
        .tip-item { display: flex; align-items: flex-start; }
        .tip-icon { font-size: 24px; margin-right: 12px; line-height: 1.2; }
        .tip-content { text-align: left; }
        .tip-title { font-size: 14px; font-weight: 800; color: #1E1B4B; margin-bottom: 4px; }
        .tip-desc { font-size: 13px; color: #5B21B6; line-height: 1.4; }
        .btn-container { text-align: center; margin-bottom: 30px; }
        .btn-cta { display: inline-block; background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%); color: #FFFFFF !important; font-weight: 800; text-decoration: none; padding: 14px 30px; border-radius: 18px; font-size: 15px; box-shadow: 0 4px 14px rgba(236, 72, 153, 0.3); transition: transform 0.2s; }
        .footer { font-size: 12px; color: #94A3B8; text-align: center; margin-top: 30px; line-height: 1.5; border-top: 1px solid #F1F5F9; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/logo/Calista_Logo.webp') }}" alt="Calista Mobile" class="logo-img">
        </div>
        <div class="title">Selamat Datang di Calista Mobile! 🎉</div>
        <div class="text">
            Halo <strong>{{ $name }}</strong>!<br>
            Akun Anda telah aktif. Calista Mobile siap menjadi teman terbaik si kecil untuk tumbuh cerdas, ceria, dan percaya diri!
        </div>
        
        <div class="tips-container">
            <div class="tip-item">
                <div class="tip-icon">🎯</div>
                <div class="tip-content">
                    <div class="tip-title">Belajar 5-10 Menit Setiap Hari</div>
                    <div class="tip-desc">Ajak si kecil belajar berhitung, membaca dongeng, dan menulis dengan metode interaktif yang menyenangkan.</div>
                </div>
            </div>
            <div style="height: 16px;"></div>
            <div class="tip-item">
                <div class="tip-icon">⭐</div>
                <div class="tip-content">
                    <div class="tip-title">Kumpulkan Bintang & Hadiah</div>
                    <div class="tip-desc">Setiap kali si kecil berlatih, mereka akan mendapatkan bintang yang bisa ditukarkan dengan baju-baju lucu di Toko Calista.</div>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="https://calista-mobile.my.id" class="btn-cta" target="_blank">Mulai Petualangan Belajar</a>
        </div>

        <div class="footer">
            Calista Mobile &copy; 2026<br>
            Aplikasi Belajar Interaktif Anak Masa Kini.
        </div>
    </div>
</body>
</html>
