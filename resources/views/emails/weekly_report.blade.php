<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perkembangan Belajar Calista Plus</title>
    <style>
        body { font-family: 'Nunito', 'Segoe UI', Geneva, Verdana, sans-serif; background-color: #FFF5F7; margin: 0; padding: 20px; color: #1E1B4B; }
        .container { max-width: 600px; background-color: #FFFFFF; border-radius: 28px; padding: 35px; margin: 0 auto; border: 2px solid #FCE7F3; box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.05); }
        .logo-container { text-align: center; margin-bottom: 20px; }
        .logo-img { max-height: 45px; width: auto; }
        .header-tag { text-align: center; font-size: 11px; font-weight: 900; color: #DB2777; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; }
        .title { font-size: 24px; font-weight: 900; text-align: center; color: #0F172A; margin-bottom: 24px; }
        
        /* Card Styles */
        .card { background-color: #FFFFFF; border: 1.5px solid #F1F5F9; border-radius: 20px; padding: 20px; margin-bottom: 24px; }
        .card-purple { border-color: #E9D5FF; background-color: #FAF5FF; }
        .card-title { font-size: 16px; font-weight: 800; color: #1E1B4B; margin-bottom: 14px; display: flex; align-items: center; }
        
        /* Stats Table */
        .stats-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .stats-table td { padding: 10px; border-bottom: 1px solid #F1F5F9; font-size: 14px; }
        .stats-table tr:last-child td { border-bottom: none; }
        .stats-label { color: #64748B; font-weight: 700; }
        .stats-val { text-align: right; color: #0F172A; font-weight: 800; }
        
        /* Style Metric Bars */
        .metric-row { margin-bottom: 14px; }
        .metric-header { display: flex; justify-content: space-between; font-size: 13px; font-weight: 800; margin-bottom: 6px; }
        .metric-name { color: #1E1B4B; }
        .metric-percentage { color: #7C3AED; }
        .metric-bar-bg { height: 10px; background-color: #F1F5F9; border-radius: 999px; overflow: hidden; }
        .metric-bar-fill { height: 100%; border-radius: 999px; }
        
        /* AI Insight Banner */
        .ai-banner { background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 100%); border: 1.5px solid #E9D5FF; border-radius: 20px; padding: 22px; margin-bottom: 24px; }
        .ai-title { font-size: 15px; font-weight: 800; color: #6B21A8; margin-bottom: 8px; display: flex; align-items: center; }
        .ai-text { font-size: 14px; color: #581C87; line-height: 1.6; font-style: italic; }
        
        /* CTA Button */
        .btn-container { text-align: center; margin: 30px 0; }
        .btn-cta { display: inline-block; background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%); color: #FFFFFF !important; font-weight: 900; text-decoration: none; padding: 14px 35px; border-radius: 20px; font-size: 15px; box-shadow: 0 4px 14px rgba(236, 72, 153, 0.3); }
        
        /* Reminder Box */
        .reminder-box { text-align: center; padding: 20px; background-color: #FFFBEB; border: 1.5px dashed #FDE68A; border-radius: 20px; margin-bottom: 24px; }
        .reminder-title { font-size: 16px; font-weight: 800; color: #92400E; margin-bottom: 8px; }
        .reminder-text { font-size: 14px; color: #B45309; line-height: 1.5; }

        .footer { font-size: 12px; color: #94A3B8; text-align: center; margin-top: 30px; line-height: 1.6; border-top: 1px solid #F1F5F9; padding-top: 20px; }
        .footer a { color: #64748B; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <!-- Gunakan fallback teks jika asset logo tidak ter-load -->
            <strong style="color: #EC4899; font-size: 24px; font-weight: 900; letter-spacing: 1px;">CALISTA</strong>
        </div>
        
        <div class="header-tag">Laporan Belajar {{ $periodLabel ?? 'Mingguan' }}</div>
        <div class="title">Rangkuman Belajar {{ $anak->nama_anak }}</div>

        @if($hasSessions)
            <!-- Kartu Ringkasan Statistik -->
            <div class="card">
                <div class="card-title" style="color: #EC4899;">📊 Statistik {{ $statLabel ?? 'Minggu Ini' }}</div>
                <table class="stats-table">
                    <tr>
                        <td class="stats-label">Sesi Bermain</td>
                        <td class="stats-val">{{ $totalSessions }} sesi</td>
                    </tr>
                    <tr>
                        <td class="stats-label">Total Waktu Belajar</td>
                        <td class="stats-val">{{ $totalPlayMinutes }} menit</td>
                    </tr>
                    <tr>
                        <td class="stats-label">Rata-rata Skor Game</td>
                        <td class="stats-val">{{ $avgScore }} / 100</td>
                    </tr>
                    <tr>
                        <td class="stats-label">Bintang Diperoleh</td>
                        <td class="stats-val">⭐ {{ $earnedStars }} bintang</td>
                    </tr>
                </table>
            </div>

            <!-- Kartu Gaya Belajar VAK -->
            @if($vak['has_data'])
                <div class="card card-purple">
                    <div class="card-title" style="color: #7C3AED;">🎯 Karakter & Gaya Belajar (VAK)</div>
                    <div style="font-size: 13px; color: #6B21A8; font-weight: 700; margin-bottom: 15px;">
                        Gaya Belajar Dominan: <strong style="text-transform: uppercase; color: #7C3AED;">{{ $vak['dominant'] }}</strong>
                    </div>

                    <!-- Visual -->
                    <div class="metric-row">
                        <div class="metric-header">
                            <span class="metric-name">👀 Visual (Melihat)</span>
                            <span class="metric-percentage" style="color: #EC4899;">{{ $vak['visual'] }}%</span>
                        </div>
                        <div class="metric-bar-bg">
                            <div class="metric-bar-fill" style="width: {{ $vak['visual'] }}%; background-color: #EC4899;"></div>
                        </div>
                    </div>

                    <!-- Auditori -->
                    <div class="metric-row">
                        <div class="metric-header">
                            <span class="metric-name">👂 Auditori (Mendengar)</span>
                            <span class="metric-percentage" style="color: #D97706;">{{ $vak['auditory'] }}%</span>
                        </div>
                        <div class="metric-bar-bg">
                            <div class="metric-bar-fill" style="width: {{ $vak['auditory'] }}%; background-color: #D97706;"></div>
                        </div>
                    </div>

                    <!-- Kinestetik -->
                    <div class="metric-row">
                        <div class="metric-header">
                            <span class="metric-name">🖐️ Gerak (Kinestetik)</span>
                            <span class="metric-percentage" style="color: #0D9488;">{{ $vak['kinesthetic'] }}%</span>
                        </div>
                        <div class="metric-bar-bg">
                            <div class="metric-bar-fill" style="width: {{ $vak['kinesthetic'] }}%; background-color: #0D9488;"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kartu Insight AI ( Whispers & Groq ) -->
            @if($anak->cita_cita || $anak->hobi || $anak->makanan_favorit)
                <div class="ai-banner">
                    <div class="ai-title">✨ Catatan Harapan Nusa (AI Insight)</div>
                    <div class="ai-text">
                        “Tahukah Bunda? Saat mengobrol dengan Nusa {{ ($period ?? 'week') === 'month' ? 'bulan ini' : 'minggu ini' }}, 
                        <strong>{{ $anak->nama_anak }}</strong> bercerita bahwa ia 
                        @if($anak->cita_cita) ingin sekali menjadi <strong>{{ $anak->cita_cita }}</strong>@endif
                        @if($anak->hobi) dan sangat suka meluangkan waktu untuk <strong>{{ $anak->hobi }}</strong>@endif.
                        @if($anak->makanan_favorit) Selain itu, makanan kesukaan yang paling membuatnya gembira adalah <strong>{{ $anak->makanan_favorit }}</strong>! 🥣@endif”
                    </div>
                </div>
            @endif

            <!-- Tips & Rekomendasi Belajar -->
            @if(count($recommendations) > 0)
                <div class="card" style="border-color: #E2E8F0;">
                    <div class="card-title" style="color: #475569;">💡 Tips Belajar & Rekomendasi</div>
                    <ul style="padding-left: 20px; margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">
                        @foreach($recommendations as $rec)
                            <li style="margin-bottom: 8px;">{{ $rec }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        @else
            <!-- Kasus: Belum Ada Aktivitas Belajar -->
            <div class="reminder-box">
                <div class="reminder-title">Yuk, Mulai Bermain Lagi! 🌟</div>
                <div class="reminder-text">
                    {{ ($period ?? 'week') === 'month' ? 'Bulan ini' : 'Minggu ini' }} <strong>{{ $anak->nama_anak }}</strong> belum sempat melakukan aktivitas belajar atau bermain game edukasi di Calista Mobile. 
                    Mari ajak si kecil meluangkan waktu 5-10 menit hari ini bersama Nusa untuk menjaga konsistensi belajarnya!
                </div>
            </div>
        @endif

        <div class="btn-container">
            <a href="https://calistaforkids.com" class="btn-cta" target="_blank">Masuk Area Orang Tua</a>
        </div>

        <div class="footer">
            Email ini dikirim secara otomatis oleh Calista Mobile.<br>
            Butuh bantuan atau pertanyaan? Hubungi kami di <a href="mailto:calista.eduapp@gmail.com">calista.eduapp@gmail.com</a>.<br>
            Calista Mobile &copy; 2026.
        </div>
    </div>
</body>
</html>
