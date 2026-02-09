<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $book->title }} - Halaman {{ $pageNumber }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS tetap sama seperti sebelumnya */
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --accent: #ff6b6b;
            --success: #48bb78;
            --warning: #f6e05e;
            --light: #f7fafc;
            --dark: #2d3748;
            --book-bg: #fef5e7;
            --book-shadow: rgba(0, 0, 0, 0.3);
            --blackboard: #1a472a;
            --chalk: #f0f0f0;
            --sky-blue: #87CEEB;
            --sun-yellow: #FFD700;
            --cloud-white: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
            -webkit-user-select: none;
            user-select: none;
        }

        body {
            font-family: 'Comic Sans MS', 'Chalkboard SE', 'Arial Rounded MT Bold', sans-serif;
            background: linear-gradient(180deg, var(--sky-blue) 0%, #B0E2FF 50%, #87CEFA 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px 20px 150px 20px;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
            cursor: default;
            background-attachment: fixed;
        }

        /* Background Elements - Awan dan Matahari */
        .sun {
            position: fixed;
            top: 30px;
            right: 30px;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle at 30% 30%, var(--sun-yellow), #FF8C00);
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(255, 215, 0, 0.6);
            z-index: 1;
            animation: sunGlow 4s ease-in-out infinite;
        }

        @keyframes sunGlow {
            0%, 100% { 
                box-shadow: 0 0 40px rgba(255, 215, 0, 0.6), 
                          0 0 60px rgba(255, 215, 0, 0.4),
                          0 0 80px rgba(255, 215, 0, 0.2);
            }
            50% { 
                box-shadow: 0 0 50px rgba(255, 215, 0, 0.8), 
                          0 0 70px rgba(255, 215, 0, 0.6),
                          0 0 90px rgba(255, 215, 0, 0.4);
            }
        }

        .cloud {
            position: fixed;
            background: var(--cloud-white);
            border-radius: 50px;
            box-shadow: 0 8px 30px rgba(255, 255, 255, 0.8);
            opacity: 0.9;
            z-index: 1;
        }

        .cloud-1 {
            top: 20px;
            left: 10%;
            width: 120px;
            height: 40px;
            animation: cloudMove 60s linear infinite;
        }

        .cloud-2 {
            top: 80px;
            left: 40%;
            width: 160px;
            height: 50px;
            animation: cloudMove 80s linear infinite;
        }

        .cloud-3 {
            top: 40px;
            right: 20%;
            width: 140px;
            height: 45px;
            animation: cloudMove 70s linear infinite reverse;
        }

        .cloud-4 {
            bottom: 150px;
            left: 15%;
            width: 100px;
            height: 35px;
            animation: cloudMove 50s linear infinite;
        }

        .cloud-5 {
            bottom: 100px;
            right: 10%;
            width: 130px;
            height: 42px;
            animation: cloudMove 65s linear infinite reverse;
        }

        @keyframes cloudMove {
            0% { transform: translateX(-100vw); }
            100% { transform: translateX(100vw); }
        }

        .cloud:before,
        .cloud:after {
            content: '';
            position: absolute;
            background: var(--cloud-white);
            border-radius: 50%;
        }

        .cloud:before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 20px;
        }

        .cloud:after {
            width: 70px;
            height: 70px;
            top: -35px;
            right: 20px;
        }

        /* Book Container */
        .book-container {
            width: 100%;
            max-width: 1200px;
            position: relative;
            z-index: 10;
            pointer-events: auto;
            touch-action: auto;
            transition: opacity 0.5s ease, pointer-events 0.5s ease;
        }

        /* Progress bar fixed */
        .progress-header {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 12px 20px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: none;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.5s ease;
            max-width: 90%;
            box-sizing: border-box;
            pointer-events: auto;
        }

        @keyframes slideDown {
            from { transform: translateX(-50%) translateY(-100%); opacity: 0; }
            to { transform: translateX(-50%) translateY(0); opacity: 1; }
        }

        .book-title-small {
            font-size: 1rem;
            color: var(--dark);
            font-weight: bold;
        }

        .progress-bar-container {
            width: 200px;
            height: 8px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 4px;
        }

        .page-counter {
            font-size: 0.9rem;
            color: var(--dark);
            font-weight: bold;
        }

        /* Blackboard Wrapper */
        .blackboard-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 80px auto 100px;
            padding: 0 10px;
        }

        .blackboard {
            background: var(--blackboard);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.5),
                inset 0 0 0 2px rgba(255, 255, 255, 0.1),
                inset 0 0 30px rgba(0, 0, 0, 0.3);
            border: 12px solid #8B4513;
            position: relative;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }

        /* Blackboard texture */
        .blackboard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 2px,
                    rgba(255, 255, 255, 0.03) 2px,
                    rgba(255, 255, 255, 0.03) 4px
                );
            pointer-events: none;
            z-index: 1;
        }

        .blackboard > * {
            position: relative;
            z-index: 2;
        }

        /* Top Section: Image + Drop Zones */
        .top-section {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            align-items: flex-start;
        }

        @media (max-width: 768px) {
            .top-section {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .image-container {
                max-width: 250px;
                aspect-ratio: 1;
            }
            
            .drop-zones-container {
                width: 100%;
            }
            
            .instruction-text {
                font-size: 1.2rem;
            }
            
            .drop-zone {
                width: 70px;
                height: 70px;
                font-size: 1.4rem;
            }
            
            .zone-content {
                font-size: 1.6rem;
            }
            
            .syllable {
                min-width: 70px;
                height: 70px;
                font-size: 1.6rem;
            }
            
            .syllables-title {
                font-size: 1.2rem;
            }
            
            .syllables-container {
                gap: 10px;
                min-height: 100px;
            }
            
            .blackboard {
                padding: 15px;
            }
            
            .blackboard-wrapper {
                margin: 70px auto 120px;
            }
        }
        
        @media (max-width: 480px) {
            .top-section {
                gap: 12px;
            }
            
            .image-container {
                max-width: 200px;
                padding: 12px;
            }
            
            .drop-zone {
                width: 65px;
                height: 65px;
                font-size: 1.3rem;
            }
            
            .zone-content {
                font-size: 1.4rem;
            }
            
            .syllable {
                min-width: 65px;
                height: 65px;
                font-size: 1.5rem;
            }
            
            .instruction-text {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            
            .syllables-title {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            
            .syllables-container {
                gap: 8px;
            }
            
            .blackboard {
                padding: 12px;
                border-width: 8px;
            }
            
            .blackboard-wrapper {
                margin: 60px auto 110px;
            }
            
            .drop-zones-container {
                padding: 15px;
            }
            
            .syllables-section {
                padding: 15px;
                border-radius: 12px;
            }
        }

        /* Landscape orientation on mobile */
        @media (max-height: 500px) and (max-width: 800px) {
            body {
                padding: 20px 20px 180px 20px;
            }
            
            .blackboard-wrapper {
                margin: 40px auto 180px;
            }
            
            .navigation {
                max-height: 150px;
            }
        }

        /* Image Container */
        .image-container {
            flex: 1;
            max-width: 300px;
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .blackboard-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.5));
        }

        /* Drop Zones Container */
        .drop-zones-container {
            flex: 2;
            min-height: 180px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            border: 3px dashed rgba(255, 255, 255, 0.3);
        }

        .instruction-text {
            font-family: 'Chalkboard SE', cursive;
            font-size: 1.6rem;
            color: var(--chalk);
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
        }

        .drop-zones {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 15px;
            min-height: 80px;
        }

        .drop-zone {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 3px dashed rgba(255, 255, 255, 0.4);
            transition: all 0.2s ease;
            position: relative;
            user-select: none;
        }

        @media (max-width: 768px) {
            .drop-zone {
                width: 80px;
                height: 80px;
            }
        }

        .drop-zone.highlight {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--success);
            border-style: solid;
            animation: pulse 1.5s infinite;
        }

        .drop-zone.correct {
            background: rgba(72, 187, 120, 0.3);
            border-color: var(--success);
            border-style: solid;
        }

        .drop-zone.wrong {
            animation: shake 0.5s ease;
            background: rgba(255, 107, 107, 0.3);
            border-color: var(--accent);
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(72, 187, 120, 0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .zone-number {
            position: absolute;
            top: 8px;
            left: 8px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }

        .zone-content {
            font-size: 2rem;
            font-weight: bold;
            color: var(--chalk);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .zone-empty {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.3);
            text-align: center;
            padding: 10px;
        }

        .completion-message {
            text-align: center;
            font-size: 1.8rem;
            color: #FFD93D;
            font-weight: bold;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
            display: none;
            animation: celebrate 1s ease;
        }

        @keyframes celebrate {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Bottom Section: Syllables */
        .syllables-section {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            margin-top: 10px;
        }

        .syllables-title {
            font-family: 'Chalkboard SE', cursive;
            font-size: 1.6rem;
            color: var(--chalk);
            text-align: center;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .syllables-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            min-height: 120px;
        }

        .syllable {
            min-width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #FF6B9D, #C06C84);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.2rem;
            font-weight: bold;
            color: white;
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
            box-shadow: 
                0 6px 15px rgba(255, 107, 157, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.2);
            pointer-events: auto;
        }

        @media (max-width: 768px) {
            .syllable {
                min-width: 80px;
                height: 80px;
                font-size: 1.8rem;
            }
            
            .syllables-title {
                font-size: 1.4rem;
            }
        }
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                transparent 30%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 70%
            );
            animation: shine 3s infinite linear;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .syllable:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 
                0 10px 20px rgba(255, 107, 157, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .syllable.dragging {
            opacity: 0.8;
            transform: scale(1.08);
            z-index: 1000;
            cursor: grabbing;
            box-shadow: 
                0 12px 25px rgba(255, 107, 157, 0.8),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .syllable.placed {
            opacity: 0.5;
            transform: scale(0.95);
        }

        /* Navigation buttons */
        .navigation {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.95), rgba(192, 108, 132, 0.95));
            backdrop-filter: blur(10px);
            padding: 12px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -8px 32px rgba(255, 107, 157, 0.4);
            z-index: 100;
            border-top: 3px solid rgba(255, 255, 255, 0.3);
            gap: 8px;
            flex-wrap: wrap;
            max-height: 130px;
            overflow-y: auto;
            pointer-events: auto;
        }

        .nav-btn {
            padding: 14px 25px;
            font-size: 1rem;
            background: linear-gradient(135deg, #FFD93D, #FFA07A);
            color: var(--dark);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(255, 217, 61, 0.5);
            font-weight: 900;
            min-width: 140px;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            flex: 1;
            min-width: 90px;
        }

        @media (max-width: 768px) {
            .nav-btn {
                padding: 10px 12px;
                font-size: 0.8rem;
                min-width: 80px;
                gap: 4px;
                flex: 0 1 auto;
                letter-spacing: 0.5px;
            }
        }

        .nav-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 25px rgba(255, 217, 61, 0.7);
        }

        .nav-btn:active {
            transform: translateY(-1px) scale(1.01);
        }

        .nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none;
        }

        .page-indicator {
            font-size: 1.1rem;
            color: white;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 30px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .page-indicator {
                font-size: 0.85rem;
                padding: 6px 12px;
                gap: 5px;
            }
        }
        
        @media (max-width: 480px) {
            .page-indicator {
                font-size: 0.75rem;
                padding: 5px 10px;
            }
        }

        /* ==================== VOICE AGENT DRAAGGABLE ==================== */
        .voice-agent-wrapper {
            position: fixed;
            bottom: 120px;
            right: 20px;
            z-index: 9999;
            width: 80px;
            height: 80px;
            cursor: move;
            user-select: none;
            touch-action: none;
            transition: transform 0.2s ease;
            pointer-events: auto;
        }

        .voice-agent-wrapper.dragging {
            cursor: grabbing;
            z-index: 10000;
            transform: scale(1.1);
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
        }

        .voice-agent-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: inline-block;
        }

        .voice-agent-button {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            cursor: pointer;
            border: 3px solid white;
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }

        .voice-agent-wrapper.dragging .voice-agent-button {
            animation: none;
            background: linear-gradient(135deg, #764ba2, #667eea);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.8);
        }

        .voice-agent-button.recording {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            animation: pulse-recording 0.8s ease-in-out infinite;
        }

        .voice-agent-icon {
            font-size: 1.8rem;
            color: white;
            pointer-events: none;
        }

        /* Area untuk drag di sekitar tombol */
        .voice-agent-drag-area {
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 50%;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse-recording {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(255, 107, 107, 0); }
        }

        /* VOICE AGENT STATUS */
        .voice-agent-status {
            position: fixed;
            bottom: 210px;
            right: 20px;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 0.85rem;
            max-width: 200px;
            display: none;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.3s ease;
        }

        .voice-agent-status.show {
            display: block;
        }

        /* WAITING FOR RESPONSE INDICATOR */
        .waiting-for-response {
            position: fixed;
            bottom: 210px;
            right: 20px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95), rgba(118, 75, 162, 0.95));
            color: white;
            padding: 10px 16px;
            border-radius: 15px;
            font-size: 0.85rem;
            display: none;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.3s ease, pulse-waiting 1.5s infinite;
            align-items: center;
            gap: 8px;
        }

        .waiting-for-response.show {
            display: flex;
        }

        .waiting-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse-waiting {
            0%, 100% { box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
            50% { box-shadow: 0 6px 20px rgba(102, 126, 234, 0.7); }
        }

        @media (max-width: 768px) {
            .waiting-for-response {
                bottom: 190px;
                right: 15px;
                padding: 8px 12px;
                font-size: 0.75rem;
            }

            .waiting-spinner {
                width: 14px;
                height: 14px;
            }
        }

        @media (max-width: 480px) {
            .waiting-for-response {
                bottom: 180px;
                right: 12px;
                padding: 7px 10px;
                font-size: 0.7rem;
            }

            .waiting-spinner {
                width: 12px;
                height: 12px;
            }
        }

        /* RECORDING STATUS */
        .recording-status {
            position: fixed;
            top: 90px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.95), rgba(255, 142, 142, 0.95));
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: bold;
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .recording-status.show {
            display: flex;
            animation: slideDown 0.3s ease;
        }

        .recording-progress {
            width: 100px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            overflow: hidden;
            margin-left: 10px;
        }

        .recording-progress-fill {
            height: 100%;
            background: white;
            border-radius: 2px;
            width: 0%;
            transition: width 0.1s linear;
        }

        /* AUDIO STATUS TOGGLE */
        .audio-toggle {
            position: fixed;
            bottom: 85px;
            left: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            pointer-events: auto;
        }

        .audio-toggle:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .audio-toggle {
                bottom: 85px;
                left: 10px;
                padding: 8px 12px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .audio-toggle {
                bottom: 80px;
                left: 8px;
                padding: 7px 10px;
                font-size: 0.75rem;
            }
        }

        /* AUDIO FEEDBACK */
        .audio-feedback {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideInRight 0.3s ease;
        }

        .audio-feedback.show {
            display: flex;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* AUDIO PLAYER ELEMENTS (hidden) */
        .audio-player {
            display: none;
        }

        /* RESPONSIVE VOICE AGENT */
        @media (max-width: 768px) {
            .voice-agent-wrapper {
                width: 70px;
                height: 70px;
                bottom: 110px;
                right: 15px;
            }
            
            .voice-agent-icon {
                font-size: 1.5rem;
            }
            
            .voice-agent-status {
                bottom: 190px;
                right: 15px;
                font-size: 0.8rem;
                padding: 10px 14px;
                max-width: 150px;
            }
            
            .recording-status {
                padding: 10px 20px;
                font-size: 0.9rem;
                top: 80px;
            }
        }
        
        @media (max-width: 480px) {
            .voice-agent-wrapper {
                width: 65px;
                height: 65px;
                bottom: 105px;
                right: 12px;
            }
            
            .voice-agent-status {
                bottom: 180px;
                right: 12px;
                font-size: 0.75rem;
            }
            
            .recording-status {
                padding: 8px 16px;
                font-size: 0.85rem;
                top: 70px;
            }
        }

        /* KOTAK TEKS DI ATAS PERUT KARAKTER */
        .speech-bubble-abdomen {
            position: absolute;
            top: 75%;
            left: 50%;
            transform: translateX(-50%) translateY(0);
            width: 200px;
            height: 65px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 16px;
            padding: 10px;
            box-shadow: 
                0 6px 20px rgba(0, 0, 0, 0.15),
                inset 0 0 0 2px rgba(59,130,246,0.06);
            z-index: 1001;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.45s ease, transform 0.35s ease;
            pointer-events: none;
        }

        .speech-bubble-abdomen.show {
            opacity: 1;
        }

        .abdomen-text {
            font-family: 'Comic Sans MS', 'Chalkboard SE', cursive;
            font-size: 1.4rem;
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

        /* ANIMATED CHARACTER (Existing) */
        .character-wrapper {
            position: fixed;
            bottom: 30px;
            right: 0;
            transform: translateX(100%);
            z-index: 10;
            width: 320px;
            height: 400px;
            cursor: grab;
            user-select: none;
            touch-action: manipulation;
            transition: transform 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            pointer-events: none;
        }

        .character-wrapper.enter {
            transform: translateX(0);
            right: 20px;
            pointer-events: auto;
        }

        .character-wrapper.exit {
            transform: translateX(100%);
            right: 0;
            pointer-events: none;
        }

        .character-wrapper.dragging {
            cursor: grabbing;
            z-index: 9999;
            transform: scale(1.05) !important;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
            transition: transform 0.1s ease-out !important;
            pointer-events: auto;
        }

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

        /* RESPONSIVE CHARACTER */
        @media (max-width: 768px) {
            .character-wrapper {
                width: 220px;
                height: 280px;
                bottom: 20px;
            }
            .speech-bubble-abdomen { 
                width: 150px; 
                height: 50px; 
                top: 70%; 
                padding: 8px;
            }
            .abdomen-text { font-size: 1.1rem; }
            .character-toggle-btn {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
                bottom: 35px;
                right: 15px;
            }
        }
        @media (max-width: 480px) {
            .character-wrapper { 
                width: 160px; 
                height: 210px; 
                bottom: 15px;
                right: 10px;
            }
            .speech-bubble-abdomen { 
                width: 120px; 
                height: 45px; 
                top: 65%; 
                padding: 6px;
            }
            .abdomen-text { font-size: 0.95rem; }
            .character-toggle-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                bottom: 30px;
                right: 12px;
            }
        }

        /* Sparkle effects */
        .sparkle {
            position: fixed;
            pointer-events: none;
            z-index: 1000;
            font-size: 24px;
        }

        .sparkle::before,
        .sparkle::after {
            content: '⭐';
            position: absolute;
            font-size: 22px;
            animation: sparkleFloat 1.2s ease-out forwards;
        }

        .sparkle::after {
            content: '✨';
            animation-delay: 0.15s;
            left: 20px;
            font-size: 20px;
        }

        @keyframes sparkleFloat {
            0% { 
                opacity: 1; 
                transform: translateY(0) translateX(0) scale(0) rotate(0deg); 
            }
            50% { 
                opacity: 1; 
                transform: translateY(-40px) translateX(var(--randomX, 15px)) scale(1.2) rotate(180deg); 
            }
            100% { 
                opacity: 0; 
                transform: translateY(-80px) translateX(var(--randomX, 30px)) scale(0.3) rotate(360deg); 
            }
        }

        /* Feedback toast */
        .feedback {
            position: fixed;
            top: 90px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #FFD93D, #FFA07A);
            padding: 15px 25px;
            border-radius: 25px;
            box-shadow: 0 8px 30px rgba(255, 217, 61, 0.5);
            z-index: 1001;
            animation: feedbackSlide 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            font-size: 1rem;
            color: var(--dark);
            border: 3px solid rgba(255, 255, 255, 0.7);
        }

        @keyframes feedbackSlide {
            from { transform: translateX(-50%) translateY(-80px) scale(0.5); opacity: 0; }
            to { transform: translateX(-50%) translateY(0) scale(1); opacity: 1; }
        }

        .feedback i {
            font-size: 1.3rem;
        }

        /* Wrong Feedback */
        .wrong-feedback {
            position: fixed;
            top: 150px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #FF6B6B, #FF8E8E);
            padding: 15px 25px;
            border-radius: 25px;
            box-shadow: 0 8px 30px rgba(255, 107, 107, 0.5);
            z-index: 1001;
            animation: feedbackSlide 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            font-size: 1rem;
            color: white;
            border: 3px solid rgba(255, 255, 255, 0.7);
        }

        /* Drag Ghost */
        .drag-ghost {
            position: fixed;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #FF6B9D, #C06C84);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.2rem;
            font-weight: bold;
            color: white;
            z-index: 99999;
            pointer-events: none;
            opacity: 0.9;
            transform: translate(-50%, -50%);
            box-shadow: 
                0 12px 25px rgba(255, 107, 157, 0.8),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Syllable Audio Feedback */
        .syllable-audio-feedback {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
            color: white;
            padding: 15px 25px;
            border-radius: 20px;
            font-size: 2.5rem;
            font-weight: bold;
            z-index: 10000;
            display: none;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.3);
            animation: syllableAudioPopup 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        @keyframes syllableAudioPopup {
            0% { 
                transform: translate(-50%, -50%) scale(0.3); 
                opacity: 0; 
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.1); 
            }
            100% { 
                transform: translate(-50%, -50%) scale(1); 
                opacity: 1; 
            }
        }
        
        .syllable-audio-feedback.show {
            display: flex;
        }
        
        /* Audio Queue Indicator */
        .audio-queue-indicator {
            position: fixed;
            top: 160px;
            right: 20px;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            z-index: 999;
            display: none;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .audio-queue-indicator.show {
            display: flex;
        }
        
        .queue-count {
            background: var(--primary);
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.7rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .blackboard {
                padding: 18px;
            }
            
            .instruction-text {
                font-size: 1.3rem;
            }
            
            .navigation {
                padding: 12px;
            }
            
            .progress-header {
                padding: 12px 20px;
                gap: 10px;
            }
            
            .progress-bar-container {
                width: 150px;
            }
            
            .audio-queue-indicator {
                top: 140px;
                right: 15px;
                font-size: 0.7rem;
            }
        }

        /* VOLUME CONTROL */
        .volume-control {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 12px 16px;
            border-radius: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 2px solid rgba(102, 126, 234, 0.3);
            max-width: 90%;
            pointer-events: auto;
        }

        .volume-icon {
            font-size: 1.2rem;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .volume-icon:hover {
            transform: scale(1.1);
            color: var(--secondary);
        }

        .volume-slider {
            width: 120px;
            height: 6px;
            cursor: pointer;
            accent-color: var(--primary);
            border-radius: 3px;
        }

        .volume-percent {
            min-width: 35px;
            font-size: 0.85rem;
            color: var(--dark);
            font-weight: bold;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .volume-control {
                padding: 10px 14px;
                gap: 10px;
                top: 18px;
                left: 18px;
            }

            .volume-icon {
                font-size: 1rem;
            }

            .volume-slider {
                width: 100px;
            }

            .volume-percent {
                min-width: 32px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .volume-control {
                padding: 8px 12px;
                gap: 8px;
                top: 15px;
                left: 15px;
            }

            .volume-icon {
                font-size: 0.9rem;
            }

            .volume-slider {
                width: 80px;
            }

            .volume-percent {
                min-width: 28px;
                font-size: 0.75rem;
            }
        }

        /* Timeout Modal Styles */
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
            font-family: 'Fredoka One', cursive;
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
</head>
<body>
    <!-- Background Elements -->
    <div class="sun"></div>
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>
    <div class="cloud cloud-3"></div>
    <div class="cloud cloud-4"></div>
    <div class="cloud cloud-5"></div>

    <!-- Progress header -->
    <div class="progress-header">
        <i class="fas fa-book"></i>
        <span class="book-title-small">{{ $book->title }}</span>
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: {{ ($pageNumber / $book->pages->count()) * 100 }}%"></div>
        </div>
        <span class="page-counter">{{ $pageNumber }}/{{ $book->pages->count() }}</span>
    </div>

    <!-- Main Blackboard -->
    <div class="book-container">
        <div class="blackboard-wrapper">
            <div class="blackboard">
                <!-- Top Section: Image + Drop Zones -->
                <div class="top-section">
                    <!-- Image -->
                    <div class="image-container">
                        <img src="{{ Storage::url($currentPage->image_path) }}" 
                             alt="{{ $currentPage->nama_benda }}" 
                             class="blackboard-image"
                             id="itemImage"
                             onerror="this.src='https://via.placeholder.com/300x300/FF6B9D/FFFFFF?text={{ urlencode($currentPage->nama_benda) }}'">
                    </div>

                    <!-- Drop Zones -->
                    <div class="drop-zones-container">
                        <div class="instruction-text">Susun Suku Kata:</div>
                        <div class="drop-zones" id="dropZones">
                            <!-- Drop zones akan dibuat dinamis oleh JavaScript -->
                        </div>
                        <div class="completion-message" id="completionMessage">
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Syllables to Drag -->
                <div class="syllables-section">
                    <div class="syllables-title">Tarik Suku Kata di Bawah Ini:</div>
                    <div class="syllables-container" id="syllablesContainer">
                        <!-- Syllables akan dibuat dinamis oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <button class="nav-btn" id="prevBtn" 
                    onclick="changePage('prev')"
                    {{ $pageNumber <= 1 ? 'disabled' : '' }}>
                <i class="fas fa-chevron-left"></i> Sebelumnya
            </button>
            
            <div class="page-indicator">
                <i class="fas fa-book-open"></i>
                Halaman {{ $pageNumber }}
            </div>
            
            <button class="nav-btn" id="nextBtn"
                    onclick="changePage('next')"
                    {{ $pageNumber >= $book->pages->count() ? 'disabled' : '' }}>
                Selanjutnya <i class="fas fa-chevron-right"></i>
            </button>

            @if($pageNumber >= $book->pages->count())
            <button class="nav-btn" id="finishBtn" 
                    onclick="finishBook()"
                    style="background: linear-gradient(135deg, #48bb78, #38a169); margin-left: auto;">
                <i class="fas fa-check"></i> Selesai
            </button>
            @endif
        </div>
    </div>

    <!-- ANIMATED CHARACTER -->
    <div class="character-wrapper" id="characterWrapper">
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

    <!-- VOICE AGENT DRAAGGABLE -->
    <div class="voice-agent-wrapper" id="voiceAgentWrapper">
        <div class="voice-agent-container">
            <div class="voice-agent-drag-area" id="voiceAgentDragArea"></div>
            <div class="voice-agent-button" id="voiceAgentButton">
                <i class="fas fa-microphone voice-agent-icon" id="voiceAgentIcon"></i>
            </div>
        </div>
    </div>

    <!-- VOICE AGENT STATUS -->
    <div class="voice-agent-status" id="voiceAgentStatus">
        <span id="statusText">Voice Agent siap</span>
    </div>

    <!-- WAITING FOR RESPONSE INDICATOR -->
    <div class="waiting-for-response" id="waitingForResponse">
        <div class="waiting-spinner"></div>
        <span>Menunggu respons AI...</span>
    </div>

    <!-- RECORDING STATUS -->
    <div class="recording-status" id="recordingStatus">
        <i class="fas fa-circle" style="color: #ff6b6b;"></i>
        <span>Merekam...</span>
        <div class="recording-progress">
            <div class="recording-progress-fill" id="recordingProgressFill"></div>
        </div>
    </div>

    <!-- AUDIO TOGGLE -->
    <div class="audio-toggle" id="audioToggle">
        <i class="fas fa-volume-up" id="audioIcon"></i>
        <span id="audioStatusText">Audio: Aktif</span>
    </div>

    <!-- AUDIO QUEUE INDICATOR -->
    <div class="audio-queue-indicator" id="audioQueueIndicator">
        <i class="fas fa-list-ol"></i>
        <span>Antrian: </span>
        <span class="queue-count" id="queueCount">0</span>
    </div>

    <!-- AUDIO FEEDBACK -->
    <div class="audio-feedback" id="audioFeedback">
        <i class="fas fa-volume-up"></i>
        <span id="feedbackText">Memutar audio...</span>
    </div>

    <!-- SYLLABLE AUDIO FEEDBACK -->
    <div class="syllable-audio-feedback" id="syllableAudioFeedback">
        <i class="fas fa-volume-up"></i>
        <span id="syllableAudioText"></span>
    </div>

    <!-- Hidden Audio Elements -->
    <audio id="backgroundMusicPlayer" class="audio-player">
        <source src="{{ asset('storage/music/play.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="audioPlayer" class="audio-player"></audio>
    <audio id="responseAudioPlayer" class="audio-player"></audio>
    <audio id="syllableAudioPlayer" class="audio-player"></audio>

    <!-- Volume Control -->
    <div class="volume-control" id="volumeControl">
        <i class="fas fa-volume-down volume-icon" id="volumeMuteIcon"></i>
        <input type="range" id="volumeSlider" class="volume-slider" min="0" max="100" value="70">
        <span class="volume-percent" id="volumePercent">70%</span>
    </div>

    <script>
        // ==================== DATA CONFIGURATION ====================
        const gameData = {
            word: '{{ $currentPage->nama_benda }}',
            syllables: {!! json_encode(explode('-', $currentPage->suku_kata)) !!},
            imageSrc: '{{ Storage::url($currentPage->image_path) }}',
            audioWord: '{{ Storage::url($currentPage->audio_kata) }}',
            audioExplanation: '{{ Storage::url($currentPage->audio_path) }}',
            explanation: '{{ $currentPage->explanation }}'
        };

        // ==================== BACKGROUND MUSIC SYSTEM ====================
        class BackgroundMusicSystem {
            constructor() {
                this.player = document.getElementById('backgroundMusicPlayer');
                this.slider = document.getElementById('volumeSlider');
                this.volumePercent = document.getElementById('volumePercent');
                this.volumeIcon = document.getElementById('volumeMuteIcon');
                this.isMuted = false;
                this.previousVolume = 0.7;
                
                // Restore state dari sessionStorage jika ada
                this.restoreMusicState();
                
                this.init();
            }
            
            init() {
                // Jika belum ada saved state, set initial volume
                if (!sessionStorage.getItem('musicVolume')) {
                    this.setVolume(70);
                }
                
                // Mark this player as background music (no animation)
                this.player.dataset.isBackgroundMusic = 'true';
                
                // Ensure loop is enabled
                this.player.loop = true;
                
                // Slider change event
                this.slider.addEventListener('input', (e) => {
                    this.setVolume(e.target.value);
                });
                
                // Mute toggle
                this.volumeIcon.addEventListener('click', () => {
                    this.toggleMute();
                });
                
                // Remove ALL animation hooks dari background music - jangan ada animasi sama sekali
                this.player.onplay = () => {};
                this.player.onended = () => {};
                this.player.onerror = () => {};
                this.player.onpause = () => {};
                this.player.onloadstart = () => {};
                
                // Auto play ketika page load
                setTimeout(() => {
                    this.play();
                }, 500);
            }
            
            setVolume(percent) {
                percent = Math.max(0, Math.min(100, percent));
                this.slider.value = percent;
                this.volumePercent.textContent = percent + '%';
                
                // Set player volume (0-1 range)
                this.player.volume = percent / 100;
                
                // Save to sessionStorage
                sessionStorage.setItem('musicVolume', percent);
                
                // Update icon
                if (percent === 0) {
                    this.volumeIcon.classList = 'fas fa-volume-mute volume-icon';
                } else if (percent < 40) {
                    this.volumeIcon.classList = 'fas fa-volume-down volume-icon';
                } else {
                    this.volumeIcon.classList = 'fas fa-volume-up volume-icon';
                }
            }
            
            toggleMute() {
                if (this.isMuted) {
                    // Unmute
                    this.setVolume(this.previousVolume * 100);
                    this.isMuted = false;
                } else {
                    // Mute
                    this.previousVolume = this.player.volume;
                    this.setVolume(0);
                    this.isMuted = true;
                }
            }
            
            play() {
                if (this.player.paused) {
                    this.player.play().catch(err => {
                        console.log('🎵 Auto-play blocked:', err);
                    });
                }
            }
            
            pause() {
                this.player.pause();
            }
            
            resume() {
                this.play();
            }
            
            stop() {
                this.player.pause();
                this.player.currentTime = 0;
            }
            
            // Simpan state musik sebelum navigasi
            saveMusicState() {
                if (this.player) {
                    sessionStorage.setItem('musicIsPlaying', !this.player.paused);
                    sessionStorage.setItem('musicCurrentTime', this.player.currentTime);
                    sessionStorage.setItem('musicVolume', Math.round(this.player.volume * 100));
                }
            }
            
            // Restore state musik setelah navigasi
            restoreMusicState() {
                const isPlaying = sessionStorage.getItem('musicIsPlaying') === 'true';
                const currentTime = parseFloat(sessionStorage.getItem('musicCurrentTime')) || 0;
                const volume = parseInt(sessionStorage.getItem('musicVolume')) || 70;
                
                // Set volume immediately
                if (this.slider) {
                    this.slider.value = volume;
                }
                if (this.volumePercent) {
                    this.volumePercent.textContent = volume + '%';
                }
                if (this.player) {
                    this.player.volume = volume / 100;
                }
                
                // Resume playing jika sebelumnya sedang dimainkan
                if (isPlaying && this.player) {
                    setTimeout(() => {
                        this.player.currentTime = currentTime;
                        this.play();
                    }, 100);
                }
            }
        }

        // ==================== AUDIO QUEUE SYSTEM ====================
        class AudioQueueSystem {
            constructor() {
                this.queue = [];
                this.isPlaying = false;
                this.syllableAudioPlayer = document.getElementById('syllableAudioPlayer');
                this.csrfToken = '{{ csrf_token() }}';
                this.queueIndicator = document.getElementById('audioQueueIndicator');
                this.queueCount = document.getElementById('queueCount');
                this.currentAudioType = null; // Track jenis audio: 'syllable', 'word', 'explanation', 'combined'
                this.voiceAgentSystem = null; // Reference ke VoiceAgentSystem
            }
            
            setVoiceAgentSystem(system) {
                this.voiceAgentSystem = system;
            }
            
            async addToQueue(type, data) {
                this.queue.push({ type, data, id: Date.now() + Math.random() });
                this.updateQueueIndicator();
                
                console.log(`📝 Added to queue: ${type}`, data);
                
                if (!this.isPlaying) {
                    this.playNext();
                }
            }
            
            updateQueueIndicator() {
                if (this.queue.length > 0) {
                    this.queueCount.textContent = this.queue.length;
                    this.queueIndicator.classList.add('show');
                } else {
                    this.queueIndicator.classList.remove('show');
                }
            }
            
            async playNext() {
                if (this.queue.length === 0 || this.isPlaying) {
                    this.isPlaying = false;
                    this.updateQueueIndicator();
                    // Enable microphone ketika queue selesai
                    if (this.queue.length === 0 && this.voiceAgentSystem) {
                        this.voiceAgentSystem.enableMicrophone();
                    }
                    return;
                }
                
                this.isPlaying = true;
                const item = this.queue.shift();
                this.currentAudioType = item.type;
                this.updateQueueIndicator();
                
                // Disable microphone untuk semua audio kecuali background
                if (item.type !== 'background' && this.voiceAgentSystem) {
                    this.voiceAgentSystem.disableMicrophone();
                }
                
                try {
                    console.log(`▶️ Playing queue item: ${item.type}`);
                    
                    switch (item.type) {
                        case 'syllable':
                            await this.playSyllable(item.data);
                            break;
                        case 'word':
                            await this.playWord();
                            break;
                        case 'explanation':
                            await this.playExplanation();
                            break;
                        case 'combined':
                            await this.playCombined(item.data);
                            break;
                    }
                    
                    // Delay antara audio
                    await new Promise(resolve => setTimeout(resolve, 500));
                    
                } catch (error) {
                    console.error('❌ Error playing audio:', error);
                    // Enable microphone on error
                    if (this.voiceAgentSystem) {
                        this.voiceAgentSystem.enableMicrophone();
                    }
                } finally {
                    this.isPlaying = false;
                    this.currentAudioType = null;
                    // Delay sebelum memainkan berikutnya
                    setTimeout(() => this.playNext(), 300);
                }
            }
            
            async playSyllable({ syllable, index }) {
                console.log(`🔊 Playing syllable: "${syllable}" (posisi ${index + 1})`);
                
                // Show feedback
                if (window.character) {
                    window.character.startTalking();
                    window.character.showAbdomenText(syllable, false);
                }
                
                // Show syllable feedback
                this.showSyllableFeedback(syllable);
                
                // Generate TTS for syllable
                const response = await fetch('/voice-agent/text-to-speech', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({ 
                        text: syllable,
                        voice: 'id-ID-Standard-A',
                        speaking_rate: 0.85,
                        pitch: 0.2
                    })
                });
                
                if (response.ok) {
                    const audioBlob = await response.blob();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    await this.playAudio(audioUrl);
                    URL.revokeObjectURL(audioUrl);
                }
                
                if (window.character) {
                    window.character.stopTalking();
                }
                
                this.hideSyllableFeedback();
            }
            
            async playWord() {
                console.log(`🔊 Playing full word: "${gameData.word}"`);
                
                if (!gameData.audioWord) {
                    console.warn('⚠️ No word audio available');
                    return;
                }
                
                if (window.character) {
                    window.character.startTalking();
                    window.character.showAbdomenText(gameData.word, false);
                }
                
                await this.playAudio(gameData.audioWord);
                
                if (window.character) {
                    window.character.stopTalking();
                }
            }
            
            async playExplanation() {
                console.log(`🔊 Playing explanation`);
                
                if (!gameData.audioExplanation) {
                    console.warn('⚠️ No explanation audio available');
                    return;
                }
                
                if (window.character) {
                    window.character.startTalking();
                    const shortExplanation = gameData.explanation.substring(0, 40) + "...";
                    window.character.showAbdomenText(shortExplanation, false);
                }
                
                await this.playAudio(gameData.audioExplanation);
                
                if (window.character) {
                    window.character.stopTalking();
                }
            }
            
            async playCombined({ syllables }) {
                console.log(`🔊 Playing combined syllables: "${syllables}"`);
                
                if (window.character) {
                    window.character.startTalking();
                    window.character.showAbdomenText(syllables, false);
                }
                
                const response = await fetch('/voice-agent/text-to-speech', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({ 
                        text: syllables,
                        voice: 'id-ID-Standard-B',
                        speaking_rate: 0.9
                    })
                });
                
                if (response.ok) {
                    const audioBlob = await response.blob();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    await this.playAudio(audioUrl);
                    URL.revokeObjectURL(audioUrl);
                }
                
                if (window.character) {
                    window.character.stopTalking();
                }
            }
            
            playAudio(src, shouldAnimate = true) {
                return new Promise((resolve, reject) => {
                    const audio = new Audio(src);
                    audio.volume = 0.85;
                    
                    // Start character animation when audio starts (JIKA shouldAnimate TRUE)
                    audio.onplay = () => {
                        if (shouldAnimate && this.characterAnimator) {
                            this.characterAnimator.startTalking();
                        }
                    };
                    
                    audio.onended = () => {
                        console.log('✅ Audio finished');
                        // Stop character animation when audio ends (JIKA shouldAnimate TRUE)
                        if (shouldAnimate && this.characterAnimator) {
                            this.characterAnimator.stopTalking();
                        }
                        // Enable microphone ketika audio selesai
                        if (this.currentAudioType !== 'background' && this.voiceAgentSystem) {
                            this.voiceAgentSystem.enableMicrophone();
                        }
                        resolve();
                    };
                    
                    audio.onerror = (error) => {
                        console.error('❌ Audio error:', error);
                        // Stop animation on error
                        if (shouldAnimate && this.characterAnimator) {
                            this.characterAnimator.stopTalking();
                        }
                        // Enable microphone on error
                        if (this.voiceAgentSystem) {
                            this.voiceAgentSystem.enableMicrophone();
                        }
                        reject(error);
                    };
                    
                    audio.play().catch((error) => {
                        // Stop animation if play fails
                        if (shouldAnimate && this.characterAnimator) {
                            this.characterAnimator.stopTalking();
                        }
                        // Enable microphone if play fails
                        if (this.voiceAgentSystem) {
                            this.voiceAgentSystem.enableMicrophone();
                        }
                        reject(error);
                    });
                });
            }
            
            showSyllableFeedback(syllable) {
                const feedback = document.getElementById('syllableAudioFeedback');
                const text = document.getElementById('syllableAudioText');
                if (feedback && text) {
                    text.textContent = syllable;
                    feedback.classList.add('show');
                }
            }
            
            hideSyllableFeedback() {
                const feedback = document.getElementById('syllableAudioFeedback');
                if (feedback) {
                    setTimeout(() => {
                        feedback.classList.remove('show');
                    }, 500);
                }
            }
            
            clearQueue() {
                this.queue = [];
                this.isPlaying = false;
                this.updateQueueIndicator();
            }
        }

        // ==================== SYLLABLE AUDIO SYSTEM ====================
        class SyllableAudioSystem {
            constructor() {
                this.isAudioEnabled = true;
                this.audioQueueSystem = new AudioQueueSystem();
                this.csrfToken = '{{ csrf_token() }}';
                
                this.init();
            }
            
            init() {
                console.log('🔊 Syllable Audio System initialized');
            }
            
            setCharacterAnimator(character) {
                this.characterAnimator = character;
            }
            
            // ============ Main: Mainkan audio suku kata menggunakan antrian ============
            async playSyllableAudio(syllable, syllableIndex = null) {
                if (!this.isAudioEnabled) return;
                
                console.log(`📝 Queueing syllable: "${syllable}" (posisi ${syllableIndex + 1})`);
                
                // Tambahkan ke antrian
                this.audioQueueSystem.addToQueue('syllable', { 
                    syllable, 
                    index: syllableIndex 
                });
            }
            
            // ============ Mainkan audio gabungan dari suku kata yang sudah ditempatkan ============
            async playCombinedSyllables() {
                if (!this.isAudioEnabled) return;
                
                const dropZones = document.querySelectorAll('.drop-zone');
                const placedSyllables = [];
                
                dropZones.forEach(zone => {
                    if (zone.dataset.syllable) {
                        placedSyllables.push(zone.dataset.syllable);
                    }
                });
                
                if (placedSyllables.length === 0) return;
                
                const combinedText = placedSyllables.join('');
                
                console.log(`📝 Queueing combined syllables: "${combinedText}"`);
                
                // Tambahkan ke antrian
                this.audioQueueSystem.addToQueue('combined', { 
                    syllables: combinedText
                });
            }
            
            // ============ Mainkan audio KATA LENGKAP dari database ============
            async playFullWordAudio() {
                if (!this.isAudioEnabled) return;
                
                console.log('📝 Queueing explanation audio');
                
                // Tunggu 800ms sebelum menambahkan ke antrian
                setTimeout(() => {
                    // Skip audio kata (audio_kata), langsung ke penjelasan (audio_path)
                    this.audioQueueSystem.addToQueue('explanation', {});
                }, 800);
            }
            
            toggleAudio() {
                this.isAudioEnabled = !this.isAudioEnabled;
                return this.isAudioEnabled;
            }
            
            setAudioStatus(enabled) {
                this.isAudioEnabled = enabled;
            }
            
            clearQueue() {
                if (this.audioQueueSystem) {
                    this.audioQueueSystem.clearQueue();
                }
            }
        }

        // ==================== VOICE AGENT SYSTEM ====================
        class VoiceAgentSystem {
            constructor() {
                this.isRecording = false;
                this.audioContext = null;
                this.microphoneStream = null;
                this.isMicrophoneAvailable = false;
                this.isDraggingVoiceAgent = false;
                this.recordingTimer = null;
                this.recordingStartTime = null;
                this.audioBuffers = [];
                this.audioProcessor = null;
                this.isMuted = false;
                this.globalVolume = 0.9;
                this.audioQueue = [];
                this.isPlayingAudio = false;
                this.csrfToken = '{{ csrf_token() }}';
                this.isAudioEnabled = true;
                this.syllableAudioSystem = null;
                
                // DOM Elements
                this.voiceAgentWrapper = document.getElementById('voiceAgentWrapper');
                this.voiceAgentButton = document.getElementById('voiceAgentButton');
                this.voiceAgentIcon = document.getElementById('voiceAgentIcon');
                this.voiceAgentDragArea = document.getElementById('voiceAgentDragArea');
                this.voiceAgentStatus = document.getElementById('voiceAgentStatus');
                this.statusText = document.getElementById('statusText');
                this.recordingStatus = document.getElementById('recordingStatus');
                this.recordingProgressFill = document.getElementById('recordingProgressFill');
                this.audioToggle = document.getElementById('audioToggle');
                this.audioIcon = document.getElementById('audioIcon');
                this.audioStatusText = document.getElementById('audioStatusText');
                this.audioFeedback = document.getElementById('audioFeedback');
                this.feedbackText = document.getElementById('feedbackText');
                this.audioPlayer = document.getElementById('audioPlayer');
                this.responseAudioPlayer = document.getElementById('responseAudioPlayer');
                this.waitingForResponse = document.getElementById('waitingForResponse');
                
                // Drag variables
                this.startX = 0;
                this.startY = 0;
                this.initialX = 0;
                this.initialY = 0;
                this.isDrag = false;
                this.dragTimeout = null;
                
                this.init();
            }
            
            init() {
                this.setupDragAndDrop();
                this.setupEventListeners();
                this.checkMicrophonePermission();
                
                // Set initial state
                this.updateStatus('Voice Agent siap');
                
                // Enter voice agent
                setTimeout(() => {
                    this.voiceAgentWrapper.style.transform = 'translateX(0)';
                    this.voiceAgentWrapper.style.right = '20px';
                }, 1500);
            }
            
            setCharacterAnimator(character) {
                this.characterAnimator = character;
            }
            
            setupDragAndDrop() {
                // DRAG AREA (pinggiran) untuk drag
                this.voiceAgentDragArea.addEventListener('mousedown', (e) => this.startDrag(e));
                this.voiceAgentDragArea.addEventListener('touchstart', (e) => {
                    e.preventDefault();
                    if (e.touches[0]) {
                        this.startDrag(e.touches[0]);
                    }
                }, { passive: false });
                
                // TOMBOL untuk click recording
                this.voiceAgentButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    e.preventDefault();
                    this.toggleRecording();
                });
                
                // Touch untuk tombol
                this.voiceAgentButton.addEventListener('touchstart', (e) => {
                    e.stopPropagation();
                }, { passive: false });
                
                this.voiceAgentButton.addEventListener('touchend', (e) => {
                    e.stopPropagation();
                    e.preventDefault();
                    this.toggleRecording();
                }, { passive: false });
                
                // Global drag events
                document.addEventListener('mousemove', (e) => this.drag(e));
                document.addEventListener('mouseup', () => this.stopDrag());
                
                document.addEventListener('touchmove', (e) => {
                    if (this.isDraggingVoiceAgent && e.touches[0]) {
                        e.preventDefault();
                        this.drag(e.touches[0]);
                    }
                }, { passive: false });
                
                document.addEventListener('touchend', () => this.stopDrag());
            }
            
            startDrag(e) {
                this.isDraggingVoiceAgent = true;
                this.isDrag = true;
                this.voiceAgentWrapper.classList.add('dragging');
                
                const rect = this.voiceAgentWrapper.getBoundingClientRect();
                this.startX = e.clientX;
                this.startY = e.clientY;
                this.initialX = rect.left;
                this.initialY = rect.top;
                
                this.updateStatus('Drag untuk pindahkan');
            }
            
            drag(e) {
                if (!this.isDraggingVoiceAgent) return;
                
                const dx = e.clientX - this.startX;
                const dy = e.clientY - this.startY;
                
                this.voiceAgentWrapper.style.left = (this.initialX + dx) + 'px';
                this.voiceAgentWrapper.style.top = (this.initialY + dy) + 'px';
                this.voiceAgentWrapper.style.right = 'auto';
                this.voiceAgentWrapper.style.bottom = 'auto';
            }
            
            stopDrag() {
                if (this.isDraggingVoiceAgent) {
                    this.isDraggingVoiceAgent = false;
                    this.isDrag = false;
                    this.voiceAgentWrapper.classList.remove('dragging');
                    this.updateStatus('Voice Agent siap');
                }
            }
            
            setupEventListeners() {
                // Audio toggle
                this.audioToggle.addEventListener('click', () => this.toggleAudio());
                
                // Audio player events
                this.audioPlayer.addEventListener('play', () => {
                    this.showAudioFeedback('Memutar audio...');
                    if (window.character) {
                        window.character.startTalking();
                    }
                });
                
                this.audioPlayer.addEventListener('ended', () => {
                    if (window.character) {
                        window.character.stopTalking();
                    }
                    this.playNextAudio();
                });
                
                this.responseAudioPlayer.addEventListener('play', () => {
                    if (window.character) {
                        window.character.startTalking();
                    }
                });
                
                this.responseAudioPlayer.addEventListener('ended', () => {
                    if (window.character) {
                        window.character.stopTalking();
                    }
                });
            }
            
            async toggleRecording() {
                if (this.isRecording) {
                    await this.stopRecording();
                } else {
                    await this.startRecording();
                }
            }
            
            async startRecording() {
                if (!this.isMicrophoneAvailable) {
                    this.updateStatus('🎤 Mengakses mikrofon... Silakan izinkan akses mikrofon');
                }
                
                // Pause background music saat mulai recording
                if (window.backgroundMusicSystem) {
                    window.backgroundMusicSystem.pause();
                }
                
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ 
                        audio: {
                            echoCancellation: true,
                            noiseSuppression: true,
                            sampleRate: 16000,
                            channelCount: 1
                        }
                    });
                    
                    this.audioContext = new (window.AudioContext || window.webkitAudioContext)({
                        sampleRate: 16000
                    });
                    
                    this.microphoneStream = stream;
                    this.audioBuffers = [];
                    
                    const source = this.audioContext.createMediaStreamSource(stream);
                    this.audioProcessor = this.audioContext.createScriptProcessor(4096, 1, 1);
                    
                    this.audioProcessor.onaudioprocess = (e) => {
                        if (!this.isRecording) return;
                        
                        const inputData = e.inputBuffer.getChannelData(0);
                        this.audioBuffers.push(new Float32Array(inputData));
                    };
                    
                    source.connect(this.audioProcessor);
                    this.audioProcessor.connect(this.audioContext.destination);
                    
                    this.isRecording = true;
                    this.recordingStartTime = Date.now();
                    this.voiceAgentButton.classList.add('recording');
                    this.voiceAgentIcon.className = 'fas fa-stop voice-agent-icon';
                    this.recordingStatus.classList.add('show');
                    
                    this.updateStatus('🎤 Merekam... Klik lagi untuk berhenti');
                    
                    this.updateRecordingProgress();
                    
                    this.recordingTimer = setTimeout(() => {
                        if (this.isRecording) {
                            this.stopRecording();
                        }
                    }, 15000);
                    
                } catch (error) {
                    console.error('Recording error:', error);
                    
                    // Resume background music jika ada error
                    if (window.backgroundMusicSystem) {
                        window.backgroundMusicSystem.resume();
                    }
                    
                    // Handle different error types
                    if (error.name === 'NotAllowedError') {
                        this.updateStatus('❌ Izin mikrofon ditolak. Klik izinkan di notifikasi browser.');
                    } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                        this.updateStatus('❌ Mikrofon tidak ditemukan');
                    } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                        this.updateStatus('❌ Mikrofon sedang digunakan aplikasi lain');
                    } else if (error.name === 'SecurityError') {
                        this.updateStatus('❌ Akses mikrofon ditolak untuk keamanan (gunakan HTTPS)');
                    } else {
                        this.updateStatus('❌ Gagal mengakses mikrofon: ' + error.message);
                    }
                    
                    this.isMicrophoneAvailable = false;
                    this.voiceAgentButton.classList.remove('recording');
                    this.voiceAgentIcon.className = 'fas fa-microphone voice-agent-icon';
                    // Enable microphone button on error
                    this.voiceAgentButton.disabled = false;
                }
            }
            
            updateRecordingProgress() {
                if (!this.isRecording) return;
                
                const elapsed = Date.now() - this.recordingStartTime;
                const progress = Math.min((elapsed / 15000) * 100, 100);
                
                this.recordingProgressFill.style.width = `${progress}%`;
                
                if (this.isRecording) {
                    requestAnimationFrame(() => this.updateRecordingProgress());
                }
            }
            
            async stopRecording() {
                if (!this.isRecording) return;
                
                this.isRecording = false;
                
                if (this.audioProcessor) {
                    this.audioProcessor.disconnect();
                    this.audioProcessor = null;
                }
                
                if (this.audioContext) {
                    await this.audioContext.close();
                    this.audioContext = null;
                }
                
                if (this.microphoneStream) {
                    this.microphoneStream.getTracks().forEach(track => track.stop());
                    this.microphoneStream = null;
                }
                
                if (this.audioBuffers.length > 0) {
                    this.processWAVAudio();
                    // Resume background music segera setelah recording selesai (tanpa tunggu respons AI)
                    if (window.backgroundMusicSystem) {
                        setTimeout(() => {
                            window.backgroundMusicSystem.resume();
                        }, 300);
                    }
                } else {
                    this.updateStatus('❌ Tidak ada suara yang direkam');
                    // Resume background music jika tidak ada suara yang direkam
                    if (window.backgroundMusicSystem) {
                        setTimeout(() => {
                            window.backgroundMusicSystem.resume();
                        }, 500);
                    }
                }
                
                this.voiceAgentButton.classList.remove('recording');
                this.voiceAgentIcon.className = 'fas fa-microphone voice-agent-icon';
                this.recordingStatus.classList.remove('show');
                this.recordingProgressFill.style.width = '0%';
                
                if (this.recordingTimer) {
                    clearTimeout(this.recordingTimer);
                    this.recordingTimer = null;
                }
                
                this.updateStatus('Memproses suara...');
            }
            
            processWAVAudio() {
                try {
                    let totalLength = 0;
                    this.audioBuffers.forEach(buffer => {
                        totalLength += buffer.length;
                    });
                    
                    const mergedBuffer = this.mergeBuffers(this.audioBuffers, totalLength);
                    const wavBuffer = this.encodeWAV(mergedBuffer, 16000);
                    const wavBlob = new Blob([wavBuffer], { type: 'audio/wav' });
                    
                    console.log('WAV created, size:', wavBlob.size, 'bytes');
                    
                    this.sendWAVToServer(wavBlob);
                    
                    this.audioBuffers = [];
                    
                } catch (error) {
                    console.error('Error processing WAV audio:', error);
                    this.updateStatus('❌ Error memproses audio');
                }
            }
            
            mergeBuffers(bufferList, length) {
                const result = new Float32Array(length);
                let offset = 0;
                for (let i = 0; i < bufferList.length; i++) {
                    result.set(bufferList[i], offset);
                    offset += bufferList[i].length;
                }
                return result;
            }
            
            encodeWAV(samples, sampleRate = 16000, numChannels = 1, bitsPerSample = 16) {
                const buffer = new ArrayBuffer(44 + samples.length * 2);
                const view = new DataView(buffer);
                
                this.writeString(view, 0, 'RIFF');
                view.setUint32(4, 36 + samples.length * 2, true);
                this.writeString(view, 8, 'WAVE');
                this.writeString(view, 12, 'fmt ');
                view.setUint32(16, 16, true);
                view.setUint16(20, 1, true);
                view.setUint16(22, numChannels, true);
                view.setUint32(24, sampleRate, true);
                view.setUint32(28, sampleRate * numChannels * bitsPerSample / 8, true);
                view.setUint16(32, numChannels * bitsPerSample / 8, true);
                view.setUint16(34, bitsPerSample, true);
                this.writeString(view, 36, 'data');
                view.setUint32(40, samples.length * 2, true);
                
                this.floatTo16BitPCM(view, 44, samples);
                
                return buffer;
            }
            
            writeString(view, offset, string) {
                for (let i = 0; i < string.length; i++) {
                    view.setUint8(offset + i, string.charCodeAt(i));
                }
            }
            
            floatTo16BitPCM(view, offset, input) {
                for (let i = 0; i < input.length; i++, offset += 2) {
                    const s = Math.max(-1, Math.min(1, input[i]));
                    view.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
                }
            }
            
            async sendWAVToServer(wavBlob) {
                const formData = new FormData();
                formData.append('audio', wavBlob, 'recording.wav');
                formData.append('user_id', 'user_{{ auth()->id() ?? "guest" }}');
                formData.append('context', 'syllable_game');
                formData.append('current_word', gameData.word);
                formData.append('current_syllables', JSON.stringify(gameData.syllables));
                formData.append('audio_format', 'wav');
                
                // Show waiting indicator
                this.showWaitingForResponse();
                
                // Disable microphone button while waiting for AI response
                this.disableMicrophone();
                
                try {
                    const response = await fetch('/voice-agent/process-voice', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrfToken },
                        body: formData
                    });
                    
                    // Hide waiting indicator
                    this.hideWaitingForResponse();
                    
                    if (response.ok) {
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.includes('audio')) {
                            const audioBlob = await response.blob();
                            const audioUrl = URL.createObjectURL(audioBlob);
                            
                            this.updateStatus('🎵 AI merespons');
                            
                            if (this.isAudioEnabled) {
                                this.playResponseAudio(audioUrl, true);
                            } else {
                                // Re-enable microphone if audio is disabled
                                this.enableMicrophone();
                            }
                            
                            // Resume background music setelah AI merespons
                            setTimeout(() => {
                                if (window.backgroundMusicSystem) {
                                    window.backgroundMusicSystem.resume();
                                }
                            }, 500);
                            
                        } else {
                            const data = await response.json();
                            if (data.text) {
                                this.updateStatus('AI: ' + data.text.substring(0, 50) + '...');
                                this.addToAudioQueue(data.text);
                            }
                            
                            // Resume background music setelah response diterima
                            setTimeout(() => {
                                if (window.backgroundMusicSystem) {
                                    window.backgroundMusicSystem.resume();
                                }
                            }, 500);
                        }
                    } else {
                        this.updateStatus('❌ Server error');
                        // Resume background music jika error
                        if (window.backgroundMusicSystem) {
                            window.backgroundMusicSystem.resume();
                        }
                        // Re-enable microphone on error
                        this.enableMicrophone();
                    }
                    
                } catch (error) {
                    // Hide waiting indicator on error
                    this.hideWaitingForResponse();
                    
                    console.error('Send error:', error);
                    this.updateStatus('❌ Gagal mengirim ke server');
                    // Resume background music jika ada error
                    if (window.backgroundMusicSystem) {
                        window.backgroundMusicSystem.resume();
                    }
                    // Re-enable microphone on error
                    this.enableMicrophone();
                    this.isPlayingAudio = false;
                }
            }
            
            addToAudioQueue(text) {
                if (!this.isAudioEnabled) return;
                
                if (this.audioQueue.length > 0 && this.audioQueue[this.audioQueue.length - 1] === text) {
                    console.warn('Duplicate audio skipped:', text);
                    return;
                }
                
                this.audioQueue.push(text);
                if (!this.isPlayingAudio) {
                    this.playNextAudio();
                }
            }
            
            async playNextAudio() {
                if (this.audioQueue.length === 0 || !this.isAudioEnabled) {
                    this.isPlayingAudio = false;
                    // Re-enable microphone when audio queue is finished
                    if (this.audioQueue.length === 0) {
                        this.enableMicrophone();
                    }
                    return;
                }
                
                this.isPlayingAudio = true;
                // Disable microphone while playing TTS (Voice Agent response)
                this.disableMicrophone();
                const text = this.audioQueue.shift();
                
                try {
                    const response = await fetch('/voice-agent/text-to-speech', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({ text: text })
                    });
                    
                    if (response.ok) {
                        const audioBlob = await response.blob();
                        const audioUrl = URL.createObjectURL(audioBlob);
                        
                        this.audioPlayer.src = audioUrl;
                        this.audioPlayer.volume = this.globalVolume;
                        
                        this.audioPlayer.onended = () => {
                            URL.revokeObjectURL(audioUrl);
                            // Stop character talking animation
                            if (window.character) {
                                window.character.stopTalking();
                            }
                            setTimeout(() => this.playNextAudio(), 500);
                        };
                        
                        this.audioPlayer.onerror = () => {
                            URL.revokeObjectURL(audioUrl);
                            if (window.character) {
                                window.character.stopTalking();
                            }
                            this.playNextAudio();
                        };
                        
                        this.audioPlayer.play().then(() => {
                            // Start character talking animation ONLY for non-background audio
                            if (window.character) {
                                window.character.startTalking();
                                window.character.showAbdomenText(text.substring(0, 100), false);
                            }
                            this.showAudioFeedback(text.substring(0, 50));
                        }).catch(e => {
                            console.log('Audio play skipped:', e);
                            URL.revokeObjectURL(audioUrl);
                            if (window.character) {
                                window.character.stopTalking();
                            }
                            this.playNextAudio();
                        });
                    } else {
                        this.playNextAudio();
                    }
                } catch (error) {
                    console.error('TTS error:', error);
                    if (window.character) {
                        window.character.stopTalking();
                    }
                    // Re-enable microphone on error
                    if (this.audioQueue.length === 0) {
                        this.enableMicrophone();
                    } else {
                        this.playNextAudio();
                    }
                }
            }
            
            playResponseAudio(audioUrl, shouldEnableMicAfter = false) {
                if (!this.isAudioEnabled) return;
                
                if (this.audioPlayer.src) {
                    this.audioPlayer.pause();
                    this.audioPlayer.currentTime = 0;
                }
                
                this.responseAudioPlayer.src = audioUrl;
                this.responseAudioPlayer.volume = this.globalVolume;
                
                // Start character talking animation for response audio
                if (window.character) {
                    window.character.startTalking();
                    window.character.showAbdomenText('🤖 AI menjawab...', false);
                }
                
                this.responseAudioPlayer.play().catch(e => {
                    console.log('Response audio play skipped');
                    URL.revokeObjectURL(audioUrl);
                    if (window.character) {
                        window.character.stopTalking();
                    }
                    // Re-enable microphone if audio failed to play
                    if (shouldEnableMicAfter) {
                        this.enableMicrophone();
                    }
                });
                
                this.responseAudioPlayer.onended = () => {
                    // Stop character talking animation when response finishes
                    if (window.character) {
                        window.character.stopTalking();
                    }
                    URL.revokeObjectURL(audioUrl);
                    // Re-enable microphone after response finishes
                    if (shouldEnableMicAfter) {
                        this.enableMicrophone();
                    }
                };
            }
            
            updateStatus(message) {
                this.statusText.textContent = message;
                this.voiceAgentStatus.classList.add('show');
                
                setTimeout(() => {
                    this.voiceAgentStatus.classList.remove('show');
                }, 3000);
            }
            
            showAudioFeedback(message) {
                this.feedbackText.textContent = message;
                this.audioFeedback.classList.add('show');
                
                setTimeout(() => {
                    this.audioFeedback.classList.remove('show');
                }, 2000);
            }
            
            toggleAudio() {
                this.isAudioEnabled = !this.isAudioEnabled;
                
                // Juga update sistem audio suku kata
                if (this.syllableAudioSystem) {
                    this.syllableAudioSystem.setAudioStatus(this.isAudioEnabled);
                }
                
                if (!this.isAudioEnabled) {
                    this.audioIcon.className = 'fas fa-volume-mute';
                    this.audioStatusText.textContent = 'Audio: Mati';
                    
                    this.audioPlayer.pause();
                    this.audioPlayer.currentTime = 0;
                    this.responseAudioPlayer.pause();
                    this.responseAudioPlayer.currentTime = 0;
                    
                    this.updateStatus('🔇 Audio dimatikan');
                } else {
                    this.audioIcon.className = 'fas fa-volume-up';
                    this.audioStatusText.textContent = 'Audio: Aktif';
                    this.updateStatus('🔊 Audio diaktifkan');
                }
            }
            
            async checkMicrophonePermission() {
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const hasMicrophone = devices.some(device => device.kind === 'audioinput');
                    
                    if (!hasMicrophone) {
                        this.updateStatus('Tidak ada mikrofon terdeteksi');
                        this.isMicrophoneAvailable = false;
                        return;
                    }
                    
                    // Try to query permission status (may not be supported in all browsers)
                    try {
                        const permissionStatus = await navigator.permissions.query({ name: 'microphone' });
                        if (permissionStatus.state === 'granted') {
                            this.isMicrophoneAvailable = true;
                        } else if (permissionStatus.state === 'denied') {
                            this.updateStatus('❌ Izin mikrofon ditolak. Aktifkan di pengaturan browser.');
                            this.isMicrophoneAvailable = false;
                        } else {
                            // Permission state is 'prompt', available for request
                            this.isMicrophoneAvailable = true;
                        }
                    } catch (permError) {
                        // Permissions API not supported, assume available
                        console.log('Permissions API not supported, assuming microphone available');
                        this.isMicrophoneAvailable = true;
                    }
                    
                } catch (error) {
                    console.error('Permission check error:', error);
                    this.isMicrophoneAvailable = false;
                }
            }
            
            // Fungsi untuk memainkan instruksi game
            playGameInstructions() {
                if (!this.isAudioEnabled) return;
                
                const instructions = `Ayo susun suku kata: ${gameData.word}. Tarik suku kata dari bawah ke kotak kosong di atas!`;
                this.addToAudioQueue(instructions);
            }
            
            // Fungsi untuk memainkan bantuan
            playHint() {
                if (!this.isAudioEnabled) return;
                
                const syllables = gameData.syllables.join(' - ');
                const hint = `Suku kata untuk ${gameData.word} adalah: ${syllables}. Susun dengan urutan yang benar!`;
                this.addToAudioQueue(hint);
            }
            
            // Setter untuk sistem audio suku kata
            setSyllableAudioSystem(system) {
                this.syllableAudioSystem = system;
            }
            
            // Disable microphone button
            disableMicrophone() {
                if (this.voiceAgentButton && !this.voiceAgentButton.disabled) {
                    this.voiceAgentButton.disabled = true;
                    this.voiceAgentButton.style.opacity = '0.5';
                    this.voiceAgentButton.style.cursor = 'not-allowed';
                    this.voiceAgentButton.style.pointerEvents = 'none';
                    console.log('🔇 Microphone disabled');
                }
            }
            
            // Enable microphone button
            enableMicrophone() {
                if (this.voiceAgentButton && this.voiceAgentButton.disabled) {
                    this.voiceAgentButton.disabled = false;
                    this.voiceAgentButton.style.opacity = '1';
                    this.voiceAgentButton.style.cursor = 'pointer';
                    this.voiceAgentButton.style.pointerEvents = 'auto';
                    console.log('🎤 Microphone enabled');
                }
            }
            
            // Show waiting for response indicator
            showWaitingForResponse() {
                if (this.waitingForResponse) {
                    this.waitingForResponse.classList.add('show');
                    console.log('⏳ Waiting for AI response...');
                }
            }
            
            // Hide waiting for response indicator
            hideWaitingForResponse() {
                if (this.waitingForResponse) {
                    this.waitingForResponse.classList.remove('show');
                    console.log('✅ Received AI response');
                }
            }
        }

        // ==================== SMOOTH DRAG AND DROP SYSTEM ====================
        class SmoothDragDropSystem {
            constructor() {
                this.dragging = null;
                this.dragGhost = null;
                this.dropZone = null;
                this.isDragging = false;
                this.currentX = 0;
                this.currentY = 0;
                this.animationFrame = null;
                this.voiceAgent = null;
                this.syllableAudioSystem = null;
                
                this.init();
            }
            
            init() {
                this.createDragGhost();
                this.setupEventListeners();
            }
            
            createDragGhost() {
                this.dragGhost = document.createElement('div');
                this.dragGhost.className = 'drag-ghost';
                this.dragGhost.style.display = 'none';
                document.body.appendChild(this.dragGhost);
            }
            
            setupEventListeners() {
                // Touch events for mobile
                document.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
                document.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
                document.addEventListener('touchend', (e) => this.handleTouchEnd(e));
                
                // Mouse events for desktop
                document.addEventListener('mousedown', (e) => this.handleMouseDown(e));
                document.addEventListener('mousemove', (e) => this.handleMouseMove(e));
                document.addEventListener('mouseup', (e) => this.handleMouseUp(e));
            }
            
            // Touch handlers
            handleTouchStart(e) {
                const touch = e.touches[0];
                const element = document.elementFromPoint(touch.clientX, touch.clientY);
                
                if (element && element.classList.contains('syllable')) {
                    e.preventDefault();
                    this.startDrag(element, touch.clientX, touch.clientY);
                }
            }
            
            handleTouchMove(e) {
                if (!this.isDragging) return;
                
                e.preventDefault();
                const touch = e.touches[0];
                this.updateDrag(touch.clientX, touch.clientY);
            }
            
            handleTouchEnd(e) {
                if (!this.isDragging) return;
                
                e.preventDefault();
                this.endDrag();
            }
            
            // Mouse handlers
            handleMouseDown(e) {
                if (e.button !== 0) return; // Only left click
                
                if (e.target.classList.contains('syllable')) {
                    this.startDrag(e.target, e.clientX, e.clientY);
                }
            }
            
            handleMouseMove(e) {
                if (!this.isDragging) return;
                
                this.updateDrag(e.clientX, e.clientY);
            }
            
            handleMouseUp(e) {
                if (!this.isDragging) return;
                
                this.endDrag();
            }
            
            startDrag(element, x, y) {
                if (this.isDragging || element.classList.contains('placed')) return;
                
                this.dragging = element;
                this.isDragging = true;
                this.currentX = x;
                this.currentY = y;
                
                // Setup drag ghost
                this.dragGhost.textContent = element.textContent;
                this.dragGhost.style.display = 'flex';
                this.dragGhost.style.left = x + 'px';
                this.dragGhost.style.top = y + 'px';
                
                // Add dragging class
                element.classList.add('dragging');
                
                // Highlight potential drop zones
                this.highlightDropZones();
                
                // Start animation loop
                this.animate();
                
                // Play sound
                this.playDragSound();
            }
            
            updateDrag(x, y) {
                this.currentX = x;
                this.currentY = y;
            }
            
            endDrag() {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                this.dragGhost.style.display = 'none';
                
                // Find drop zone
                const dropZone = this.findDropZone(this.currentX, this.currentY);
                
                if (dropZone && this.dragging) {
                    this.handleDrop(dropZone);
                } else {
                    // Return to original position
                    this.dragging.classList.remove('dragging');
                }
                
                // Remove highlights
                this.removeDropZoneHighlights();
                
                // Cancel animation frame
                if (this.animationFrame) {
                    cancelAnimationFrame(this.animationFrame);
                    this.animationFrame = null;
                }
                
                this.dragging = null;
            }
            
            animate() {
                if (!this.isDragging) return;
                
                // Update drag ghost position
                this.dragGhost.style.left = this.currentX + 'px';
                this.dragGhost.style.top = this.currentY + 'px';
                
                // Check for drop zone under cursor
                const dropZone = this.findDropZone(this.currentX, this.currentY);
                if (dropZone !== this.dropZone) {
                    if (this.dropZone) {
                        this.dropZone.classList.remove('highlight');
                    }
                    this.dropZone = dropZone;
                    if (this.dropZone) {
                        this.dropZone.classList.add('highlight');
                    }
                }
                
                // Continue animation
                this.animationFrame = requestAnimationFrame(() => this.animate());
            }
            
            findDropZone(x, y) {
                const elements = document.elementsFromPoint(x, y);
                for (const element of elements) {
                    if (element.classList.contains('drop-zone')) {
                        return element;
                    }
                }
                return null;
            }
            
            highlightDropZones() {
                const dropZones = document.querySelectorAll('.drop-zone');
                dropZones.forEach(zone => {
                    if (!zone.dataset.syllable) {
                        zone.classList.add('highlight');
                    }
                });
            }
            
            removeDropZoneHighlights() {
                const dropZones = document.querySelectorAll('.drop-zone');
                dropZones.forEach(zone => {
                    zone.classList.remove('highlight');
                });
            }
            
            handleDrop(dropZone) {
                if (!this.dragging || !dropZone) return;
                
                const syllable = this.dragging.textContent;
                const zoneIndex = parseInt(dropZone.dataset.index);
                
                // Check if zone is empty
                if (dropZone.dataset.syllable) {
                    this.showFeedback('Zona ini sudah terisi!', 'warning');
                    this.dragging.classList.remove('dragging');
                    return;
                }
                
                // Check correctness
                const isCorrect = syllable === gameData.syllables[zoneIndex];
                
                // Update UI
                const content = dropZone.querySelector('.zone-content');
                const empty = dropZone.querySelector('.zone-empty');
                
                content.textContent = syllable;
                empty.style.display = 'none';
                dropZone.dataset.syllable = syllable;
                
                // Update syllable state
                this.dragging.classList.remove('dragging');
                this.dragging.classList.add('placed');
                this.dragging.style.pointerEvents = 'none';
                
                // Visual feedback
                if (isCorrect) {
                    dropZone.classList.add('correct');
                    dropZone.classList.remove('wrong');
                    this.playSuccessSound();
                    
                    // Mainkan audio suku kata yang berhasil ditempatkan
                    this.playSyllableAudio(syllable, zoneIndex);
                    
                    // Check if all are correct
                    if (this.checkAllCorrect()) {
                        this.handleComplete();
                    }
                } else {
                    dropZone.classList.add('wrong');
                    this.playErrorSound();
                    
                    // Show wrong feedback
                    this.showWrongFeedback(`Coba lagi! "${syllable}" bukan di posisi ${zoneIndex + 1}`);
                    
                    // Remove after delay
                    setTimeout(() => {
                        this.removeFromDropZone(dropZone);
                    }, 1000);
                }
            }
            
            // ============ Mainkan audio untuk suku kata yang berhasil ditempatkan ============
            playSyllableAudio(syllable, zoneIndex) {
                if (this.syllableAudioSystem) {
                    // Delay kecil untuk efek yang lebih smooth
                    setTimeout(() => {
                        console.log(`📝 Queueing syllable audio: "${syllable}"`);
                        this.syllableAudioSystem.playSyllableAudio(syllable, zoneIndex);
                    }, 250);
                }
            }
            
            removeFromDropZone(dropZone) {
                const syllableText = dropZone.dataset.syllable;
                if (!syllableText) return;
                
                // Find syllable element
                const syllables = document.querySelectorAll('.syllable');
                const syllableElement = Array.from(syllables).find(el => 
                    el.textContent === syllableText && el.classList.contains('placed')
                );
                
                if (syllableElement) {
                    syllableElement.classList.remove('placed');
                    syllableElement.style.pointerEvents = '';
                }
                
                // Clear drop zone
                const content = dropZone.querySelector('.zone-content');
                const empty = dropZone.querySelector('.zone-empty');
                
                content.textContent = '';
                empty.style.display = 'block';
                dropZone.dataset.syllable = '';
                dropZone.classList.remove('correct', 'wrong');
            }
            
            checkAllCorrect() {
                const dropZones = document.querySelectorAll('.drop-zone');
                let allCorrect = true;
                
                dropZones.forEach((zone, index) => {
                    const syllable = zone.dataset.syllable;
                    const correctSyllable = gameData.syllables[index];
                    
                    if (syllable !== correctSyllable) {
                        allCorrect = false;
                    }
                });
                
                return allCorrect;
            }
            
            handleComplete() {
                console.log('✅ GAME COMPLETED - Starting audio sequence');
                
                const message = document.getElementById('completionMessage');
                message.style.display = 'block';
                
                const dropZones = document.querySelectorAll('.drop-zone');
                dropZones.forEach(zone => {
                    const rect = zone.getBoundingClientRect();
                    this.createSparkle(rect.left + rect.width / 2, rect.top + rect.height / 2);
                });
                
                this.showFeedback('🎉 Yeay! Kamu berhasil menyusun dengan benar!', 'success');
                
                // Sequence audio yang teratur menggunakan antrian
                setTimeout(() => {
                    if (this.syllableAudioSystem) {
                        // 1. Mainkan gabungan suku kata yang sudah disusun
                        this.syllableAudioSystem.playCombinedSyllables();
                        
                        // 2. Setelah gabungan selesai, mainkan kata lengkap dan penjelasan
                        //    Ini akan diqueue otomatis di dalam playFullWordAudio()
                        setTimeout(() => {
                            this.syllableAudioSystem.playFullWordAudio();
                        }, 1500);
                    }
                }, 1200);
            }
            
            createSparkle(x, y) {
                for (let i = 0; i < 8; i++) {
                    const sparkle = document.createElement('div');
                    sparkle.className = 'sparkle';
                    
                    const randomX = (Math.random() - 0.5) * 100;
                    sparkle.style.setProperty('--randomX', `${randomX}px`);
                    sparkle.style.left = x + 'px';
                    sparkle.style.top = y + 'px';
                    
                    document.body.appendChild(sparkle);
                    
                    setTimeout(() => sparkle.remove(), 1200);
                }
            }
            
            playDragSound() {
                const sound = new Audio('{{ asset("storage/music/klik.mp3") }}');
                sound.volume = 0.3;
                sound.play().catch(() => {});
            }
            
            playSuccessSound() {
                const sound = new Audio('{{ asset("storage/music/klik.mp3") }}');
                sound.volume = 0.5;
                sound.play().catch(() => {});
            }
            
            playErrorSound() {
                const sound = new Audio('{{ asset("storage/music/error.mp3") }}');
                sound.volume = 0.5;
                sound.play().catch(() => {});
            }
            
            showFeedback(message, type = 'info') {
                const existing = document.querySelector('.feedback');
                if (existing) existing.remove();
                
                const feedback = document.createElement('div');
                feedback.className = 'feedback';
                feedback.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}" 
                       style="color: var(--${type === 'success' ? 'success' : 'warning'});"></i>
                    ${message}
                `;
                
                document.body.appendChild(feedback);
                
                setTimeout(() => {
                    feedback.style.animation = 'feedbackSlide 0.3s ease reverse';
                    setTimeout(() => feedback.remove(), 300);
                }, 3000);
            }
            
            showWrongFeedback(message) {
                const existing = document.querySelector('.wrong-feedback');
                if (existing) existing.remove();
                
                const feedback = document.createElement('div');
                feedback.className = 'wrong-feedback';
                feedback.innerHTML = `
                    <i class="fas fa-times-circle" style="color: white;"></i>
                    ${message}
                `;
                
                document.body.appendChild(feedback);
                
                setTimeout(() => {
                    feedback.style.animation = 'feedbackSlide 0.3s ease reverse';
                    setTimeout(() => feedback.remove(), 300);
                }, 2000);
            }
            
            // Setter untuk sistem audio suku kata
            setSyllableAudioSystem(system) {
                this.syllableAudioSystem = system;
            }
            
            setVoiceAgent(voiceAgent) {
                this.voiceAgent = voiceAgent;
            }
        }

        // ==================== DRAGGABLE ANIMATED CHARACTER ====================
        class DraggableAnimatedCharacter {
            constructor(containerId, options = {}) {
                this.container = document.getElementById(containerId);
                if (!this.container) return;

                this.images = {
                    idle: '{{ asset("storage/AI/orangdiammelek.png") }}',
                    blink: '{{ asset("storage/AI/orangdiammerem.png") }}',
                    talk: '{{ asset("storage/AI/orangngomonga.png") }}',
                    talk2: '{{ asset("storage/AI/orangngomongi.png") }}',
                    waveLeft: '{{ asset("storage/AI/oranglambaitangana.png") }}',
                    waveRight: '{{ asset("storage/AI/oranglambaitangano.png") }}',
                    lifted: '{{ asset("storage/AI/orangngomongo.png") }}'
                };

                this.settings = {
                    blinkInterval: 3000,
                    talkSpeed: 200,
                    autoBlink: true,
                    abdomenMessages: [],
                    abdomenDisplayTime: 2400,
                    waveDuration: 800
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

                this.halloAudio = new Audio('{{ asset("storage/music/hallo.mp3") }}');
                this.halloAudio.preload = 'auto';

                this.init();
            }

            init() {
                // Create image element
                this.imgElement = document.createElement('img');
                this.imgElement.className = 'character-image';
                this.imgElement.alt = 'Nusa AI';
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

                // Start auto blink
                this.startAutoBlink();
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
            }

            startInteraction(e) {
                this.clickStartTime = Date.now();
                this.clickStartX = e.clientX;
                this.clickStartY = e.clientY;
                this.isClick = true;
                
                setTimeout(() => {
                    if (this.isClick) {
                        this.startDrag(e);
                        this.isClick = false;
                    }
                }, 150);
            }

            handleMove(e, touchEvent) {
                if (!this.isDragging && this.isClick) {
                    const moveX = Math.abs(e.clientX - this.clickStartX);
                    const moveY = Math.abs(e.clientY - this.clickStartY);
                    
                    if (moveX > 5 || moveY > 5) {
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
                    const clickDuration = Date.now() - this.clickStartTime;
                    if (clickDuration < 200) {
                        this.waveAndTalk();
                    }
                }
                
                this.isClick = false;
            }

            startDrag(e) {
                if (this.isDragging) return;
                
                this.isDragging = true;
                this.wrapper.classList.add('dragging');
                
                const rect = this.wrapper.getBoundingClientRect();
                this.dragOffsetX = e.clientX - rect.left;
                this.dragOffsetY = e.clientY - rect.top;
                
                this.setImage(this.images.lifted);
                this.imgElement.style.animation = 'none';
                
                if (e.preventDefault) e.preventDefault();
            }

            drag(e) {
                if (!this.isDragging) return;
                
                const newX = e.clientX - this.dragOffsetX;
                const newY = e.clientY - this.dragOffsetY;
                
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;
                const wrapperWidth = this.wrapper.offsetWidth;
                const wrapperHeight = this.wrapper.offsetHeight;
                
                const boundedX = Math.max(0, Math.min(newX, viewportWidth - wrapperWidth));
                const boundedY = Math.max(0, Math.min(newY, viewportHeight - wrapperHeight));
                
                this.wrapper.style.left = boundedX + 'px';
                this.wrapper.style.top = boundedY + 'px';
                this.wrapper.style.right = 'auto';
                this.wrapper.style.bottom = 'auto';
            }

            stopDrag() {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                this.wrapper.classList.remove('dragging');
                this.wrapper.classList.add('dropped');
                
                if (this.isTalking) {
                    this.setImage(this.currentImageState === 'talk' ? this.images.talk : this.images.talk2);
                } else if (this.isWaving) {
                    // Keep wave state
                } else {
                    this.setImage(this.images.idle);
                }
                
                setTimeout(() => {
                    this.imgElement.style.animation = 'characterPulse 3.5s ease-in-out infinite';
                    this.wrapper.classList.remove('dropped');
                }, 500);
            }

            // Wave animation
            async waveAndTalk() {
                if (this.isWaving || this.isDragging) return;
                
                this.isWaving = true;
                this.stopTalking();
                
                if (this.halloAudio) {
                    try {
                        this.halloAudio.currentTime = 0;
                        this.halloAudio.play().catch(e => console.log('hallo play failed:', e));
                    } catch (e) {
                        console.warn('halloAudio play error:', e);
                    }
                }
                
                this.setImage(this.images.waveLeft);
                await this.delay(300);
                
                this.setImage(this.images.waveRight);
                await this.delay(300);
                
                this.setImage(this.images.idle);
                this.isWaving = false;
                
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

            startTalking() {
                if (this.isTalking || this.isDragging) return;
                
                this.isTalking = true;
                
                this.talkInterval = setInterval(() => {
                    this.isMouthOpen = !this.isMouthOpen;
                    
                    if (!this.isBlinking && !this.isWaving && !this.isDragging) {
                        if (this.isMouthOpen) {
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
                            this.abdomenTimeout = setTimeout(() => {}, this.settings.abdomenDisplayTime);
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
                
                this.showAbdomenText("NUSA AI", false);
                
                this.abdomenLoopInterval = setInterval(() => {
                    if (!this.abdomenTextElement || this.abdomenTextElement.textContent !== "NUSA AI") {
                        this.showAbdomenText("NUSA AI", false);
                    }
                }, 5000);
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
        }

        // ==================== GAME INITIALIZATION ====================
        class GameManager {
            constructor() {
                this.syllables = gameData.syllables;
                this.dropZones = document.getElementById('dropZones');
                this.syllablesContainer = document.getElementById('syllablesContainer');
                this.voiceAgent = null;
                this.syllableAudioSystem = null;
                
                this.init();
            }
            
            init() {
                this.createDropZones();
                this.createSyllables();
                
                // Setup voice help button in navigation
                this.setupVoiceHelpButton();
            }
            
            createDropZones() {
                this.dropZones.innerHTML = '';
                
                this.syllables.forEach((_, index) => {
                    const zone = document.createElement('div');
                    zone.className = 'drop-zone';
                    zone.dataset.index = index;
                    zone.dataset.syllable = '';
                    
                    const number = document.createElement('div');
                    number.className = 'zone-number';
                    number.textContent = (index + 1);
                    
                    const content = document.createElement('div');
                    content.className = 'zone-content';
                    
                    const empty = document.createElement('div');
                    empty.className = 'zone-empty';
                    empty.textContent = 'Tarik ke sini';
                    
                    zone.appendChild(number);
                    zone.appendChild(content);
                    zone.appendChild(empty);
                    this.dropZones.appendChild(zone);
                });
            }
            
            createSyllables() {
                this.syllablesContainer.innerHTML = '';
                
                // Shuffle syllables for variety
                const shuffledSyllables = [...this.syllables].sort(() => Math.random() - 0.5);
                
                shuffledSyllables.forEach((syllable, index) => {
                    const element = document.createElement('div');
                    element.className = 'syllable';
                    element.textContent = syllable;
                    element.dataset.syllable = syllable;
                    element.dataset.id = `syllable-${syllable}-${index}`;
                    
                    this.syllablesContainer.appendChild(element);
                });
            }
            
            setupVoiceHelpButton() {
                // Add voice help button to navigation
                const navigation = document.querySelector('.navigation');
                const voiceHelpBtn = document.createElement('button');
                voiceHelpBtn.className = 'nav-btn';
                voiceHelpBtn.id = 'voiceHelpBtn';
                voiceHelpBtn.innerHTML = '<i class="fas fa-microphone"></i> Bantuan Suara';
                voiceHelpBtn.style.position = 'absolute';
                voiceHelpBtn.style.left = '50%';
                voiceHelpBtn.style.transform = 'translateX(-50%)';
                voiceHelpBtn.style.zIndex = '101';
                
                voiceHelpBtn.addEventListener('click', () => {
                    if (this.voiceAgent) {
                        this.voiceAgent.playGameInstructions();
                    }
                });
                
                navigation.appendChild(voiceHelpBtn);
            }
            
            setVoiceAgent(voiceAgent) {
                this.voiceAgent = voiceAgent;
            }
            
            setSyllableAudioSystem(system) {
                this.syllableAudioSystem = system;
            }
        }

        // ==================== GLOBAL VARIABLES ====================
        let character = null;
        let dragDropSystem = null;
        let gameManager = null;
        let voiceAgentSystem = null;
        let syllableAudioSystem = null;
        let backgroundMusicSystem = null;

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize background music system first
            backgroundMusicSystem = new BackgroundMusicSystem();
            window.backgroundMusicSystem = backgroundMusicSystem;
            
            // Initialize systems
            gameManager = new GameManager();
            dragDropSystem = new SmoothDragDropSystem();
            voiceAgentSystem = new VoiceAgentSystem();
            syllableAudioSystem = new SyllableAudioSystem();
            
            // Connect systems
            gameManager.setVoiceAgent(voiceAgentSystem);
            gameManager.setSyllableAudioSystem(syllableAudioSystem);
            dragDropSystem.setVoiceAgent(voiceAgentSystem);
            dragDropSystem.setSyllableAudioSystem(syllableAudioSystem);
            voiceAgentSystem.setSyllableAudioSystem(syllableAudioSystem);
            
            // Connect AudioQueueSystem dengan VoiceAgentSystem untuk disable/enable microphone
            if (syllableAudioSystem && syllableAudioSystem.audioQueueSystem) {
                syllableAudioSystem.audioQueueSystem.setVoiceAgentSystem(voiceAgentSystem);
            }
            
            // Initialize character
            setTimeout(() => {
                try {
                    character = new DraggableAnimatedCharacter('characterContainer');
                    window.character = character;
                    
                    // Pass character reference to audio systems
                    if (voiceAgentSystem) {
                        voiceAgentSystem.setCharacterAnimator(character);
                    }
                    if (syllableAudioSystem) {
                        syllableAudioSystem.setCharacterAnimator(character);
                    }
                    
                    // Enter character
                    setTimeout(() => {
                        if (character) {
                            character.enterFromRight();
                        }
                    }, 1500);

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
            }, 1000);
            
            // Welcome message
            setTimeout(() => {
                const feedback = document.createElement('div');
                feedback.className = 'feedback';
                feedback.innerHTML = `
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                    Ayo susun suku kata: ${gameData.word}
                `;
                document.body.appendChild(feedback);
                
                setTimeout(() => feedback.remove(), 3000);
                
                // Play voice instructions
                if (voiceAgentSystem) {
                    setTimeout(() => {
                        voiceAgentSystem.playGameInstructions();
                    }, 2000);
                }
            }, 2000);
            
            // Setup keyboard shortcuts
            setupKeyboardShortcuts();
        });

        // Function to reinitialize drag and drop after page load
        function initializeDragDrop() {
            // Remove old system
            if (window.dragDropSystem) {
                window.dragDropSystem = null;
            }
            
            // Create new drag drop system
            dragDropSystem = new SmoothDragDropSystem();
            if (voiceAgentSystem) {
                dragDropSystem.setVoiceAgent(voiceAgentSystem);
            }
            if (syllableAudioSystem) {
                dragDropSystem.setSyllableAudioSystem(syllableAudioSystem);
            }
            
            // Setup keyboard shortcuts again
            setupKeyboardShortcuts();
        }

        // ==================== NAVIGATION FUNCTIONS ====================
        function changePage(direction) {
            const currentPage = {{ $pageNumber }};
            const totalPages = {{ $book->pages->count() }};
            let nextPage = currentPage;

            if (direction === 'next' && currentPage < totalPages) {
                nextPage = currentPage + 1;
            } else if (direction === 'prev' && currentPage > 1) {
                nextPage = currentPage - 1;
            } else {
                return;
            }

            // PENTING: Simpan music state sebelum fetch
            if (backgroundMusicSystem) {
                backgroundMusicSystem.saveMusicState();
            }

            // Disable buttons during loading
            const navBtns = document.querySelectorAll('.nav-btn');
            navBtns.forEach(btn => btn.disabled = true);

            // Fade out content
            const bookContainer = document.querySelector('.book-container');
            const character = document.querySelector('.character-wrapper');
            const voiceAgent = document.querySelector('.voice-agent-wrapper');
            
            if (bookContainer) bookContainer.style.opacity = '0.5';
            if (bookContainer) bookContainer.style.pointerEvents = 'none';

            // Exit animations
            if (character) character.classList.add('exit');
            if (voiceAgent) voiceAgent.style.transform = 'translateX(100%)';

            setTimeout(() => {
                // Build the URL - using the slug and page
                const bookSlug = '{{ $book->slug }}';
                const url = `/book-page/${bookSlug}/${nextPage}`;

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Parse the new HTML from the response
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(data.html, 'text/html');
                    
                    // Update the blackboard content
                    const oldBlackboard = document.querySelector('.blackboard-wrapper');
                    const newBlackboard = newDoc.querySelector('.blackboard-wrapper');
                    
                    if (oldBlackboard && newBlackboard) {
                        oldBlackboard.innerHTML = newBlackboard.innerHTML;
                    }

                    // Update page indicators
                    const pageCounters = document.querySelectorAll('.page-counter');
                    pageCounters.forEach(counter => {
                        counter.textContent = `${nextPage}/${totalPages}`;
                    });

                    // Update progress bar
                    const progressBar = document.querySelector('.progress-bar');
                    if (progressBar) {
                        const progress = (nextPage / totalPages) * 100;
                        progressBar.style.width = progress + '%';
                    }

                    // Fade in and reset
                    if (bookContainer) bookContainer.style.opacity = '1';
                    if (bookContainer) bookContainer.style.pointerEvents = 'auto';
                    if (character) character.classList.remove('exit');
                    if (character) character.classList.add('enter');

                    // Re-enable buttons
                    navBtns.forEach(btn => {
                        btn.disabled = false;
                    });

                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // PENTING: Restore music state setelah content update
                    setTimeout(() => {
                        if (backgroundMusicSystem) {
                            backgroundMusicSystem.restoreMusicState();
                        }
                    }, 100);

                    // Reinitialize drag and drop
                    if (dragDropSystem) {
                        dragDropSystem = null;
                    }
                    initializeDragDrop();

                    // Reinitialize other systems if needed
                    if (gameManager) {
                        gameManager.resetPage();
                    }
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    // Fallback to normal navigation
                    const bookSlug = '{{ $book->slug }}';
                    const fallbackUrl = `/book-page/${bookSlug}/${nextPage}`;
                    window.location.href = fallbackUrl;
                });
            }, 600);
        }

        // ==================== FINISH BOOK FUNCTION ====================
        function finishBook() {
            // Show completion message
            alert('🎉 Selamat! Kamu sudah menyelesaikan buku ini!');
            
            // Redirect ke halaman membaca calista
            window.location.href = '/calista/membaca-calista';
        }

        // Keyboard shortcuts
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') changePage('prev');
                if (e.key === 'ArrowRight') changePage('next');
                if (e.key.toLowerCase() === 'v' && voiceAgentSystem) {
                    voiceAgentSystem.playGameInstructions();
                }
                if (e.key.toLowerCase() === 'h' && voiceAgentSystem) {
                    voiceAgentSystem.playHint();
                }
                if (e.key.toLowerCase() === 'm' && voiceAgentSystem) {
                    voiceAgentSystem.toggleAudio();
                }
                if (e.key.toLowerCase() === ' ' && voiceAgentSystem) {
                    voiceAgentSystem.toggleRecording();
                }
            });
        }

        // Prevent zoom on double tap
        let lastTouchEnd = 0;
        document.addEventListener('touchend', (event) => {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Prevent context menu on long press
        document.addEventListener('contextmenu', (e) => {
            if (e.target.classList.contains('syllable') || 
                e.target.classList.contains('character-wrapper') ||
                e.target.classList.contains('voice-agent-wrapper')) {
                e.preventDefault();
            }
        });
    </script>

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