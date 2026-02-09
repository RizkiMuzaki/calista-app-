<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $writingItem->text }} - Calista Menulis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>:
        /* RESET & BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(180deg, #87CEEB 0%, #E0F6FF 100%);
            color: #333;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding: 0;
        }

        /* CONTAINER */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* HEADER */
        .writing-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #3b82f6;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }

        .back-btn:hover {
            background: #2563eb;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .writing-title {
            text-align: center;
            flex-grow: 1;
        }

        .writing-title h1 {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .writing-subtitle {
            font-size: 1.2rem;
            color: #64748b;
            font-weight: 600;
        }

        /* TYPE BADGE */
        .type-badge {
            display: inline-block;
            padding: 8px 25px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* MAIN CONTENT - PUSATKAN PAPAN TULIS */
        .writing-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 1024px) {
            .writing-container {
                flex-direction: column;
            }
        }

        /* PAPAN TULIS SECTION */
        .canvas-section {
            background: #2F4F2F;
            border-radius: 20px;
            padding: 40px 30px 30px 30px;
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.3),
                inset 0 2px 5px rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            border: 12px solid #8B4513;
            position: relative;
            max-width: 800px;
            width: 100%;
            min-height: 700px;
        }

        /* Efek kayu pada border */
        .canvas-section::before {
            content: '';
            position: absolute;
            top: -12px;
            left: -12px;
            right: -12px;
            bottom: -12px;
            background: linear-gradient(135deg, #D2691E, #8B4513);
            border-radius: 20px;
            z-index: -1;
        }

        .canvas-section h2 {
            font-size: 2.5rem;
            color: #FFFFFF;
            margin-bottom: 25px;
            font-family: 'Fredoka One', cursive;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            letter-spacing: 2px;
        }

        /* Gambar di dalam papan tulis */
        .blackboard-image-preview {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .image-frame-blackboard {
            width: 180px;
            height: 180px;
            border-radius: 15px;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .preview-image-blackboard {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
            filter: brightness(1.1) contrast(1.1);
        }

        /* AREA MENULIS */
        .canvas-wrapper {
            position: relative;
            flex: 1;
            min-height: 500px;
            background: #1a3a1a;
            border-radius: 15px;
            border: 3px solid #0d240d;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        #writingCanvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: crosshair;
            touch-action: none;
            background: transparent;
        }

        /* TRACING GUIDE */
        .tracing-guide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .tracing-text {
            font-family: 'Fredoka One', cursive;
            color: rgba(255, 255, 255, 0.15);
            text-align: center;
            user-select: none;
            line-height: 1;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.1);
            position: relative;
            word-break: break-all;
            overflow-wrap: break-word;
            white-space: normal;
            padding: 10px;
        }

        /* COMPLETION BUTTON */
        .completion-container {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            z-index: 5;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }

        .completion-container.show {
            opacity: 1;
            visibility: visible;
        }

        .finish-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 18px 40px;
            border-radius: 50px;
            border: none;
            font-size: 1.4rem;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }

        .finish-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(245, 158, 11, 0.5);
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* KONTROLS - TOMBOL KAPUR */
        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .control-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 50px;
            border: none;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            min-width: 140px;
            justify-content: center;
        }

        .clear-btn {
            background: linear-gradient(135deg, #FF6B6B, #EE5A52);
            color: white;
        }

        .clear-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-3px);
        }

        .undo-btn {
            background: linear-gradient(135deg, #FFD93D, #F4C430);
            color: #333;
        }

        .undo-btn:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-3px);
        }

        .guide-btn {
            background: linear-gradient(135deg, #6BCF7F, #4CAF50);
            color: white;
        }

        .guide-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-3px);
        }

        /* FEEDBACK SECTION */
        .feedback-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            backdrop-filter: blur(10px);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .feedback-section h2 {
            font-size: 1.8rem;
            color: #1e40af;
            margin-bottom: 20px;
            font-family: 'Fredoka One', cursive;
        }

        .progress-container {
            margin: 30px 0;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 15px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 10px;
            width: 0%;
            transition: width 0.5s ease;
        }

        .score-display {
            font-size: 3.5rem;
            font-weight: 900;
            color: #3b82f6;
            margin: 20px 0;
        }

        .stars {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 25px 0;
        }

        .star {
            font-size: 2.5rem;
            color: #e2e8f0;
            transition: all 0.3s;
        }

        .star.filled {
            color: #fbbf24;
            animation: starPop 0.5s ease-out;
        }

        @keyframes starPop {
            0% { transform: scale(0); }
            70% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* AUDIO BUTTON DI DALAM PAPAN TULIS */
        .audio-control-blackboard {
            width: 100%;
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .audio-btn-blackboard {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.9), rgba(124, 58, 237, 0.9));
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .audio-btn-blackboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.4);
            background: linear-gradient(135deg, rgba(139, 92, 246, 1), rgba(124, 58, 237, 1));
        }

        /* ==================== ANIMASI KARAKTER YANG BISA DIGESER ==================== */
        .character-wrapper {
            position: fixed;
            bottom: 30px;
            right: 0;
            transform: translateX(100%);
            z-index: 10;
            width: 360px;
            height: 450px;
            cursor: grab;
            user-select: none;
            touch-action: manipulation;
            transition: transform 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            pointer-events: none;
        }

        .character-wrapper.enter {
            transform: translateX(0);
            right: 30px;
            pointer-events: auto;
        }

        .character-wrapper.exit {
            transform: translateX(100%);
            right: 0;
            pointer-events: none;
        }

        /* State saat karakter sedang di-drag */
        .character-wrapper.dragging {
            cursor: grabbing;
            z-index: 9999;
            transform: scale(1.05) !important;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
            transition: transform 0.1s ease-out !important;
            pointer-events: auto;
        }

        /* State saat karakter dilepas */
        .character-wrapper.dropped {
            animation: dropBounce 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes dropBounce {
            0% { transform: scale(1.05); }
            50% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }

        .character-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: inline-block;
            pointer-events: auto;
        }

        .character-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: auto;
            filter: drop-shadow(0 14px 30px rgba(0,0,0,0.22));
            animation: characterPulse 3.5s ease-in-out infinite;
            transform-origin: center center;
            transition: transform 250ms ease, filter 200ms ease;
        }

        /* Karakter saat diangkat akan menggunakan gambar orangngomongo.png */
        .character-wrapper.dragging .character-image {
            animation: none;
            transform: scale(1.08);
            content: url('{{ asset("storage/AI/orangngomongo.png") }}');
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));
        }

        .character-image:hover {
            transform: scale(1.08);
        }

        @keyframes characterPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.06); }
        }

        /* KOTAK TEKS DI ATAS PERUT */
        .speech-bubble-abdomen {
            position: absolute;
            top: 75%;
            left: 50%;
            transform: translateX(-50%) translateY(0);
            width: 220px;
            height: 72px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 18px;
            padding: 12px;
            box-shadow: 
                0 8px 26px rgba(0, 0, 0, 0.18),
                inset 0 0 0 2px rgba(59,130,246,0.06);
            z-index: 1001;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.45s ease, transform 0.35s ease;
            pointer-events: auto;
        }

        .speech-bubble-abdomen.show {
            opacity: 1;
        }

        .abdomen-text {
            font-family: 'Fredoka One', cursive;
            font-size: 1.6rem;
            color: #1e40af;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.06);
            line-height: 1;
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
        }

        .abdomen-text.typing {
            border-right: 3px solid #3b82f6;
            animation: typingCursor 0.8s infinite;
        }

        @keyframes typingCursor {
            0%, 100% { border-color: transparent; }
            50% { border-color: #3b82f6; }
        }

        /* CHARACTER TOGGLE BUTTON */
        .character-toggle-btn {
            position: fixed;
            bottom: 40px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            z-index: 100;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
            opacity: 1;
            visibility: visible;
        }

        .character-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6);
        }

        .character-toggle-btn:active {
            transform: scale(0.95);
        }

        .character-toggle-btn.hidden {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        @media (max-width: 768px) {
            .character-wrapper {
                width: 260px;
                height: 340px;
            }
            .speech-bubble-abdomen { width: 160px; height: 56px; top: 70%; }
            .abdomen-text { font-size: 1.2rem; }
            .character-toggle-btn {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
                bottom: 35px;
                right: 15px;
            }
        }
        @media (max-width: 480px) {
            .character-wrapper { width: 210px; height: 280px; }
            .speech-bubble-abdomen { width: 140px; height: 50px; top: 65%; }
            .abdomen-text { font-size: 1.05rem; }
            .character-toggle-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                bottom: 30px;
                right: 12px;
            }
        }

        /* ==================== VICTORY MODAL ==================== */
        .victory-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            pointer-events: auto;
        }

        .victory-modal * {
            pointer-events: auto;
        }
 
        body.victory-modal-active {
            overflow: auto !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .victory-content {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 24px;
            padding: 30px;
            text-align: center;
            max-width: 480px;
            width: 92%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: 6px solid transparent;
            position: relative;
            animation: popUp 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            background-clip: padding-box;
            background-image: linear-gradient(white, white), 
                              linear-gradient(135deg, #f59e0b, #10b981, #3b82f6);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        @keyframes popUp {
            0% { transform: scale(0.7); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Modal Close Button */
        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.1rem;
            color: #64748b;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #ef4444;
            color: white;
            transform: rotate(90deg);
        }

        /* Trophy Icon */
        .victory-icon {
            font-size: 5rem;
            color: #fbbf24;
            margin-bottom: 15px;
            animation: trophyFloat 3s ease-in-out infinite;
            text-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
        }

        @keyframes trophyFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Confetti Effect */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 12px;
            height: 12px;
            opacity: 0;
            animation: confettiRain 3s linear forwards;
        }

        @keyframes confettiRain {
            0% { 
                opacity: 1;
                top: -10px;
                transform: rotate(0deg);
            }
            100% { 
                opacity: 0;
                top: 100%;
                transform: rotate(720deg);
            }
        }

        /* Title Styling */
        .victory-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.2rem;
            background: linear-gradient(135deg, #10b981, #3b82f6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 10px 0;
            position: relative;
            display: inline-block;
        }

        .victory-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
            border-radius: 2px;
        }

        /* Main Text Display */
        .victory-text-large {
            font-family: 'Fredoka One', cursive;
            font-size: 4rem;
            color: #1e40af;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            animation: textGlow 2s ease-in-out infinite alternate;
        }

        @keyframes textGlow {
            from { text-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
            to { text-shadow: 0 0 20px rgba(59, 130, 246, 0.8), 0 0 30px rgba(59, 130, 246, 0.6); }
        }

        /* Image Container */
        .victory-image-container {
            width: 100%;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .victory-image-frame {
            width: 180px;
            height: 180px;
            border-radius: 20px;
            overflow: hidden;
            border: 4px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #f59e0b, #ef4444, #8b5cf6) border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            margin: 10px 0;
            position: relative;
        }

        .victory-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
        }

        /* Stars Display */
        .stars-display {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .star-icon {
            font-size: 2.8rem;
            color: #e2e8f0;
            transition: all 0.3s;
            animation: starTwinkle 3s infinite;
        }

        .star-icon.filled {
            color: #fbbf24;
            animation: starTwinkle 1.5s infinite;
        }

        .star-icon:nth-child(2) { animation-delay: 0.5s; }
        .star-icon:nth-child(3) { animation-delay: 1s; }

        @keyframes starTwinkle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(0.9); }
        }

        /* Achievement Stats */
        .achievement-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 16px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e40af;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            justify-content: center;
        }

        .modal-btn {
            padding: 14px 28px;
            border-radius: 50px;
            border: none;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            min-width: 140px;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .modal-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }

        .modal-btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .modal-btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }

        /* Audio Button */
        .audio-btn-small {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            border: none;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 15px 0;
            transition: all 0.3s;
        }

        .audio-btn-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
        }

        /* Success Message */
        .success-message {
            font-size: 1.2rem;
            color: #059669;
            margin: 15px 0;
            font-weight: 600;
            background: #d1fae5;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            animation: messagePop 0.5s ease-out;
        }

        @keyframes messagePop {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* TOMBOL PLAY GREETING */
        .greeting-play-btn {
            position: absolute;
            top: -60px;
            right: 10px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1003;
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.4);
            transition: all 0.3s;
        }

        .greeting-play-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.6);
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            .canvas-section {
                border-width: 8px;
                padding: 30px 20px 20px 20px;
                min-height: 600px;
            }
            
            .canvas-section h2 {
                font-size: 1.8rem;
            }
            
            .canvas-wrapper {
                min-height: 400px;
            }
            
            .image-frame-blackboard {
                width: 140px;
                height: 140px;
            }
            
            .writing-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .back-btn, .type-badge {
                width: 100%;
                max-width: 200px;
            }
            
            .writing-title h1 {
                font-size: 1.8rem;
            }
            
            .control-btn {
                min-width: 120px;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .canvas-section {
                min-height: 500px;
            }
            
            .canvas-wrapper {
                min-height: 350px;
            }
            
            .finish-btn {
                padding: 14px 25px;
                font-size: 1.1rem;
            }
            
            .score-display {
                font-size: 2.8rem;
            }
        }
    </style>

    <!-- Audio Elements -->
    <audio id="greetingAudio" style="display: none;"></audio>
    <audio id="progressAudio" preload="auto" style="display: none;"></audio>
    <audio id="selamatAudio" preload="auto" style="display: none;"></audio>
    <audio id="playAudio" autoplay preload="auto" style="display: none;"></audio>

    <script>
    // Inisialisasi audio untuk progress dan selamat
    document.addEventListener('DOMContentLoaded', function() {
        // Audio untuk progress (50% sekali saja)
        const progressAudio = document.getElementById('progressAudio');
        progressAudio.src = '{{ asset("storage/music/hebat.mp3") }}';
        
        // Audio untuk selamat
        const selamatAudio = document.getElementById('selamatAudio');
        selamatAudio.src = '{{ asset("storage/music/selamat.mp3") }}';
        
        // Audio untuk play (autoplay)
        const playAudio = document.getElementById('playAudio');
        playAudio.src = '{{ asset("storage/music/play.mp3") }}';
        playAudio.volume = 0.8;
        playAudio.play().catch(e => {
            console.log('Play audio autoplay failed (browser may require user interaction):', e);
        });
        
        // Greeting audio
        const greetingAudio = document.getElementById('greetingAudio');
        
        @if(isset($audioGreeting) && $audioGreeting)
        greetingAudio.src = '{{ $audioGreeting }}';
        greetingAudio.preload = 'auto';
        greetingAudio.crossOrigin = 'anonymous';

        const greetingText = {!! json_encode($greetingText ?? '') !!};
        window.greetingText = greetingText;

        window.attachAudioCharacterSync = function(audio, bubbleText) {
            audio.onplay = null;
            audio.onended = null;
            audio.onpause = null;

            audio.onplay = function() {
                if (window.character) {
                    try {
                        window.character.startTalking();
                        if (bubbleText) {
                            window.character.showAbdomenText(bubbleText, true);
                        }
                    } catch(e){ console.error(e); }
                }
            };

            const stopHandler = function() {
                if (window.character) {
                    try {
                        window.character.stopTalking();
                    } catch(e){ console.error(e); }
                }
            };
            audio.onended = stopHandler;
            audio.onpause = stopHandler;
        }

        function tryAutoPlayGreeting() {
            window.attachAudioCharacterSync(greetingAudio, greetingText);

            setTimeout(() => {
                greetingAudio.play().then(() => {
                    // played successfully
                }).catch((err) => {
                    const onFirstInteraction = () => {
                        greetingAudio.play().catch(e => {
                            showGreetingButton();
                        });
                        document.removeEventListener('pointerdown', onFirstInteraction);
                        document.removeEventListener('touchstart', onFirstInteraction);
                    };
                    document.addEventListener('pointerdown', onFirstInteraction, { once: true });
                    document.addEventListener('touchstart', onFirstInteraction, { once: true });
                    
                    setTimeout(() => {
                        showGreetingButton(true);
                    }, 900);
                });
            }, 1000);
        }
        
        tryAutoPlayGreeting();
        @endif
        
        function showGreetingButton() {
            // Opsi untuk menampilkan tombol play manual
            return;
        }
    });
    </script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="writing-header">
            <a href="{{ url()->previous() }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <div class="writing-title">
                <h1>Praktik Menulis</h1>
                <p class="writing-subtitle">Level {{ $writingItem->level->order_number ?? '1' }} - {{ $writingItem->level->module->name ?? 'Module' }}</p>
            </div>
            
            <div class="type-badge">
                {{ $writingItem->type === 'letter' ? 'HURUF' : 'KATA' }}
            </div>
        </div>

        <!-- Main Content - Papan Tulis di Tengah -->
        <div class="writing-container">
            <!-- PAPAN TULIS SECTION -->
            <div class="canvas-section">
                <h2>Papan Tulis Menulis</h2>
                
                <!-- Image Preview di dalam papan tulis -->
                @if($writingItem->image_path)
                <div class="blackboard-image-preview">
                    <div class="image-frame-blackboard">
                        <img src="{{ asset('storage/' . $writingItem->image_path) }}" 
                             alt="{{ $writingItem->text }}" 
                             class="preview-image-blackboard"
                             id="previewImage"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'color:#FFFFFF; padding:20px; text-align:center\'>Gambar tidak tersedia</div>';">
                    </div>
                </div>
                @endif
                
                <!-- Audio Control di dalam papan tulis -->
                @if($writingItem->audio_path)
                <div class="audio-control-blackboard">
                    <button class="audio-btn-blackboard" onclick="playAudio()">
                        <i class="fas fa-volume-up"></i> Dengarkan Pengucapan
                    </button>
                    <audio id="audioPlayer" src="{{ asset('storage/' . $writingItem->audio_path) }}" preload="auto"></audio>
                </div>
                @endif
                
                <div class="canvas-wrapper">
                    <!-- Tracing Guide Text -->
                    <div class="tracing-guide">
                        <div class="tracing-text" id="tracingText">{{ $writingItem->text }}</div>
                    </div>
                    
                    <!-- Drawing Canvas -->
                    <canvas id="writingCanvas"></canvas>
                    
                    <!-- Finish Button -->
                    <div class="completion-container" id="completionContainer">
                        <button class="finish-btn" onclick="showVictoryModal()">
                            <i class="fas fa-check-circle"></i> Saya Sudah Selesai!
                        </button>
                    </div>
                </div>

                <!-- Controls -->
                <div class="controls">
                    <button class="control-btn clear-btn" onclick="clearCanvas()">
                        <i class="fas fa-broom"></i> Hapus Semua
                    </button>
                    
                    <button class="control-btn undo-btn" onclick="undoLastStroke()">
                        <i class="fas fa-undo"></i> Batalkan
                    </button>
                    
                    <button class="control-btn guide-btn" onclick="toggleGuide()" id="guideBtn">
                        <i class="fas fa-eye"></i> Sembunyikan Garis
                    </button>
                </div>
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="feedback-section">
            <h2>Progress Belajarmu</h2>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="score-display" id="scoreDisplay">0%</div>
            </div>
            
            <div class="stars">
                @for($i = 1; $i <= 3; $i++)
                    <span class="star" id="star{{ $i }}">
                        <i class="fas fa-star"></i>
                    </span>
                @endfor
            </div>
            
            <div id="encouragementText" style="font-size: 1.3rem; color: #475569; margin: 20px 0; min-height: 30px;">
                Mulailah menulis untuk melihat progress-mu!
            </div>
        </div>
    </div>

    <!-- ANIMATED CHARACTER -->
    <div class="character-wrapper" id="characterWrapper">
        <button class="greeting-play-btn" id="greetingPlayBtn" style="display: none;">
            <i class="fas fa-volume-up"></i>
        </button>
        <div id="characterContainer" class="character-container">
            <!-- Kotak teks di perut -->
            <div class="speech-bubble-abdomen" id="abdomenBubble">
                <div class="abdomen-text" id="abdomenText"></div>
            </div>
        </div>
    </div>

    <!-- CHARACTER TOGGLE BUTTON -->
    <button class="character-toggle-btn" id="characterToggleBtn" title="Sembunyikan/Tampilkan Animasi">
        <i class="fas fa-eye"></i>
    </button>

    <!-- Victory Modal -->
    <div class="victory-modal" id="victoryModal">
        <div class="confetti-container" id="confettiContainer"></div>
        
        <div class="victory-content">
            <button class="modal-close" onclick="closeVictoryModal()">
                <i class="fas fa-times"></i>
            </button>

            <h2 class="victory-title">SELAMAT!</h2>
            
            @if($writingItem->image_path)
            <div class="victory-image-container">
                <div class="victory-image-frame">
                    <img src="{{ asset('storage/' . $writingItem->image_path) }}" 
                         alt="{{ $writingItem->text }}" 
                         class="victory-image"
                         id="modalImage"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'color:#64748b; padding:20px; text-align:center\'>Gambar tidak tersedia</div>';">
                </div>
            </div>
            @endif

            <div class="stars-display" id="modalStarsDisplay">
                <span class="star-icon"><i class="fas fa-star"></i></span>
                <span class="star-icon"><i class="fas fa-star"></i></span>
                <span class="star-icon"><i class="fas fa-star"></i></span>
            </div>

            <div class="achievement-stats">
                <div class="stat-item">
                    <span class="stat-label">Skor Akhir</span>
                    <span class="stat-value" id="modalScore">0%</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Tipe</span>
                    <span class="stat-value">{{ $writingItem->type === 'letter' ? 'Huruf' : 'Kata' }}</span>
                </div>
            </div>

            @if($writingItem->audio_path)
            <div>
                <button class="audio-btn-small" onclick="playVictoryAudio()">
                    <i class="fas fa-volume-up"></i> Dengarkan Lagi
                </button>
                <audio id="victoryAudio" preload="auto" src="{{ asset('storage/' . $writingItem->audio_path) }}"></audio>
            </div>
            @endif

            <div class="action-buttons">
                <button class="modal-btn modal-btn-primary" onclick="tryAgain()">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
                <button class="modal-btn modal-btn-success" onclick="continueToNext()">
                    <i class="fas fa-arrow-right"></i> Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        // ==================== ANIMATED CHARACTER CLASS DENGAN DRAG & DROP DAN WAVE ====================
        class DraggableAnimatedCharacter {
            constructor(containerId, options = {}) {
                this.container = document.getElementById(containerId);
                if (!this.container) {
                    console.error(`Container with id "${containerId}" not found`);
                    return;
                }

                // Gambar-gambar untuk berbagai state
                this.images = {
                    idle: options.images?.idle || '{{ asset("storage/AI/orangdiammelek.png") }}',
                    blink: options.images?.blink || '{{ asset("storage/AI/orangdiammerem.png") }}',
                    talk: options.images?.talk || '{{ asset("storage/AI/orangngomonga.png") }}',
                    talk2: options.images?.talk2 || '{{ asset("storage/AI/orangngomongi.png") }}',
                    waveLeft: options.images?.waveLeft || '{{ asset("storage/AI/oranglambaitangana.png") }}',
                    waveRight: options.images?.waveRight || '{{ asset("storage/AI/oranglambaitangano.png") }}',
                    lifted: options.images?.lifted || '{{ asset("storage/AI/orangngomongo.png") }}'
                };

                this.settings = {
                    blinkInterval: options.blinkInterval || 3000,
                    talkSpeed: options.talkSpeed || 200,
                    autoBlink: options.autoBlink !== false,
                    abdomenMessages: options.abdomenMessages || ["Hallo!", "Semangat aja!"],
                    abdomenDisplayTime: options.abdomenDisplayTime || 2400,
                    waveDuration: options.waveDuration || 800,
                    ...options
                };

                this.isTalking = false;
                this.isWaving = false;
                this.isDragging = false;
                this.talkInterval = null;
                this.blinkInterval = null;
                this.isMouthOpen = false;
                this.isBlinking = false;
                this.abdomenBubble = null;
                this.abdomenTextElement = null;
                this.abdomenLoopInterval = null;
                this.abdomenTimeout = null;
                this.isEntered = false;
                this.currentImageState = 'idle';
                this.wrapper = document.getElementById('characterWrapper');
                this.dragOffsetX = 0;
                this.dragOffsetY = 0;
                this.clickStartTime = 0;
                this.clickStartX = 0;
                this.clickStartY = 0;
                this.isClick = false;

                // Sound effects
                this.halloAudio = new Audio('{{ asset("storage/music/hallo.mp3") }}');
                this.halloAudio.preload = 'auto';

                // Efek suara wave kiri/kanan (abunasu)
                this.waveLeftAudio = new Audio('{{ asset("storage/music/abunasu_oranglambaitangana.mp3") }}');
                this.waveLeftAudio.preload = 'auto';

                this.waveRightAudio = new Audio('{{ asset("storage/music/abunasu_oranglambaitangano.mp3") }}');
                this.waveRightAudio.preload = 'auto';

                this.init();
            }

            init() {
                // Create image element
                this.imgElement = document.createElement('img');
                this.imgElement.className = 'character-image';
                this.imgElement.alt = 'Teman Belajar';
                this.imgElement.style.width = '100%';
                this.imgElement.style.height = '100%';
                this.imgElement.style.objectFit = 'contain';
                this.imgElement.style.cursor = 'pointer';
                
                // Set initial state
                this.setImage(this.images.idle);
                this.currentImageState = 'idle';
                this.container.appendChild(this.imgElement);

                // Get abdomen bubble elements
                this.abdomenBubble = document.getElementById('abdomenBubble');
                this.abdomenTextElement = document.getElementById('abdomenText');

                // Setup drag & drop dan click events
                this.setupEvents();

                // Start auto blink if enabled
                if (this.settings.autoBlink) {
                    this.startAutoBlink();
                }

                console.log('Draggable character initialized with extended animations:', this.container.id);
            }

            setupEvents() {
                // Event untuk mulai interaksi (mouse)
                this.wrapper.addEventListener('mousedown', (e) => this.startInteraction(e));
                
                // Event untuk mulai interaksi (touch)
                this.wrapper.addEventListener('touchstart', (e) => {
                    const touch = e.touches[0];
                    this.startInteraction(touch);
                }, { passive: true });

                // Event untuk drag movement (mouse)
                document.addEventListener('mousemove', (e) => this.handleMove(e));
                
                // Event untuk drag movement (touch) - hanya preventDefault saat drag
                document.addEventListener('touchmove', (e) => {
                    const touch = e.touches[0];
                    this.handleMove(touch, e);
                }, { passive: true });

                // Event untuk berhenti interaksi (mouse)
                document.addEventListener('mouseup', (e) => this.stopInteraction(e));
                
                // Event untuk berhenti interaksi (touch)
                document.addEventListener('touchend', (e) => this.stopInteraction(e));
                
                // Event untuk cancel interaksi
                document.addEventListener('touchcancel', () => this.stopInteraction());
                
                // Cegah event default untuk drag
                this.wrapper.addEventListener('dragstart', (e) => e.preventDefault());
            }

            startInteraction(e) {
                this.clickStartTime = Date.now();
                this.clickStartX = e.clientX;
                this.clickStartY = e.clientY;
                this.isClick = true;
                
                // Mulai drag setelah delay kecil untuk membedakan click dan drag
                setTimeout(() => {
                    if (this.isClick) {
                        // Ini adalah drag (bukan click)
                        this.startDrag(e);
                        this.isClick = false;
                    }
                }, 150);
            }

            handleMove(e, touchEvent) {
                if (!this.isDragging && this.isClick) {
                    // Cek apakah gerakan cukup besar untuk dianggap sebagai drag
                    const moveX = Math.abs(e.clientX - this.clickStartX);
                    const moveY = Math.abs(e.clientY - this.clickStartY);
                    
                    if (moveX > 5 || moveY > 5) {
                        // Ini adalah drag, bukan click
                        this.startDrag({
                            clientX: this.clickStartX,
                            clientY: this.clickStartY
                        });
                        this.isClick = false;
                        // Hanya preventDefault saat drag dimulai
                        if (touchEvent) touchEvent.preventDefault();
                    }
                }
                
                if (this.isDragging) {
                    this.drag(e);
                    // Prevent scroll hanya saat sedang drag
                    if (touchEvent) touchEvent.preventDefault();
                }
            }

            stopInteraction(e) {
                if (this.isDragging) {
                    this.stopDrag();
                } else if (this.isClick) {
                    // Ini adalah click, bukan drag
                    const clickDuration = Date.now() - this.clickStartTime;
                    if (clickDuration < 200) {
                        // Quick click untuk wave
                        this.waveAndTalk();
                    }
                }
                
                this.isClick = false;
            }

            startDrag(e) {
                if (this.isDragging) return;
                
                this.isDragging = true;
                
                // Tambahkan class dragging untuk styling
                this.wrapper.classList.add('dragging');
                
                // Hitung offset antara posisi klik dan posisi wrapper
                const rect = this.wrapper.getBoundingClientRect();
                this.dragOffsetX = e.clientX - rect.left;
                this.dragOffsetY = e.clientY - rect.top;
                
                // Ganti gambar saat diangkat (menggunakan orangngomongo.png)
                this.setImage(this.images.lifted);
                
                
                // Hentikan animasi float
                this.imgElement.style.animation = 'none';
                
                // Prevent default untuk menghindari seleksi teks
                if (e.preventDefault) e.preventDefault();
            }

            drag(e) {
                if (!this.isDragging) return;
                
                // Hitung posisi baru
                const newX = e.clientX - this.dragOffsetX;
                const newY = e.clientY - this.dragOffsetY;
                
                // Batasi posisi dalam viewport
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;
                const wrapperWidth = this.wrapper.offsetWidth;
                const wrapperHeight = this.wrapper.offsetHeight;
                
                const boundedX = Math.max(0, Math.min(newX, viewportWidth - wrapperWidth));
                const boundedY = Math.max(0, Math.min(newY, viewportHeight - wrapperHeight));
                
                // Update posisi
                this.wrapper.style.left = boundedX + 'px';
                this.wrapper.style.top = boundedY + 'px';
                this.wrapper.style.right = 'auto';
                this.wrapper.style.bottom = 'auto';
            }

            stopDrag() {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                
                // Hapus class dragging dan tambahkan efek bounce
                this.wrapper.classList.remove('dragging');
                this.wrapper.classList.add('dropped');
                
                // Kembalikan gambar ke normal berdasarkan state saat ini
                if (this.isTalking) {
                    this.setImage(this.currentImageState === 'talk' ? this.images.talk : this.images.talk2);
                } else if (this.isWaving) {
                    // Jika sedang waving, tetap pertahankan state wave
                } else {
                    this.setImage(this.images.idle);
                }
                
                
                // Kembalikan animasi float
                setTimeout(() => {
                    this.imgElement.style.animation = 'characterPulse 3.5s ease-in-out infinite';
                    this.wrapper.classList.remove('dropped');
                }, 500);
            }

            // Wave animation dengan bergantian tangan
            async waveAndTalk() {
                if (this.isWaving || this.isDragging) return;
                
                this.isWaving = true;
                // hentikan bicara kalau sedang berlangsung (aman)
                this.stopTalking();
                
              
                // Mulai mainkan hallo.mp3 segera
                if (this.halloAudio) {
                    try {
                        this.halloAudio.currentTime = 0;
                        this.halloAudio.play().catch(e => console.log('hallo play failed:', e));
                    } catch (e) {
                        console.warn('halloAudio play error:', e);
                    }
                }
                
                // Tampilkan dan mainkan wave kiri
                this.setImage(this.images.waveLeft);
                await this.playAudioElement(this.waveLeftAudio).catch(()=>{});
                await this.delay(160); // jeda kecil
                
                // Tampilkan dan mainkan wave kanan
                this.setImage(this.images.waveRight);
                await this.playAudioElement(this.waveRightAudio).catch(()=>{});
                await this.delay(160); // jeda kecil
                
                // Selesai: kembali ke idle
                this.setImage(this.images.idle);
                this.isWaving = false;
                
                // Pastikan bicara benar-benar berhenti jika ada
                setTimeout(() => {
                    if (this.isTalking) {
                        this.stopTalking();
                    }
                }, 300);
            }

            enterFromRight() {
                if (this.isEntered) return;
                const wrapper = this.wrapper;
                if (!wrapper) return;
                wrapper.classList.remove('enter');
                wrapper.classList.remove('exit');
                void wrapper.offsetWidth;
                setTimeout(() => {
                    wrapper.classList.add('enter');
                    this.isEntered = true;
                    setTimeout(() => {
                        this.startAbdomenLoop();
                    }, 700);

                    if (typeof window.playGreetingOnEnter === 'function') {
                        window.playGreetingOnEnter();
                    }
                }, 1000);
            }

            exitToRight() {
                if (!this.isEntered) return;
                
                const wrapper = this.wrapper;
                if (!wrapper) return;
                
                wrapper.classList.remove('enter');
                wrapper.classList.add('exit');
                this.isEntered = false;
                
                this.stopAbdomenLoop();
                this.hideAbdomenText();
            }

            setImage(src) {
                if (this.imgElement.src !== src) {
                    this.imgElement.src = src;
                }
            }

            blink() {
                if (this.isBlinking || this.isWaving || this.isDragging) return;
                
                this.isBlinking = true;
                
                const wasTalking = this.isTalking;
                const wasMouthOpen = this.isMouthOpen;
                
                this.setImage(this.images.blink);
                
                setTimeout(() => {
                    this.isBlinking = false;
                    
                    if (wasTalking) {
                        if (wasMouthOpen) {
                            this.setImage(this.currentImageState === 'talk' ? this.images.talk : this.images.talk2);
                        } else {
                            this.setImage(this.images.idle);
                        }
                    } else {
                        this.setImage(this.images.idle);
                    }
                }, 150);
            }

            startAutoBlink() {
                this.blinkInterval = setInterval(() => {
                    if (!this.isBlinking && !this.isWaving && !this.isDragging) {
                        this.blink();
                    }
                }, this.settings.blinkInterval);
            }

            delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            // Helper untuk memainkan audio dan menunggu selesai
            playAudioElement(audio) {
                if (!audio) return Promise.resolve();
                return new Promise(resolve => {
                    let resolved = false;
                    function cleanup() {
                        if (resolved) return;
                        resolved = true;
                        resolve();
                    }
                    audio.currentTime = 0;
                    audio.play().then(() => {
                        audio.addEventListener('ended', cleanup, { once: true });
                        audio.addEventListener('error', cleanup, { once: true });
                    }).catch((err) => {
                        console.log('Audio play failed (continue):', err);
                        cleanup();
                    });
                });
            }

            startTalking() {
                if (this.isTalking || this.isDragging) return;
                
                this.isTalking = true;
                
                // Bergantian antara dua gambar bicara untuk animasi yang lebih hidup
                this.talkInterval = setInterval(() => {
                    this.isMouthOpen = !this.isMouthOpen;
                    
                    if (!this.isBlinking && !this.isWaving && !this.isDragging) {
                        if (this.isMouthOpen) {
                            // Bergantian antara dua gambar bicara
                            this.currentImageState = Math.random() > 0.5 ? 'talk' : 'talk2';
                            this.setImage(this.currentImageState === 'talk' ? this.images.talk : this.images.talk2);
                        } else {
                            this.setImage(this.images.idle);
                        }
                    }
                }, this.settings.talkSpeed);
            }

            stopTalking() {
                if (!this.isTalking) return;
                
                this.isTalking = false;
                
                if (this.talkInterval) {
                    clearInterval(this.talkInterval);
                    this.talkInterval = null;
                }
                
                if (!this.isBlinking && !this.isWaving && !this.isDragging) {
                    this.setImage(this.images.idle);
                }
                this.isMouthOpen = false;
            }

            // Show abdomen text dengan efek ketikan
            showAbdomenText(text, withAnimation = true) {
                if (!this.abdomenBubble || !this.abdomenTextElement) return;
                
                if (this.abdomenTimeout) {
                    clearTimeout(this.abdomenTimeout);
                    this.abdomenTimeout = null;
                }

                this.abdomenTextElement.textContent = '';
                this.abdomenBubble.classList.add('show');
                
                if (withAnimation) {
                    this.abdomenTextElement.classList.add('typing');
                    let i = 0;
                    const typeWriter = () => {
                        if (i < text.length) {
                            this.abdomenTextElement.textContent += text.charAt(i);
                            i++;
                            this.abdomenTimeout = setTimeout(typeWriter, 90);
                        } else {
                            this.abdomenTextElement.classList.remove('typing');
                            this.abdomenTimeout = setTimeout(() => {
                                // Biarkan text terlihat sebentar
                            }, this.settings.abdomenDisplayTime);
                        }
                    };
                    typeWriter();
                } else {
                    this.abdomenTextElement.textContent = text;
                    this.abdomenTimeout = setTimeout(() => {}, this.settings.abdomenDisplayTime);
                }
            }

            hideAbdomenText() {
                if (this.abdomenBubble) {
                    if (this.abdomenTimeout) {
                        clearTimeout(this.abdomenTimeout);
                        this.abdomenTimeout = null;
                    }
                    this.abdomenBubble.classList.remove('show');
                    if (this.abdomenTextElement) {
                        this.abdomenTextElement.textContent = '';
                        this.abdomenTextElement.classList.remove('typing');
                    }
                }
            }

            startAbdomenLoop() {
                this.stopAbdomenLoop();
                
                // Tampilkan "Nusa AI" saja tanpa perulangan
                this.showAbdomenText("Nusa AI", false); // false = tanpa efek ketikan
                
                // Buat loop interval untuk memastikan teks tetap ada
                this.abdomenLoopInterval = setInterval(() => {
                    // Cek apakah teks masih ada, jika tidak, tampilkan lagi
                    if (!this.abdomenTextElement || this.abdomenTextElement.textContent !== "Nusa AI") {
                        this.showAbdomenText("Nusa AI", false);
                    }
                }, 5000); // Cek setiap 5 detik
            }

            stopAbdomenLoop() {
                if (this.abdomenLoopInterval) {
                    clearInterval(this.abdomenLoopInterval);
                    this.abdomenLoopInterval = null;
                }
                if (this.abdomenTimeout) {
                    clearTimeout(this.abdomenTimeout);
                    this.abdomenTimeout = null;
                }
            }

            destroy() {
                this.stopTalking();
                if (this.blinkInterval) {
                    clearInterval(this.blinkInterval);
                }
                if (this.imgElement && this.imgElement.parentNode) {
                    this.imgElement.parentNode.removeChild(this.imgElement);
                }
                this.hideAbdomenText();
                this.stopAbdomenLoop();
                
                // Remove events
                document.removeEventListener('mousemove', this.handleMove);
                document.removeEventListener('touchmove', this.handleMove);
                document.removeEventListener('mouseup', this.stopInteraction);
                document.removeEventListener('touchend', this.stopInteraction);
            }
        }

        // ==================== GLOBAL VARIABLES ====================
        let drawingState = {
            isDrawing: false,
            lastX: 0,
            lastY: 0,
            strokes: [],
            currentStroke: [],
            score: 0,
            stars: 0,
            isGuideVisible: true,
            brushColor: '#FFFFFF', // Warna kapur putih
            brushSize: 8,
            isCompleted: false,
            totalStrokes: 0,
            currentDistance: 0,
            victoryShown: false,
            autoCloseTimer: null,
            hasInteracted: false,
            encouragementMessages: [
                "Mulailah menulis untuk melihat progress-mu!",
                "Bagus! Teruskan menulis...",
                "Hebat! Tulisanmu semakin baik!",
                "Wah, hampir sempurna!",
                "LUAR BIASA! Tulisanmu sempurna! 🎉"
            ],
            progress50Played: false, // Flag untuk audio 50% (hanya sekali)
            lastProgressSound: 0
        };
        
        const canvas = document.getElementById('writingCanvas');
        const ctx = canvas.getContext('2d');
        const targetText = "{{ $writingItem->text }}";
        
        let character = null;

        // ==================== PERHITUNGAN PROGRES BERDASARKAN TULISAN ====================
        // Hitung kompleksitas tulisan untuk menentukan berapa stroke yang dibutuhkan untuk 50%
        function calculateTargetStrokes() {
            const textLength = targetText.length;
            const isLetter = "{{ $writingItem->type }}" === 'letter';
            
            // Untuk huruf tunggal: 20-30 stroke untuk 100%
            // Untuk kata: 30-50 stroke untuk 100%
            if (isLetter) {
                return Math.floor(20 + Math.random() * 10); // 20-30 stroke
            } else {
                return Math.floor(30 + Math.random() * 20); // 30-50 stroke
            }
        }

        const targetStrokesFor100 = calculateTargetStrokes();
        const targetStrokesFor50 = Math.floor(targetStrokesFor100 * 0.5);

        console.log(`Target strokes untuk 100%: ${targetStrokesFor100}`);
        console.log(`Target strokes untuk 50%: ${targetStrokesFor50}`);

        // ==================== MAIN INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function() {
            initWritingApp();

            setTimeout(() => {
                try {
                    character = new DraggableAnimatedCharacter('characterContainer', {
                        blinkInterval: 2500,
                        talkSpeed: 200,
                        autoBlink: true,
                        abdomenMessages: ["Nusa AI"],
                        abdomenDisplayTime: 2400,
                        waveDuration: 800,
                        images: {
                            idle: '{{ asset("storage/AI/orangdiammelek.png") }}',
                            blink: '{{ asset("storage/AI/orangdiammerem.png") }}',
                            talk: '{{ asset("storage/AI/orangngomonga.png") }}',
                            talk2: '{{ asset("storage/AI/orangngomongi.png") }}',
                            waveLeft: '{{ asset("storage/AI/oranglambaitangana.png") }}',
                            waveRight: '{{ asset("storage/AI/oranglambaitangano.png") }}',
                            lifted: '{{ asset("storage/AI/orangngomongo.png") }}'
                        }
                    });

                    window.character = character;

                    // Function untuk binding audio dengan karakter
                    function bindAudioToCharacterById(id, bubbleText) {
                        const audioEl = document.getElementById(id);
                        if (!audioEl || !character) return;
                        
                        // Reset semua event listeners
                        audioEl.onplay = null;
                        audioEl.onended = null;
                        audioEl.onpause = null;
                        
                        audioEl.onplay = function() {
                            console.log('Audio started playing:', id);
                            character.startTalking();
                            if (bubbleText) {
                                character.showAbdomenText(bubbleText, true);
                            }
                        };
                        
                        const stopHandler = function() {
                            console.log('Audio stopped:', id);
                            character.stopTalking();
                        };
                        
                        audioEl.onended = stopHandler;
                        audioEl.onpause = stopHandler;
                        
                        // Jika audio sedang diputar, trigger onplay
                        if (!audioEl.paused && !audioEl.ended && audioEl.currentTime > 0) {
                            try {
                                character.startTalking();
                                if (bubbleText) {
                                    character.showAbdomenText(bubbleText, true);
                                }
                            } catch (e) { console.error(e); }
                        }
                    }

                    // Bind audio untuk greeting
                    bindAudioToCharacterById('greetingAudio', window.greetingText || '');
                    
                    // Bind audio untuk audioPlayer (preview)
                    const audioPlayer = document.getElementById('audioPlayer');
                    if (audioPlayer) {
                        bindAudioToCharacterById('audioPlayer', targetText);
                    }
                    
                    // Bind audio untuk victoryAudio dengan text yang sesuai
                    const victoryAudio = document.getElementById('victoryAudio');
                    if (victoryAudio) {
                        bindAudioToCharacterById('victoryAudio', "Selamat! Kamu berhasil menulis " + targetText);
                    }
                    
                    // Bind untuk progressAudio (50% sekali saja)
                    const progressAudio = document.getElementById('progressAudio');
                    if (progressAudio) {
                        progressAudio.onplay = function() {
                            if (character) {
                                character.startTalking();
                                // Tampilkan teks khusus untuk 50%
                                character.showAbdomenText("Hebat! Sudah 50% selesai! 🎉", true);
                            }
                        };
                        progressAudio.onended = function() {
                            if (character) {
                                character.stopTalking();
                            }
                        };
                    }

                    setTimeout(() => {
                        if (character) {
                            character.enterFromRight();
                        }
                    }, 1000);
                    
                    canvas.addEventListener('mouseup', onDrawingStop);
                    canvas.addEventListener('touchend', onDrawingStop);

                    // ==================== CHARACTER TOGGLE BUTTON ====================
                    const toggleBtn = document.getElementById('characterToggleBtn');
                    const characterWrapper = document.getElementById('characterWrapper');
                    
                    if (toggleBtn && characterWrapper) {
                        toggleBtn.addEventListener('click', function() {
                            const isHidden = characterWrapper.style.display === 'none';
                            
                            if (isHidden) {
                                // Show character
                                characterWrapper.style.display = 'block';
                                toggleBtn.classList.remove('hidden');
                                toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                                toggleBtn.title = 'Sembunyikan Animasi';
                            } else {
                                // Hide character
                                characterWrapper.style.display = 'none';
                                toggleBtn.classList.add('hidden');
                                toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                                toggleBtn.title = 'Tampilkan Animasi';
                            }
                        });
                    }
                    
                } catch (error) {
                    console.error('Failed to initialize character:', error);
                }
            }, 1500);
        });

        // ==================== AUDIO FUNCTIONS ====================
        function playProgressSound() {
            const progressAudio = document.getElementById('progressAudio');
            if (progressAudio && progressAudio.src) {
                progressAudio.currentTime = 0;
                progressAudio.play().catch(e => {
                    console.log('Progress audio play failed:', e);
                });
            }
        }

        function playSelamatSound() {
            const selamatAudio = document.getElementById('selamatAudio');
            if (selamatAudio && selamatAudio.src) {
                selamatAudio.currentTime = 0;
                selamatAudio.play().catch(e => {
                    console.log('Selamat audio play failed:', e);
                });
            }
        }

        function checkAndPlayProgressSound(currentStrokes) {
            const now = Date.now();
            const timeSinceLastSound = now - drawingState.lastProgressSound;
            
            // Cek jika mencapai 50% dari target dan belum pernah diputar
            if (currentStrokes >= targetStrokesFor50 && 
                !drawingState.progress50Played && 
                timeSinceLastSound > 3000) {
                
                // Mainkan sound hebat.mp3
                playProgressSound();
                drawingState.progress50Played = true;
                drawingState.lastProgressSound = now;
                
                return;
            }
        }

        // ==================== WRITING APP FUNCTIONS ====================
        function initWritingApp() {
            resizeCanvasAndScale();
            initEventListeners();
            initDrawingState();
            updateFontSizes();
        }

        function updateBrush() {
            ctx.strokeStyle = drawingState.brushColor;
            ctx.lineWidth = drawingState.brushSize;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.globalAlpha = 0.9;
        }

        function resizeCanvasAndScale() {
            const wrapper = document.querySelector('.canvas-wrapper');
            if (!wrapper) return;

            const dpr = window.devicePixelRatio || 1;
            const cssWidth = wrapper.offsetWidth;
            const cssHeight = wrapper.offsetHeight;

            const oldWidth = canvas.width ? canvas.width / (window.devicePixelRatio || 1) : 0;
            const oldHeight = canvas.height ? canvas.height / (window.devicePixelRatio || 1) : 0;

            let scaleX = 1, scaleY = 1;
            if (oldWidth && oldHeight) {
                scaleX = cssWidth / oldWidth;
                scaleY = cssHeight / oldHeight;
            }

            if (drawingState.strokes && (scaleX !== 1 || scaleY !== 1)) {
                drawingState.strokes = drawingState.strokes.map(stroke =>
                    stroke.map(pt => ({ x: pt.x * scaleX, y: pt.y * scaleY }))
                );
            }

            canvas.width = Math.round(cssWidth * dpr);
            canvas.height = Math.round(cssHeight * dpr);
            canvas.style.width = cssWidth + 'px';
            canvas.style.height = cssHeight + 'px';

            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            updateBrush();
            redrawCanvas();
            updateFontSizes();
        }

        function initEventListeners() {
            if (!canvas) return;

            canvas.addEventListener('pointerdown', function(e) {
                canvas.setPointerCapture && canvas.setPointerCapture(e.pointerId);
                startDrawing(e);
            });
            canvas.addEventListener('pointermove', draw);
            canvas.addEventListener('pointerup', function(e) {
                try { canvas.releasePointerCapture && canvas.releasePointerCapture(e.pointerId); } catch(e){}
                stopDrawing(e);
            });
            canvas.addEventListener('pointercancel', function(e) {
                try { canvas.releasePointerCapture && canvas.releasePointerCapture(e.pointerId); } catch(e){}
                stopDrawing(e);
            });

            canvas.addEventListener('contextmenu', function(e) { e.preventDefault(); });

            window.addEventListener('resize', function() {
                resizeCanvasAndScale();
                updateFontSizes();
            });
        }

        function getCoordinates(e) {
            const rect = canvas.getBoundingClientRect();
            let clientX, clientY;
            if (e.type && e.type.includes('touch')) {
                const t = e.touches && e.touches[0] ? e.touches[0] : (e.changedTouches && e.changedTouches[0]);
                clientX = t.clientX;
                clientY = t.clientY;
            } else if (e.clientX !== undefined) {
                clientX = e.clientX; clientY = e.clientY;
            } else if (e.touches && e.touches[0]) {
                clientX = e.touches[0].clientX; clientY = e.touches[0].clientY;
            } else {
                clientX = 0; clientY = 0;
            }
            const x = clientX - rect.left;
            const y = clientY - rect.top;
            return [x, y];
        }

        function updateFontSizes() {
            const tracingText = document.getElementById('tracingText');
            if (!tracingText) return;
            const canvasRect = canvas.getBoundingClientRect();
            const canvasWidth = canvasRect.width - 40;
            const canvasHeight = canvasRect.height - 40;
            const textLength = targetText.length;
            const maxFontSize = Math.min(
                120,
                canvasWidth / Math.max(textLength * 0.7, 1),
                canvasHeight * 0.6
            );
            const fontSize = Math.max(40, Math.min(100, maxFontSize));
            tracingText.style.fontSize = fontSize + 'px';
            tracingText.style.opacity = drawingState.isGuideVisible ? '0.15' : '0';
        }

        function initDrawingState() {
            ctx.fillStyle = '#1a3a1a'; // Background hijau gelap papan tulis
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            updateBrush();
            
            updateProgress();
            updateScoreDisplay();
            updateStars();
            updateEncouragement();
        }

        function startDrawing(e) {
            e.preventDefault();
            drawingState.isDrawing = true;
            const [x, y] = getCoordinates(e);
            drawingState.lastX = x;
            drawingState.lastY = y;
            drawingState.currentStroke = [{x, y}];

            if (!drawingState.hasInteracted) {
                drawingState.hasInteracted = true;
            }

            if (drawingState.autoCloseTimer) {
                clearTimeout(drawingState.autoCloseTimer);
                drawingState.autoCloseTimer = null;
            }

            const completionContainer = document.getElementById('completionContainer');
            if (!completionContainer.classList.contains('show')) {
                setTimeout(() => {
                    completionContainer.classList.add('show');
                }, 1000);
            }
        }

        function draw(e) {
            if (!drawingState.isDrawing) return;
            e.preventDefault();
            
            const [x, y] = getCoordinates(e);
            const distance = Math.sqrt(
                Math.pow(x - drawingState.lastX, 2) + 
                Math.pow(y - drawingState.lastY, 2)
            );
            
            if (distance > 1) {
                ctx.beginPath();
                ctx.moveTo(drawingState.lastX, drawingState.lastY);
                ctx.lineTo(x, y);
                ctx.stroke();
                
                drawingState.currentStroke.push({x, y});
                drawingState.currentDistance += distance;
                drawingState.lastX = x;
                drawingState.lastY = y;
                
                if (drawingState.currentStroke.length % 5 === 0) {
                    drawingState.totalStrokes++;
                    updateScores();
                }
            }
        }

        function stopDrawing() {
            if (!drawingState.isDrawing) return;
            
            drawingState.isDrawing = false;
            
            if (drawingState.currentStroke.length > 3) {
                drawingState.strokes.push([...drawingState.currentStroke]);
                drawingState.totalStrokes++;
            }
            
            drawingState.currentStroke = [];
            updateScores();
        }

        function onDrawingStop() {
            if (character && drawingState.totalStrokes > 10 && !character.isTalking && !character.isDragging) {
                const messages = [
                    "Bagus! Teruskan!",
                    "Hebat! Tulisanmu makin rapi!",
                    "Wah, skormu naik!",
                    "Keren! Kamu cepat belajar!"
                ];
                const randomMsg = messages[Math.floor(Math.random() * messages.length)];
                character.showAbdomenText(randomMsg, true);
            }
        }

        // ==================== SCORING SYSTEM BERDASARKAN STROKE ====================
        function updateScores() {
            // Hitung persentase berdasarkan jumlah stroke
            let newScore = 0;
            
            if (drawingState.totalStrokes <= targetStrokesFor100) {
                // Linear progress sampai target strokes
                newScore = Math.min(100, (drawingState.totalStrokes / targetStrokesFor100) * 100);
            } else {
                // Jika melebihi target, tetap 100%
                newScore = 100;
            }
            
            // Tambahkan bonus untuk distance (kualitas tulisan)
            const distanceFactor = Math.min(1, drawingState.currentDistance / 1000);
            newScore = Math.min(100, newScore * (0.8 +  0.2 * distanceFactor));
            
            // Bulatkan dan pastikan tidak turun
            newScore = Math.min(100, Math.max(drawingState.score, Math.round(newScore)));
            
            // Cek dan mainkan sound progress jika mencapai 50% (sekali saja)
            checkAndPlayProgressSound(drawingState.totalStrokes);
            
            drawingState.score = newScore;
            
            updateProgress();
            updateScoreDisplay();
            updateStars();
            updateEncouragement();
        }

        function updateProgress() {
            const progressFill = document.getElementById('progressFill');
            const progress = Math.min(100, drawingState.score);
            progressFill.style.width = `${progress}%`;
        }

        function updateScoreDisplay() {
            const scoreDisplay = document.getElementById('scoreDisplay');
            const modalScore = document.getElementById('modalScore');
            const roundedScore = Math.round(drawingState.score);
            
            scoreDisplay.textContent = `${roundedScore}%`;
            if (modalScore) modalScore.textContent = `${roundedScore}%`;
            
            if (roundedScore >= 95) {
                scoreDisplay.style.color = '#10b981';
            } else if (roundedScore >= 80) {
                scoreDisplay.style.color = '#f59e0b';
            } else if (roundedScore >= 60) {
                scoreDisplay.style.color = '#3b82f6';
            } else {
                scoreDisplay.style.color = '#64748b';
            }
        }

        function updateStars() {
            let newStars = 0;
            if (drawingState.score >= 95) newStars = 3;
            else if (drawingState.score >= 80) newStars = 2;
            else if (drawingState.score >= 60) newStars = 1;
            
            if (newStars > drawingState.stars) {
                drawingState.stars = newStars;
                for (let i = 1; i <= 3; i++) {
                    const star = document.getElementById(`star${i}`);
                    if (star) {
                        if (i <= drawingState.stars) {
                            star.classList.add('filled');
                        } else {
                            star.classList.remove('filled');
                        }
                    }
                }
            }
        }

        function updateEncouragement() {
            const encouragementText = document.getElementById('encouragementText');
            const roundedScore = Math.round(drawingState.score);
            
            let messageIndex = 0;
            if (roundedScore >= 95) messageIndex = 4;
            else if (roundedScore >= 80) messageIndex = 3;
            else if (roundedScore >= 60) messageIndex = 2;
            else if (roundedScore >= 30) messageIndex = 1;
            else messageIndex = 0;
            
            encouragementText.textContent = drawingState.encouragementMessages[messageIndex];
            
            if (roundedScore >= 80) {
                encouragementText.style.color = '#10b981';
                encouragementText.style.fontWeight = 'bold';
            } else {
                encouragementText.style.color = '#475569';
                encouragementText.style.fontWeight = 'normal';
            }
        }

        // ==================== CANVAS CONTROLS ====================
        function clearCanvas() {
            drawingState.strokes = [];
            drawingState.currentStroke = [];
            drawingState.score = 0;
            drawingState.stars = 0;
            drawingState.totalStrokes = 0;
            drawingState.currentDistance = 0;
            drawingState.victoryShown = false;
            drawingState.progress50Played = false; // Reset flag
            drawingState.lastProgressSound = 0;
            
            ctx.fillStyle = '#1a3a1a';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            const completionContainer = document.getElementById('completionContainer');
            completionContainer.classList.remove('show');
            
            updateProgress();
            updateScoreDisplay();
            updateStars();
            updateEncouragement();
            
            if (character) {
                setTimeout(() => {
                    if (character && !character.isTalking && !character.isDragging) {
                        character.showAbdomenText("Ayo mulai lagi! Kamu pasti bisa!", true);
                    }
                }, 300);
            }
        }

        function undoLastStroke() {
            if (drawingState.strokes.length > 0) {
                drawingState.strokes.pop();
                drawingState.totalStrokes = Math.max(0, drawingState.totalStrokes - 1);
                drawingState.currentDistance = Math.max(0, drawingState.currentDistance - 30);
                redrawCanvas();
                
                // Hitung ulang score setelah undo
                let newScore = 0;
                if (drawingState.totalStrokes <= targetStrokesFor100) {
                    newScore = Math.min(100, (drawingState.totalStrokes / targetStrokesFor100) * 100);
                } else {
                    newScore = 100;
                }
                drawingState.score = Math.max(0, newScore - 5);
                drawingState.victoryShown = false;
                
                updateScores();
                
                if (character && drawingState.strokes.length === 0) {
                    setTimeout(() => {
                        if (character && !character.isTalking && !character.isDragging) {
                            character.showAbdomenText("Bagus, mulai lagi dengan hati-hati!", true);
                        }
                    }, 300);
                }
            }
        }

        function redrawCanvas() {
            ctx.fillStyle = '#1a3a1a';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            updateBrush();
            drawingState.strokes.forEach(stroke => {
                if (stroke.length > 0) {
                    ctx.beginPath();
                    ctx.moveTo(stroke[0].x, stroke[0].y);
                    
                    for (let i = 1; i < stroke.length; i++) {
                        ctx.lineTo(stroke[i].x, stroke[i].y);
                    }
                    
                    ctx.stroke();
                }
            });
        }

        function toggleGuide() {
            const btn = document.getElementById('guideBtn');
            drawingState.isGuideVisible = !drawingState.isGuideVisible;
            
            const tracingText = document.getElementById('tracingText');
            
            if (drawingState.isGuideVisible) {
                btn.innerHTML = '<i class="fas fa-eye"></i> Sembunyikan Garis';
                tracingText.style.opacity = '0.15';
            } else {
                btn.innerHTML = '<i class="fas fa-eye-slash"></i> Tampilkan Garis';
                tracingText.style.opacity = '0';
            }
        }

        // ==================== VICTORY MODAL FUNCTIONS ====================
        function createConfetti() {
            const confettiContainer = document.getElementById('confettiContainer');
            confettiContainer.innerHTML = '';
            
            const colors = ['#fbbf24', '#ef4444', '#3b82f6', '#10b981', '#8b5cf6'];
            
            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                
                const size = Math.random() * 10 + 5;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const left = Math.random() * 100;
                const delay = Math.random() * 2;
                const duration = Math.random() * 2 + 2;
                
                confetti.style.width = `${size}px`;
                confetti.style.height = `${size}px`;
                confetti.style.backgroundColor = color;
                confetti.style.left = `${left}%`;
                confetti.style.animationDelay = `${delay}s`;
                confetti.style.animationDuration = `${duration}s`;
                
                if (Math.random() > 0.5) {
                    confetti.style.borderRadius = '50%';
                }
                
                confettiContainer.appendChild(confetti);
            }
        }

        function updateModalStars() {
            const starContainer = document.getElementById('modalStarsDisplay');
            if (starContainer) {
                const stars = starContainer.querySelectorAll('.star-icon');
                stars.forEach((star, index) => {
                    star.classList.remove('filled');
                    if (index < drawingState.stars) {
                        star.classList.add('filled');
                    }
                });
            }
        }

        function updateModalContent() {
            const modalScore = document.getElementById('modalScore');
            const roundedScore = Math.round(drawingState.score);
            
            if (modalScore) modalScore.textContent = `${roundedScore}%`;
            
            updateModalStars();
            
            if (character) {
                let celebrationMessage = "";
                if (roundedScore >= 95) {
                    celebrationMessage = "SEMPURNA! 🎉 Kamu luar biasa!";
                } else if (roundedScore >= 80) {
                    celebrationMessage = "Hebat! Skormu sangat bagus! ✨";
                } else {
                    celebrationMessage = "Kerja bagus! Terus berlatih ya! 💪";
                }
                
                setTimeout(() => {
                    if (character && !character.isTalking && !character.isDragging) {
                        character.showAbdomenText(celebrationMessage, true);
                    }
                }, 500);
            }
        }

        function showVictoryModal() {
            if (drawingState.totalStrokes < 3) {
                if (character) {
                    character.showAbdomenText("📝 Yuk, tulis dulu sedikit sebelum selesai!", true);
                } else {
                    alert("📝 Yuk, tulis dulu sedikit sebelum selesai!");
                }
                return;
            }

            if (drawingState.victoryShown) return;
            drawingState.victoryShown = true;

            createConfetti();
            updateModalContent();

            const modal = document.getElementById('victoryModal');
            modal.style.display = 'flex';
            
            // Keep body scrollable
            document.body.classList.add('victory-modal-active');

            drawingState.autoCloseTimer = setTimeout(() => {
                if (modal.style.display === 'flex') {
                    tryAgain();
                }
            }, 10000);

            // Mainkan sequence audio: victoryAudio -> selamat.mp3
            setTimeout(() => {
                playVictoryAudioSequence();
            }, 500);
        }

        function closeVictoryModal() {
            const modal = document.getElementById('victoryModal');
            modal.style.display = 'none';
            drawingState.victoryShown = false;
            
            // Remove body class to allow normal scrolling
            document.body.classList.remove('victory-modal-active');

            if (drawingState.autoCloseTimer) {
                clearTimeout(drawingState.autoCloseTimer);
                drawingState.autoCloseTimer = null;
            }

            const audio = document.getElementById('victoryAudio');
            if (audio) {
                audio.pause();
                audio.currentTime = 0;
            }
        }

        function playVictoryAudioSequence() {
            const victoryAudio = document.getElementById('victoryAudio');
            const selamatAudio = document.getElementById('selamatAudio');
            
            if (victoryAudio && victoryAudio.src) {
                victoryAudio.currentTime = 0;
                victoryAudio.play().then(() => {
                    console.log('Victory audio started');
                    victoryAudio.onended = function() {
                        console.log('Victory audio ended, playing selamat');
                        // Setelah victoryAudio selesai, mainkan selamat.mp3
                        if (selamatAudio && selamatAudio.src) {
                            selamatAudio.currentTime = 0;
                            selamatAudio.play().catch(e => {
                                console.log('Selamat audio play failed:', e);
                            });
                        }
                    };
                }).catch(e => {
                    console.log('Victory audio play failed:', e);
                    // Fallback ke selamat.mp3 jika victoryAudio gagal
                    if (selamatAudio && selamatAudio.src) {
                        selamatAudio.currentTime = 0;
                        selamatAudio.play().catch(err => console.log('Selamat audio also failed:', err));
                    }
                });
            } else if (selamatAudio && selamatAudio.src) {
                // Jika victoryAudio tidak ada, langsung mainkan selamat.mp3
                selamatAudio.currentTime = 0;
                selamatAudio.play().catch(err => console.log('Selamat audio failed:', err));
            }
        }

        function playVictoryAudio() {
            const audio = document.getElementById('victoryAudio');
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(e => {
                    console.log('Victory audio playback failed:', e);
                });
            }
        }

        function playAudio() {
            const audio = document.getElementById('audioPlayer');
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(e => console.log('Audio playback failed:', e));
            }
        }

        function tryAgain() {
            closeVictoryModal();
            clearCanvas();
            
            if (character) {
                setTimeout(() => {
                    character.showAbdomenText("Ayo coba lagi! Pasti lebih baik!", true);
                }, 500);
            }
        }

        function continueToNext() {
            closeVictoryModal();
            
            // Simpan progress sebelum pindah ke halaman berikutnya
            saveProgressBeforeContinue();
        }

        function saveProgressBeforeContinue() {
            const roundedScore = Math.round(drawingState.score);
            const levelId = "{{ $level->id }}";
            const writingItemId = "{{ $writingItem->id }}";
            
            // Data untuk disimpan
            const progressData = {
                level_id: levelId,
                score: roundedScore,
                bintang: drawingState.stars,
                writing_item_id: writingItemId
            };
            
            // Kirim ke backend API
            fetch("{{ route('progres.writing.save') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify(progressData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Progress saved:', data);
                if (data.success) {
                    // Redirect ke halaman sebelumnya setelah berhasil menyimpan
                    setTimeout(() => {
                        window.location.href = "{{ url()->previous() }}";
                    }, 500);
                } else {
                    console.error('Failed to save progress:', data.message);
                    // Tetap redirect meskipun gagal
                    window.location.href = "{{ url()->previous() }}";
                }
            })
            .catch(error => {
                console.error('Error saving progress:', error);
                // Tetap redirect meskipun error
                window.location.href = "{{ url()->previous() }}";
            });
        }

        // Click sound script
        (function(){
            const CLICK_SRC = "{{ asset('storage/music/klik.mp3') }}";
            const clickAudio = new Audio(CLICK_SRC);
            clickAudio.preload = 'auto';
            function playClick() { 
                try { 
                    const snd = clickAudio.cloneNode(); 
                    snd.volume = 0.3;
                    snd.play().catch(()=>{}); 
                } catch(e){} 
            }
            document.addEventListener('click', function(e){
            const el = e.target;
                const btn = el.closest('button, a, .control-btn, .finish-btn, .audio-btn-blackboard, .audio-btn-small, .modal-btn, .modal-close');
                if (btn && btn.dataset.noSound !== '1') playClick();
            }, true);
        })();
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