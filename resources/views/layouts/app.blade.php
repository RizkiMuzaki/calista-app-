<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Petualangan Belajar Seru')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Fredoka', 'Comic Neue', cursive, sans-serif;
            background: linear-gradient(135deg, #FFF5E1 0%, #FFE8CC 50%, #FFDAB9 100%);
            min-height: 100vh;
            color: #333;
            position: relative;
            overflow-x: hidden;
        }

        /* Dekorasi Background */
        .bg-decoration {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            background: white;
            border-radius: 100px;
            opacity: 0.6;
        }

        .cloud:before, .cloud:after {
            content: '';
            position: absolute;
            background: white;
            border-radius: 100px;
        }

        .cloud1 {
            width: 100px;
            height: 40px;
            top: 10%;
            left: -120px;
        }

        .cloud1:before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 10px;
        }

        .cloud1:after {
            width: 60px;
            height: 40px;
            top: -15px;
            right: 10px;
        }

        .cloud2 {
            width: 120px;
            height: 50px;
            top: 25%;
            left: -150px;
            animation-delay: 10s;
            animation-duration: 35s;
        }

        .cloud2:before {
            width: 60px;
            height: 60px;
            top: -30px;
            left: 15px;
        }

        .cloud2:after {
            width: 70px;
            height: 50px;
            top: -20px;
            right: 15px;
        }

        .cloud3 {
            width: 90px;
            height: 35px;
            top: 60%;
            left: -110px;
            animation-delay: 20s;
            animation-duration: 40s;
        }

        .cloud3:before {
            width: 45px;
            height: 45px;
            top: -20px;
            left: 10px;
        }

        .cloud3:after {
            width: 55px;
            height: 35px;
            top: -12px;
            right: 10px;
        }

        @keyframes float-cloud {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(100vw + 200px)); }
        }

        .star {
            position: absolute;
            font-size: 20px;
            animation: twinkle 2s infinite alternate;
        }

        .star1 { top: 15%; left: 10%; animation-delay: 0s; }
        .star2 { top: 30%; right: 15%; animation-delay: 0.5s; }
        .star3 { top: 70%; left: 20%; animation-delay: 1s; }
        .star4 { top: 50%; right: 25%; animation-delay: 1.5s; }

        @keyframes twinkle {
            0% { opacity: 0.3; transform: scale(1) rotate(0deg); }
            100% { opacity: 1; transform: scale(1.2) rotate(180deg); }
        }

        /* Navigation */
        .navbar {
            background: white;
            padding: 15px 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-img {
            width: 200px;
            height: 60px;
            object-fit: contain;
            animation: bounce 2s infinite;
        }

       

        .nav-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-permainan {
            background: linear-gradient(135deg, #FFB347 0%, #FFCC33 100%);
            color: #663300;
        }

        .btn-permainan:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(255, 179, 71, 0.4);
        }

        .btn-profile {
            background: linear-gradient(135deg, #87CEEB 0%, #4FC3F7 100%);
            color: white;
        }

        .btn-profile:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
        }

        .btn-logout {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
            color: white;
            border: none;
        }

        .btn-logout:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
        }

        .nav-btn i {
            font-size: 1.3rem;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #FF6B9D 0%, #C06C84 100%);
            color: white;
            padding: 40px 20px 20px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-text {
            font-size: 1.1rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .footer-icons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
        }

        .footer-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .footer-icon:hover {
            background: white;
            color: #FF6B9D;
            transform: scale(1.2) rotate(10deg);
        }

        .copyright {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            padding: 20px 30px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            z-index: 2000;
            animation: slideInRight 0.5s ease-out;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            max-width: 350px;
            font-size: 1.1rem;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .notification.success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        }

        .notification.error {
            background: linear-gradient(135deg, #ff8787 0%, #ff6b6b 100%);
        }

        .notification.info {
            background: linear-gradient(135deg, #4dabf7 0%, #339af0 100%);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .navbar {
                padding: 15px 25px;
            }

            .nav-container {
                max-width: 100%;
            }

            .nav-menu {
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 15px;
            }

            .nav-container {
                flex-wrap: wrap;
                gap: 12px;
            }

            .logo-container {
                gap: 10px;
            }

            .logo-img {
                width: 140px;
                height: 45px;
            }

            .nav-menu {
                flex-direction: row;
                gap: 8px;
                width: 100%;
            }

            .nav-btn {
                flex: 1;
                min-width: 100px;
                padding: 12px 15px;
                font-size: 1rem;
                gap: 8px;
            }

            .nav-btn span {
                display: inline;
            }

            .main-content {
                padding: 25px 15px;
            }

            .footer {
                padding: 30px 15px 15px;
            }

            .footer-text {
                font-size: 1rem;
                margin-bottom: 12px;
            }

            .footer-icons {
                gap: 15px;
                margin: 15px 0;
            }

            .footer-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .copyright {
                font-size: 0.85rem;
                margin-top: 15px;
                padding-top: 15px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 10px 12px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .nav-container {
                flex-direction: column;
                gap: 10px;
            }

            .logo-container {
                justify-content: center;
                gap: 8px;
            }

            .logo-img {
                width: 120px;
                height: 40px;
            }

            .nav-menu {
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }

            .nav-btn {
                width: 100%;
                padding: 12px 16px;
                font-size: 0.95rem;
                gap: 8px;
                border-radius: 40px;
                justify-content: center;
            }

            .nav-btn i {
                font-size: 1.2rem;
            }

            .nav-btn span {
                display: inline;
            }

            .main-content {
                min-height: calc(100vh - 180px);
                padding: 20px 12px;
            }

            .footer {
                padding: 25px 12px 12px;
            }

            .footer-content {
                padding: 0 5px;
            }

            .footer-text {
                font-size: 0.95rem;
                margin-bottom: 10px;
            }

            .footer-icons {
                gap: 12px;
                margin: 12px 0;
            }

            .footer-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .copyright {
                font-size: 0.8rem;
                margin-top: 12px;
                padding-top: 12px;
            }

            .notification {
                top: 80px;
                right: 10px;
                left: 10px;
                max-width: none;
                padding: 15px 20px;
                font-size: 1rem;
                border-radius: 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Background Decoration -->
    <div class="bg-decoration">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
        <div class="star star1">⭐</div>
        <div class="star star2">✨</div>
        <div class="star star3">🌟</div>
        <div class="star star4">💫</div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo-container">
<img src="{{ asset('storage/logo/logo.png') }}" alt="Logo Petualangan Belajar" class="logo-img">
            </a>
            
            <div class="nav-menu">
                @auth
                    <a href="{{ route('anak.profile') }}" class="nav-btn btn-profile">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-btn btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('halamanawal') }}" class="nav-btn btn-permainan">
                        <i class="fas fa-play-circle"></i>
                        <span>Mulai!</span>
                    </a>
                    <a href="{{ route('login') }}" class="nav-btn btn-profile">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">✨ Petualangan Belajar - Tempat Belajar Paling Seru! ✨</p>
            
            <div class="footer-icons">
                <a href="https://www.instagram.com/calista.indo?igsh=M2JxMnIzZ2I4c3Y%3D&utm_source=qr" target="_blank" rel="noopener noreferrer" class="footer-icon" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com/profile.php?id=61584633572687" target="_blank" rel="noopener noreferrer" class="footer-icon" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://wa.me/6285142176762" target="_blank" rel="noopener noreferrer" class="footer-icon" title="WhatsApp Business">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
            
            <div class="copyright">
                © 2024 Petualangan Belajar. Dibuat dengan 💖 untuk anak-anak Indonesia
            </div>
        </div>
    </footer>

    <!-- Notifications -->
    @if(session('success'))
        <div class="notification success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const notif = document.querySelector('.notification');
                if(notif) {
                    notif.style.opacity = '0';
                    notif.style.transform = 'translateX(100%)';
                    setTimeout(() => notif.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    @if(session('error'))
        <div class="notification error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        <script>
            setTimeout(() => {
                const notif = document.querySelector('.notification');
                if(notif) {
                    notif.style.opacity = '0';
                    notif.style.transform = 'translateX(100%)';
                    setTimeout(() => notif.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    @if(session('info'))
        <div class="notification info">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
        <script>
            setTimeout(() => {
                const notif = document.querySelector('.notification');
                if(notif) {
                    notif.style.opacity = '0';
                    notif.style.transform = 'translateX(100%)';
                    setTimeout(() => notif.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    @stack('scripts')
</body>
</html>