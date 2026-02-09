<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Premium - Calista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #6A11CB 0%, #2575FC 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background elements untuk efek anak-anak */
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .bubble-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 5%;
            animation: float 6s ease-in-out infinite;
        }

        .bubble-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 5%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .bubble-3 {
            width: 80px;
            height: 80px;
            bottom: 10%;
            left: 15%;
            animation: float 7s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 20px;
            backdrop-filter: blur(5px);
            font-size: 14px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
        }

        .premium-header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            position: relative;
            padding: 20px;
        }

        .crown-container {
            position: relative;
            margin: 0 auto 20px;
            width: 120px;
            height: 120px;
        }

        .crown-icon {
            font-size: 4rem;
            color: #FFD700;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
            animation: shine 2s ease-in-out infinite;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .sparkle {
            position: absolute;
            color: #FFD700;
            font-size: 1.2rem;
            animation: sparkle 1.5s ease-in-out infinite;
        }

        .sparkle-1 { top: 10px; left: 25px; animation-delay: 0s; }
        .sparkle-2 { top: 30px; right: 20px; animation-delay: 0.3s; }
        .sparkle-3 { bottom: 20px; left: 40px; animation-delay: 0.6s; }

        @keyframes shine {
            0%, 100% { opacity: 1; transform: translateX(-50%) scale(1); }
            50% { opacity: 0.8; transform: translateX(-50%) scale(1.1); }
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }

        .premium-header h1 {
            font-family: 'Comic Neue', cursive;
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 3px 3px 0 #FF6B6B;
        }

        .premium-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8));
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 3px solid #FFD700;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: "🌟";
            position: absolute;
            font-size: 2rem;
            top: 10px;
            left: 10px;
            opacity: 0.3;
        }

        .info-box::after {
            content: "✨";
            position: absolute;
            font-size: 1.5rem;
            bottom: 10px;
            right: 10px;
            opacity: 0.3;
        }

        .info-box h3 {
            margin-bottom: 10px;
            color: #2575FC;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Comic Neue', cursive;
        }

        .info-box p {
            color: #333;
            line-height: 1.6;
        }

        .status-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: bold;
            margin-bottom: 20px;
            font-family: 'Comic Neue', cursive;
            font-size: 1.1rem;
        }

        .badge-premium {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #333;
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3);
        }

        .badge-free {
            background: linear-gradient(135deg, #7B68EE, #9370DB);
            color: white;
            box-shadow: 0 5px 15px rgba(123, 104, 238, 0.3);
        }

        .active-subscription {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(255, 165, 0, 0.1));
            border: 3px dashed #FFA500;
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
        }

        .subscription-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .subscription-detail {
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .subscription-label {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .subscription-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #2575FC;
        }

        .days-remaining {
            font-size: 1.3rem;
            font-weight: 900;
            color: #FF6B6B;
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 15px;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.2);
        }

        .section-title {
            color: white;
            margin-bottom: 20px;
            text-align: center;
            font-family: 'Comic Neue', cursive;
            font-size: 2rem;
            text-shadow: 2px 2px 0 #FF6B6B;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: #FFD700;
            border-radius: 2px;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .plan-card {
            background: white;
            border-radius: 25px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            border: 3px solid transparent;
        }

        .plan-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1);
        }

        .plan-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .plan-card.active {
            border-color: #FFD700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
        }

        .plan-card.active::before {
            background: linear-gradient(90deg, #FFD700, #FFA500);
        }

        .plan-name {
            font-family: 'Comic Neue', cursive;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .plan-name::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background: #4ECDC4;
            border-radius: 1.5px;
        }

        .plan-duration {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .plan-price {
            font-size: 2.8rem;
            font-weight: 900;
            color: #2575FC;
            margin-bottom: 5px;
            position: relative;
        }

        .plan-price::before {
            content: "Rp";
            font-size: 1rem;
            position: absolute;
            top: 5px;
            left: -20px;
            color: #666;
        }

        .plan-price-period {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .plan-features {
            text-align: left;
            margin-bottom: 25px;
            list-style: none;
        }

        .plan-features li {
            padding: 10px 0;
            color: #555;
            border-bottom: 1px dashed #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .plan-features li:last-child {
            border-bottom: none;
        }

        .plan-features i {
            color: #FF6B6B;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .plan-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 15px;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Comic Neue', cursive;
            position: relative;
            overflow: hidden;
        }

        .plan-btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .plan-btn:hover::after {
            left: 100%;
        }

        .plan-btn.subscribe {
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            box-shadow: 0 8px 0 rgba(255, 107, 107, 0.3);
        }

        .plan-btn.subscribe:hover {
            transform: translateY(-3px);
            box-shadow: 0 11px 0 rgba(255, 107, 107, 0.3);
        }

        .plan-btn.current {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #333;
            cursor: default;
            box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3);
        }

        .payment-info {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8));
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            margin-top: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 3px solid #4ECDC4;
        }

        .payment-info h3 {
            color: #2575FC;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Comic Neue', cursive;
        }

        .security-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .security-icons i {
            font-size: 2rem;
            color: #4ECDC4;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .premium-header h1 {
                font-size: 2rem;
            }

            .crown-container {
                width: 100px;
                height: 100px;
            }

            .crown-icon {
                font-size: 3rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .plans-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .plan-price {
                font-size: 2.2rem;
            }

            .subscription-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .plan-card {
                padding: 20px;
            }

            .plan-btn {
                padding: 14px;
                font-size: 1rem;
            }

            .bubble {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .premium-header h1 {
                font-size: 1.8rem;
            }

            .plan-price {
                font-size: 1.8rem;
            }

            .plan-name {
                font-size: 1.5rem;
            }

            .back-btn {
                padding: 10px 20px;
                font-size: 13px;
            }

            .status-badge {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }

        /* Animasi tambahan */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .bounce {
            animation: bounce 2s infinite;
        }

        /* Scrollbar custom */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <div class="bubble bubble-3"></div>

    <div class="container">
        <a href="{{ route('calista.index') }}" class="back-btn bounce">
            <i class="fas fa-arrow-left"></i> Kembali ke Calista
        </a>

        <div class="premium-header">
            <div class="crown-container">
                <i class="fas fa-crown crown-icon"></i>
                <i class="fas fa-star sparkle sparkle-1"></i>
                <i class="fas fa-star sparkle sparkle-2"></i>
                <i class="fas fa-star sparkle sparkle-3"></i>
            </div>
            <h1>Naik Level ke Premium!</h1>
            <p>Buka semua petualangan belajar seru dengan akses tak terbatas!</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h3>
                <i class="fas fa-magic"></i>
                Keajaiban Premium
            </h3>
            <p>Dengan upgrade ke premium, kamu bisa membaca semua buku seru, mendengarkan cerita rakyat ajaib, dan bermain game edukasi yang menyenangkan! Belajar jadi seperti petualangan!</p>
        </div>

        <!-- Current Subscription Status -->
        <div class="status-section">
            @if($activeSubscription)
                <div class="status-badge badge-premium">
                    <i class="fas fa-trophy"></i> LEVEL PREMIUM AKTIF!
                </div>
                <div class="active-subscription">
                    <div class="subscription-grid">
                        <div class="subscription-detail">
                            <div class="subscription-label">Paket Petualangan</div>
                            <div class="subscription-value">{{ $activeSubscription->plan->nama_paket }}</div>
                        </div>
                        <div class="subscription-detail">
                            <div class="subscription-label">Mulai Petualangan</div>
                            <div class="subscription-value">{{ \Carbon\Carbon::parse($activeSubscription->tanggal_mulai)->format('d M Y') }}</div>
                        </div>
                        <div class="subscription-detail">
                            <div class="subscription-label">Sampai Tanggal</div>
                            <div class="subscription-value">{{ \Carbon\Carbon::parse($activeSubscription->tanggal_berakhir)->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="days-remaining">
                        <i class="fas fa-clock"></i> Masih ada {{ $daysRemaining }} hari petualangan!
                    </div>
                </div>
            @else
                <div class="status-badge badge-free">
                    <i class="fas fa-user-astronaut"></i> PENJELAJAH GRATIS
                </div>
                <p style="color: #666; text-align: center; margin-top: 10px;">
                    Upgrade sekarang untuk membuka semua petualangan belajar!
                </p>
            @endif
        </div>

        <!-- Plans Grid -->
        <h2 class="section-title">Pilih Peta Petualanganmu</h2>

        <div class="plans-grid">
            @foreach($plans as $plan)
                <div class="plan-card {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? 'active' : '' }}">
                    @if($activeSubscription && $activeSubscription->plan_id === $plan->id)
                        <div class="status-badge badge-premium" style="width: 100%; display: block; margin-bottom: 15px;">
                            <i class="fas fa-check-circle"></i> Peta Aktif
                        </div>
                    @endif

                    <h3 class="plan-name">{{ $plan->nama_paket }}</h3>
                    <p class="plan-duration">
                        <i class="fas fa-hourglass-half"></i>
                        Eksplorasi {{ $plan->durasi_bulan }} Bulan
                    </p>

                    <div class="plan-price">
                        {{ number_format($plan->harga_jual, 0, ',', '.') }}
                    </div>
                    <p class="plan-price-period">Untuk {{ $plan->durasi_bulan }} bulan petualangan</p>

                    <ul class="plan-features">
                        <li>
                            <i class="fas fa-book-open"></i>
                            <span>Buku Membaca Tanpa Batas</span>
                        </li>
                        <li>
                            <i class="fas fa-dragon"></i>
                            <span>Cerita Rakyat Ajaib dengan AI</span>
                        </li>
                        <li>
                            <i class="fas fa-puzzle-piece"></i>
                            <span>Semua Game Edukasi Seru</span>
                        </li>
                        <li>
                            <i class="fas fa-chart-line"></i>
                            <span>Peta Kemajuan Belajar</span>
                        </li>
                        <li>
                            <i class="fas fa-robot"></i>
                            <span>AI Assistant Pribadi</span>
                        </li>
                    </ul>

                    @if($activeSubscription && $activeSubscription->plan_id === $plan->id)
                        <button class="plan-btn current" disabled>
                            <i class="fas fa-flag-checkered"></i> Sedang Berpetualang!
                        </button>
                    @else
                        <button class="plan-btn subscribe" onclick="upgradePlan({{ $plan->id }})">
                            <i class="fas fa-rocket"></i> Mulai Petualangan!
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <h3>
                <i class="fas fa-shield-alt"></i>
                Gerbang Pembayaran Aman
            </h3>
            <p>Pintu gerbang pembayaran kami dilindungi oleh perisai keamanan terkuat untuk melindungi petualanganmu!</p>
            <div class="security-icons">
                <i class="fas fa-lock"></i>
                <i class="fas fa-shield-alt"></i>
                <i class="fas fa-key"></i>
            </div>
        </div>
    </div>

    <script>
        function upgradePlan(planId) {
            if (confirm('Yakin ingin memulai petualangan baru dengan peta ini?')) {
                fetch('{{ route("premium.upgrade") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ plan_id: planId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Langsung redirect ke halaman payment
                        window.location.href = data.redirect;
                    } else {
                        alert('Gagal memulai petualangan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ada masalah di gerbang portal. Coba lagi ya!');
                });
            }
        }

        // Tambahkan efek interaktif untuk mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Efek sentuhan untuk tombol
            const buttons = document.querySelectorAll('.plan-btn');
            buttons.forEach(button => {
                button.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                button.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
            
            // Efek scroll halus untuk mobile
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if(this.getAttribute('href') !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if(target) {
                            window.scrollTo({
                                top: target.offsetTop - 20,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>