<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $book->title }} - Cerita Interaktif AI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Comic Neue', cursive;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
            min-height: 100vh;
            overflow: hidden;
            user-select: none;
        }

        /* Container Buku */
        .book-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.5);
            width: 95vw;
            height: 90vh;
            max-width: 1400px;
            background: #fff5e6;
            border-radius: 30px;
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.4),
                inset 0 0 0 20px #ffcc80,
                inset 0 0 0 40px #fff5e6;
            overflow: hidden;
            opacity: 0;
            transition: all 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1000;
            perspective: 2000px;
        }

        .book-container.open {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
            animation: bookOpen 1s ease-out;
        }

        @keyframes bookOpen {
            0% { transform: translate(-50%, -50%) scale(0.3) rotateY(90deg); }
            70% { transform: translate(-50%, -50%) scale(1.05) rotateY(-10deg); }
            100% { transform: translate(-50%, -50%) scale(1) rotateY(0deg); }
        }

        /* Halaman Buku */
        .book-page {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 20px;
            background: #fffaf0;
            display: none;
        }

        .book-page.active {
            display: flex;
            animation: pageAppear 0.8s ease-out;
        }

        @keyframes pageAppear {
            from { 
                opacity: 0; 
                transform: translateY(50px) scale(0.9); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        /* Header Halaman */
        .page-header {
            width: 100%;
            text-align: center;
            padding: 10px 0;
            border-bottom: 6px double #ff9800;
            margin-bottom: 10px;
            flex-shrink: 0;
            height: 80px;
        }

        .chapter-title {
            font-size: 2.8rem;
            color: #ff5722;
            text-shadow: 4px 4px 0 #ffcc80, 8px 8px 0 rgba(255, 152, 0, 0.3);
            margin-bottom: 5px;
            animation: bounceIn 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3) rotate(-10deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(5deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); }
        }

        /* AREA UTAMA */
        .content-area {
            display: flex;
            width: 100%;
            height: calc(100% - 120px);
            gap: 20px;
            flex: 1;
            overflow: hidden;
        }

        /* AREA ILUSTRASI - 70% */
        .illustration-container {
            flex: 7;
            position: relative;
            border-radius: 25px;
            overflow: hidden !important;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            box-shadow: 
                inset 0 0 40px rgba(0, 0, 0, 0.1),
                0 15px 50px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 10px solid #ffcc80;
        }

        /* BACKGROUND IMAGE - FULL COVER */
        .background-image {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            z-index: 1 !important;
            opacity: 1 !important;
            transition: opacity 1s ease;
        }

        /* MATAHARI KECIL DI TENGAH ATAS */
        .sun {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            background: radial-gradient(circle at 30% 30%, #FFD700, #FF8C00);
            border-radius: 50%;
            z-index: 2;
            box-shadow: 
                0 0 30px #FFD700,
                0 0 60px rgba(255, 215, 0, 0.5);
            animation: sunGlow 3s ease-in-out infinite alternate;
        }

        @keyframes sunGlow {
            0% { 
                box-shadow: 
                    0 0 30px #FFD700,
                    0 0 60px rgba(255, 215, 0, 0.5);
                transform: translateX(-50%) scale(1);
            }
            100% { 
                box-shadow: 
                    0 0 40px #FFD700,
                    0 0 80px rgba(255, 215, 0, 0.7);
                transform: translateX(-50%) scale(1.1);
            }
        }

        /* EFEK AWAN BERGERAK */
        .clouds-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40%;
            z-index: 3;
            pointer-events: none;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 50px;
            filter: blur(25px);
            animation: cloudMove 60s linear infinite;
            opacity: 0.7;
        }

        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 50%;
        }

        .cloud::before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 20px;
            filter: blur(15px);
        }

        .cloud::after {
            width: 60px;
            height: 60px;
            top: -30px;
            right: 20px;
            filter: blur(15px);
        }

        .cloud.fast {
            animation-duration: 25s;
            opacity: 0.6;
        }

        .cloud.medium {
            animation-duration: 40s;
            opacity: 0.7;
        }

        .cloud.slow {
            animation-duration: 60s;
            opacity: 0.8;
        }

        @keyframes cloudMove {
            0% { 
                transform: translateX(-400px) scale(var(--scale));
                opacity: 0.5; 
            }
            10% { opacity: 0.9; }
            90% { opacity: 0.9; }
            100% { 
                transform: translateX(calc(100vw + 400px)) scale(var(--scale));
                opacity: 0.5; 
            }
        }

        /* SCENE CONTAINER */
        .scene-container {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 4;
            pointer-events: none;
        }

        /* CHARACTER/OBJECT WRAPPER */
        .image-wrapper {
            position: absolute;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            z-index: var(--z-index, 5);
            transition: all 0.8s ease;
            opacity: 0;
            transform-origin: bottom center;
        }

        .image-wrapper.show {
            opacity: 1 !important;
        }

        /* IMAGE STYLES */
        .scene-image {
            position: relative;
            max-height: var(--max-height, 70%);
            max-width: var(--max-width, 60%);
            object-fit: contain;
            filter: drop-shadow(0 15px 25px rgba(0,0,0,0.5));
            transform: var(--transform, none);
        }

        /* POSITIONING CLASSES */
        .position-left { left: var(--pos-x, 10%); justify-content: flex-start; }
        .position-right { right: var(--pos-x, 10%); justify-content: flex-end; }
        .position-center { left: var(--pos-x, 50%); transform: translateX(-50%); }
        .position-top { top: var(--pos-y, 10%); align-items: flex-start; }
        .position-middle { top: var(--pos-y, 50%); transform: translateY(-50%); }
        .position-bottom { bottom: var(--pos-y, 10%); align-items: flex-end; }

        /* SIZE VARIATIONS */
        .size-large { --max-height: 80%; --max-width: 70%; z-index: 7; }
        .size-medium { --max-height: 60%; --max-width: 50%; z-index: 6; }
        .size-small { --max-height: 40%; --max-width: 30%; z-index: 5; }

        /* ENTRANCE ANIMATIONS */
        .enter-left { animation: enterFromLeft 1s forwards; }
        .enter-right { animation: enterFromRight 1s forwards; }
        .enter-bottom { animation: enterFromBottom 1s forwards; }
        .enter-fade { animation: fadeInScale 1s forwards; }

        @keyframes enterFromLeft {
            from { transform: translateX(-100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes enterFromRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes enterFromBottom {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeInScale {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* ========== AREA TEXT DAN CHOICES ========== */
        .text-choices-container {
            flex: 3;
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 400px;
        }

        /* Story Text Area dengan Auto Scroll */
        .story-text-area {
            background: rgba(255, 255, 255, 0.98);
            border: 12px solid transparent;
            border-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none" stroke="%23ff9800" stroke-width="10" stroke-dasharray="10,10" rx="20"/></svg>') 30 stretch;
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            position: relative;
            flex: 1;
            min-height: 200px;
            max-height: 300px;
            overflow: hidden;
        }

        .story-text {
            font-size: 1.8rem;
            line-height: 2;
            color: #5d4037;
            text-align: justify;
            padding: 15px;
            max-height: 100%;
            overflow-y: auto;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
            scroll-behavior: smooth;
        }

        .story-text.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Auto Scroll Indicator */
        .auto-scroll-indicator {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: linear-gradient(145deg, rgba(76, 175, 80, 0.9), rgba(46, 125, 50, 0.9));
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 1rem;
            display: none;
            z-index: 10;
            animation: pulse 2s infinite;
            border: 3px solid white;
            backdrop-filter: blur(5px);
        }

        .auto-scroll-indicator.show {
            display: block;
        }

        /* Choice Area (TIDAK DITAMPILKAN) */
        .choice-area {
            display: none !important;
        }

        /* ========== MODAL CHOICE FULLSCREEN ========== */
        .choice-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(15px);
            animation: fadeInOverlay 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .choice-modal-overlay.show {
            display: flex;
        }

        @keyframes fadeInOverlay {
            from { opacity: 0; backdrop-filter: blur(0px); }
            to { opacity: 1; backdrop-filter: blur(15px); }
        }

        .choice-modal {
            background: linear-gradient(145deg, #fff5e6, #ffe0b2);
            border-radius: 30px;
            padding: 30px;
            max-width: 600px;
            width: 85%;
            box-shadow: 
                0 50px 100px rgba(0, 0, 0, 0.7),
                inset 0 0 0 20px #ffcc80,
                inset 0 0 0 40px #fff5e6;
            animation: modalAppear 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 15px solid #ff9800;
            position: relative;
            overflow: hidden;
        }

        @keyframes modalAppear {
            0% { transform: scale(0.3) rotate(-10deg); opacity: 0; }
            70% { transform: scale(1.05) rotate(5deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); }
        }

        .choice-modal-title {
            font-size: 2.2rem;
            color: #ff5722;
            text-align: center;
            margin-bottom: 25px;
            text-shadow: 
                4px 4px 0 #ffcc80,
                5px 5px 0 rgba(255, 152, 0, 0.3);
            animation: bounceInText 1s ease-out;
            padding: 15px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            border: 6px dashed #4CAF50;
        }

        @keyframes bounceInText {
            0% { transform: scale(0.5) translateY(100px); opacity: 0; }
            60% { transform: scale(1.1) translateY(-20px); opacity: 1; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }

        .choice-modal-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .choice-modal-btn {
            background: linear-gradient(145deg, #4caf50, #2e7d32);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 20px 25px;
            font-size: 1.6rem;
            font-family: 'Comic Neue', cursive;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 
                0 20px 40px rgba(46, 125, 50, 0.6),
                inset 0 0 0 4px rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            border: 8px solid white;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70px;
        }

        .choice-modal-btn:hover {
            transform: translateY(-15px) scale(1.1);
            box-shadow: 
                0 40px 80px rgba(46, 125, 50, 0.8),
                inset 0 0 0 4px rgba(255, 255, 255, 0.5);
            background: linear-gradient(145deg, #ff9800, #ff5722);
            border-color: #ffcc80;
        }

        .choice-modal-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s;
        }

        .choice-modal-btn:hover::before {
            left: 100%;
        }

        .choice-modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(145deg, #ff5722, #e64a19);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            box-shadow: 0 10px 25px rgba(255, 87, 34, 0.6);
            transition: all 0.3s;
            border: 5px solid white;
        }

        .choice-modal-close:hover {
            transform: rotate(90deg) scale(1.2);
            background: linear-gradient(145deg, #4caf50, #2e7d32);
        }

        /* MODAL CHARACTER ILLUSTRATION */
        .modal-character {
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            z-index: 1;
            opacity: 0.3;
            pointer-events: none;
        }

        .modal-character img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.5));
        }

        /* MODAL BACKGROUND PATTERN */
        .modal-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 152, 0, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(76, 175, 80, 0.1) 2px, transparent 2px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        /* MODAL CONTENT */
        .modal-content {
            position: relative;
            z-index: 2;
        }

        /* COUNTDOWN TIMER FOR CHOICE */
        .choice-timer {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(145deg, #2196F3, #1976D2);
            color: white;
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 1.3rem;
            font-weight: bold;
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.5);
            display: flex;
            align-items: center;
            gap: 8px;
            border: 5px solid white;
            animation: pulseTimer 1s infinite;
        }

        @keyframes pulseTimer {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .choice-timer i {
            font-size: 1.5rem;
        }

        /* Navigation Controls */
        .nav-controls {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 25px;
            z-index: 1001;
        }

        .nav-btn {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(145deg, #ff9800, #f57c00);
            color: white;
            font-size: 2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 20px 40px rgba(245, 124, 0, 0.6);
            position: relative;
            overflow: hidden;
            border: 8px solid white;
        }

        .nav-btn:hover {
            transform: scale(1.2) rotate(15deg);
            box-shadow: 0 30px 60px rgba(245, 124, 0, 0.8);
        }

        /* Audio Player */
        .audio-player {
            position: fixed;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 60px;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            z-index: 1001;
            border: 8px solid #ffcc80;
            transform: scale(0);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: none;
        }

        .audio-player.show {
            transform: scale(1);
        }

        /* Start Screen - TOMBOL BESAR */
        .start-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            transition: opacity 1s, transform 1s;
        }

        .start-screen.hidden {
            opacity: 0;
            transform: scale(1.2);
            pointer-events: none;
        }

        .book-cover {
            width: 400px;
            height: 500px;
            background: linear-gradient(145deg, #ff9800, #ff5722);
            border-radius: 30px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.6);
            transform-style: preserve-3d;
            animation: bookFloat 3s ease-in-out infinite;
            position: relative;
            overflow: hidden;
            border: 20px solid white;
            margin-bottom: 60px;
        }

        @keyframes bookFloat {
            0%, 100% { transform: translateY(0) rotateY(0deg); }
            50% { transform: translateY(-40px) rotateY(15deg); }
        }

        .book-title {
            color: white;
            font-size: 3.5rem;
            text-shadow: 6px 6px 0 rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
            background: rgba(0, 0, 0, 0.2);
            padding: 25px;
            border-radius: 20px;
        }

        /* TOMBOL START BESAR BANGET */
        .big-start-btn {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(145deg, #4caf50, #2e7d32);
            color: white;
            border: none;
            padding: 35px 80px;
            font-size: 2.8rem;
            border-radius: 70px;
            cursor: pointer;
            box-shadow: 0 30px 70px rgba(76, 175, 80, 0.7);
            z-index: 3000;
            font-family: 'Comic Neue', cursive;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            display: flex;
            align-items: center;
            gap: 30px;
            animation: bigButtonPulse 1.5s infinite, rainbowBorder 4s infinite;
            border: 12px solid white;
            min-width: 650px;
            text-align: center;
            justify-content: center;
        }

        @keyframes bigButtonPulse {
            0%, 100% { transform: translateX(-50%) scale(1); }
            50% { transform: translateX(-50%) scale(1.1); }
        }

        @keyframes rainbowBorder {
            0% { border-color: #ff0000; }
            25% { border-color: #00ff00; }
            50% { border-color: #0000ff; }
            75% { border-color: #ffff00; }
            100% { border-color: #ff00ff; }
        }

        .big-start-btn:hover {
            background: linear-gradient(145deg, #ff5722, #ff9800);
            transform: translateX(-50%) scale(1.2) !important;
            box-shadow: 0 50px 100px rgba(255, 87, 34, 0.9);
        }

        .big-start-btn i {
            font-size: 3.5rem;
            animation: spinIcon 2s infinite linear;
        }

        /* Page Number Indicator */
        .page-indicator {
            position: absolute;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(145deg, #ff9800, #ff5722);
            color: white;
            padding: 18px 35px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.6rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 6;
            border: 6px solid white;
            animation: pulse 2s infinite;
        }

        .page-indicator::before {
            content: "📖";
            font-size: 2rem;
        }

        /* ========== AI VOICE AGENT STYLES ========== */
        .ai-controls {
            position: fixed;
            bottom: 150px;
            right: 40px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1001;
        }

        .ai-btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(145deg, #2196F3, #1976D2);
            color: white;
            font-size: 2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 15px 35px rgba(33, 150, 243, 0.5);
            border: 6px solid white;
        }

        .ai-btn:hover {
            transform: scale(1.15);
            background: linear-gradient(145deg, #FF9800, #F57C00);
        }

        .ai-btn.listening {
            animation: pulse 1.5s infinite;
            background: linear-gradient(145deg, #FF5722, #E64A19);
        }

        .ai-btn.ai-btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .ai-status {
            position: fixed;
            bottom: 240px;
            right: 40px;
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            max-width: 300px;
            display: none;
            border: 4px solid #4CAF50;
            font-size: 1.1rem;
            color: #2E7D32;
        }

        .ai-status.show {
            display: block;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Question Area (untuk pertanyaan cerita) */
        .question-area {
            background: linear-gradient(145deg, #FFF3E0, #FFE0B2);
            border-radius: 20px;
            padding: 25px;
            margin: 20px 0;
            border: 8px solid #FF9800;
            display: none;
            animation: fadeIn 0.8s;
        }

        .question-area.show {
            display: block;
        }

        .question-text {
            font-size: 1.8rem;
            color: #E65100;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .auto-advance-notice {
            background: rgba(76, 175, 80, 0.1);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            margin-top: 15px;
            border: 3px dashed #4CAF50;
            color: #2E7D32;
            font-size: 1.2rem;
            display: none;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            display: none;
        }

        .loading-spinner-ai {
            width: 80px;
            height: 80px;
            border: 8px solid #ffcc80;
            border-top: 8px solid #ff5722;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Audio Generation Status */
        .audio-gen-status {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 30px;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1002;
            display: none;
            font-size: 1.2rem;
            color: #2E7D32;
            border: 4px solid #4CAF50;
        }

        .audio-gen-status.show {
            display: block;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from { top: -100px; opacity: 0; }
            to { top: 20px; opacity: 1; }
        }

        /* ========== MOBILE OPTIMIZATION ========== */

        /* Tablet & Small Desktop (768px - 1200px) */
        @media (max-width: 1200px) {
            .content-area {
                flex-direction: column;
                height: auto;
                gap: 15px;
            }
            
            .illustration-container {
                flex: 1;
                min-height: 45vh;
                max-height: 50vh;
            }
            
            .text-choices-container {
                flex: 1;
                min-width: 100%;
                gap: 15px;
            }
            
            .story-text-area {
                max-height: 25vh;
                min-height: 150px;
            }
            
            .story-text {
                font-size: 1.6rem;
                line-height: 1.8;
            }
            
            .chapter-title {
                font-size: 2.2rem;
            }
            
            .big-start-btn {
                min-width: 90vw;
                font-size: 2rem;
                padding: 25px 40px;
            }
        }

        /* Mobile Landscape (481px - 767px) */
        @media (max-width: 767px) and (orientation: landscape) {
            .book-container {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
            }
            
            .content-area {
                flex-direction: row;
                height: calc(100% - 100px);
            }
            
            .illustration-container {
                flex: 6;
                min-height: 100%;
            }
            
            .text-choices-container {
                flex: 4;
                max-height: 100%;
                overflow-y: auto;
            }
            
            .page-header {
                height: 60px;
                padding: 5px 0;
            }
            
            .chapter-title {
                font-size: 1.8rem;
            }
        }

        /* Mobile Portrait (320px - 767px) */
        @media (max-width: 767px) {
            /* Reset container */
            .book-container {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
                box-shadow: none;
                transform: translate(-50%, -50%) scale(1) !important;
                padding: 0;
            }
            
            .book-container.open {
                transform: translate(-50%, -50%) scale(1) !important;
            }
            
            /* Page Layout */
            .book-page {
                padding: 10px;
            }
            
            .page-header {
                height: 60px;
                padding: 5px 0;
                margin-bottom: 8px;
                border-bottom: 4px double #ff9800;
            }
            
            .chapter-title {
                font-size: 1.8rem;
                text-shadow: 2px 2px 0 #ffcc80, 4px 4px 0 rgba(255, 152, 0, 0.3);
            }
            
            /* Content Area - Stack Vertical */
            .content-area {
                flex-direction: column;
                height: calc(100% - 70px);
                gap: 10px;
            }
            
            /* Illustration Area */
            .illustration-container {
                flex: 1.2;
                min-height: 40vh;
                max-height: 45vh;
                border-radius: 15px;
                border-width: 6px;
            }
            
            /* Sun - Smaller */
            .sun {
                width: 20px;
                height: 20px;
                top: 10px;
            }
            
            /* Cloud - Less & Smaller */
            .clouds-overlay {
                height: 25%;
            }
            
            .cloud {
                transform: scale(0.6);
            }
            
            /* Scene Images - Smaller */
            .scene-image {
                max-height: 50% !important;
                max-width: 45% !important;
            }
            
            .size-large {
                --max-height: 60%;
                --max-width: 55%;
            }
            
            .size-medium {
                --max-height: 45%;
                --max-width: 40%;
            }
            
            .size-small {
                --max-height: 30%;
                --max-width: 25%;
            }
            
            /* Text & Choices Area */
            .text-choices-container {
                flex: 0.8;
                min-width: 100%;
                gap: 10px;
            }
            
            .story-text-area {
                max-height: 20vh;
                min-height: 120px;
                padding: 15px;
                border-radius: 15px;
                border-width: 8px;
            }
            
            .story-text {
                font-size: 1.4rem;
                line-height: 1.7;
                padding: 10px;
            }
            
            .auto-scroll-indicator {
                bottom: 10px;
                right: 10px;
                padding: 6px 12px;
                font-size: 0.9rem;
                border-width: 2px;
            }
            
            /* Page Indicator */
            .page-indicator {
                bottom: 15px;
                right: 15px;
                padding: 12px 20px;
                font-size: 1.2rem;
                border-width: 4px;
            }
            
            .page-indicator::before {
                font-size: 1.5rem;
            }
            
            /* Navigation Buttons - Bigger & Better Position */
            .nav-controls {
                bottom: 20px;
                gap: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-btn {
                width: 60px;
                height: 60px;
                font-size: 1.3rem;
                border-width: 5px;
                box-shadow: 0 10px 25px rgba(245, 124, 0, 0.5);
            }
            
            /* Audio Player - Compact */
            .audio-player {
                top: 10px;
                right: 10px;
                padding: 12px 15px;
                gap: 10px;
                border-width: 5px;
                border-radius: 40px;
                flex-direction: row;
                width: auto;
                max-width: 90vw;
            }
            
            .audio-btn {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
                flex-shrink: 0;
            }
            
            .progress-container {
                height: 8px;
                margin: 0 10px;
                flex: 1;
                min-width: 100px;
            }
            
            /* AI Controls - Side Panel */
            .ai-controls {
                bottom: 90px;
                right: 10px;
                gap: 10px;
            }
            
            .ai-btn {
                width: 55px;
                height: 55px;
                font-size: 1.3rem;
                border-width: 4px;
            }
            
            .ai-status {
                bottom: 160px;
                right: 10px;
                max-width: 200px;
                font-size: 0.95rem;
                padding: 10px 15px;
                border-width: 3px;
            }
            
            /* Start Screen */
            .start-screen {
                padding: 20px;
            }
            
            .book-cover {
                width: 90vw;
                max-width: 350px;
                height: auto;
                padding: 25px 15px;
                margin-bottom: 30px;
                border-width: 12px;
            }
            
            .book-title {
                font-size: 2.2rem;
                padding: 15px;
                margin-bottom: 25px;
            }
            
            .book-cover > div {
                font-size: 1.4rem !important;
            }
            
            .book-cover > div p {
                font-size: 1.4rem !important;
                margin-bottom: 15px !important;
            }
            
            /* Big Start Button - HUGE */
            .big-start-btn {
                bottom: 40px;
                min-width: 85vw;
                max-width: 500px;
                font-size: 1.8rem;
                padding: 20px 30px;
                border-radius: 50px;
                border-width: 8px;
                gap: 15px;
                letter-spacing: 1px;
            }
            
            .big-start-btn i {
                font-size: 2.5rem;
            }
            
            /* Choice Modal - FULLSCREEN on Mobile */
            .choice-modal-overlay {
                padding: 0;
            }
            
            .choice-modal {
                width: 100vw;
                height: 100vh;
                max-width: 100vw;
                border-radius: 0;
                border-width: 0;
                padding: 20px 15px;
                box-shadow: none;
                overflow-y: auto;
            }
            
            .choice-modal-title {
                font-size: 1.8rem;
                padding: 12px;
                margin-bottom: 20px;
                border-width: 4px;
                text-shadow: 2px 2px 0 #ffcc80, 3px 3px 0 rgba(255, 152, 0, 0.3);
            }
            
            .choice-modal-buttons {
                gap: 12px;
                margin-top: 15px;
            }
            
            .choice-modal-btn {
                font-size: 1.5rem;
                padding: 18px 20px;
                min-height: 65px;
                border-width: 6px;
                border-radius: 20px;
            }
            
            .choice-modal-close {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
                top: 10px;
                right: 10px;
                border-width: 4px;
            }
            
            .choice-timer {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 15px;
                padding: 10px 18px;
                font-size: 1.2rem;
                border-width: 4px;
                width: fit-content;
                margin-left: auto;
                margin-right: auto;
            }
            
            .choice-timer i {
                font-size: 1.3rem;
            }
            
            .modal-character {
                width: 180px;
                height: 180px;
                bottom: -20px;
                right: -20px;
                opacity: 0.25;
            }
            
            .modal-bg-pattern {
                background-size: 30px 30px;
            }
            
            /* Question Area */
            .question-area {
                padding: 18px;
                margin: 15px 0;
                border-width: 5px;
            }
            
            .question-text {
                font-size: 1.5rem;
                margin-bottom: 15px;
            }
            
            .auto-advance-notice {
                padding: 12px;
                font-size: 1.1rem;
                border-width: 2px;
            }
            
            /* Loading & Status */
            .loading-spinner-ai {
                width: 60px;
                height: 60px;
                border-width: 6px;
            }
            
            .audio-gen-status {
                top: 15px;
                padding: 12px 25px;
                font-size: 1.1rem;
                border-width: 3px;
                max-width: 90vw;
            }
        }

        /* Extra Small Mobile (320px - 480px) */
        @media (max-width: 480px) {
            .story-text {
                font-size: 1.3rem;
                line-height: 1.6;
            }
            
            .chapter-title {
                font-size: 1.6rem;
            }
            
            .choice-modal-btn {
                font-size: 1.4rem;
                padding: 16px 18px;
                min-height: 60px;
            }
            
            .big-start-btn {
                font-size: 1.6rem;
                padding: 18px 25px;
            }
            
            .nav-btn {
                width: 55px;
                height: 55px;
                font-size: 1.2rem;
            }
            
            .illustration-container {
                min-height: 35vh;
                max-height: 40vh;
            }
            
            .story-text-area {
                max-height: 18vh;
            }
        }

        /* Touch Optimization */
        @media (hover: none) and (pointer: coarse) {
            /* Semua elemen clickable minimal 44x44px (Apple HIG) */
            .nav-btn,
            .ai-btn,
            .audio-btn,
            .choice-modal-btn,
            .choice-modal-close {
                min-width: 44px;
                min-height: 44px;
            }
            
            /* Increase tap area */
            .nav-btn::after,
            .ai-btn::after,
            .audio-btn::after {
                content: '';
                position: absolute;
                top: -10px;
                left: -10px;
                right: -10px;
                bottom: -10px;
            }
            
            /* Remove hover effects on touch devices */
            .nav-btn:hover,
            .ai-btn:hover,
            .choice-modal-btn:hover {
                transform: none;
            }
            
            /* Add active state instead */
            .nav-btn:active,
            .ai-btn:active,
            .choice-modal-btn:active {
                transform: scale(0.95);
                transition: transform 0.1s;
            }
        }

        /* Landscape Mobile Optimization */
        @media (max-height: 500px) and (orientation: landscape) {
            .illustration-container {
                min-height: 60vh;
            }
            
            .story-text-area {
                max-height: 30vh;
            }
            
            .page-header {
                height: 50px;
            }
            
            .chapter-title {
                font-size: 1.5rem;
            }
            
            .nav-controls {
                bottom: 10px;
            }
            
            .ai-controls {
                bottom: 60px;
                right: 5px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 87, 34, 0.7); }
            50% { transform: scale(1.1); box-shadow: 0 0 0 20px rgba(255, 87, 34, 0); }
        }

        @keyframes spinIcon {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Loading Spinner */
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            border: 10px solid #ffcc80;
            border-top: 10px solid #ff5722;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 10;
        }

        @keyframes spin {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        @keyframes celebrationPulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.15) rotate(10deg); }
        }

        /* Audio Progress Bar */
        .progress-container {
            flex: 1;
            height: 10px;
            background: #ddd;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ff9800, #ff5722);
            width: 0%;
            transition: width 0.1s;
        }

        .audio-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: #ff9800;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .audio-btn:hover {
            background: #ff5722;
            transform: scale(1.1);
        }

        /* Selection Animation */
        @keyframes selectionPop {
            0% { transform: translate(-50%, -50%) scale(0.3); opacity: 0; }
            70% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner-ai"></div>
    </div>

    <!-- Audio Generation Status -->
    <div class="audio-gen-status" id="audioGenStatus">
        <i class="fas fa-spinner fa-spin"></i> Generating audio...
    </div>

    <!-- Start Screen -->
    <div class="start-screen" id="startScreen">
        <div class="book-cover">
            <h1 class="book-title">{{ $book->title }}</h1>
            <div style="color: white; font-size: 1.8rem; margin: 30px 0; position: relative; z-index: 2;">
                <p style="margin-bottom: 20px;">🤖 Cerita Interaktif AI 🤖</p>
                <p>Untuk Anak Usia 4-10 Tahun</p>
                <p style="margin-top: 20px;">🎭 Bicara dengan Karakter Cerita!</p>
                <p style="font-size: 1.4rem; margin-top: 20px;">
                    <i class="fas fa-volume-up"></i> Audio AI Otomatis<br>
                    <i class="fas fa-microphone"></i> Voice Agent Aktif<br>
                    <i class="fas fa-robot"></i> Interaksi Cerdas
                </p>
            </div>
        </div>
        
        <!-- TOMBOL START BESAR BANGET -->
        <button class="big-start-btn" id="bigStartBtn">
            <i class="fas fa-play-circle"></i>
            MULAI CERITA AI SEKARANG!
            <i class="fas fa-play-circle"></i>
        </button>
    </div>

    <!-- Book Container -->
    <div class="book-container" id="bookContainer">
        @foreach($storyPages as $page)
        <div class="book-page" id="page-{{ $page->page_number }}" data-page="{{ $page->page_number }}">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="chapter-title">Halaman {{ $page->page_number }}</h1>
            </div>

            <!-- Content Area: Gambar Besar + Text -->
            <div class="content-area">
                <!-- AREA ILUSTRASI BESAR (70%) -->
                <div class="illustration-container" id="illustration-{{ $page->page_number }}">
                    <!-- Background, awan, dan characters akan dimuat via JavaScript -->
                </div>

                <!-- AREA TEXT DAN CHOICES (30%) -->
                <div class="text-choices-container">
                    <!-- Story Text Area -->
                    <div class="story-text-area">
                        <div class="auto-scroll-indicator" id="autoScrollIndicator-{{ $page->page_number }}">
                            <i class="fas fa-arrow-down"></i> Auto-scroll aktif
                        </div>
                        <div class="story-text" id="text-{{ $page->page_number }}">
                            {{ $page->story_text }}
                        </div>
                    </div>

                    <!-- Choice Area (TIDAK DITAMPILKAN - HANYA UNTUK BACKEND) -->
                    @if($page->choices && count($page->choices) > 0)
                    <div class="choice-area" id="choices-{{ $page->page_number }}" style="display: none;">
                        @foreach($page->choices as $choice)
                        <!-- Choices akan ditampilkan di modal -->
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Page Number -->
            <div class="page-indicator">
                {{ $page->page_number }} / {{ $storyPages->count() }}
                @if($page->question)
                <span style="margin-left: 10px; font-size: 1.2rem;">❓</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Choice Modal Overlay (FULLSCREEN) -->
    <div class="choice-modal-overlay" id="choiceModalOverlay">
        <div class="choice-modal">
            <!-- Background Pattern -->
            <div class="modal-bg-pattern"></div>
            
            <!-- Character Illustration -->
            <div class="modal-character" id="modalCharacter">
                <img src="" alt="Character" id="modalCharacterImg">
            </div>
            
            <!-- Close Button -->
            <button class="choice-modal-close" onclick="closeChoiceModal()">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Timer -->
            <div class="choice-timer" id="choiceTimer">
                <i class="fas fa-clock"></i>
                <span id="timerCount">15</span> detik
            </div>
            
            <!-- Content -->
            <div class="modal-content">
                <h2 class="choice-modal-title" id="choiceModalTitle">
                    Apa yang akan kamu lakukan selanjutnya?
                </h2>
                
                <div class="choice-modal-buttons" id="choiceModalButtons">
                    <!-- Buttons akan di-generate oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Player -->
    <div class="audio-player" id="audioPlayer">
        <button class="audio-btn" id="playBtn">
            <i class="fas fa-play"></i>
        </button>
        <button class="audio-btn" id="pauseBtn" style="display: none;">
            <i class="fas fa-pause"></i>
        </button>
        <button class="audio-btn" id="repeatBtn">
            <i class="fas fa-redo"></i>
        </button>
        <div class="progress-container" id="progressContainer">
            <div class="progress-bar" id="progressBar"></div>
        </div>
    </div>

    <!-- Question Area -->
    <div class="question-area" id="questionArea">
        <div class="question-text" id="questionText"></div>
        <div class="auto-advance-notice" id="autoAdvanceNotice">
            <i class="fas fa-forward"></i> Otomatis lanjut ke halaman berikutnya...
        </div>
    </div>

    <!-- AI Controls -->
    <div class="ai-controls">
        <button class="ai-btn" id="aiVoiceBtn" onclick="toggleVoiceRecording()" title="Bicara dengan karakter">
            <i class="fas fa-microphone"></i>
        </button>
        <button class="ai-btn" id="aiHelpBtn" onclick="showHelp()" title="Bantuan AI">
            <i class="fas fa-robot"></i>
        </button>
        <button class="ai-btn" id="aiAudioBtn" onclick="generateCurrentAudio()" title="Generate audio">
            <i class="fas fa-volume-up"></i>
        </button>
    </div>

    <!-- AI Status -->
    <div class="ai-status" id="aiStatus">
        <i class="fas fa-info-circle"></i> <span id="aiStatusText">Tekan mikrofon untuk bicara</span>
    </div>

    <!-- Navigation Controls -->
    <div class="nav-controls">
        <button class="nav-btn" id="prevBtn" onclick="goToPreviousPage()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="nav-btn" id="nextBtn" onclick="goToNextPage()">
            <i class="fas fa-arrow-right"></i>
        </button>
        <button class="nav-btn" id="homeBtn" onclick="goToHome()">
            <i class="fas fa-home"></i>
        </button>
        <button class="nav-btn" id="completeBtn" onclick="goToStoryPage()" style="display: none; background: linear-gradient(145deg, #4CAF50, #2E7D32); box-shadow: 0 20px 50px rgba(76, 175, 80, 0.8); animation: celebrationPulse 0.8s infinite;">
            <i class="fas fa-trophy"></i>
        </button>
    </div>

    <script>
        // ==================== GLOBAL VARIABLES ====================
        let currentPage = 1;
        let totalPages = {{ $storyPages->count() }};
        let audio = null;
        let isAudioPlaying = false;
        let storyPages = @json($storyPages);
        let imagesLoaded = {};
        let mediaRecorder = null;
        let audioChunks = [];
        let isRecording = false;
        let autoAdvanceTimer = null;
        let pythonAPIReady = false;
        let choiceTimerInterval = null;
        let choiceTimerSeconds = 15;
        let currentChoiceId = null;

        // API URLs
        const LARAVEL_API_URL = '{{ url("/") }}';
        const PYTHON_API_URL = 'http://localhost:5001';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📖 Cerita Rakyat AI Initialized');
            console.log('📚 Story ID:', '{{ $book->slug }}');
            console.log('📄 Total Pages:', totalPages);
            
            // Preload semua gambar
            preloadAllImages();
            
            // Setup event listeners
            setupEventListeners();
            
            // Check Python API connection
            checkPythonConnection();
        });

        function setupEventListeners() {
            // Tombol start besar
            bigStartBtn.addEventListener('click', startStory);
            
            // Audio controls
            document.getElementById('playBtn').addEventListener('click', playAudio);
            document.getElementById('pauseBtn').addEventListener('click', pauseAudio);
            document.getElementById('repeatBtn').addEventListener('click', restartAudio);
            
            // Progress bar
            document.getElementById('progressContainer').addEventListener('click', (e) => {
                if (!audio) return;
                const rect = document.getElementById('progressContainer').getBoundingClientRect();
                const percent = (e.clientX - rect.left) / rect.width;
                audio.currentTime = percent * audio.duration;
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                switch(e.key) {
                    case 'ArrowLeft':
                        goToPreviousPage();
                        break;
                    case 'ArrowRight':
                        goToNextPage();
                        break;
                    case ' ':
                        e.preventDefault();
                        if (isAudioPlaying) {
                            pauseAudio();
                        } else {
                            playAudio();
                        }
                        break;
                    case 'Home':
                        goToHome();
                        break;
                    case 'm':
                    case 'M':
                        toggleVoiceRecording();
                        break;
                    case '1': case '2': case '3': case '4': case '5': 
                    case '6': case '7': case '8': case '9':
                        const num = parseInt(e.key);
                        if (num <= totalPages) {
                            goToPage(num);
                        }
                        break;
                }
            });
        }

        function checkPythonConnection() {
            // Cek koneksi ke Python API
            fetch(`${PYTHON_API_URL}/`)
                .then(response => {
                    if (response.ok) {
                        pythonAPIReady = true;
                        console.log('✅ Python API Connected');
                        showAIStatus('Python AI API terhubung!', 'success');
                    } else {
                        pythonAPIReady = false;
                        console.warn('⚠️ Python API Not Available');
                        showAIStatus('Python API tidak tersedia, audio akan di-generate', 'warning');
                    }
                })
                .catch(error => {
                    pythonAPIReady = false;
                    console.error('❌ Python API Connection Error:', error);
                    showAIStatus('Python API tidak terhubung', 'error');
                    document.getElementById('aiVoiceBtn').classList.add('ai-btn-disabled');
                    document.getElementById('aiAudioBtn').classList.add('ai-btn-disabled');
                });
        }

        // ==================== STORY FLOW FUNCTIONS ====================

        function startStory() {
            // Animasi tombol start
            bigStartBtn.style.animation = 'none';
            bigStartBtn.style.transform = 'translateX(-50%) scale(1.5)';
            bigStartBtn.style.opacity = '0';
            
            // Tunggu animasi selesai
            setTimeout(() => {
                startScreen.classList.add('hidden');
                
                // Animasi buku terbuka
                setTimeout(() => {
                    bookContainer.classList.add('open');
                    
                    // Tampilkan audio player
                    setTimeout(() => {
                        audioPlayer.style.display = 'flex';
                        setTimeout(() => {
                            audioPlayer.classList.add('show');
                        }, 100);
                    }, 500);
                    
                    // Load halaman pertama
                    setTimeout(() => {
                        goToPage(1);
                    }, 1000);
                }, 500);
            }, 500);
        }

        function goToPage(pageNumber) {
            if (pageNumber < 1 || pageNumber > totalPages) return;
            
            // Clear auto-advance timer
            if (autoAdvanceTimer) {
                clearTimeout(autoAdvanceTimer);
                autoAdvanceTimer = null;
            }
            
            // Close modal jika terbuka
            const modalOverlay = document.getElementById('choiceModalOverlay');
            if (modalOverlay.classList.contains('show')) {
                modalOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            // Stop choice timer
            if (choiceTimerInterval) {
                clearInterval(choiceTimerInterval);
                choiceTimerInterval = null;
            }
            
            // Reset timer
            choiceTimerSeconds = 15;
            document.getElementById('timerCount').textContent = choiceTimerSeconds;
            
            // Stop audio sebelumnya
            if (audio) {
                audio.pause();
                audio = null;
                isAudioPlaying = false;
                document.getElementById('playBtn').style.display = 'block';
                document.getElementById('pauseBtn').style.display = 'none';
            }
            
            // Hide question area
            document.getElementById('questionArea').classList.remove('show');
            document.getElementById('autoAdvanceNotice').style.display = 'none';
            
            // Check if this page was reached via choice (ending page)
            const isEnding = sessionStorage.getItem('reachedByChoice') === 'true';
            
            // Hide/show navigation buttons based on ending status
            if (isEnding) {
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('prevBtn').style.display = 'none';
                document.getElementById('homeBtn').style.display = 'none';
                document.getElementById('completeBtn').style.display = 'block';
                console.log('🎬 Ending page detected - showing complete button');
            } else {
                document.getElementById('nextBtn').style.display = 'block';
                document.getElementById('prevBtn').style.display = 'block';
                document.getElementById('homeBtn').style.display = 'block';
                document.getElementById('completeBtn').style.display = 'none';
            }
            
            // Sembunyikan halaman saat ini
            document.querySelectorAll('.book-page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Update halaman saat ini
            currentPage = pageNumber;
            
            // Tampilkan halaman baru
            const newPage = document.getElementById(`page-${pageNumber}`);
            newPage.classList.add('active');
            
            // Load konten halaman
            loadPageContent(pageNumber);
            
            // Update tombol navigasi
            updateNavigationButtons();
            
            // Load audio untuk halaman ini
            setTimeout(() => {
                loadAudioForPage(pageNumber);
            }, 300);
            
            // Check jika halaman ini ada pertanyaan atau pilihan
            checkPageQuestion(pageNumber);
            
            // Update AI status
            showAIStatus(`Halaman ${pageNumber} dari ${totalPages}`, 'info');
        }

        function loadPageContent(pageNumber) {
            const page = storyPages.find(p => p.page_number == pageNumber);
            if (!page) return;
            
            const illustrationContainer = document.getElementById(`illustration-${pageNumber}`);
            const storyText = document.getElementById(`text-${pageNumber}`);
            
            // Clear container ilustrasi
            illustrationContainer.innerHTML = '';
            
            // Tambahkan matahari kecil di TENGAH ATAS
            const sun = document.createElement('div');
            sun.className = 'sun';
            illustrationContainer.appendChild(sun);
            
            // Load background image - FULL COVER
            if (imagesLoaded[pageNumber]?.background) {
                const bgImg = document.createElement('img');
                bgImg.src = imagesLoaded[pageNumber].background.element.src;
                bgImg.className = 'background-image';
                bgImg.alt = `Background halaman ${pageNumber}`;
                illustrationContainer.appendChild(bgImg);
                
                // Animasi background muncul
                setTimeout(() => {
                    bgImg.style.opacity = '1';
                }, 100);
            } else {
                // Fallback color jika tidak ada background
                illustrationContainer.style.background = 'linear-gradient(135deg, #87CEEB, #1E90FF)';
            }
            
            // Tambahkan efek awan bergerak - HANYA DI ATAS SAJA
            createClouds(illustrationContainer);
            
            // Create scene container untuk characters dan objects
            const sceneContainer = document.createElement('div');
            sceneContainer.className = 'scene-container';
            illustrationContainer.appendChild(sceneContainer);
            
            // Load characters dan objects - MENGGUNAKAN DATA DARI DATABASE
            loadSceneElements(sceneContainer, pageNumber);
            
            // Animate text appearance
            storyText.classList.remove('show');
            storyText.style.opacity = '0';
            storyText.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                storyText.classList.add('show');
                storyText.style.opacity = '1';
                storyText.style.transform = 'translateY(0)';
                
                // Setup auto-scroll
                setupAutoScroll(pageNumber);
                
                // Auto-scroll ke bawah di story text area
                const storyTextArea = storyText.parentElement;
                setTimeout(() => {
                    storyTextArea.scrollTop = storyTextArea.scrollHeight;
                }, 100);
            }, 500);
        }

        function checkPageQuestion(pageNumber) {
            const page = storyPages.find(p => p.page_number == pageNumber);
            if (!page) return;
            
            // JANGAN TAMPILKAN MODAL SAAT INI
            // Modal akan ditampilkan setelah audio selesai di onAudioEnded()
            // Hanya log untuk debugging
            console.log('Page has choices:', page.choices?.length > 0);
            console.log('Page has question:', page.question?.trim() !== '');
        }

        function loadAudioForPage(pageNumber) {
            const page = storyPages.find(p => p.page_number == pageNumber);
            if (!page) return;
            
            // Check if audio exists
            if (page.audio_path) {
                audioPlayer.style.opacity = '1';
                audio = new Audio(`{{ asset('storage') }}/${page.audio_path}`);
                setupAudioEvents();
                console.log('✅ Audio loaded from storage:', page.audio_path);
            } else {
                // Generate audio if not exists
                console.log('⏳ Generating audio for page:', pageNumber);
                if (pythonAPIReady) {
                    generatePageAudioWithPython(pageNumber);
                } else {
                    generatePageAudioWithLaravel(pageNumber);
                }
            }
        }

        function setupAudioEvents() {
            if (!audio) return;
            
            audio.addEventListener('timeupdate', updateProgressBar);
            audio.addEventListener('ended', onAudioEnded);
            audio.addEventListener('loadedmetadata', () => {
                document.getElementById('progressBar').style.width = '0%';
            });
            
            // Auto-play untuk halaman cerita
            setTimeout(() => {
                playAudio();
            }, 500);
        }

        function playAudio() {
            if (!audio) return;
            
            const playPromise = audio.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    isAudioPlaying = true;
                    document.getElementById('playBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'block';
                }).catch(error => {
                    console.log('Autoplay prevented:', error);
                    isAudioPlaying = false;
                    document.getElementById('playBtn').style.display = 'block';
                    document.getElementById('pauseBtn').style.display = 'none';
                });
            }
        }

        function pauseAudio() {
            if (!audio) return;
            
            audio.pause();
            isAudioPlaying = false;
            document.getElementById('playBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
        }

        function restartAudio() {
            if (!audio) return;
            
            audio.currentTime = 0;
            if (!isAudioPlaying) {
                playAudio();
            }
        }

        function updateProgressBar() {
            if (!audio) return;
            
            const percent = (audio.currentTime / audio.duration) * 100;
            document.getElementById('progressBar').style.width = `${percent}%`;
        }

        function onAudioEnded() {
            isAudioPlaying = false;
            document.getElementById('playBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
            
            console.log('🎵 Audio ended, checking for choices/questions...');
            
            // Check if current page has choices
            const page = storyPages.find(p => p.page_number == currentPage);
            if (page && page.choices && page.choices.length > 0) {
                // BARU TAMPILKAN MODAL setelah audio selesai
                console.log('📋 Showing choice modal...');
                setTimeout(() => {
                    showChoiceModal(currentPage);
                }, 1000);
            } else if (page && page.question && page.question.trim() !== '') {
                // BARU TAMPILKAN MODAL dengan pertanyaan
                console.log('❓ Showing question modal...');
                setTimeout(() => {
                    showChoiceModal(currentPage);
                }, 1000);
            } else {
                // Auto-proceed jika tidak ada pilihan
                console.log('⏭️ Auto-proceeding to next page...');
                setTimeout(() => {
                    if (currentPage < totalPages) {
                        goToNextPage();
                    } else {
                        showCompleteButton();
                    }
                }, 1500);
            }
        }

        /* ========== MODAL CHOICE FULLSCREEN ========== */
        function showChoiceModal(pageNumber) {
            const page = storyPages.find(p => p.page_number == pageNumber);
            if (!page || !page.choices || page.choices.length === 0) return;
            
            console.log('🎬 Opening choice modal for page:', pageNumber);
            
            // Set modal title
            const modalTitle = document.getElementById('choiceModalTitle');
            if (page.question && page.question.trim() !== '') {
                modalTitle.textContent = page.question;
            } else {
                modalTitle.textContent = "Apa yang akan kamu lakukan selanjutnya?";
            }
            
            // Clear previous buttons
            const modalButtons = document.getElementById('choiceModalButtons');
            modalButtons.innerHTML = '';
            
            // Create choice buttons
            page.choices.forEach((choice, index) => {
                const button = document.createElement('button');
                button.className = 'choice-modal-btn';
                button.innerHTML = `
                    <span class="choice-number">${index + 1}.</span>
                    <span class="choice-text">${choice.choice_text}</span>
                    <span class="choice-arrow">→</span>
                `;
                
                // Add hover effect with sound
                button.addEventListener('mouseenter', () => {
                    playHoverSound();
                });
                
                button.addEventListener('click', () => {
                    playClickSound();
                    selectChoiceModal(choice.next_page_number, choice.choice_text, choice.id);
                });
                
                modalButtons.appendChild(button);
            });
            
            // Set character image if available
            setModalCharacterImage(pageNumber);
            
            // Start countdown timer
            startChoiceTimer();
            
            // Show modal - SEKARANG SUDAH AUDIO SELESAI
            const modalOverlay = document.getElementById('choiceModalOverlay');
            modalOverlay.classList.add('show');
            
            // Disable background interactions
            document.body.style.overflow = 'hidden';
            
            // Generate TTS untuk pertanyaan jika ada
            if (page.question && page.question.trim() !== '') {
                console.log('🔊 Generating question TTS...');
                generateQuestionTTS(page.question);
            } else {
                console.log('🎤 Speaking choice prompt...');
                speakText("Pilih salah satu pilihan berikut!");
            }
            
            currentChoiceId = page.id;
        }

        function closeChoiceModal() {
            const modalOverlay = document.getElementById('choiceModalOverlay');
            modalOverlay.classList.remove('show');
            document.body.style.overflow = '';
            
            // Stop timer
            if (choiceTimerInterval) {
                clearInterval(choiceTimerInterval);
                choiceTimerInterval = null;
            }
            
            // Reset timer
            choiceTimerSeconds = 15;
            document.getElementById('timerCount').textContent = choiceTimerSeconds;
            
            // Jika modal ditutup tanpa pilihan, auto-select pilihan pertama
            if (currentChoiceId) {
                const page = storyPages.find(p => p.id == currentChoiceId);
                if (page && page.choices && page.choices.length > 0) {
                    const firstChoice = page.choices[0];
                    selectChoiceModal(firstChoice.next_page_number, firstChoice.choice_text, firstChoice.id);
                }
            }
        }

        function startChoiceTimer() {
            choiceTimerSeconds = 15;
            const timerElement = document.getElementById('timerCount');
            timerElement.textContent = choiceTimerSeconds;
            
            if (choiceTimerInterval) {
                clearInterval(choiceTimerInterval);
            }
            
            choiceTimerInterval = setInterval(() => {
                choiceTimerSeconds--;
                timerElement.textContent = choiceTimerSeconds;
                
                // Change color based on time
                const timerDiv = document.getElementById('choiceTimer');
                if (choiceTimerSeconds <= 5) {
                    timerDiv.style.background = 'linear-gradient(145deg, #ff5722, #e64a19)';
                    timerDiv.style.animation = 'pulseTimer 0.5s infinite';
                } else if (choiceTimerSeconds <= 10) {
                    timerDiv.style.background = 'linear-gradient(145deg, #ff9800, #f57c00)';
                }
                
                // Auto-select first choice when time runs out
                if (choiceTimerSeconds <= 0) {
                    clearInterval(choiceTimerInterval);
                    autoSelectChoice();
                }
            }, 1000);
        }

        function autoSelectChoice() {
            if (!currentChoiceId) return;
            
            const page = storyPages.find(p => p.id == currentChoiceId);
            if (page && page.choices && page.choices.length > 0) {
                const firstChoice = page.choices[0];
                selectChoiceModal(firstChoice.next_page_number, firstChoice.choice_text, firstChoice.id);
            }
        }

        function selectChoiceModal(nextPage, choiceText, choiceId) {
            // Close modal first
            const modalOverlay = document.getElementById('choiceModalOverlay');
            modalOverlay.classList.remove('show');
            document.body.style.overflow = '';
            
            // Stop timer
            if (choiceTimerInterval) {
                clearInterval(choiceTimerInterval);
                choiceTimerInterval = null;
            }
            
            console.log('✅ Choice selected:', choiceText);
            
            // Animate selection
            playSelectionAnimation(choiceText);
            
            // Submit choice dengan delay untuk animasi
            setTimeout(() => {
                submitChoice(choiceText, choiceId, nextPage);
            }, 500);
        }

        function playSelectionAnimation(choiceText) {
            // Create selection effect
            const selectionDiv = document.createElement('div');
            selectionDiv.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: linear-gradient(145deg, rgba(76, 175, 80, 0.95), rgba(46, 125, 50, 0.95));
                color: white;
                padding: 30px 50px;
                border-radius: 25px;
                font-size: 2.5rem;
                text-align: center;
                z-index: 10000;
                box-shadow: 0 30px 60px rgba(0,0,0,0.5);
                border: 10px solid white;
                animation: selectionPop 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                font-family: 'Comic Neue', cursive;
            `;
            
            selectionDiv.innerHTML = `
                <div style="margin-bottom: 15px;">🎉 PILIHAN KAMU 🎉</div>
                <div style="font-size: 2rem; background: rgba(255,255,255,0.2); padding: 15px; border-radius: 15px;">
                    "${choiceText}"
                </div>
                <div style="margin-top: 15px; font-size: 1.8rem;">
                    <i class="fas fa-spinner fa-spin"></i> Melanjutkan cerita...
                </div>
            `;
            
            document.body.appendChild(selectionDiv);
            
            // Remove after animation
            setTimeout(() => {
                selectionDiv.style.opacity = '0';
                selectionDiv.style.transform = 'translate(-50%, -50%) scale(0.5)';
                setTimeout(() => {
                    if (selectionDiv.parentNode) {
                        selectionDiv.parentNode.removeChild(selectionDiv);
                    }
                }, 500);
            }, 2000);
        }

        function playHoverSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 523.25;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                
                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.2);
            } catch (e) {
                console.log('Audio context not available');
            }
        }

        function playClickSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 659.25;
                oscillator.type = 'triangle';
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start();
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (e) {
                console.log('Audio context not available');
            }
        }

        function setModalCharacterImage(pageNumber) {
            const images = imagesLoaded[pageNumber];
            const modalCharacterImg = document.getElementById('modalCharacterImg');
            
            if (images && images.characters && images.characters.length > 0) {
                const mainCharacter = images.characters[0];
                if (mainCharacter && mainCharacter.element) {
                    modalCharacterImg.src = mainCharacter.element.src;
                    modalCharacterImg.style.opacity = '0.4';
                }
            } else {
                modalCharacterImg.src = 'https://cdn-icons-png.flaticon.com/512/4323/4323004.png';
                modalCharacterImg.style.opacity = '0.3';
            }
        }

        function submitChoice(choiceText, choiceId, nextPage) {
            // Mark bahwa halaman berikutnya dicapai via choice (ini adalah ending)
            sessionStorage.setItem('reachedByChoice', 'true');
            
            // Kirim pilihan ke backend
            fetch(`${LARAVEL_API_URL}/cerita-ai/{{ $book->slug }}/submit-choice`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    choice_id: choiceId,
                    choice_text: choiceText,
                    page_number: currentPage,
                    next_page: nextPage,
                    timestamp: new Date().toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('✅ Choice submitted to server:', data);
                
                // Generate TTS untuk pilihan yang dipilih
                console.log('🔊 Generating choice TTS...');
                generateChoiceTTS(choiceText, () => {
                    // Setelah TTS selesai, baru beralih ke halaman berikutnya
                    console.log('⏭️ Moving to next page (ENDING):', nextPage);
                    setTimeout(() => {
                        goToPage(nextPage);
                    }, 500);
                });
            })
            .catch(error => {
                console.error('❌ Error submitting choice:', error);
                generateChoiceTTS(choiceText, () => {
                    setTimeout(() => {
                        goToPage(nextPage);
                    }, 500);
                });
            });
        }

        // ==================== AUDIO FUNCTIONS ====================

        function loadAudioForPage(pageNumber) {
            const page = storyPages.find(p => p.page_number == pageNumber);
            if (!page) return;
            
            // Check if audio exists
            if (page.audio_path) {
                audioPlayer.style.opacity = '1';
                audio = new Audio(`{{ asset('storage') }}/${page.audio_path}`);
                setupAudioEvents();
                console.log('✅ Audio loaded from storage:', page.audio_path);
            } else {
                // Generate audio if not exists
                console.log('⏳ Generating audio for page:', pageNumber);
                if (pythonAPIReady) {
                    generatePageAudioWithPython(pageNumber);
                } else {
                    generatePageAudioWithLaravel(pageNumber);
                }
            }
        }

        function generatePageAudioWithPython(pageNumber) {
            const page = storyPages.find(p => p.page_number == pageNumber);
            if (!page) return;
            
            showLoading('Membuat audio dengan AI...');
            
            fetch(`${PYTHON_API_URL}/api/story/audio`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    story_id: '{{ $book->slug }}',
                    page_number: pageNumber,
                    story_text: page.story_text,
                    age_group: '5-7',
                    save_to_cache: true
                })
            })
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('Python API error');
            })
            .then(blob => {
                saveAudioToServer(blob, pageNumber);
            })
            .catch(error => {
                console.error('Python API error:', error);
                generatePageAudioWithLaravel(pageNumber);
            })
            .finally(() => {
                hideLoading();
            });
        }

        function generatePageAudioWithLaravel(pageNumber) {
            showLoading('Membuat audio...');
            
            fetch(`${LARAVEL_API_URL}/cerita-ai/{{ $book->slug }}/generate-audio`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    page_number: pageNumber,
                    force_regenerate: false
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success && data.audio_url) {
                    showAIStatus('✅ Audio berhasil dibuat!', 'success');
                    
                    const pageIndex = storyPages.findIndex(p => p.page_number == pageNumber);
                    if (pageIndex !== -1) {
                        storyPages[pageIndex].audio_path = data.audio_url.replace('{{ asset('storage') }}/', '');
                    }
                    
                    audioPlayer.style.opacity = '1';
                    audio = new Audio(data.audio_url);
                    setupAudioEvents();
                } else {
                    showAIStatus('❌ Gagal membuat audio', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error generating audio:', error);
                showAIStatus('❌ Error membuat audio', 'error');
            });
        }

        function saveAudioToServer(audioBlob, pageNumber) {
            const formData = new FormData();
            formData.append('audio', audioBlob, `page_${pageNumber}.mp3`);
            formData.append('page_number', pageNumber);
            formData.append('story_id', '{{ $book->slug }}');
            
            fetch(`${LARAVEL_API_URL}/cerita-ai/{{ $book->slug }}/save-audio`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const pageIndex = storyPages.findIndex(p => p.page_number == pageNumber);
                    if (pageIndex !== -1) {
                        storyPages[pageIndex].audio_path = data.audio_path;
                    }
                    
                    audioPlayer.style.opacity = '1';
                    audio = new Audio(`{{ asset('storage') }}/${data.audio_path}`);
                    setupAudioEvents();
                    
                    showAIStatus('✅ Audio disimpan!', 'success');
                }
            })
            .catch(error => {
                console.error('Error saving audio:', error);
            });
        }

        function generateQuestionTTS(question) {
            if (!pythonAPIReady) {
                speakText(question);
                return;
            }
            
            fetch(`${PYTHON_API_URL}/api/story/question`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    question: question,
                    age_group: '5-7'
                })
            })
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('Question TTS failed');
            })
            .then(blob => {
                const questionAudio = new Audio(URL.createObjectURL(blob));
                questionAudio.play();
            })
            .catch(error => {
                console.error('Error generating question TTS:', error);
                speakText(question);
            });
        }

        function generateChoiceTTS(choiceText, callback) {
            if (!pythonAPIReady) {
                speakText(choiceText);
                if (callback) callback();
                return;
            }
            
            fetch(`${PYTHON_API_URL}/api/story/choice`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    choice_text: choiceText,
                    age_group: '5-7'
                })
            })
            .then(response => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error('Choice TTS failed');
            })
            .then(blob => {
                const choiceAudio = new Audio(URL.createObjectURL(blob));
                choiceAudio.play();
                
                if (callback) {
                    choiceAudio.onended = callback;
                }
            })
            .catch(error => {
                console.error('Error generating choice TTS:', error);
                speakText(choiceText);
                if (callback) callback();
            });
        }

        function generateCurrentAudio() {
            if (pythonAPIReady) {
                generatePageAudioWithPython(currentPage);
            } else {
                generatePageAudioWithLaravel(currentPage);
            }
        }

        function setupAudioEvents() {
            if (!audio) return;
            
            audio.addEventListener('timeupdate', updateProgressBar);
            audio.addEventListener('ended', onAudioEnded);
            audio.addEventListener('loadedmetadata', () => {
                document.getElementById('progressBar').style.width = '0%';
            });
            
            // Auto-play untuk halaman cerita
            setTimeout(() => {
                playAudio();
            }, 500);
        }

        function playAudio() {
            if (!audio) return;
            
            const playPromise = audio.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    isAudioPlaying = true;
                    document.getElementById('playBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'block';
                }).catch(error => {
                    console.log('Autoplay prevented:', error);
                    isAudioPlaying = false;
                    document.getElementById('playBtn').style.display = 'block';
                    document.getElementById('pauseBtn').style.display = 'none';
                });
            }
        }

        function pauseAudio() {
            if (!audio) return;
            
            audio.pause();
            isAudioPlaying = false;
            document.getElementById('playBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
        }

        function restartAudio() {
            if (!audio) return;
            
            audio.currentTime = 0;
            if (!isAudioPlaying) {
                playAudio();
            }
        }

        function updateProgressBar() {
            if (!audio) return;
            
            const percent = (audio.currentTime / audio.duration) * 100;
            document.getElementById('progressBar').style.width = `${percent}%`;
        }

        function onAudioEnded() {
            isAudioPlaying = false;
            document.getElementById('playBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
            
            console.log('🎵 Audio ended, checking for choices/questions...');
            
            // Check if ini adalah ending page (reached via choice)
            const wasReachedByChoice = sessionStorage.getItem('reachedByChoice') === 'true';
            if (wasReachedByChoice) {
                console.log('🎬 Story ended at this page (reached via choice)!');
                showCompleteButton();
                sessionStorage.removeItem('reachedByChoice');
                return;
            }
            
            // Check if current page has choices
            const page = storyPages.find(p => p.page_number == currentPage);
            if (page && page.choices && page.choices.length > 0) {
                // BARU TAMPILKAN MODAL setelah audio selesai
                console.log('📋 Showing choice modal...');
                setTimeout(() => {
                    showChoiceModal(currentPage);
                }, 1000);
            } else if (page && page.question && page.question.trim() !== '') {
                // BARU TAMPILKAN MODAL dengan pertanyaan
                console.log('❓ Showing question modal...');
                setTimeout(() => {
                    showChoiceModal(currentPage);
                }, 1000);
            } else {
                // Auto-proceed jika tidak ada pilihan
                console.log('⏭️ Auto-proceeding to next page...');
                setTimeout(() => {
                    if (currentPage < totalPages) {
                        goToNextPage();
                    } else {
                        showCompleteButton();
                    }
                }, 1500);
            }
        }

        // ==================== VOICE AGENT FUNCTIONS ====================

        async function toggleVoiceRecording() {
            if (!pythonAPIReady) {
                showAIStatus('Python API tidak tersedia untuk voice agent', 'error');
                return;
            }
            
            if (isRecording) {
                stopRecording();
            } else {
                await startRecording();
            }
        }

        async function startRecording() {
            try {
                // Coba dengan constraint ideal, fallback ke basic jika gagal
                let stream;
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        audio: {
                            echoCancellation: {ideal: true},
                            noiseSuppression: {ideal: true},
                            autoGainControl: {ideal: true}
                        }
                    });
                } catch (e) {
                    console.warn('🔊 Ideal constraints tidak support, mencoba basic...');
                    // Fallback ke constraint minimal
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        audio: true
                    });
                }
                
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = event => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    await sendVoiceToAI(audioBlob);
                    
                    stream.getTracks().forEach(track => track.stop());
                };

                mediaRecorder.start();
                isRecording = true;
                
                // Update UI
                document.getElementById('aiVoiceBtn').classList.add('listening');
                showAIStatus('🎤 Mendengarkan... Bicara sekarang!', 'info');
                document.getElementById('aiVoiceBtn').innerHTML = '<i class="fas fa-stop"></i>';
                
                // Auto-stop after 10 seconds
                setTimeout(() => {
                    if (isRecording) {
                        stopRecording();
                    }
                }, 10000);

            } catch (error) {
                console.error('❌ Recording error:', error.name, error.message);
                
                // Handle berbagai error code
                if (error.name === 'NotAllowedError') {
                    showAIStatus('❌ Permission ditolak. Izinkan akses mikrofon di browser settings', 'error');
                } else if (error.name === 'NotFoundError') {
                    showAIStatus('❌ Mikrofon tidak ditemukan di device', 'error');
                } else if (error.name === 'NotSecureError') {
                    showAIStatus('❌ Hanya HTTPS atau localhost yang support', 'error');
                } else if (error.name === 'OverconstrainedError') {
                    showAIStatus('❌ Browser tidak support konfigurasi audio ini', 'error');
                } else {
                    showAIStatus('❌ Tidak dapat mengakses mikrofon: ' + error.name, 'error');
                }
            }
        }

        function stopRecording() {
            if (mediaRecorder && isRecording) {
                mediaRecorder.stop();
                isRecording = false;
                
                // Update UI
                document.getElementById('aiVoiceBtn').classList.remove('listening');
                showAIStatus('⚡ Memproses suara...', 'info');
                document.getElementById('aiVoiceBtn').innerHTML = '<i class="fas fa-microphone"></i>';
            }
        }

        async function sendVoiceToAI(audioBlob) {
            showLoading('Memproses suara dengan AI...');
            
            const formData = new FormData();
            formData.append('audio', audioBlob, 'voice.wav');
            formData.append('story_context', getCurrentStoryContext());
            formData.append('current_page', currentPage);
            formData.append('story_id', '{{ $book->slug }}');
            formData.append('age_group', '5-7');
            formData.append('character_name', '{{ $book->title }}');
            
            try {
                const response = await fetch(`${PYTHON_API_URL}/api/story/voice`, {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const audioBlob = await response.blob();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    // Play AI response audio
                    const aiAudio = new Audio(audioUrl);
                    aiAudio.play();
                    
                    // Get response text from headers
                    const responseText = response.headers.get('X-AI-Response');
                    const sttText = response.headers.get('X-STT-Text');
                    
                    if (responseText) {
                        showAIStatus(`🤖 ${responseText.substring(0, 50)}...`, 'success');
                        submitVoiceInteraction(sttText, responseText);
                    }
                    
                } else {
                    throw new Error('Voice AI error');
                }
                
            } catch (error) {
                console.error('Error sending voice to AI:', error);
                showAIStatus('❌ Gagal memproses suara', 'error');
            } finally {
                hideLoading();
                setTimeout(() => {
                    showAIStatus('Tekan mikrofon untuk bicara lagi', 'info');
                }, 3000);
            }
        }

        function getCurrentStoryContext() {
            const page = storyPages.find(p => p.page_number == currentPage);
            return page ? page.story_text : '';
        }

        function submitVoiceInteraction(userText, aiResponse) {
            fetch(`${LARAVEL_API_URL}/cerita-ai/{{ $book->slug }}/submit-voice`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    user_text: userText,
                    ai_response: aiResponse,
                    page_number: currentPage,
                    timestamp: new Date().toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Voice interaction submitted:', data);
            })
            .catch(error => {
                console.error('Error submitting voice interaction:', error);
            });
        }

        // ==================== HELPER FUNCTIONS ====================

        function goToNextPage() {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            } else {
                completeStory();
            }
        }

        function goToPreviousPage() {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        }

        function goToHome() {
            const confirmHome = confirm('Kembali ke halaman awal cerita?');
            if (confirmHome) {
                goToPage(1);
            }
        }

        function goToStoryPage() {
            // Navigasi ke halaman cerita rakyat
            window.location.href = '{{ url("/calista/cerita-rakyat-calista") }}';
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;
            
            const currentPageEl = document.getElementById(`page-${currentPage}`);
            const choicesArea = currentPageEl.querySelector('.choice-area');
            const questionArea = document.getElementById('questionArea');
            
            if ((choicesArea && choicesArea.classList.contains('show')) || 
                questionArea.classList.contains('show')) {
                nextBtn.style.visibility = 'hidden';
            } else {
                nextBtn.style.visibility = 'visible';
            }
        }

        function showCompleteButton() {
            // Show complete button
            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('homeBtn').style.display = 'none';
            document.getElementById('completeBtn').style.display = 'block';
            
            showAIStatus('🎉 Cerita selesai! Kamu hebat!', 'success');
            
            const congratsText = `Hore! Kamu telah menyelesaikan cerita {{ $book->title }}. Hebat sekali!`;
            speakText(congratsText);
            
            setTimeout(() => {
                alert('🎉 CERITA SELESAI! 🎉\n\nKamu telah menyelesaikan "{{ $book->title }}"!\n\nTerima kasih telah membaca!');
            }, 2000);
        }

        function showAIStatus(message, type = 'info') {
            const statusDiv = document.getElementById('aiStatus');
            const statusText = document.getElementById('aiStatusText');
            
            statusText.textContent = message;
            
            if (type === 'success') {
                statusDiv.style.borderColor = '#4CAF50';
                statusDiv.style.color = '#2E7D32';
            } else if (type === 'error') {
                statusDiv.style.borderColor = '#F44336';
                statusDiv.style.color = '#C62828';
            } else if (type === 'warning') {
                statusDiv.style.borderColor = '#FF9800';
                statusDiv.style.color = '#EF6C00';
            } else {
                statusDiv.style.borderColor = '#2196F3';
                statusDiv.style.color = '#1565C0';
            }
            
            statusDiv.classList.add('show');
            
            setTimeout(() => {
                statusDiv.classList.remove('show');
            }, 5000);
        }

        function showLoading(message = 'Loading...') {
            document.getElementById('loadingOverlay').style.display = 'flex';
            if (message) {
                document.getElementById('audioGenStatus').textContent = message;
                document.getElementById('audioGenStatus').classList.add('show');
            }
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
            document.getElementById('audioGenStatus').classList.remove('show');
        }

        function speakText(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1.2;
                speechSynthesis.speak(utterance);
            }
        }

        function showHelp() {
            const helpMessage = `
Gunakan fitur-fitur berikut:
🎤 Tekan tombol mikrofon untuk bicara dengan karakter
📖 Pilih pilihan di modal untuk melanjutkan cerita
◀️ ▶️ Tombol panah untuk navigasi halaman
🏠 Tombol rumah untuk kembali ke halaman awal
🔊 Audio akan diputar otomatis

Tekan tombol angka 1-9 untuk langsung ke halaman tertentu!
`;
            alert(helpMessage);
        }

        // ==================== IMAGE FUNCTIONS ====================

        function preloadAllImages() {
            const loadingSpinner = document.createElement('div');
            loadingSpinner.className = 'loading-spinner';
            bookContainer.appendChild(loadingSpinner);
            
            storyPages.forEach(page => {
                imagesLoaded[page.page_number] = {
                    background: null,
                    characters: [],
                    objects: []
                };
                
                if (page.images && page.images.length > 0) {
                    page.images.forEach(img => {
                        const image = new Image();
                        image.src = "{{ asset('storage') }}/" + img.image_path;
                        
                        image.onload = () => {
                            if (img.type === 'background') {
                                imagesLoaded[page.page_number].background = {
                                    element: image,
                                    data: img
                                };
                            } else if (img.type === 'character') {
                                imagesLoaded[page.page_number].characters.push({
                                    element: image,
                                    data: img
                                });
                            } else if (img.type === 'object') {
                                imagesLoaded[page.page_number].objects.push({
                                    element: image,
                                    data: img
                                });
                            }
                        };
                        
                        image.onerror = () => {
                            console.error(`Failed to load image for page ${page.page_number}:`, image.src);
                            image.src = 'https://placehold.co/800x600/87CEEB/FFFFFF?text=Gambar+Tidak+Ditemukan&font=comic-sans';
                        };
                    });
                }
            });
            
            setTimeout(() => {
                loadingSpinner.style.opacity = '0';
                setTimeout(() => {
                    if (loadingSpinner.parentNode) {
                        loadingSpinner.parentNode.removeChild(loadingSpinner);
                    }
                }, 500);
            }, 2000);
        }

        function loadSceneElements(sceneContainer, pageNumber) {
            const images = imagesLoaded[pageNumber];
            if (!images) return;
            
            const allElements = [];
            
            if (images.characters && images.characters.length > 0) {
                images.characters.forEach((char, index) => {
                    allElements.push({
                        type: 'character',
                        data: char,
                        order: char.data.order || (index + 1)
                    });
                });
            }
            
            if (images.objects && images.objects.length > 0) {
                images.objects.forEach((obj, index) => {
                    allElements.push({
                        type: 'object',
                        data: obj,
                        order: obj.data.order || (index + 100)
                    });
                });
            }
            
            allElements.sort((a, b) => a.order - b.order);
            
            allElements.forEach((element, index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'image-wrapper';
                
                const imgData = element.data.data;
                
                if (imgData.position_x !== null && imgData.position_x !== undefined) {
                    wrapper.style.setProperty('--pos-x', `${imgData.position_x}%`);
                    
                    if (imgData.position_x < 33) {
                        wrapper.classList.add('position-left');
                    } else if (imgData.position_x > 66) {
                        wrapper.classList.add('position-right');
                    } else {
                        wrapper.classList.add('position-center');
                    }
                } else {
                    const positions = ['position-left', 'position-center', 'position-right'];
                    wrapper.classList.add(positions[index % 3]);
                }
                
                if (imgData.position_y !== null && imgData.position_y !== undefined) {
                    wrapper.style.setProperty('--pos-y', `${imgData.position_y}%`);
                    
                    if (imgData.position_y < 33) {
                        wrapper.classList.add('position-top');
                    } else if (imgData.position_y > 66) {
                        wrapper.classList.add('position-bottom');
                    } else {
                        wrapper.classList.add('position-middle');
                    }
                } else {
                    if (element.type === 'character') {
                        wrapper.classList.add('position-bottom');
                    } else {
                        wrapper.classList.add('position-middle');
                    }
                }
                
                let sizeClass = 'size-medium';
                if (element.type === 'character' && element.order === 1) {
                    sizeClass = 'size-large';
                } else if (element.type === 'object') {
                    sizeClass = 'size-small';
                }
                wrapper.classList.add(sizeClass);
                
                wrapper.style.zIndex = element.order;
                
                const img = document.createElement('img');
                img.src = element.data.element.src;
                img.className = 'scene-image';
                img.alt = `${element.type} ${index + 1}`;
                
                const entranceAnimations = ['enter-left', 'enter-right', 'enter-bottom', 'enter-fade'];
                let animationClass = 'enter-fade';
                
                if (imgData.animation && entranceAnimations.includes(imgData.animation)) {
                    animationClass = imgData.animation;
                } else {
                    animationClass = entranceAnimations[index % entranceAnimations.length];
                }
                
                wrapper.classList.add(animationClass);
                
                wrapper.appendChild(img);
                sceneContainer.appendChild(wrapper);
                
                setTimeout(() => {
                    wrapper.classList.add('show');
                    
                    if (element.type === 'character' && element.order === 1) {
                        img.style.filter = 'drop-shadow(0 25px 40px rgba(0,0,0,0.6)) brightness(1.05)';
                    }
                    
                }, 400 + (index * 300));
            });
        }

        function createClouds(container) {
            const cloudsOverlay = document.createElement('div');
            cloudsOverlay.className = 'clouds-overlay';
            container.appendChild(cloudsOverlay);
            
            const cloudCount = 12;
            const speedTypes = ['fast', 'medium', 'slow'];
            
            for (let i = 0; i < cloudCount; i++) {
                const cloud = document.createElement('div');
                cloud.className = 'cloud';
                
                const speedType = speedTypes[i % 3];
                cloud.classList.add(speedType);
                
                const cloudWidth = 80 + Math.random() * 120;
                const cloudHeight = 40 + Math.random() * 60;
                const scale = 0.7 + Math.random() * 0.3;
                
                cloud.style.width = `${cloudWidth}px`;
                cloud.style.height = `${cloudHeight}px`;
                cloud.style.top = `${5 + Math.random() * 30}%`;
                cloud.style.left = `-${300 + Math.random() * 200}px`;
                cloud.style.animationDelay = `${Math.random() * 20}s`;
                cloud.style.opacity = `${0.5 + Math.random() * 0.4}`;
                cloud.style.background = `rgba(255, 255, 255, ${0.7 + Math.random() * 0.2})`;
                cloud.style.setProperty('--scale', scale);
                
                cloudsOverlay.appendChild(cloud);
            }
        }

        function setupAutoScroll(pageNumber) {
            const storyTextElement = document.getElementById(`text-${pageNumber}`);
            const indicator = document.getElementById(`autoScrollIndicator-${pageNumber}`);
            
            if (indicator && storyTextElement.scrollHeight > storyTextElement.clientHeight) {
                setTimeout(() => {
                    indicator.classList.add('show');
                }, 1000);
                
                let scrollPosition = 0;
                const scrollHeight = storyTextElement.scrollHeight - storyTextElement.clientHeight;
                const scrollInterval = setInterval(() => {
                    if (scrollPosition < scrollHeight) {
                        scrollPosition += 1;
                        storyTextElement.scrollTop = scrollPosition;
                    } else {
                        clearInterval(scrollInterval);
                        setTimeout(() => {
                            indicator.classList.remove('show');
                        }, 1000);
                    }
                }, 30);
            }
        }

        console.log("📚 Story pages loaded:", storyPages.length, "pages");
        console.log("🔗 Python API:", pythonAPIReady ? 'Connected' : 'Not Connected');
        console.log("🤖 AI Features:", pythonAPIReady ? 'Active' : 'Limited');
    </script>
</body>
</html>