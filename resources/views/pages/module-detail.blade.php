<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $module->name }} - Calista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #67b0ff 0%, #3a8cff 100%);
            color: #333;
            min-height: 100vh;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Matahari di kanan atas */
        .small-sun {
            position: fixed;
            top: 20px;
            right: 30px;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, #ffdd00 0%, #ff9500 100%);
            border-radius: 50%;
            box-shadow: 0 0 25px 15px rgba(255, 221, 0, 0.5),
                        0 0 50px 30px rgba(255, 149, 0, 0.4);
            z-index: 5; /* Lebih tinggi dari awan */
            animation: sunPulse 3s infinite alternate ease-in-out;
        }

        @keyframes sunPulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 25px 15px rgba(255, 221, 0, 0.5),
                            0 0 50px 30px rgba(255, 149, 0, 0.4);
            }
            100% {
                transform: scale(1.1);
                box-shadow: 0 0 30px 20px rgba(255, 221, 0, 0.6),
                            0 0 60px 35px rgba(255, 149, 0, 0.5);
            }
        }

        /* Container awan - Awan melewati depan matahari */
        .clouds-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 150px;
            pointer-events: none;
            z-index: 6; /* Lebih tinggi dari matahari agar lewat di depan */
            overflow: visible;
        }

        /* Awan bulat yang smooth */
        .cloud {
            position: absolute;
            background: #ffffff;
            border-radius: 50%;
            filter: blur(0.5px);
            box-shadow: 
                0 0 20px rgba(255, 255, 255, 0.8),
                0 0 40px rgba(255, 255, 255, 0.4);
            animation: cloudFloatLeft 40s infinite linear;
        }

        /* Awan dengan z-index lebih tinggi untuk melewati depan matahari */
        .cloud-1 {
            width: 100px;
            height: 40px;
            top: 40px;
            right: -100px;
            animation-delay: 0s;
            animation-duration: 45s;
            z-index: 7;
        }

        .cloud-1::before {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            background: #ffffff;
            border-radius: 50%;
            top: -20px;
            left: 15px;
            filter: blur(0.5px);
        }

        .cloud-1::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            top: -25px;
            right: 10px;
            filter: blur(0.5px);
        }

        .cloud-2 {
            width: 90px;
            height: 35px;
            top: 70px;
            right: -90px;
            animation-delay: 8s;
            animation-duration: 50s;
            z-index: 7;
        }

        .cloud-2::before {
            content: '';
            position: absolute;
            width: 45px;
            height: 45px;
            background: #ffffff;
            border-radius: 50%;
            top: -18px;
            left: 12px;
            filter: blur(0.5px);
        }

        .cloud-2::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            background: #ffffff;
            border-radius: 50%;
            top: -22px;
            right: 8px;
            filter: blur(0.5px);
        }

        .cloud-3 {
            width: 110px;
            height: 45px;
            top: 25px;
            right: -110px;
            animation-delay: 15s;
            animation-duration: 55s;
            z-index: 7;
        }

        .cloud-3::before {
            content: '';
            position: absolute;
            width: 55px;
            height: 55px;
            background: #ffffff;
            border-radius: 50%;
            top: -22px;
            left: 18px;
            filter: blur(0.5px);
        }

        .cloud-3::after {
            content: '';
            position: absolute;
            width: 65px;
            height: 65px;
            background: #ffffff;
            border-radius: 50%;
            top: -28px;
            right: 12px;
            filter: blur(0.5px);
        }

        .cloud-4 {
            width: 85px;
            height: 32px;
            top: 55px;
            right: -85px;
            animation-delay: 22s;
            animation-duration: 48s;
            z-index: 7;
        }

        .cloud-4::before {
            content: '';
            position: absolute;
            width: 42px;
            height: 42px;
            background: #ffffff;
            border-radius: 50%;
            top: -16px;
            left: 11px;
            filter: blur(0.5px);
        }

        .cloud-4::after {
            content: '';
            position: absolute;
            width: 47px;
            height: 47px;
            background: #ffffff;
            border-radius: 50%;
            top: -20px;
            right: 7px;
            filter: blur(0.5px);
        }

        .cloud-5 {
            width: 95px;
            height: 38px;
            top: 35px;
            right: -95px;
            animation-delay: 30s;
            animation-duration: 52s;
            z-index: 7;
        }

        .cloud-5::before {
            content: '';
            position: absolute;
            width: 48px;
            height: 48px;
            background: #ffffff;
            border-radius: 50%;
            top: -19px;
            left: 14px;
            filter: blur(0.5px);
        }

        .cloud-5::after {
            content: '';
            position: absolute;
            width: 52px;
            height: 52px;
            background: #ffffff;
            border-radius: 50%;
            top: -23px;
            right: 9px;
            filter: blur(0.5px);
        }

        @keyframes cloudFloatLeft {
            0% { 
                transform: translateX(0) translateY(0); 
                opacity: 0.9;
            }
            25% {
                transform: translateX(-25vw) translateY(3px);
                opacity: 1;
            }
            50% {
                transform: translateX(-50vw) translateY(5px);
                opacity: 1;
            }
            75% {
                transform: translateX(-75vw) translateY(2px);
                opacity: 1;
            }
            100% { 
                transform: translateX(calc(-100vw - 400px)) translateY(0); 
                opacity: 0.9;
            }
        }

        /* RUMPUT AWAN BERGERAK DI BAWAH */
        .grass-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 140px;
            z-index: 1;
            overflow: hidden;
        }

        /* Awan hijau bergelombang */
        .grass-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 140px;
            background: linear-gradient(to top, 
                transparent 0%,
                rgba(27, 94, 32, 0.8) 20%,
                rgba(56, 142, 60, 0.9) 40%,
                rgba(76, 175, 80, 1) 60%,
                rgba(102, 187, 106, 0.95) 80%,
                rgba(129, 199, 132, 0.9) 100%
            );
            border-radius: 50% 50% 0 0;
            z-index: 1;
        }

        .grass-wave::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 140px;
            background: linear-gradient(to top, 
                transparent 0%,
                rgba(46, 125, 50, 0.85) 30%,
                rgba(76, 175, 80, 0.95) 50%,
                rgba(102, 187, 106, 1) 70%,
                rgba(129, 199, 132, 0.95) 90%
            );
            border-radius: 50% 50% 0 0;
        }

        .grass-wave::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 140px;
            background: linear-gradient(to top, 
                transparent 0%,
                rgba(27, 94, 32, 0.9) 25%,
                rgba(56, 142, 60, 0.95) 45%,
                rgba(76, 175, 80, 1) 65%,
                rgba(102, 187, 106, 0.98) 85%
            );
            border-radius: 50% 50% 0 0;
        }

        /* Rumah di grass */
        .house {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            width: 120px;
            height: 100px;
        }

        .house-body {
            position: absolute;
            bottom: 0;
            left: 10%;
            width: 80%;
            height: 60px;
            background: linear-gradient(145deg, #d2691e, #8b4513);
            border: 3px solid #654321;
            border-radius: 0 0 8px 8px;
            box-shadow: 
                inset -3px -3px 8px rgba(0, 0, 0, 0.3),
                5px 5px 15px rgba(0, 0, 0, 0.3);
        }

        .house-roof {
            position: absolute;
            bottom: 60px;
            left: 0;
            width: 0;
            height: 0;
            border-left: 60px solid transparent;
            border-right: 60px solid transparent;
            border-bottom: 50px solid #8b0000;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.4));
        }

        .house-door {
            position: absolute;
            bottom: 5px;
            left: 35%;
            width: 30%;
            height: 50px;
            background: linear-gradient(145deg, #654321, #3e2723);
            border: 2px solid #2c1810;
            border-radius: 0 0 3px 3px;
        }

        .house-doorknob {
            position: absolute;
            top: 50%;
            right: 5px;
            width: 6px;
            height: 6px;
            background: #ffd700;
            border-radius: 50%;
            box-shadow: 0 0 3px rgba(255, 215, 0, 0.8);
        }

        .house-window {
            position: absolute;
            top: 10px;
            width: 16px;
            height: 16px;
            background: #87ceeb;
            border: 2px solid #654321;
            border-radius: 2px;
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
        }

        .house-window:first-of-type {
            left: 15px;
        }

        .house-window:last-of-type {
            right: 15px;
        }

        .house-window::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background: #654321;
            top: 50%;
            left: 0;
        }

        .house-window::after {
            content: '';
            position: absolute;
            width: 2px;
            height: 100%;
            background: #654321;
            top: 0;
            left: 50%;
        }

        /* Container utama untuk konten */
        .container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 160px; /* Space untuk rumput awan */
            z-index: 10; /* Lebih tinggi dari rumput */
        }

        /* Header */
        .module-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .back-btn {
            position: fixed;
            left: 20px;
            top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #3b82f6;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 0;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid #3b82f6;
            z-index: 100;
        }

        .back-btn:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .module-title {
            font-family: 'Fredoka One', cursive;
            font-size: 3.5rem;
            color: white;
            margin-bottom: 15px;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.2);
        }

        .module-description {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 800px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        /* Motivasi Text */
        .motivation-text {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 40px;
            margin-bottom: 40px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .motivation-text h2 {
            font-size: 2.2rem;
            color: white;
            margin-bottom: 10px;
            font-family: 'Fredoka One', cursive;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
        }

        .motivation-text p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        /* Progress Info */
        .progress-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 20px;
            margin-top: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .progress-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .progress-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: white;
        }

        .progress-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        /* Level Section */
        .levels-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .level-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 3px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .level-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .level-card.completed {
            border-color: #10b981;
            background: linear-gradient(145deg, rgba(16, 185, 129, 0.1), rgba(255, 255, 255, 0.9));
        }

        .level-card.current {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            animation: pulse 2s infinite;
        }

        .level-card.locked {
            opacity: 0.7;
            cursor: not-allowed;
            background: linear-gradient(145deg, rgba(226, 232, 240, 0.9), rgba(241, 245, 249, 0.85));
        }

        .level-number-center {
            font-size: 4rem;
            font-weight: 900;
            color: rgba(59, 130, 246, 0.4);
            font-family: 'Fredoka One', cursive;
            margin: 10px 0;
            line-height: 1;
            text-shadow: 2px 2px 0 rgba(255, 255, 255, 0.5);
        }

        .level-card.completed .level-number-center {
            color: rgba(16, 185, 129, 0.5);
        }

        .level-card.locked .level-number-center {
            color: rgba(148, 163, 184, 0.4);
        }

        .level-title {
            display: none;
        }

        .level-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .status-completed {
            background: #10b981;
            color: white;
        }

        .status-current {
            background: #3b82f6;
            color: white;
        }

        .status-locked {
            background: #94a3b8;
            color: white;
        }

        .level-stars {
            margin-bottom: 15px;
        }

        .star {
            font-size: 1.5rem;
            color: #e2e8f0;
            margin: 0 3px;
        }

        .star.filled {
            color: #fbbf24;
            text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
        }

        .start-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 12px 25px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 6px 0 #1d4ed8;
            width: 100%;
            justify-content: center;
            margin-top: 15px;
        }

        .start-btn:hover {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            transform: translateY(-3px);
            box-shadow: 0 9px 0 #1d4ed8;
        }

        .start-btn:disabled {
            background: #94a3b8;
            box-shadow: 0 6px 0 #64748b;
            cursor: not-allowed;
            transform: none;
        }

        /* Navigation */
        .level-navigation {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .nav-info {
            text-align: center;
        }

        .nav-info h3 {
            color: white;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .nav-info p {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 1.1rem;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.1); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .levels-container {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
            
            .module-title {
                font-size: 2.8rem;
            }
            
            .motivation-text h2 {
                font-size: 1.9rem;
            }
            
            .level-number-center {
                font-size: 3.5rem;
            }
        }

        @media (max-width: 768px) {
            /* Matahari lebih kecil */
            .small-sun {
                width: 60px;
                height: 60px;
                top: 15px;
                right: 20px;
            }
            
            /* Awan container lebih pendek */
            .clouds-container {
                height: 120px;
            }
            
            /* Rumput awan lebih pendek */
            .grass-container {
                height: 110px;
            }
            
            .grass-wave,
            .grass-wave::before,
            .grass-wave::after {
                height: 110px;
            }
            
            .container {
                padding-bottom: 130px; /* Kurangi padding untuk mobile */
            }
            
            .levels-container {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }
            
            .module-header {
                padding: 0 15px;
            }
            
            .module-title {
                font-size: 2.3rem;
            }
            
            .motivation-text {
                padding: 20px;
            }
            
            .motivation-text h2 {
                font-size: 1.7rem;
            }
            
            .progress-info {
                flex-direction: column;
                gap: 15px;
            }
            
            .level-card {
                padding: 15px;
                min-height: 180px;
            }
            
            .level-number-center {
                font-size: 3rem;
            }
        }

        @media (max-width: 600px) {
            .levels-container {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .level-number-center {
                font-size: 2.8rem;
            }
            
            .start-btn {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            
            .level-status {
                font-size: 0.9rem;
                padding: 6px 15px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px 10px;
            }
            
            /* Matahari lebih kecil lagi */
            .small-sun {
                width: 50px;
                height: 50px;
                top: 10px;
                right: 15px;
            }
            
            /* Awan container */
            .clouds-container {
                height: 100px;
            }
            
            .cloud {
                opacity: 0.8;
            }
            
            /* Rumput awan lebih pendek */
            .grass-container {
                height: 90px;
            }
            
            .grass-wave,
            .grass-wave::before,
            .grass-wave::after {
                height: 90px;
            }
            
            /* Awan hijau lebih kecil di mobile */
            .grass-cloud {
                transform: scale(0.8);
            }
            
            .container {
                padding-bottom: 110px;
            }
            
            .module-header {
                padding: 0 10px;
                margin-bottom: 25px;
            }
            
            .module-title {
                font-size: 1.8rem;
                margin-top: 50px; /* Beri ruang untuk matahari */
            }
            
            .module-description {
                font-size: 1rem;
                line-height: 1.4;
            }
            
            .motivation-text {
                padding: 15px 20px;
                margin-bottom: 25px;
            }
            
            .motivation-text h2 {
                font-size: 1.3rem;
            }
            
            .motivation-text p {
                font-size: 1rem;
            }
            
            .back-btn {
                font-size: 0.85rem;
                padding: 8px 16px;
                left: 10px;
                top: 10px;
            }
            
            /* Level cards mobile */
            .levels-container {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin-bottom: 30px;
            }
            
            .level-card {
                padding: 10px;
                min-height: 140px;
                border-radius: 15px;
            }
            
            .level-number-center {
                font-size: 2.2rem;
                margin: 5px 0;
            }
            
            .level-stars {
                margin-bottom: 8px;
            }
            
            .star {
                font-size: 1rem;
                margin: 0 2px;
            }
            
            .start-btn {
                padding: 8px 12px;
                font-size: 0.85rem;
                margin-top: 8px;
                border-radius: 12px;
            }
            
            .level-status {
                font-size: 0.8rem;
                padding: 5px 12px;
                margin-top: 8px;
            }
            
            .progress-info {
                flex-direction: column;
                gap: 12px;
                padding: 15px;
            }
            
            .progress-number {
                font-size: 2rem;
            }
            
            .progress-label {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 360px) {
            /* Extra small screens */
            .small-sun {
                width: 40px;
                height: 40px;
                right: 10px;
            }
            
            .grass-container {
                height: 80px;
            }
            
            .grass-wave,
            .grass-wave::before,
            .grass-wave::after {
                height: 80px;
            }
            
            .container {
                padding-bottom: 90px;
            }
            
            .levels-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Landscape mode di mobile */
        @media (max-width: 896px) and (orientation: landscape) {
            .module-title {
                font-size: 2rem;
                margin-top: 35px;
            }
            
            .small-sun {
                width: 45px;
                height: 45px;
                top: 8px;
                right: 15px;
            }
            
            .clouds-container {
                height: 80px;
            }
            
            .grass-container {
                height: 70px;
            }
            
            .grass-wave,
            .grass-wave::before,
            .grass-wave::after {
                height: 70px;
            }
            
            .container {
                padding-bottom: 90px;
            }
            
            .levels-container {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
            }
            
            .level-card {
                min-height: 120px;
                padding: 8px;
            }
            
            .motivation-text {
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Matahari di kanan atas -->
    <div class="small-sun"></div>
    
    <!-- Awan bergerak di depan matahari -->
    <div class="clouds-container">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
        <div class="cloud cloud-4"></div>
        <div class="cloud cloud-5"></div>
    </div>
    
    <!-- Rumput awan bergerak di bawah -->
    <div class="grass-container">
        <div class="grass-wave"></div>
        
        <!-- Rumah -->
        <div class="house">
            <div class="house-roof"></div>
            <div class="house-body">
                <div class="house-window"></div>
                <div class="house-window"></div>
                <div class="house-door">
                    <div class="house-doorknob"></div>
                </div>
            </div>
        </div>
        
        <!-- Awan hijau kecil yang bergerak -->
        <div class="grass-cloud grass-cloud-1"></div>
        <div class="grass-cloud grass-cloud-2"></div>
        <div class="grass-cloud grass-cloud-3"></div>
        <div class="grass-cloud grass-cloud-4"></div>
        <div class="grass-cloud grass-cloud-5"></div>
    </div>
    
    <div class="container">
        <!-- Module Header -->
        <div class="module-header">
            <a href="{{ route('permainan') }}" class="back-btn" aria-label="Kembali"
               onclick="(function(){try{const s=new Audio('{{ asset('storage/music/klik.mp3') }}'); s.preload='auto'; s.play().catch(()=>{});}catch(e){} })()">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="module-title">{{ $module->name }}</h1>
            <p class="module-description">
                {{ $module->description ?? 'Selesaikan semua level untuk menguasai materi ini!' }}
            </p>
        </div>

        <!-- Levels Grid -->
        <div class="levels-container">
            @foreach($levels as $levelItem)
                @php
                    $isCompleted = isset($progresMap[$levelItem->id]) && $progresMap[$levelItem->id] === true;
                    
                    $isLocked = false;
                    
                    if ($levelItem->order_number === 1) {
                        $isLocked = false;
                    } else {
                        $prevLevel = $levels->where('order_number', $levelItem->order_number - 1)->first();
                        if ($prevLevel) {
                            $isPrevCompleted = isset($progresMap[$prevLevel->id]) && $progresMap[$prevLevel->id] === true;
                            $isLocked = !$isPrevCompleted;
                        }
                    }
                    
                    $isCurrent = !$isLocked && !$isCompleted;
                    
                    if ($isCurrent) {
                        $hasUncompletedBefore = false;
                        foreach ($levels as $prevLevelCheck) {
                            if ($prevLevelCheck->id === $levelItem->id) break;
                            $prevIsCompleted = isset($progresMap[$prevLevelCheck->id]) && $progresMap[$prevLevelCheck->id] === true;
                            $prevIsLocked = $prevLevelCheck->order_number > 1 && !isset($progresMap[$levels->where('order_number', $prevLevelCheck->order_number - 1)->first()->id]);
                            
                            if (!$prevIsCompleted && !$prevIsLocked) {
                                $hasUncompletedBefore = true;
                                break;
                            }
                        }
                        $isCurrent = !$hasUncompletedBefore;
                    }
                    
                    $stars = 0;
                    if ($isCompleted && isset($progresDetails[$levelItem->id])) {
                        $stars = $progresDetails[$levelItem->id]->bintang ?? 0;
                    }
                    
                    $firstWriting = \App\Models\WritingItem::where('level_id', $levelItem->id)->first();
                    $firstCounting = \App\Models\CountingItem::where('level_id', $levelItem->id)->first();
                    $firstPuzzle = \App\Models\PuzzleItem::where('level_id', $levelItem->id)
                                    ->where('is_active', true)
                                    ->first();
                @endphp
                
                <div class="level-card 
                    {{ $isCompleted ? 'completed' : '' }} 
                    {{ $isCurrent && !$isLocked ? 'current' : '' }}
                    {{ $isLocked ? 'locked' : '' }}"
                    data-level-id="{{ $levelItem->id }}"
                    data-writing-id="{{ $firstWriting ? $firstWriting->id : '' }}"
                    data-counting="{{ $firstCounting ? '1' : '' }}"
                    data-puzzle-id="{{ $firstPuzzle ? $firstPuzzle->id : '' }}"
                    title="{{ $isLocked ? 'Selesaikan level sebelumnya terlebih dahulu' : '' }}">
                    
                    <div class="level-number-center">{{ $levelItem->order_number }}</div>
                    
                    <div class="level-stars">
                        @for($i = 1; $i <= 3; $i++)
                            <span class="star {{ $i <= $stars ? 'filled' : '' }}">
                                <i class="fas fa-star"></i>
                            </span>
                        @endfor
                    </div>
                    
                    @if($isLocked)
                        <button class="start-btn" disabled title="Level terkunci">
                            <i class="fas fa-lock"></i> Terkunci
                        </button>
                    @else
                        @if($firstPuzzle)
                        <a href="{{ route('calista.level.puzzle', ['slug' => $module->slug, 'level' => $levelItem->id]) }}" 
                           class="start-btn">
                            <i class="fas fa-{{ $isCompleted ? 'redo' : 'play' }}"></i>
                            {{ $isCompleted ? 'Ulangi' : 'Mulai' }}
                        </a>
                        @elseif($firstWriting)
                        <a href="{{ route('calista.level.writing', ['slug' => $module->slug, 'level' => $levelItem->id, 'writingItem' => $firstWriting->id]) }}" 
                           class="start-btn">
                            <i class="fas fa-{{ $isCompleted ? 'redo' : 'play' }}"></i>
                            {{ $isCompleted ? 'Ulangi' : 'Mulai' }}
                        </a>
                        @elseif($firstCounting)
                        <a href="{{ route('calista.level.counting', ['slug' => $module->slug, 'level' => $levelItem->id]) }}" 
                           class="start-btn">
                            <i class="fas fa-{{ $isCompleted ? 'redo' : 'play' }}"></i>
                            {{ $isCompleted ? 'Ulangi' : 'Mulai' }}
                        </a>
                        @else
                        <a href="{{ route('calista.level.show', ['slug' => $module->slug, 'level' => $levelItem->id]) }}" 
                           class="start-btn">
                            <i class="fas fa-{{ $isCompleted ? 'redo' : 'play' }}"></i>
                            {{ $isCompleted ? 'Ulangi' : 'Mulai' }}
                        </a>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulasi data progres (nanti diganti dengan data dari server)
            const completedLevels = 0;
            const totalStars = 0;
            
            // Update progress info
            if (document.getElementById('completedLevels')) {
                document.getElementById('completedLevels').textContent = completedLevels;
            }
            if (document.getElementById('totalStars')) {
                document.getElementById('totalStars').textContent = totalStars;
            }
            
            // Add click effect to level cards
            const levelCards = document.querySelectorAll('.level-card:not(.locked)');
            levelCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || 
                        e.target.closest('a') || e.target.closest('button')) {
                        return;
                    }
                    
                    const levelId = this.getAttribute('data-level-id');
                    const writingId = this.getAttribute('data-writing-id');
                    const hasCounting = this.getAttribute('data-counting');
                    const puzzleId = this.getAttribute('data-puzzle-id');
                    if (puzzleId) {
                        window.location.href = `/calista/{{ $module->slug }}/level/${levelId}/puzzle`;
                    } else if (writingId) {
                        window.location.href = `/calista/{{ $module->slug }}/level/${levelId}/writing/${writingId}`;
                    } else if (hasCounting) {
                        window.location.href = `/calista/{{ $module->slug }}/level/${levelId}/counting`;
                    } else {
                        window.location.href = `/calista/{{ $module->slug }}/level/${levelId}`;
                    }
                });
            });
            
            // Add hover effect
            levelCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('locked')) {
                        this.style.transform = 'translateY(-10px)';
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('locked')) {
                        this.style.transform = 'translateY(0)';
                    }
                });
            });
        });
    

    </script>



    <!-- Time Out Modal Styles -->
    <style>
        .timeout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .timeout-modal {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%);
            border-radius: 40px;
            padding: 50px 40px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .timeout-modal i {
            font-size: 80px;
            color: white;
            margin-bottom: 20px;
            display: block;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .timeout-modal h2 {
            font-size: 36px;
            color: white;
            margin: 20px 0;
            font-family: 'Fredoka One', sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .timeout-modal p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 30px 0;
        }

        .timeout-button {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-family: 'Nunito', sans-serif;
        }

        .timeout-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .timeout-button:active {
            transform: translateY(-1px);
        }
    </style>

    <!-- Real-time Timer Check Script -->
    <script>
        (function() {
            // Ambil child_id dari active child
            let childId = null;
            let isTimeoutShown = false;

            // Function untuk mendapatkan active child terlebih dahulu
            function getActiveChild() {
                fetch(`/anak/check-active-status`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        childId = data.active_child.id;
                    }
                })
                .catch(error => console.error('Error getting active child:', error));
            }

            // Function untuk cek waktu tersisa via API
            function checkRemainingTime() {
                if (!childId || isTimeoutShown) return;

                fetch(`/anak/${childId}/remaining-time`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.remaining_seconds <= 0) {
                        showTimeoutModal();
                    }
                })
                .catch(error => console.error('Error checking time:', error));
            }

            // Function untuk menampilkan timeout modal
            function showTimeoutModal() {
                if (isTimeoutShown) return;
                isTimeoutShown = true;

                // Ciptakan overlay dan modal
                const overlay = document.createElement('div');
                overlay.className = 'timeout-overlay';
                
                const modal = document.createElement('div');
                modal.className = 'timeout-modal';
                modal.innerHTML = `
                    <i class="fas fa-hourglass-end"></i>
                    <h2>Yahhh Waktu Habis! ⏰</h2>
                    <p>Waktu bermain kamu sudah habis untuk hari ini.</p>
                    <p>Coba lagi besok, ya!</p>
                    <button class="timeout-button">Kembali ke Profil</button>
                `;

                overlay.appendChild(modal);
                document.body.appendChild(overlay);

                // Tambahkan event listener ke button
                const button = modal.querySelector('.timeout-button');
                button.addEventListener('click', function() {
                    // Redirect ke selesai
                    window.location.href = '{{ route("selesai") }}';
                });

                // Prevent scroll
                document.body.style.overflow = 'hidden';
            }

            // Mulai dengan mengambil active child
            document.addEventListener('DOMContentLoaded', function() {
                getActiveChild();
                // Setelah mendapat childId, cek waktu
                setTimeout(() => {
                    checkRemainingTime();
                    // Kemudian cek setiap 2 detik
                    setInterval(checkRemainingTime, 2000);
                }, 100);
            });
        })();
    </script>
</body>
</html>
