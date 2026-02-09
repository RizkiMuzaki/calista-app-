<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=5.0, user-scalable=yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#87CEEB">
    <title>{{ $gameData['nama_game'] }} - Game Gelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sky-blue: #87CEEB;
            --light-blue: #B0E2FF;
            --cloud-white: #FFFFFF;
            --sun-yellow: #FFD700;
            --grass-green: #7CFC00;
            --glass-color: #F0F8FF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            background: linear-gradient(to bottom, var(--sky-blue) 0%, var(--light-blue) 100%);
            min-height: 100vh;
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        /* Sky Background with Clouds */
        .sky-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .cloud {
            position: absolute;
            background: var(--cloud-white);
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.5);
            opacity: 0.9;
            animation: floatCloud 60s infinite linear;
        }

        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: var(--cloud-white);
            border-radius: 50%;
        }

        .cloud-1 {
            width: 150px;
            height: 60px;
            top: 15%;
            left: -200px;
            animation-delay: 0s;
        }

        .cloud-1::before {
            width: 70px;
            height: 70px;
            top: -30px;
            left: 20px;
        }

        .cloud-1::after {
            width: 50px;
            height: 50px;
            top: -20px;
            right: 20px;
        }

        .cloud-2 {
            width: 200px;
            height: 80px;
            top: 30%;
            right: -250px;
            animation-delay: 10s;
            animation-duration: 80s;
        }

        .cloud-2::before {
            width: 80px;
            height: 80px;
            top: -40px;
            left: 30px;
        }

        .cloud-2::after {
            width: 60px;
            height: 60px;
            top: -30px;
            right: 40px;
        }

        .cloud-3 {
            width: 120px;
            height: 50px;
            top: 60%;
            left: -150px;
            animation-delay: 20s;
            animation-duration: 70s;
        }

        .cloud-3::before {
            width: 60px;
            height: 60px;
            top: -25px;
            left: 15px;
        }

        @keyframes floatCloud {
            0% {
                transform: translateX(-100px);
            }
            100% {
                transform: translateX(calc(100vw + 300px));
            }
        }

        /* Sun */
        .sun {
            position: fixed;
            top: 30px;
            right: 30px;
            width: 80px;
            height: 80px;
            background: var(--sun-yellow);
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(255, 215, 0, 0.7);
            z-index: -1;
            animation: sunGlow 4s infinite alternate;
        }

        @keyframes sunGlow {
            0% {
                box-shadow: 0 0 40px rgba(255, 215, 0, 0.7);
            }
            100% {
                box-shadow: 0 0 60px rgba(255, 215, 0, 0.9);
            }
        }

        /* Game Container */
        .game-container {
            max-width: 100%;
            min-height: 100vh;
            padding: 15px;
            position: relative;
            z-index: 1;
        }

        /* Game Header */
        .game-header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 5px solid var(--sun-yellow);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .game-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, 
                #87CEEB 0%, 
                #4682B4 50%, 
                #1E90FF 100%);
        }

        .game-title {
            color: #2C3E50;
            font-size: clamp(2rem, 6vw, 3rem);
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 3px 3px 0 rgba(135, 206, 235, 0.2);
            line-height: 1.2;
            background: linear-gradient(45deg, #1E90FF, #4682B4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .game-subtitle {
            color: #3498DB;
            font-size: clamp(1rem, 4vw, 1.3rem);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .game-main-image {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            margin: 20px auto;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: white;
            padding: 15px;
            border: 3px solid var(--sun-yellow);
            display: block;
        }

        /* Prize Selection Section */
        .prize-selection-section {
            background: transparent;
            border-radius: 25px;
            padding: 30px 25px;
            margin-bottom: 25px;
            box-shadow: none;
            backdrop-filter: none;
            border: none;
        }

        .section-title {
            color: #2C3E50;
            font-size: clamp(1.5rem, 5vw, 2rem);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 4px solid #1E90FF;
            display: flex;
            align-items: center;
            gap: 15px;
            text-shadow: 2px 2px 0 rgba(135, 206, 235, 0.2);
        }

        .game-section .section-title {
            display: none;
        }

        .section-title i {
            color: #1E90FF;
            font-size: 1.8rem;
        }

        .prizes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            .prizes-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 15px;
            }
        }

        .prize-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
            border-radius: 20px;
            padding: 25px 20px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 4px solid transparent;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 20px rgba(30, 144, 255, 0.1);
        }

        .prize-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #87CEEB, #1E90FF);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .prize-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(30, 144, 255, 0.2);
            border-color: #87CEEB;
        }

        .prize-card:hover::before {
            opacity: 1;
        }

        .prize-card.selected {
            border-color: #2ECC71;
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.1) 0%, #FFFFFF 100%);
            animation: selectedPulse 2s infinite;
        }

        @keyframes selectedPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.4);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(46, 204, 113, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }

        .prize-image-container {
            width: 100%;
            height: 140px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            border-radius: 15px;
            overflow: hidden;
            border: 3px solid #B3E5FC;
            padding: 10px;
        }

        .prize-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: all 0.4s ease;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.1));
        }

        .prize-card:hover .prize-img {
            transform: scale(1.1) rotate(5deg);
        }

        .prize-name {
            font-size: clamp(1.1rem, 4vw, 1.3rem);
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 10px;
            text-align: center;
            line-height: 1.3;
            text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.8);
        }

        .prize-type {
            font-size: 0.95rem;
            color: #3498DB;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
            padding: 5px 10px;
            background: rgba(135, 206, 235, 0.1);
            border-radius: 20px;
        }

        .selection-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
            border: 3px solid white;
        }

        .prize-card.selected .selection-badge {
            opacity: 1;
            transform: scale(1);
        }

        /* Game Section */
        .game-section {
            background: transparent;
            border-radius: 25px;
            padding: 30px 25px;
            margin-bottom: 25px;
            box-shadow: none;
            display: none;
            backdrop-filter: none;
            border: none;
        }

        .game-section.active {
            display: block;
            animation: fadeInUp 0.5s ease;
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

        /* Glass Game Area */
        .game-area {
            position: relative;
            width: 100%;
            min-height: 500px;
            margin: 30px 0;
            border-radius: 20px;
            background: transparent;
            border: none;
            overflow: hidden;
            box-shadow: none;
        }

        /* Glass Shuffling Area */
        .shuffle-area {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .shuffle-area {
                flex-direction: column;
                gap: 30px;
            }
        }

        /* Glass Item */
        .glass-item {
            position: relative;
            width: 160px;
            height: 260px;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: transparent;
            padding: 20px;
            border-radius: 20px;
            border: 3px solid transparent;
            perspective: 1000px;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .glass-item:hover {
            transform: translateY(-10px);
            filter: drop-shadow(0 30px 50px rgba(0, 0, 0, 0.25));
        }

        /* Disable click effects when showing prize */
        .glass-item.show-prize {
            cursor: not-allowed !important;
            pointer-events: none !important;
            border: none !important;
            border-color: transparent !important;
            background: transparent !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
            animation: none !important;
            outline: none !important;
        }

        .glass-item.show-prize:hover {
            transform: none !important;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15)) !important;
            border: none !important;
        }

        .glass-item.show-prize.selected {
            border-color: transparent !important;
            background: transparent !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
            animation: none !important;
        }

        .glass-item.selected {
            background: linear-gradient(135deg, rgba(30, 144, 255, 0.15) 0%, rgba(46, 204, 113, 0.15) 100%);
            border-color: #1E90FF;
            box-shadow: 
                0 0 30px rgba(30, 144, 255, 0.5),
                inset 0 0 20px rgba(30, 144, 255, 0.1);
            animation: glassSelectedGlow 1.5s ease-in-out infinite;
        }

        /* Disable effects during shuffling */
        .glass-item.shuffling {
            cursor: not-allowed !important;
            pointer-events: none !important;
            filter: none !important;
            transform: none !important;
        }

        .glass-item.shuffling:hover {
            transform: none !important;
            filter: none !important;
        }

        .glass-item.shuffling.selected {
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
            animation: none !important;
        }

        /* Completely disable clicks before shuffle - pre-shuffle state */
        .glass-item.disabled-click {
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        @keyframes glassSelectedGlow {
            0%, 100% {
                box-shadow: 
                    0 0 30px rgba(30, 144, 255, 0.5),
                    inset 0 0 20px rgba(30, 144, 255, 0.1);
            }
            50% {
                box-shadow: 
                    0 0 50px rgba(30, 144, 255, 0.8),
                    inset 0 0 30px rgba(30, 144, 255, 0.2);
            }
        }

        @media (max-width: 768px) {
            .glass-item {
                width: 130px;
                height: 220px;
            }
        }

        @media (max-width: 480px) {
            .glass-item {
                width: 110px;
                height: 190px;
            }
        }

        .glass-cup {
            position: relative;
            width: 100%;
            height: 200px;
            background: linear-gradient(
                135deg,
                rgba(200, 230, 255, 0.4) 0%,
                rgba(150, 200, 255, 0.3) 25%,
                rgba(100, 180, 255, 0.25) 50%,
                rgba(150, 200, 255, 0.3) 75%,
                rgba(200, 230, 255, 0.4) 100%
            );
            border-radius: 0 0 70px 70px;
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.2),
                inset -8px -8px 20px rgba(0, 0, 0, 0.08),
                inset 8px 8px 20px rgba(255, 255, 255, 0.6),
                inset 0 -30px 50px rgba(100, 180, 255, 0.2);
            border: 1px solid rgba(200, 230, 255, 0.5);
            border-top: 2px solid rgba(255, 255, 255, 0.8);
            overflow: hidden;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            position: relative;
            transform-style: preserve-3d;
            backdrop-filter: blur(2px);
        }

        .glass-cup::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.4) 20%,
                rgba(255, 255, 255, 0.2) 40%,
                transparent 60%
            );
            animation: glassShine 3.5s infinite ease-in-out;
            pointer-events: none;
        }

        .glass-cup::after {
            content: '';
            position: absolute;
            top: 10%;
            left: 10%;
            width: 20%;
            height: 30%;
            background: radial-gradient(
                ellipse at center,
                rgba(255, 255, 255, 0.6) 0%,
                rgba(255, 255, 255, 0.2) 100%
            );
            border-radius: 50%;
            filter: blur(8px);
        }

        @keyframes glassShine {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        .glass-base {
            position: absolute;
            width: 110px;
            height: 30px;
            background: linear-gradient(180deg, rgba(200, 230, 255, 0.4) 0%, rgba(150, 200, 255, 0.3) 50%, rgba(100, 180, 255, 0.25) 100%);
            border-radius: 50%;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 
                0 3px 10px rgba(0, 0, 0, 0.15),
                inset 0 1px 3px rgba(255, 255, 255, 0.6),
                inset 0 -1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(200, 230, 255, 0.5);
        }

        .glass-stem {
            position: absolute;
            width: 18px;
            height: 55px;
            background: linear-gradient(90deg, rgba(150, 200, 255, 0.3) 0%, rgba(200, 230, 255, 0.4) 50%, rgba(150, 200, 255, 0.3) 100%);
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 10px;
            border: 1px solid rgba(200, 230, 255, 0.4);
            box-shadow: 
                inset -1px 0 3px rgba(0, 0, 0, 0.08),
                inset 1px 0 3px rgba(255, 255, 255, 0.6);
        }



        /* Prize Inside Glass */
        .prize-inside {
            position: absolute;
            width: 100px;
            height: 100px;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            z-index: 1;
            transition: all 0.5s ease;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
        }

        .glass-item.show-prize .prize-inside {
            opacity: 1;
            animation: prizeReveal 1s ease-out forwards;
        }

        @keyframes prizeReveal {
            0% {
                transform: translateX(-50%) scale(0);
            }
            50% {
                transform: translateX(-50%) scale(1.2);
            }
            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        .prize-inside img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Prize Placement Animation */
        .prize-placement {
            position: absolute;
            width: 100px;
            height: 100px;
            z-index: 20;
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .prize-placement img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.4));
        }

        /* Controls */
        .controls-container {
            text-align: center;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 3px solid rgba(135, 206, 235, 0.3);
        }

        .btn-game {
            padding: clamp(15px, 4vw, 20px) clamp(30px, 6vw, 50px);
            font-size: clamp(1.1rem, 4vw, 1.4rem);
            font-weight: 700;
            border-radius: 50px;
            border: none;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            min-width: 220px;
            margin: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-game::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
        }

        .btn-game:hover::before {
            left: 100%;
        }

        @media (max-width: 480px) {
            .btn-game {
                min-width: 170px;
                padding: 15px 25px;
            }
        }

        .btn-primary-game {
            background: linear-gradient(135deg, #1E90FF 0%, #4169E1 100%);
        }

        .btn-primary-game:hover:not(:disabled) {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(30, 144, 255, 0.4);
        }

        .btn-success-game {
            background: linear-gradient(135deg, #2ECC71 0%, #27AE60 100%);
        }

        .btn-success-game:hover:not(:disabled) {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(46, 204, 113, 0.4);
        }

        .btn-warning-game {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
        }

        .btn-warning-game:hover:not(:disabled) {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(255, 165, 0, 0.4);
        }

        .btn-game:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-game i {
            font-size: 1.3em;
        }

        /* Selected Info */
        .selected-info {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.15) 0%, rgba(46, 204, 113, 0.05) 100%);
            border: 3px solid #2ECC71;
            border-radius: 20px;
            padding: 25px;
            margin: 25px 0;
            display: none;
            box-shadow: 0 10px 25px rgba(46, 204, 113, 0.1);
        }

        .selected-info.show {
            display: block;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .glass-number {
            display: none;
            font-size: 2.5rem;
            font-weight: 900;
            color: #1E90FF;
            text-shadow: 
                2px 2px 0 rgba(255, 255, 255, 0.8),
                -2px -2px 0 rgba(0, 0, 0, 0.1),
                3px 3px 8px rgba(30, 144, 255, 0.3);
            bottom: -45px;
            position: absolute;
            animation: numberBounce 0.6s ease-out;
            background: linear-gradient(135deg, #1E90FF, #4169E1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Arial Black', sans-serif;
        }

        @keyframes numberBounce {
            0% {
                transform: scale(0) translateY(-20px);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .selected-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        /* Instructions */
        .instructions {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.15) 0%, rgba(52, 152, 219, 0.05) 100%);
            border-left: 5px solid #3498DB;
            padding: 25px;
            border-radius: 15px;
            margin: 25px 0;
            font-size: 1rem;
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.1);
        }

        .instruction-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
        }

        .instruction-step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            background: linear-gradient(135deg, #3498DB, #2980B9);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }

        /* Responsive glass number */
        @media (max-width: 768px) {
            .glass-number {
                font-size: 2rem;
                bottom: -35px;
            }
        }

        @media (max-width: 576px) {
            .glass-number {
                font-size: 1.5rem;
                bottom: -30px;
            }
        }

        /* Loading Spinner */
        .spinner-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 80px;
            height: 80px;
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #1E90FF;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Celebration Effects */
        .celebration-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
            display: none;
        }

        .confetti {
            position: absolute;
            width: 15px;
            height: 15px;
            opacity: 0;
            animation: confettiFall 2s ease-out forwards;
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Responsive - Tablet */
        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }

            .game-container {
                padding: 10px;
            }

            .game-header,
            .prize-selection-section,
            .game-section {
                padding: 20px 15px;
                border-radius: 20px;
                margin-bottom: 15px;
            }

            .game-title {
                font-size: clamp(1.5rem, 5vw, 2.5rem);
            }

            .game-subtitle {
                font-size: clamp(0.9rem, 3vw, 1.1rem);
            }

            .section-title {
                font-size: clamp(1.2rem, 4vw, 1.7rem);
                margin-bottom: 20px;
            }

            .prize-image-container {
                height: 120px;
            }

            .game-area {
                min-height: 400px;
                padding: 15px;
            }

            .shuffle-area {
                gap: 25px;
            }

            .glass-item {
                width: 120px;
                height: 200px;
            }

            .btn-game {
                padding: 12px 20px;
                font-size: 0.95rem;
                min-width: 160px;
                margin: 8px;
            }

            .prize-card {
                padding: 20px 15px;
            }

            .instructions {
                padding: 20px;
            }

            .instruction-step {
                margin-bottom: 12px;
            }

            .step-number {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
                margin-right: 12px;
            }

            .game-main-image {
                max-height: 200px;
            }
        }

        /* Responsive - Mobile */
        @media (max-width: 576px) {
            body {
                font-size: 13px;
            }

            .game-container {
                padding: 8px;
            }

            .game-header {
                padding: 15px 12px;
                margin-bottom: 12px;
                border: 3px solid var(--sun-yellow);
            }

            .game-header .row {
                flex-direction: column;
            }

            .game-title {
                font-size: clamp(1.2rem, 4vw, 2rem);
                margin-bottom: 8px;
            }

            .game-subtitle {
                font-size: clamp(0.8rem, 2.5vw, 0.95rem);
                margin-bottom: 12px;
            }

            .game-main-image {
                max-height: 150px;
                margin: 10px auto;
                padding: 10px;
            }

            .prize-selection-section,
            .game-section {
                padding: 15px 12px;
                margin-bottom: 12px;
                border-radius: 15px;
            }

            .section-title {
                font-size: clamp(1rem, 3.5vw, 1.4rem);
                margin-bottom: 15px;
                gap: 10px;
                padding-bottom: 10px;
            }

            .section-title i {
                font-size: 1.4rem;
            }

            .prizes-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 12px;
                margin-bottom: 15px;
            }

            .prize-card {
                padding: 15px 12px;
                border-radius: 15px;
            }

            .prize-image-container {
                height: 100px;
                margin-bottom: 12px;
                border-radius: 12px;
            }

            .prize-name {
                font-size: clamp(0.95rem, 3vw, 1.1rem);
                margin-bottom: 8px;
            }

            .prize-type {
                font-size: 0.85rem;
                margin-bottom: 10px;
                padding: 4px 8px;
            }

            .selected-info {
                padding: 15px;
                margin: 15px 0;
            }

            .selected-count {
                width: 35px;
                height: 35px;
                font-size: 1rem;
                margin-right: 10px;
            }

            .instructions {
                padding: 15px 12px;
                margin: 15px 0;
                border-left: 4px solid #3498DB;
            }

            .instruction-step {
                margin-bottom: 10px;
                padding: 8px;
            }

            .step-number {
                width: 26px;
                height: 26px;
                font-size: 0.85rem;
                margin-right: 10px;
                flex-shrink: 0;
            }

            .game-area {
                min-height: 350px;
                padding: 10px;
                border: 4px solid #4682B4;
            }

            .shuffle-area {
                flex-direction: column;
                gap: 20px;
                padding: 10px;
            }

            .glass-item {
                width: 100px;
                height: 180px;
            }

            @media (max-width: 480px) {
                .glass-item {
                    width: 90px;
                    height: 160px;
                }
            }

            .glass-cup {
                height: 170px;
                border-radius: 0 0 60px 60px;
                border: 8px solid #F0F8FF;
                margin-bottom: 12px;
            }

            .controls-container {
                margin-top: 20px;
                padding-top: 15px;
                border-top: 3px solid rgba(135, 206, 235, 0.3);
            }

            .btn-game {
                padding: 12px 18px;
                font-size: 0.9rem;
                min-width: 150px;
                min-height: 44px;
                margin: 6px 5px;
                border-radius: 40px;
                letter-spacing: 1px;
            }

            .btn-game i {
                font-size: 1.1em;
            }

            .sun {
                width: 60px;
                height: 60px;
                top: 20px;
                right: 20px;
            }

            /* Modal responsive */
            .modal-content {
                border-radius: 20px;
                border: 4px solid #1E90FF;
            }

            .modal-header {
                padding: 15px;
                border-radius: 17px 17px 0 0;
            }

            .modal-header h5 {
                font-size: 1.2rem;
            }

            .modal-body {
                padding: 20px 15px;
            }

            .modal-footer {
                flex-wrap: wrap;
                gap: 8px;
            }

            /* Cloud animation adjustment untuk mobile */
            .cloud-1 {
                width: 100px;
                height: 50px;
            }

            .cloud-2 {
                width: 140px;
                height: 60px;
            }

            .cloud-3 {
                width: 90px;
                height: 40px;
            }
        }

        /* Extra small devices */
        @media (max-width: 360px) {
            .game-title {
                font-size: clamp(1rem, 3.5vw, 1.7rem);
            }

            .prizes-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 10px;
            }

            .btn-game {
                padding: 10px 15px;
                font-size: 0.85rem;
                min-width: 140px;
            }

            .glass-item {
                width: 85px;
                height: 150px;
            }

            .section-title {
                font-size: 1rem;
            }

            .game-area {
                min-height: 300px;
            }
        }

        /* Glass Shuffling Animation - Simple Left-Right */
        @keyframes shuffleRight {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(80px); }
        }

        @keyframes shuffleLeft {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-80px); }
        }

        .shuffling-1 {
            animation: shuffleRight 0.4s ease-in-out infinite;
        }

        .shuffling-2 {
            animation: shuffleLeft 0.4s ease-in-out infinite;
        }

        .shuffling-3 {
            animation: shuffleRight 0.4s ease-in-out infinite;
        }

        /* Volume Control */
        .volume-control-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 2000;
            background: white;
            border-radius: 50px;
            padding: 12px 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(135, 206, 235, 0.3);
        }

        .volume-icon {
            color: #1E90FF;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .volume-icon:hover {
            color: #4169E1;
            transform: scale(1.1);
        }

        .volume-slider {
            width: 120px;
            height: 5px;
            -webkit-appearance: none;
            appearance: none;
            background: linear-gradient(to right, #87CEEB 0%, #1E90FF 100%);
            border-radius: 5px;
            outline: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(30, 144, 255, 0.3);
        }

        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(30, 144, 255, 0.4);
            border: 2px solid #1E90FF;
            transition: all 0.2s ease;
        }

        .volume-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
            box-shadow: 0 4px 12px rgba(30, 144, 255, 0.6);
        }

        .volume-slider::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(30, 144, 255, 0.4);
            border: 2px solid #1E90FF;
            transition: all 0.2s ease;
        }

        .volume-slider::-moz-range-thumb:hover {
            transform: scale(1.2);
            box-shadow: 0 4px 12px rgba(30, 144, 255, 0.6);
        }

        .volume-value {
            font-size: 0.85rem;
            color: #1E90FF;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .volume-control-container {
                top: 15px;
                left: 15px;
                padding: 10px 15px;
                gap: 12px;
            }

            .volume-slider {
                width: 100px;
            }

            .volume-icon {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Volume Control -->
    <div class="volume-control-container">
        <i class="fas fa-volume-down volume-icon" id="volumeIcon"></i>
        <input type="range" id="volumeSlider" class="volume-slider" min="0" max="100" value="50">
        <span class="volume-value" id="volumeValue">50%</span>
    </div>

    <!-- Sky Background -->
    <div class="sky-background">
        <div class="sun"></div>
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-container" id="loadingSpinner">
        <div class="spinner"></div>
    </div>

    <!-- Celebration Effects -->
    <div class="celebration-container" id="celebrationContainer"></div>

    <!-- Game Container -->
    <div class="game-container">
        <!-- Prize Selection Section -->
        <div class="prize-selection-section" id="prizeSelectionSection">
            <h2 class="section-title">
                <i class="fas fa-gift"></i>
                <span>PILIH HADIAH FAVORITMU</span>
            </h2>

            <div class="prizes-grid" id="prizesGrid">
                @foreach($gameData['hadiahs'] as $index => $prize)
                    <div class="prize-card" 
                         data-prize-id="{{ $prize['id'] }}" 
                         data-prize-name="{{ $prize['nama_hadiah'] }}"
                         data-prize-type="{{ $prize['jenis_hadiah'] }}"
                         data-prize-photo="{{ $prize['foto'] ? asset('storage/' . $prize['foto']) : '' }}"
                         data-prize-audio="{{ $prize['audio'] ? asset('storage/' . $prize['audio']) : '' }}">
                        <div class="selection-badge">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="prize-image-container">
                            @if($prize['foto'])
                                <img src="{{ asset('storage/' . $prize['foto']) }}" 
                                     alt="{{ $prize['nama_hadiah'] }}" 
                                     class="prize-img"
                                     loading="lazy">
                            @else
                                <i class="fas fa-gift fa-4x" style="color: #1E90FF;"></i>
                            @endif
                        </div>
                        <h5 class="prize-name">{{ $prize['nama_hadiah'] }}</h5>
                        <p class="prize-type">{{ $prize['jenis_hadiah'] }}</p>
                        <div class="text-center mt-2">
                            <span class="badge bg-primary px-3 py-2">KLIK UNTUK PILIH</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="controls-container">
                <button class="btn-game btn-success-game" id="startGameBtn" disabled>
                    <i class="fas fa-play-circle"></i>
                    <span>SIMPAN HADIAH & MULAI GAME</span>
                </button>
            </div>
        </div>

        <!-- Game Section -->
        <div class="game-section" id="gameSection">
            <!-- Game Area -->
            <div class="game-area" id="gameArea">
                <div class="shuffle-area" id="shuffleArea">
                    <!-- Glass items will be dynamically added here -->
                </div>
            </div>

            <div class="controls-container">
                <button class="btn-game btn-warning-game" id="shuffleBtn" style="display: none;">
                    <i class="fas fa-random"></i>
                    ACAK GELAS SEKARANG!
                </button>
                <button class="btn-game btn-success-game" id="selectGlassBtn" style="display: none;" disabled>
                    <i class="fas fa-hand-pointer"></i>
                    PILIH GELAS INI
                </button>
                <button class="btn-game btn-primary-game" id="backToSelectionBtn">
                    <i class="fas fa-arrow-left"></i>
                    GANTI HADIAH LAIN
                </button>
            </div>
        </div>
    </div>

    <!-- Results Modal -->
    <div class="modal fade" id="resultsModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 25px; border: 5px solid #1E90FF;">
                <div class="modal-header" style="background: linear-gradient(135deg, #1E90FF 0%, #4169E1 100%); border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-trophy me-2"></i>
                        <span>SELAMAT!</span>
                    </h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <i class="fas fa-trophy fa-4x" style="color: #FFD700; animation: bounce 1s infinite;"></i>
                        <h2 class="text-success mt-3">KAMU MENDAPATKAN:</h2>
                    </div>
                    <div id="resultContent">
                        <!-- Results will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border-top: 3px solid #87CEEB;">
                    <button type="button" class="btn-game btn-primary-game" data-bs-dismiss="modal">
                        <i class="fas fa-redo"></i>
                        MAIN LAGI
                    </button>
                    <a href="{{ route('permainan') }}" class="btn-game btn-success-game">
                        <i class="fas fa-home"></i>
                        KEMBALI
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gameId = {{ $gameData['id'] }};
            const prizes = @json($gameData['hadiahs']);
            
            // Background Music Setup
            const backgroundMusic = new Audio('{{ asset("storage/music/play.mp3") }}');
            backgroundMusic.loop = true;
            backgroundMusic.volume = 0.5;
            
            // Volume Control Elements
            const volumeSlider = document.getElementById('volumeSlider');
            const volumeIcon = document.getElementById('volumeIcon');
            const volumeValue = document.getElementById('volumeValue');
            
            // Volume Control Functions
            function updateVolume(value) {
                const volumePercent = Math.round(value);
                backgroundMusic.volume = volumePercent / 100;
                volumeValue.textContent = volumePercent + '%';
                
                // Update icon based on volume level
                if (volumePercent === 0) {
                    volumeIcon.className = 'fas fa-volume-mute volume-icon';
                } else if (volumePercent < 50) {
                    volumeIcon.className = 'fas fa-volume-down volume-icon';
                } else {
                    volumeIcon.className = 'fas fa-volume-up volume-icon';
                }
            }
            
            // Volume slider change event
            volumeSlider.addEventListener('input', function(e) {
                updateVolume(e.target.value);
            });
            
            // Mute/Unmute on icon click
            volumeIcon.addEventListener('click', function() {
                if (backgroundMusic.volume > 0) {
                    volumeSlider.value = 0;
                    updateVolume(0);
                } else {
                    volumeSlider.value = 50;
                    updateVolume(50);
                }
            });
            
            // Play background music on page load
            function playBackgroundMusic() {
                try {
                    backgroundMusic.currentTime = 0;
                    backgroundMusic.play().catch(err => {
                        console.log('Auto-play blocked for background music:', err);
                    });
                } catch (e) {
                    console.log('Background music playback not available');
                }
            }
            
            // Play after user interaction or small delay
            setTimeout(() => {
                playBackgroundMusic();
            }, 800);
            
            // Also allow playing on first user interaction
            document.addEventListener('click', function initMusicOnInteraction() {
                if (backgroundMusic.paused) {
                    playBackgroundMusic();
                }
                document.removeEventListener('click', initMusicOnInteraction);
            }, { once: true });
            
            // DOM Elements
            const prizeSelectionSection = document.getElementById('prizeSelectionSection');
            const gameSection = document.getElementById('gameSection');
            const gameArea = document.getElementById('gameArea');
            const shuffleArea = document.getElementById('shuffleArea');
            const startGameBtn = document.getElementById('startGameBtn');
            const shuffleBtn = document.getElementById('shuffleBtn');
            const selectGlassBtn = document.getElementById('selectGlassBtn');
            const backToSelectionBtn = document.getElementById('backToSelectionBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const celebrationContainer = document.getElementById('celebrationContainer');
            
            // Game State
            let selectedPrizeIds = [];
            let selectedPrizeData = [];
            let displayedPrizes = [];
            let glassItems = [];
            let isShuffling = false;
            let selectedGlassIndex = null;
            let isPlacementPhase = false;
            
            // Audio elements
            const greetingAudio = new Audio('{{ asset("storage/game/greeting.mp3") }}');
            greetingAudio.volume = 0.7;
            
            // Prize audio element
            let prizeAudio = null;
            
            // Pilih audio element (for shuffle complete)
            const pilihAudio = new Audio('{{ asset("storage/music/pilih.mp3") }}');
            pilihAudio.volume = 0.7;
            
            // Klik audio element (for selecting glass)
            const klikAudio = new Audio('{{ asset("storage/music/klik.mp3") }}');
            klikAudio.volume = 0.7;
            
            // Play greeting sound on page load
            function playGreetingSound() {
                try {
                    greetingAudio.currentTime = 0;
                    greetingAudio.play().catch(err => console.log('Auto-play blocked:', err));
                } catch (e) {
                    console.log('Audio playback not available');
                }
            }
            
            // Play pilih sound when shuffle is done
            function playPilihAudio() {
                try {
                    pilihAudio.currentTime = 0;
                    pilihAudio.play().catch(err => console.log('Auto-play blocked:', err));
                } catch (e) {
                    console.log('Audio playback not available');
                }
            }
            
            // Play klik sound when selecting glass
            function playKlikAudio() {
                try {
                    klikAudio.currentTime = 0;
                    klikAudio.play().catch(err => console.log('Auto-play blocked:', err));
                } catch (e) {
                    console.log('Audio playback not available');
                }
            }
            
            // Initialize
            initializePrizeSelection();
            
            // Play greeting sound after a short delay to allow user interaction
            setTimeout(() => {
                playGreetingSound();
            }, 500);
            
            // Prize Selection Functions
            function initializePrizeSelection() {
                const prizeCards = document.querySelectorAll('.prize-card');
                prizeCards.forEach(card => {
                    card.addEventListener('click', function() {
                        // Cegah pemilihan lebih dari 4 hadiah
                        if (!card.classList.contains('selected') && selectedPrizeIds.length >= 4) {
                            alert('Maksimal 4 hadiah yang dapat dipilih');
                            return;
                        }
                        
                        // Play klik sound when selecting prize
                        playKlikAudio();
                        
                        const prizeId = this.dataset.prizeId;
                        const index = selectedPrizeIds.indexOf(prizeId);
                        
                        if (index === -1) {
                            // Add to selection
                            selectedPrizeIds.push(prizeId);
                            selectedPrizeData.push({
                                id: prizeId,
                                name: this.dataset.prizeName,
                                type: this.dataset.prizeType,
                                photo: this.dataset.prizePhoto,
                                audio: this.dataset.prizeAudio
                            });
                            this.classList.add('selected');
                        } else {
                            // Remove from selection
                            selectedPrizeIds.splice(index, 1);
                            selectedPrizeData.splice(index, 1);
                            this.classList.remove('selected');
                        }
                        
                        updateSelectionInfo();
                        updateStartGameButton();
                    });
                });
                
                updateSelectionInfo();
            }
            
            function updateSelectionInfo() {
                // Info selection sudah dihapus dari HTML
                // Fungsi ini bisa dikosongkan atau hanya update state
            }
            
            function updateStartGameButton() {
                const isValid = selectedPrizeIds.length >= 3 && selectedPrizeIds.length <= 4;
                startGameBtn.disabled = !isValid;
                startGameBtn.style.opacity = isValid ? '1' : '0.6';
            }
            
            // Start Game
            startGameBtn.addEventListener('click', function() {
                // Play klik sound when clicking button
                playKlikAudio();
                
                showLoading(true);
                
                // Save selected prizes to server
                fetch(`/game/${gameId}/save-selected-prizes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        selected_prizes: selectedPrizeIds
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Simpan displayed prizes untuk digunakan saat placement
                        displayedPrizes = data.displayed_prizes || [];
                        
                        setTimeout(() => {
                            showLoading(false);
                            prizeSelectionSection.style.display = 'none';
                            gameSection.classList.add('active');
                            initializeGame();
                        }, 500);
                    } else {
                        showLoading(false);
                        alert('Gagal menyimpan hadiah: ' + (data.message || 'Tidak ada pesan error'));
                    }
                })
                .catch(error => {
                    showLoading(false);
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message + '\nMohon pastikan endpoint API tersedia dan CSRF token valid.');
                });
            });
            
            // Back to Selection
            backToSelectionBtn.addEventListener('click', function() {
                gameSection.classList.remove('active');
                prizeSelectionSection.style.display = 'block';
                resetGame();
            });
            
            // Game Functions
            function initializeGame() {
                resetGame();
                
                // Create glass elements sesuai jumlah hadiah yang dipilih (3-4)
                const prizesToUse = selectedPrizeData.slice(0, selectedPrizeData.length);
                
                // Auto-trigger placement otomatis tanpa perlu tombol
                startPlacementAnimation();
            }
            
            // Start Placement Animation (auto-triggered)
            async function startPlacementAnimation() {
                if (isPlacementPhase) return;
                
                isPlacementPhase = true;
                
                // Clear any existing glasses
                shuffleArea.innerHTML = '';
                glassItems = [];
                
                const prizesToUse = selectedPrizeData.slice(0, selectedPrizeData.length);
                
                // Create all glasses first in a row (like shuffled position)
                for (let i = 0; i < prizesToUse.length; i++) {
                    await createGlassInRow(i, prizesToUse.length);
                }
                
                await delay(500);
                
                // Animate prize placement into glasses
                for (let i = 0; i < prizesToUse.length; i++) {
                    // Gunakan original prize untuk display (bisa termasuk uang)
                    await placePrizeInGlassWithAnimation(i, prizesToUse[i]);
                    await delay(1200);
                }
                
                // Show shuffle button
                shuffleBtn.style.display = 'inline-flex';
                
                // Disable all glass clicks before shuffle
                glassItems.forEach(glass => glass.classList.add('disabled-click'));
                
                isPlacementPhase = false;
            }
            
            function createGlass(index) {
                return new Promise(resolve => {
                    const glass = document.createElement('div');
                    glass.className = 'glass-item';
                    glass.dataset.index = index;
                    
                    glass.innerHTML = `
                        <div class="glass-cup">
                            <div class="glass-stem"></div>
                            <div class="glass-base"></div>
                        </div>
                    `;
                    
                    glass.addEventListener('click', function() {
                        if (isShuffling || isPlacementPhase || this.classList.contains('show-prize')) return;
                        
                        // Deselect all glasses
                        glassItems.forEach(g => g.classList.remove('selected'));
                        
                        // Select this glass
                        this.classList.add('selected');
                        selectedGlassIndex = index;
                        selectGlassBtn.disabled = false;
                    });
                    
                    shuffleArea.appendChild(glass);
                    glassItems.push(glass);
                    
                    // Animate glass appearance
                    setTimeout(() => {
                        glass.style.opacity = '1';
                        glass.style.transform = 'translateY(0)';
                        resolve();
                    }, 100);
                });
            }
            
            function createGlassInRow(index, totalGlasses) {
                return new Promise(resolve => {
                    const glass = document.createElement('div');
                    glass.className = 'glass-item';
                    glass.dataset.index = index;
                    
                    glass.innerHTML = `
                        <div class="glass-cup">
                            <div class="glass-stem"></div>
                            <div class="glass-base"></div>
                        </div>
                        <div class="glass-number">${index + 1}</div>
                    `;
                    
                    glass.addEventListener('click', function() {
                        if (isShuffling || isPlacementPhase || this.classList.contains('show-prize')) return;
                        
                        // Deselect all glasses
                        glassItems.forEach(g => g.classList.remove('selected'));
                        
                        // Select this glass
                        this.classList.add('selected');
                        selectedGlassIndex = index;
                        selectGlassBtn.disabled = false;
                    });
                    
                    shuffleArea.appendChild(glass);
                    glassItems.push(glass);
                    
                    // Position glass in row (like shuffled state)
                    const areaWidth = shuffleArea.clientWidth;
                    const glassWidth = 160;
                    const totalWidth = glassWidth * totalGlasses + 40 * (totalGlasses - 1);
                    const startX = (areaWidth - totalWidth) / 2;
                    const centerY = (shuffleArea.clientHeight - 260) / 2;
                    
                    glass.style.position = 'absolute';
                    glass.style.left = `${startX + (glassWidth + 40) * index}px`;
                    glass.style.top = `${centerY}px`;
                    glass.style.opacity = '0';
                    glass.style.transform = 'translateX(-100px)';
                    glass.style.transition = 'all 0.6s ease';
                    
                    setTimeout(() => {
                        glass.style.opacity = '1';
                        glass.style.transform = 'translateX(0)';
                        resolve();
                    }, 400);
                });
            }
            
            function placePrizeInGlass(index, prize) {
                return new Promise(resolve => {
                    const glass = glassItems[index];
                    const prizePlacement = document.createElement('div');
                    prizePlacement.className = 'prize-placement';
                    prizePlacement.style.left = '50%';
                    prizePlacement.style.top = '0';
                    
                    if (prize.photo) {
                        prizePlacement.innerHTML = `<img src="${prize.photo}" alt="${prize.name}">`;
                    } else {
                        prizePlacement.innerHTML = '<i class="fas fa-gift fa-3x" style="color: #1E90FF;"></i>';
                    }
                    
                    gameArea.appendChild(prizePlacement);
                    
                    // Animate prize moving into glass
                    const glassRect = glass.getBoundingClientRect();
                    const areaRect = gameArea.getBoundingClientRect();
                    
                    const startX = 50; // Center of game area
                    const startY = 0;
                    const endX = glassRect.left + glassRect.width/2 - areaRect.left;
                    const endY = glassRect.top + glassRect.height - 100 - areaRect.top;
                    
                    // Start position
                    prizePlacement.style.left = `${startX}%`;
                    prizePlacement.style.top = `${startY}px`;
                    prizePlacement.style.transform = 'scale(0.5)';
                    
                    // Animate to glass
                    setTimeout(() => {
                        prizePlacement.style.left = `${endX}px`;
                        prizePlacement.style.top = `${endY}px`;
                        prizePlacement.style.transform = 'scale(0.8)';
                        prizePlacement.style.transition = 'all 1s cubic-bezier(0.4, 0, 0.2, 1)';
                    }, 100);
                    
                    // After reaching glass, create prize inside
                    setTimeout(() => {
                        prizePlacement.remove();
                        
                        // Create prize inside glass
                        const prizeInside = document.createElement('div');
                        prizeInside.className = 'prize-inside';
                        prizeInside.dataset.prizeId = prize.id;
                        // Store original prize jenis untuk pengecekan nanti
                        prizeInside.dataset.prizeType = prize.type || '';
                        
                        if (prize.photo) {
                            prizeInside.innerHTML = `<img src="${prize.photo}" alt="${prize.name}">`;
                        } else {
                            prizeInside.innerHTML = '<i class="fas fa-gift fa-3x" style="color: #1E90FF;"></i>';
                        }
                        
                        glass.querySelector('.glass-cup').appendChild(prizeInside);
                        
                        // Show prize briefly
                        glass.classList.add('show-prize');
                        setTimeout(() => {
                            glass.classList.remove('show-prize');
                            // Hide prize inside after animation
                            prizeInside.style.opacity = '0';
                            prizeInside.style.pointerEvents = 'none';
                            resolve();
                        }, 1000);
                    }, 1100);
                });
            }
            
            function placePrizeInGlassWithAnimation(index, prize) {
                return new Promise(resolve => {
                    const glass = glassItems[index];
                    playGlassSound();
                    
                    // Create prize inside glass with animation
                    const prizeInside = document.createElement('div');
                    prizeInside.className = 'prize-inside';
                    prizeInside.dataset.prizeId = prize.id;
                    prizeInside.dataset.prizeType = prize.type || '';
                    
                    if (prize.photo) {
                        prizeInside.innerHTML = `<img src="${prize.photo}" alt="${prize.name}">`;
                    } else {
                        prizeInside.innerHTML = '<i class="fas fa-gift fa-3x" style="color: #1E90FF;"></i>';
                    }
                    
                    glass.querySelector('.glass-cup').appendChild(prizeInside);
                    
                    // Show prize with animation - PELAN
                    glass.classList.add('show-prize');
                    
                    setTimeout(() => {
                        glass.classList.remove('show-prize');
                        // Hide prize inside after animation
                        prizeInside.style.opacity = '0';
                        prizeInside.style.pointerEvents = 'none';
                        resolve();
                    }, 1800);
                });
            }
            
            // Audio Context for Glass Sound
            let audioContext = null;
            
            function initAudioContext() {
                if (!audioContext) {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                return audioContext;
            }
            
            function playGlassSound() {
                try {
                    const ctx = initAudioContext();
                    
                    // Create oscillator untuk glass clinking sound
                    const now = ctx.currentTime;
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    const filter = ctx.createBiquadFilter();
                    
                    osc.connect(filter);
                    filter.connect(gain);
                    gain.connect(ctx.destination);
                    
                    // Glass sound parameters - lebih soft dan natural
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(1000, now);
                    osc.frequency.exponentialRampToValueAtTime(400, now + 0.2);
                    
                    // Add filter untuk efek lebih natural
                    filter.type = 'highpass';
                    filter.frequency.setValueAtTime(800, now);
                    
                    gain.gain.setValueAtTime(0.15, now);
                    gain.gain.exponentialRampToValueAtTime(0.01, now + 0.2);
                    
                    osc.start(now);
                    osc.stop(now + 0.2);
                } catch (e) {
                    console.log('Audio playback not available');
                }
            }
            
            // Shuffle Glasses
            shuffleBtn.addEventListener('click', async function() {
                if (isShuffling || glassItems.length === 0) return;
                
                isShuffling = true;
                shuffleBtn.disabled = true;
                shuffleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MENGACAK...';
                
                // Add shuffling class to disable effects
                glassItems.forEach(glass => glass.classList.add('shuffling'));
                
                const areaWidth = shuffleArea.clientWidth;
                const areaHeight = shuffleArea.clientHeight;
                const glassWidth = glassItems[0].offsetWidth;
                const totalGlasses = glassItems.length;
                const totalWidth = glassWidth * totalGlasses + 40 * (totalGlasses - 1);
                const centerX = (areaWidth - totalWidth) / 2;
                const centerY = (areaHeight - glassItems[0].offsetHeight) / 2;
                
                // STEP 1: Gather glasses ke tengah
                glassItems.forEach((glass) => {
                    glass.style.position = 'absolute';
                    glass.style.transition = 'all 0.4s ease-in-out';
                    glass.style.zIndex = '2';
                    
                    // Move to center - semua gelas ke posisi tengah
                    const centerGlassX = centerX + (totalWidth / 2) - (glassWidth / 2);
                    glass.style.left = `${centerGlassX}px`;
                    glass.style.top = `${centerY}px`;
                });
                
                await delay(400);
                
                // STEP 2: Arrange in row again
                glassItems.forEach((glass, index) => {
                    glass.style.transition = 'all 0.35s ease-in-out';
                    const x = centerX + index * (glassWidth + 40);
                    glass.style.left = `${x}px`;
                    glass.style.top = `${centerY}px`;
                });
                
                await delay(300);
                
                // STEP 3: Shuffle dengan cepat - HORIZONTAL ONLY
                let shuffleCount = 0;
                const maxShuffles = 12;
                
                function performShuffle() {
                    if (shuffleCount >= maxShuffles) {
                        finishShuffle();
                        return;
                    }
                    
                    playGlassSound();
                    
                    // Acak posisi gelas
                    const glassArray = [...glassItems];
                    
                    for (let i = glassArray.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [glassArray[i], glassArray[j]] = [glassArray[j], glassArray[i]];
                    }
                    
                    // Update posisi HORIZONTAL ONLY - tidak berubah vertikal
                    glassArray.forEach((glass, index) => {
                        void glass.offsetHeight;
                        
                        const x = centerX + index * (glassWidth + 40);
                        glass.style.left = `${x}px`;
                        glass.style.top = `${centerY}px`;
                        glass.style.transition = 'all 0.12s ease-in-out';
                    });
                    
                    shuffleCount++;
                    setTimeout(performShuffle, 120);
                }
                
                function finishShuffle() {
                    const glassArray = [...glassItems];
                    
                    // Final positioning - HORIZONTAL ONLY
                    glassArray.forEach((glass, index) => {
                        const x = centerX + index * (glassWidth + 40);
                        glass.style.transition = 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1)';
                        glass.style.left = `${x}px`;
                        glass.style.top = `${centerY}px`;
                        glass.style.animation = 'none';
                    });
                    
                    playGlassSound();
                    setTimeout(() => playGlassSound(), 120);
                    
                    setTimeout(() => {
                        isShuffling = false;
                        
                        // Remove shuffling class to restore effects
                        glassItems.forEach(glass => {
                            glass.classList.remove('shuffling');
                            glass.classList.remove('disabled-click');
                        });
                        
                        shuffleBtn.disabled = false;
                        shuffleBtn.style.display = 'none';
                        selectGlassBtn.style.display = 'inline-flex';
                        selectGlassBtn.disabled = false;
                        shuffleBtn.innerHTML = '<i class="fas fa-random"></i> ACAK GELAS SEKARANG!';
                        
                        // Play pilih audio when shuffle is done
                        playPilihAudio();
                    }, 500);
                }
                
                setTimeout(performShuffle, 100);
            });
            
            // Select Glass
            selectGlassBtn.addEventListener('click', function() {
                if (selectedGlassIndex === null || isShuffling) return;
                
                // Play klik sound when selecting glass
                playKlikAudio();
                
                showLoading(true);
                
                // Get selected prize
                const selectedGlass = glassItems[selectedGlassIndex];
                const prizeInside = selectedGlass.querySelector('.prize-inside');
                const prizeId = prizeInside?.dataset.prizeId;
                const prizeType = prizeInside?.dataset.prizeType || '';
                
                let finalPrizeId = prizeId;
                
                // Jika hadiah di gelas adalah jenis "uang", ganti dengan hadiah lain yang dipilih
                if (prizeType.toLowerCase() === 'uang') {
                    // Cari hadiah yang dipilih user yang bukan uang
                    const nonCashPrizes = selectedPrizeData.filter(p => 
                        p.type && p.type.toLowerCase() !== 'uang'
                    );
                    
                    if (nonCashPrizes.length > 0) {
                        // Pilih random dari hadiah non-cash yang dipilih
                        const randomPrize = nonCashPrizes[Math.floor(Math.random() * nonCashPrizes.length)];
                        finalPrizeId = randomPrize.id;
                    }
                }
                
                // Send selection to server
                fetch(`/game/${gameId}/get-prizes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        selected_prizes: [finalPrizeId]
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    showLoading(false);
                    
                    if (data.success) {
                        // Show prize in glass
                        selectedGlass.classList.add('show-prize');
                        
                        // Jika hadiah adalah uang, ganti gambar dengan hadiah non-uang
                        const prizeData = data.prizes[0];
                        if (prizeData && prizeData.jenis_hadiah && prizeData.jenis_hadiah.toLowerCase() === 'uang') {
                            // Cari hadiah non-uang yang dipilih user
                            const nonCashPrizes = selectedPrizeData.filter(p => 
                                p.type && p.type.toLowerCase() !== 'uang'
                            );
                            
                            if (nonCashPrizes.length > 0) {
                                // Pilih random hadiah non-uang
                                const randomPrize = nonCashPrizes[Math.floor(Math.random() * nonCashPrizes.length)];
                                
                                // Update gambar di gelas
                                const prizeInside = selectedGlass.querySelector('.prize-inside');
                                if (prizeInside) {
                                    prizeInside.innerHTML = '';
                                    if (randomPrize.photo) {
                                        prizeInside.innerHTML = `<img src="${randomPrize.photo}" alt="${randomPrize.name}">`;
                                    } else {
                                        prizeInside.innerHTML = '<i class="fas fa-gift fa-3x" style="color: #1E90FF;"></i>';
                                    }
                                }
                            }
                        }
                        
                        // Trigger celebration
                        triggerCelebration();
                        
                        // Show results after delay
                        setTimeout(() => {
                            showResults(data);
                        }, 1500);
                    } else {
                        alert('Error: ' + (data.message || 'Tidak ada pesan error'));
                    }
                })
                .catch(error => {
                    showLoading(false);
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message + '\nMohon pastikan endpoint API tersedia dan CSRF token valid.');
                });
            });
            
            // Reset Game
            function resetGame() {
                // Clear glass items
                glassItems.forEach(glass => glass.remove());
                glassItems = [];
                isShuffling = false;
                isPlacementPhase = false;
                selectedGlassIndex = null;
                
                // Reset buttons
                shuffleBtn.disabled = false;
                shuffleBtn.innerHTML = '<i class="fas fa-random"></i> ACAK GELAS SEKARANG!';
                selectGlassBtn.disabled = true;
                selectGlassBtn.style.display = 'none';
            }
            
            // Results Modal
            function showResults(data) {
                let resultHtml = '';
                
                // Helper function untuk normalize image path
                function getImagePath(photoPath) {
                    if (!photoPath) return '';
                    
                    // Jika path sudah full URL, gunakan langsung
                    if (photoPath.startsWith('http')) {
                        return photoPath;
                    }
                    
                    // Jika path sudah dengan /storage/, gunakan dengan asset()
                    if (photoPath.includes('/storage/')) {
                        return photoPath;
                    }
                    
                    // Jika path tanpa /storage/, tambahkan
                    return '/storage/' + (photoPath.startsWith('/') ? photoPath.substring(1) : photoPath);
                }
                
                // Helper function untuk normalize audio path
                function getAudioPath(audioPath) {
                    if (!audioPath) return '';
                    
                    if (audioPath.startsWith('http')) return audioPath;
                    if (audioPath.includes('/storage/')) return audioPath;
                    
                    return '/storage/' + (audioPath.startsWith('/') ? audioPath.substring(1) : audioPath);
                }
                
                if (data.prizes && data.prizes.length > 0) {
                    let actualPrize = data.prizes[0]; // Hadiah yang sebenarnya diterima
                    
                    // Jika hadiah adalah uang, ganti dengan hadiah non-uang
                    if (actualPrize.jenis_hadiah && actualPrize.jenis_hadiah.toLowerCase() === 'uang') {
                        // Cari hadiah non-uang dari yang dipilih user
                        const nonCashPrizes = selectedPrizeData.filter(p => 
                            p.type && p.type.toLowerCase() !== 'uang'
                        );
                        
                        if (nonCashPrizes.length > 0) {
                            // Pilih random hadiah non-uang
                            const randomPrize = nonCashPrizes[Math.floor(Math.random() * nonCashPrizes.length)];
                            actualPrize = {
                                nama_hadiah: randomPrize.name,
                                jenis_hadiah: randomPrize.type,
                                foto: randomPrize.photo,
                                audio: randomPrize.audio
                            };
                        }
                    }
                    
                    // Normalize image path
                    const imagePath = getImagePath(actualPrize.foto);
                    
                    resultHtml += `
                        <div class="bg-light p-4 rounded-3 shadow mb-4" style="border: 3px solid #1E90FF;">
                            <div class="prize-image-container mb-3" style="height: 150px;">
                                ${imagePath ? 
                                    `<img src="${imagePath}" alt="${actualPrize.nama_hadiah}" class="prize-img" onerror="this.style.display='none'; this.parentElement.innerHTML += '<i class=\"fas fa-gift fa-5x\" style=\"color: #1E90FF;\"></i>'">` :
                                    `<i class="fas fa-gift fa-5x" style="color: #1E90FF;"></i>`
                                }
                            </div>
                            <h3 class="text-primary mb-2">${actualPrize.nama_hadiah}</h3>
                            <p class="text-muted">${actualPrize.jenis_hadiah}</p>
                            <div class="mt-3">
                                <span class="badge bg-success p-2 fs-6">HADIAH TERPILIH</span>
                            </div>
                        </div>
                    `;
                    
                    // Play prize audio HANYA untuk hadiah non-uang (actualPrize adalah hadiah yang sudah dimanipulasi jika uang)
                    if (actualPrize.audio) {
                        const audioPath = getAudioPath(actualPrize.audio);
                        if (audioPath) {
                            // Stop any previously playing audio
                            if (prizeAudio) {
                                prizeAudio.pause();
                                prizeAudio.currentTime = 0;
                            }
                            
                            // Create and play new audio with delay untuk memastikan modal sudah terbuka
                            setTimeout(() => {
                                prizeAudio = new Audio(audioPath);
                                prizeAudio.volume = 0.8;
                                prizeAudio.play().catch(err => console.log('Audio autoplay blocked:', err));
                            }, 500);
                        }
                    }
                }
                
                document.getElementById('resultContent').innerHTML = resultHtml;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('resultsModal'));
                modal.show();
                
                // Reset game after modal closes
                document.getElementById('resultsModal').addEventListener('hidden.bs.modal', function() {
                    // Stop audio when modal closes
                    if (prizeAudio) {
                        prizeAudio.pause();
                        prizeAudio.currentTime = 0;
                    }
                    
                    resetGame();
                    prizeSelectionSection.style.display = 'block';
                    gameSection.classList.remove('active');
                });
            }
            
            // Celebration Effects
            function triggerCelebration() {
                celebrationContainer.style.display = 'block';
                celebrationContainer.innerHTML = '';
                
                const colors = ['#1E90FF', '#FFD700', '#2ECC71', '#FF6B6B', '#9B59B6'];
                const areaWidth = window.innerWidth;
                
                for (let i = 0; i < 100; i++) {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = `${Math.random() * areaWidth}px`;
                    confetti.style.width = `${Math.random() * 20 + 10}px`;
                    confetti.style.height = confetti.style.width;
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    confetti.style.animationDuration = `${Math.random() * 2 + 1}s`;
                    confetti.style.animationDelay = `${Math.random() * 1}s`;
                    
                    celebrationContainer.appendChild(confetti);
                }
                
                setTimeout(() => {
                    celebrationContainer.style.display = 'none';
                }, 3000);
            }
            
            // Utility Functions
            function showLoading(show) {
                loadingSpinner.style.display = show ? 'flex' : 'none';
            }
            
            function delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            
            // Initialize button states
            updateStartGameButton();
        });
    </script>
</body>
</html>