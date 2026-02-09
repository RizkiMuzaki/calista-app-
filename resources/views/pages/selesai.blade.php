<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesai - Calista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif;
            background: linear-gradient(180deg, #87CEEB 0%, #E0F6FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        /* Sun */
        .sun {
            position: absolute;
            width: 120px;
            height: 120px;
            background: #FFD700;
            border-radius: 50%;
            top: 30px;
            right: 30px;
            box-shadow: 0 0 40px rgba(255, 215, 0, 0.8);
            animation: sun-glow 3s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes sun-glow {
            0%, 100% { 
                box-shadow: 0 0 40px rgba(255, 215, 0, 0.8);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 60px rgba(255, 215, 0, 1);
                transform: scale(1.05);
            }
        }

        /* Clouds */
        .clouds-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 60%;
            z-index: 0;
            pointer-events: none;
        }

        .cloud {
            position: absolute;
            background: white;
            border-radius: 100px;
            opacity: 0.8;
            animation: float-cloud 15s infinite linear;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: white;
            border-radius: 100px;
        }

        .cloud-1 {
            width: 100px;
            height: 40px;
            top: 20%;
            left: -100px;
            animation-duration: 20s;
        }

        .cloud-1::before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 10px;
        }

        .cloud-1::after {
            width: 60px;
            height: 40px;
            top: -15px;
            right: 10px;
        }

        .cloud-2 {
            width: 120px;
            height: 45px;
            top: 35%;
            right: -120px;
            animation-duration: 25s;
            animation-delay: -5s;
        }

        .cloud-2::before {
            width: 55px;
            height: 55px;
            top: -28px;
            left: 15px;
        }

        .cloud-2::after {
            width: 70px;
            height: 45px;
            top: -18px;
            right: 15px;
        }

        .cloud-3 {
            width: 90px;
            height: 35px;
            top: 50%;
            left: -90px;
            animation-duration: 18s;
            animation-delay: -10s;
        }

        .cloud-3::before {
            width: 45px;
            height: 45px;
            top: -22px;
            left: 10px;
        }

        .cloud-3::after {
            width: 55px;
            height: 35px;
            top: -12px;
            right: 10px;
        }

        @keyframes float-cloud {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(100vw);
            }
        }

        /* Main container */
        .completion-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            position: relative;
            width: 100%;
            max-width: 1200px;
        }

        /* Selesai Image - BESAR */
        .selesai-image {
            width: 700px;
            height: auto;
            max-width: 95vw;
            animation: image-pop 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            margin-bottom: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        @keyframes image-pop {
            0% {
                opacity: 0;
                transform: scale(0) rotateZ(-15deg);
            }
            70% {
                transform: scale(1.2) rotateZ(5deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotateZ(0deg);
            }
        }

        /* Audio play button */
        .audio-play-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            padding: 16px 35px;
            border-radius: 50px;
            font-size: 1.3rem;
            font-weight: bold;
            cursor: pointer;
            display: none; /* Sembunyikan dulu, akan ditampilkan jika autoplay gagal */
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.5);
            transition: all 0.3s;
            z-index: 100;
            animation: pulse 2s infinite;
        }

        .audio-play-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.7);
            animation: none;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Completion Message */
        .completion-message {
            display: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-back {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 20;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .btn-back:hover {
            transform: scale(1.1);
            color: white;
            text-decoration: none;
        }

        .btn-back i {
            font-size: 1.3rem;
            color: white;
        }

        /* Emoji decorations */
        .emoji-row {
            display: none;
        }

        /* Confetti - Disabled */
        .confetti {
            display: none;
        }

        /* Success Message Box */
        .success-box {
            display: none;
        }

        /* Audio status indicator */
        .audio-status {
            display: none !important;
        }

        /* Loading animation for audio */
        .audio-loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Mobile responsiveness */
        @media (max-width: 900px) {
            .selesai-image {
                width: 550px;
            }
            
            .completion-message h1 {
                font-size: 3.2rem;
            }
            
            .success-box {
                padding: 25px 35px;
                max-width: 90%;
            }
        }

        @media (max-width: 700px) {
            .selesai-image {
                width: 450px;
            }

            .sun {
                width: 80px;
                height: 80px;
                top: 20px;
                right: 20px;
            }

            .btn-back {
                top: 15px;
                left: 15px;
                width: 45px;
                height: 45px;
            }

            .btn-back i {
                font-size: 1.1rem;
            }
            
            .completion-message h1 {
                font-size: 2.8rem;
            }
            
            .completion-message p {
                font-size: 1.4rem;
            }
            
            .emoji-row {
                font-size: 2.5rem;
                letter-spacing: 15px;
            }
        }

        @media (max-width: 500px) {
            .selesai-image {
                width: 380px;
            }

            .sun {
                width: 70px;
                height: 70px;
                top: 15px;
                right: 15px;
            }

            .btn-back {
                top: 12px;
                left: 12px;
                width: 40px;
                height: 40px;
            }

            .btn-back i {
                font-size: 1rem;
            }
            
            .completion-message h1 {
                font-size: 2.4rem;
            }
            
            .completion-message p {
                font-size: 1.2rem;
            }
            
            .emoji-row {
                font-size: 2rem;
                letter-spacing: 12px;
            }
            
            .success-box {
                padding: 20px;
                margin-top: 20px;
            }
            
            .audio-play-btn {
                padding: 14px 30px;
                font-size: 1.2rem;
            }
        }

        @media (max-width: 400px) {
            .selesai-image {
                width: 320px;
            }
            
            .completion-message h1 {
                font-size: 2rem;
            }
            
            .emoji-row {
                font-size: 1.8rem;
                letter-spacing: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Sun -->
    <div class="sun"></div>

    <!-- Clouds -->
    <div class="clouds-container">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    <!-- Back Button -->
    <a href="{{ route('artikel.index') }}" class="btn-back" title="Kembali ke Artikel">
        <i class="fas fa-arrow-left"></i>
    </a>

    <!-- Main completion container -->
    <div class="completion-container">
        <!-- Selesai Image - BESAR -->
        <img src="{{ asset('storage/AI/selesai.png') }}" alt="Selesai" class="selesai-image">
    </div>

    <!-- Audio Element -->
    <audio id="successAudio" preload="none"></audio>

    <script>
        let audio = null;
        let playAttempted = false;
        
        // Setup audio langsung saat script load
        window.addEventListener('load', function() {
            
            // Pakai HTML audio element
            audio = document.getElementById('successAudio');
            audio.volume = 0.8;
            
            // Set src langsung
            const audioUrl = '/storage/AI/waktuhabis.mp3?t=' + Date.now();
            audio.src = audioUrl;
            
            console.log("🎵 Audio URL:", audioUrl);
            
            // Setup tombol play
            const playBtn = document.getElementById('audioPlayBtn');
            if (playBtn) {
                playBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log("👆 Tombol diklik, play audio");
                    playAudio();
                });
            }
            
            // Audio event listeners dengan verbose logging
            audio.addEventListener('loadstart', () => console.log("📥 Loading audio..."));
            
            audio.addEventListener('canplay', () => {
                console.log("✅ Audio ready to play");
                if (!playAttempted) {
                    playAudio();
                }
            });
            
            audio.addEventListener('play', () => console.log("▶️ Audio playing"));
            
            audio.addEventListener('ended', () => console.log("⏹️ Audio ended"));
            
            audio.addEventListener('error', (e) => {
                const error = audio.error;
                if (error) {
                    console.error("❌ Audio error:", {
                        code: error.code,
                        message: error.message,
                        MEDIA_ERR_ABORTED: error.code === 1,
                        MEDIA_ERR_NETWORK: error.code === 2,
                        MEDIA_ERR_DECODE: error.code === 3,
                        MEDIA_ERR_SRC_NOT_SUPPORTED: error.code === 4,
                    });
                }
            });
            
            // Cek metadata untuk debugging
            audio.addEventListener('loadedmetadata', () => {
                console.log("📊 Audio metadata loaded:", {
                    duration: audio.duration,
                    currentTime: audio.currentTime
                });
            });
            
            // Load audio
            audio.load();
            
            // Fallback: play on user interaction
            document.addEventListener('click', function playOnClick() {
                if (!playAttempted) {
                    console.log("🖱️ User interact, coba play");
                    playAudio();
                }
            }, { once: true });
        });
        
        function playAudio() {
            if (!audio) return;
            
            playAttempted = true;
            
            if (audio.paused) {
                audio.currentTime = 0;
                
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise
                        .then(() => {
                            console.log("✅ Autoplay dimulai");
                        })
                        .catch(error => {
                            console.error("❌ Autoplay error:", error.name, error.message);
                            console.log("💡 Tip: Browser mungkin memblokir autoplay. User harus klik tombol play.");
                        });
                }
            }
        }
    </script>
</body>
</html>