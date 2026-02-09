<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Awal Calista</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #87CEEB 0%, #87CEEB 50%, #B0E0E6 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            overflow-x: hidden;
            position: relative;
            max-width: 100vw;
        }
        
        html {
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* Container utama */
        .container-awal {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            gap: 0;
        }

        /* Logo dengan animasi fade-in dan enlargement */
        .logo {
            width: 500px;
            height: 500px;
            margin: 0;
            animation: fadeInEnlarge 1.5s ease-in-out forwards;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.1);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        @keyframes fadeInEnlarge {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Button Play */
        .button-play {
            background: linear-gradient(135deg, #ff6b6b, #ff8787);
            color: white;
            border: none;
            padding: 15px 50px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
            animation: fadeIn 2s ease-in-out;
        }

        .button-play:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.6);
        }

        .button-play:active {
            transform: translateY(-1px);
        }

        .button-play i {
            margin-right: 10px;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Awan */
        .cloud {
            position: absolute;
            background: white;
            border-radius: 100px;
            opacity: 0.7;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: white;
            border-radius: 100px;
        }

        .cloud1 {
            width: 100px;
            height: 40px;
            top: 10%;
            left: 5%;
            animation: moveCloud 20s infinite linear;
        }

        .cloud1::before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 10px;
        }

        .cloud1::after {
            width: 60px;
            height: 40px;
            top: -15px;
            right: 10px;
        }

        .cloud2 {
            width: 120px;
            height: 45px;
            top: 20%;
            right: 8%;
            animation: moveCloud 25s infinite linear reverse;
        }

        .cloud2::before {
            width: 55px;
            height: 55px;
            top: -28px;
            left: 15px;
        }

        .cloud2::after {
            width: 65px;
            height: 45px;
            top: -18px;
            right: 15px;
        }

        .cloud3 {
            width: 90px;
            height: 35px;
            top: 70%;
            left: 10%;
            animation: moveCloud 30s infinite linear;
        }

        .cloud3::before {
            width: 45px;
            height: 45px;
            top: -22px;
            left: 8px;
        }

        .cloud3::after {
            width: 55px;
            height: 35px;
            top: -12px;
            right: 8px;
        }

        .cloud4 {
            width: 110px;
            height: 42px;
            top: 15%;
            right: 5%;
            animation: moveCloud 28s infinite linear reverse;
        }

        .cloud4::before {
            width: 52px;
            height: 52px;
            top: -26px;
            left: 12px;
        }

        .cloud4::after {
            width: 62px;
            height: 42px;
            top: -16px;
            right: 12px;
        }

        .cloud5 {
            width: 95px;
            height: 38px;
            top: 45%;
            left: 2%;
            animation: moveCloud 32s infinite linear;
        }

        .cloud5::before {
            width: 48px;
            height: 48px;
            top: -24px;
            left: 10px;
        }

        .cloud5::after {
            width: 58px;
            height: 38px;
            top: -14px;
            right: 10px;
        }

        .cloud6 {
            width: 105px;
            height: 40px;
            top: 65%;
            right: 12%;
            animation: moveCloud 35s infinite linear reverse;
        }

        .cloud6::before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 13px;
        }

        .cloud6::after {
            width: 60px;
            height: 40px;
            top: -15px;
            right: 13px;
        }

        .cloud7 {
            width: 100px;
            height: 38px;
            top: 25%;
            left: 8%;
            animation: moveCloud 26s infinite linear;
        }

        .cloud7::before {
            width: 48px;
            height: 48px;
            top: -24px;
            left: 9px;
        }

        .cloud7::after {
            width: 58px;
            height: 38px;
            top: -14px;
            right: 9px;
        }

        .cloud8 {
            width: 115px;
            height: 43px;
            top: 55%;
            right: 6%;
            animation: moveCloud 33s infinite linear reverse;
        }

        .cloud8::before {
            width: 53px;
            height: 53px;
            top: -27px;
            left: 14px;
        }

        .cloud8::after {
            width: 63px;
            height: 43px;
            top: -17px;
            right: 14px;
        }

        .cloud9 {
            width: 98px;
            height: 39px;
            top: 80%;
            left: 40%;
            animation: moveCloud 29s infinite linear;
        }

        .cloud9::before {
            width: 49px;
            height: 49px;
            top: -24px;
            left: 11px;
        }

        .cloud9::after {
            width: 59px;
            height: 39px;
            top: -14px;
            right: 11px;
        }

        @keyframes moveCloud {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(100vw);
            }
        }

        /* Matahari di kanan atas */
        .sun {
            position: absolute;
            top: 30px;
            right: 40px;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle at 30% 30%, #ffeb3b, #fbc02d);
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(255, 235, 59, 0.8), 0 0 80px rgba(255, 235, 59, 0.4);
            animation: sunGlow 3s ease-in-out infinite;
        }

        .sun::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 15px;
            background: #fbc02d;
            border-radius: 4px;
        }

        .sun::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 15px;
            background: #fbc02d;
            border-radius: 4px;
        }

        .sun-ray {
            position: absolute;
            background: #fbc02d;
            border-radius: 4px;
        }

        .sun-ray:nth-child(1) {
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 20px;
        }

        .sun-ray:nth-child(2) {
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 20px;
        }

        .sun-ray:nth-child(3) {
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 8px;
        }

        .sun-ray:nth-child(4) {
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 8px;
        }

        /* Sun glow animation */
        @keyframes sunGlow {
            0%, 100% {
                box-shadow: 0 0 40px rgba(255, 235, 59, 0.8), 0 0 80px rgba(255, 235, 59, 0.4);
            }
            50% {
                box-shadow: 0 0 50px rgba(255, 235, 59, 1), 0 0 100px rgba(255, 235, 59, 0.6);
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .logo {
                width: 450px;
                height: 450px;
            }

            .button-play {
                padding: 13px 45px;
                font-size: 17px;
            }

            .sun {
                width: 70px;
                height: 70px;
                top: 25px;
                right: 30px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .container-awal {
                width: 100%;
                max-width: 100%;
            }

            .logo {
                width: 300px;
                height: 300px;
                margin: 0;
            }

            .button-play {
                padding: 12px 40px;
                font-size: 16px;
            }

            .sun {
                width: 60px;
                height: 60px;
                top: 20px;
                right: 20px;
            }

            .cloud1 {
                width: 80px;
                height: 32px;
                left: 2%;
            }

            .cloud1::before {
                width: 40px;
                height: 40px;
            }

            .cloud1::after {
                width: 48px;
                height: 32px;
            }

            .cloud2 {
                width: 90px;
                height: 35px;
                right: 5%;
            }

            .cloud2::before {
                width: 44px;
                height: 44px;
            }

            .cloud2::after {
                width: 52px;
                height: 35px;
            }

            .cloud3 {
                width: 75px;
                height: 30px;
                left: 5%;
            }

            .cloud3::before {
                width: 36px;
                height: 36px;
            }

            .cloud3::after {
                width: 44px;
                height: 30px;
            }

            .cloud4 {
                width: 85px;
                height: 33px;
                right: 3%;
            }

            .cloud4::before {
                width: 41px;
                height: 41px;
            }

            .cloud4::after {
                width: 49px;
                height: 33px;
            }

            .cloud5 {
                width: 78px;
                height: 31px;
                left: 1%;
            }

            .cloud5::before {
                width: 38px;
                height: 38px;
            }

            .cloud5::after {
                width: 46px;
                height: 31px;
            }

            .cloud6 {
                width: 82px;
                height: 32px;
                right: 8%;
            }

            .cloud6::before {
                width: 40px;
                height: 40px;
            }

            .cloud6::after {
                width: 48px;
                height: 32px;
            }

            .cloud7 {
                width: 80px;
                height: 30px;
                left: 4%;
            }

            .cloud7::before {
                width: 38px;
                height: 38px;
            }

            .cloud7::after {
                width: 46px;
                height: 30px;
            }

            .cloud8 {
                width: 90px;
                height: 34px;
                right: 2%;
            }

            .cloud8::before {
                width: 42px;
                height: 42px;
            }

            .cloud8::after {
                width: 50px;
                height: 34px;
            }

            .cloud9 {
                width: 78px;
                height: 31px;
                left: 30%;
            }

            .cloud9::before {
                width: 39px;
                height: 39px;
            }

            .cloud9::after {
                width: 47px;
                height: 31px;
            }
        }

        @media (max-width: 480px) {
            .logo {
                width: 220px;
                height: 220px;
                margin: 0;
            }

            .button-play {
                padding: 11px 35px;
                font-size: 14px;
            }

            .sun {
                width: 50px;
                height: 50px;
                top: 15px;
                right: 15px;
            }

            .sun::before {
                top: -12px;
                width: 6px;
                height: 12px;
            }

            .sun::after {
                bottom: -12px;
                width: 6px;
                height: 12px;
            }

            .sun-ray:nth-child(1) {
                top: -16px;
                width: 6px;
                height: 16px;
            }

            .sun-ray:nth-child(2) {
                bottom: -16px;
                width: 6px;
                height: 16px;
            }

            .sun-ray:nth-child(3) {
                left: -16px;
                width: 16px;
                height: 6px;
            }

            .sun-ray:nth-child(4) {
                right: -16px;
                width: 16px;
                height: 6px;
            }

            .cloud1, .cloud3, .cloud5, .cloud7, .cloud9 {
                opacity: 0.5;
            }

            .cloud2 {
                width: 70px;
                height: 28px;
                top: 15%;
                right: 5%;
                animation: moveCloud 20s infinite linear reverse;
            }

            .cloud2::before {
                width: 35px;
                height: 35px;
            }

            .cloud2::after {
                width: 40px;
                height: 28px;
            }

            .cloud4 {
                width: 75px;
                height: 30px;
                top: 50%;
                right: 3%;
                animation: moveCloud 22s infinite linear reverse;
            }

            .cloud4::before {
                width: 37px;
                height: 37px;
            }

            .cloud4::after {
                width: 43px;
                height: 30px;
            }

            .cloud6 {
                width: 68px;
                height: 26px;
                top: 75%;
                right: 8%;
                animation: moveCloud 24s infinite linear reverse;
            }

            .cloud6::before {
                width: 34px;
                height: 34px;
            }

            .cloud6::after {
                width: 38px;
                height: 26px;
            }

            .cloud8 {
                width: 72px;
                height: 28px;
                top: 35%;
                right: 2%;
                animation: moveCloud 23s infinite linear reverse;
            }

            .cloud8::before {
                width: 36px;
                height: 36px;
            }

            .cloud8::after {
                width: 40px;
                height: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Matahari -->
    <div class="sun">
        <div class="sun-ray"></div>
        <div class="sun-ray"></div>
        <div class="sun-ray"></div>
        <div class="sun-ray"></div>
    </div>

    <!-- Awan -->
    <div class="cloud cloud1"></div>
    <div class="cloud cloud2"></div>
    <div class="cloud cloud3"></div>
    <div class="cloud cloud4"></div>
    <div class="cloud cloud5"></div>
    <div class="cloud cloud6"></div>
    <div class="cloud cloud7"></div>
    <div class="cloud cloud8"></div>
    <div class="cloud cloud9"></div>

    <!-- Konten Utama -->
    <div class="container-awal">
        <!-- Logo dengan animasi -->
        <div class="logo">
            <img src="{{ asset('storage/logo/logo.png') }}" alt="Calista Logo">
        </div>

        <!-- Button Play -->
        <button class="button-play" onclick="handlePlayClick()">
            ▶ MULAI
        </button>
    </div>

    <script>
        function handlePlayClick() {
            @auth
                // Jika sudah login, redirect ke profile anak
                window.location.href = '{{ route("anak.profile") }}';
            @else
                // Jika belum login, redirect ke halaman login
                window.location.href = '{{ route("login") }}';
            @endauth
        }
    </script>
</body>
</html>
