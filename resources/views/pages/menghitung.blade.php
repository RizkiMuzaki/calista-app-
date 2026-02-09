<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menghitung - Calista dengan Voice Agent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --warning-color: #fbbf24;
            --danger-color: #f72585;
        }
        
        /* 1. Ubah Background Body dengan Animasi Awan */
        body {
            font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif;
            background: linear-gradient(to bottom, #87CEEB 0%, #E0F6FF 100%);
            min-height: 100vh;
            padding: 10px;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Animasi Awan */
        .clouds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 100px;
            opacity: 0.7;
            animation: float-cloud linear infinite;
        }
        
        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 100px;
        }
        
        .cloud-1 {
            width: 100px;
            height: 40px;
            top: 10%;
            left: -100px;
            animation-duration: 30s;
        }
        
        .cloud-1::before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 15px;
        }
        
        .cloud-1::after {
            width: 60px;
            height: 40px;
            top: -15px;
            right: 15px;
        }
        
        .cloud-2 {
            width: 120px;
            height: 50px;
            top: 30%;
            left: -120px;
            animation-duration: 45s;
            animation-delay: 5s;
        }
        
        .cloud-2::before {
            width: 60px;
            height: 60px;
            top: -30px;
            left: 20px;
        }
        
        .cloud-2::after {
            width: 70px;
            height: 50px;
            top: -20px;
            right: 20px;
        }
        
        .cloud-3 {
            width: 80px;
            height: 35px;
            top: 60%;
            left: -80px;
            animation-duration: 35s;
            animation-delay: 10s;
        }
        
        .cloud-3::before {
            width: 40px;
            height: 40px;
            top: -20px;
            left: 10px;
        }
        
        .cloud-3::after {
            width: 50px;
            height: 35px;
            top: -15px;
            right: 10px;
        }
        
        @keyframes float-cloud {
            0% {
                left: -200px;
            }
            100% {
                left: 110%;
            }
        }
        
        /* Pastikan game container di atas awan */
        .game-container {
            position: relative;
            z-index: 1;
            max-width: 1800px;
            margin: 0 auto;
        }
        
        /* 8. Header Card Adjustments */
        .header-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border-left: 6px solid #8B4513;
            backdrop-filter: blur(10px);
        }
        
        .game-title {
            color: var(--secondary-color);
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .game-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .game-area {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            height: 75vh;
            min-height: 500px;
        }
        
        @media (max-width: 992px) {
            .game-area {
                flex-direction: row;
                gap: 10px;
                height: auto;
                min-height: 60vh;
                align-items: stretch;
            }
        }
        
        @media (max-width: 768px) {
            .game-area {
                gap: 8px;
                min-height: 55vh;
            }
        }
        
        @media (max-width: 480px) {
            .game-area {
                gap: 5px;
                min-height: 50vh;
            }
        }
        
        /* 2. Ubah Panel Utama (Papan Tulis) - Sekarang Lebih Lebar */
        .question-panel {
            flex: 2;
            background: #2F4538; /* Warna hijau papan tulis */
            border-radius: 20px;
            padding: 20px;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.3),
                inset 0 0 100px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            border: 12px solid #8B4513; /* Border kayu */
            width: 100%; /* Ambil full width */
            min-width: 0;
        }
        
        @media (max-width: 992px) {
            .question-panel {
                flex: 1.5;
                padding: 15px;
                border: 8px solid #8B4513;
                border-radius: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .question-panel {
                flex: 1.3;
                padding: 12px;
                border: 6px solid #8B4513;
                border-radius: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .question-panel {
                flex: 1.2;
                padding: 10px;
                border: 4px solid #8B4513;
                border-radius: 10px;
            }
        }
        
        /* Hilangkan gradient sebelumnya, ganti dengan kayu */
        .question-panel::before {
            content: '';
            position: absolute;
            top: -12px;
            left: -12px;
            right: -12px;
            bottom: -12px;
            background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
            z-index: -1;
            border-radius: 20px;
        }
        
        /* Texture papan tulis */
        .question-panel::after {
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
            opacity: 0.5;
        }
        
        .panel-title {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 3px dashed rgba(255, 255, 255, 0.4);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
        }
        
        /* Wrapper untuk layout kiri-kanan di dalam papan tulis */
        .panel-content-wrapper {
            display: flex;
            gap: 20px;
            height: 100%;
            width: 100%;
            min-width: 0;
        }
        
        @media (max-width: 992px) {
            .panel-content-wrapper {
                gap: 12px;
            }
        }
        
        @media (max-width: 768px) {
            .panel-content-wrapper {
                gap: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .panel-content-wrapper {
                gap: 8px;
            }
        }

        /* Bagian Kiri: Visual Soal */
        .visual-section {
            flex: 2;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        
        @media (max-width: 992px) {
            .visual-section {
                flex: 1.5;
            }
        }

        /* Bagian Kanan: Drop Area */
        .drop-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px;
            border: 3px dashed rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            min-width: 0;
            min-height: 0;
        }
        
        @media (max-width: 992px) {
            .drop-section {
                padding: 12px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
                border-radius: 12px;
                flex: 1;
            }
        }
        
        @media (max-width: 768px) {
            .drop-section {
                padding: 10px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
                border-radius: 10px;
                flex: 0.9;
            }
        }
        
        @media (max-width: 480px) {
            .drop-section {
                padding: 8px;
                border: 1px dashed rgba(255, 255, 255, 0.3);
                border-radius: 8px;
                flex: 0.8;
            }
        }
        
        .visual-operation {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 15px;
            background: transparent;
            border-radius: 15px;
            border: none;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        @media (max-width: 992px) {
            .visual-operation {
                padding: 12px;
            }
        }
        
        @media (max-width: 768px) {
            .visual-operation {
                padding: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .visual-operation {
                padding: 8px;
            }
        }
        
        .operation-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            width: 100%;
        }
        
        @media (max-width: 992px) {
            .operation-container {
                gap: 8px;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .operation-container {
                gap: 6px;
                margin-bottom: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .operation-container {
                gap: 5px;
                margin-bottom: 10px;
            }
        }
        
        /* 3. Ubah Object Groups (Hilangkan Background Putih) */
        .object-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
            padding: 25px 15px;
            background: transparent; /* Hilangkan background putih */
            border-radius: 15px;
            box-shadow: none; /* Hilangkan shadow */
            position: relative;
            min-height: 180px;
            min-width: 180px;
            flex: 1;
            border: 3px dashed rgba(255, 255, 255, 0.3); /* Border kapur */
            align-content: flex-start;
            overflow-y: auto;
        }
        
        @media (max-width: 992px) {
            .object-group {
                gap: 12px;
                padding: 15px 12px;
                min-height: 150px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
                border-radius: 12px;
            }
        }
        
        @media (max-width: 768px) {
            .object-group {
                gap: 10px;
                padding: 12px 10px;
                min-height: 130px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
                border-radius: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .object-group {
                gap: 8px;
                padding: 10px 8px;
                min-height: 110px;
                border: 1px dashed rgba(255, 255, 255, 0.3);
                border-radius: 8px;
            }
        }
        
        .object-group .object-item {
            flex: 0 0 calc(33.333% - 10px);
            max-width: 90px;
        }
        
        @media (max-width: 992px) {
            .object-group .object-item {
                flex: 0 0 calc(33.333% - 8px);
                max-width: 75px;
            }
        }
        
        @media (max-width: 768px) {
            .object-group {
                min-height: 120px;
                min-width: 120px;
                padding: 12px 8px;
                gap: 8px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
            }
            
            .object-group .object-item {
                flex: 0 0 calc(33.333% - 6px);
                max-width: 60px;
            }
        }
        
        @media (max-width: 480px) {
            .object-group {
                min-height: 100px;
                min-width: 100px;
                padding: 10px 6px;
                gap: 6px;
            }
            
            .object-group .object-item {
                flex: 0 0 calc(33.333% - 4px);
                max-width: 50px;
            }
        }
        
        .group-label {
            position: absolute;
            top: -12px;
            left: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.85rem;
            white-space: nowrap;
            border: 2px solid rgba(255, 255, 255, 0.4);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        /* 4. Ubah Operator dan Teks Menjadi Putih (Seperti Kapur) */
        .operator-center {
            font-size: 3rem;
            font-weight: 900;
            color: #ffffff; /* Putih seperti kapur */
            min-width: 40px;
            text-align: center;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
            margin: 0 15px;
        }
        
        @media (max-width: 992px) {
            .operator-center {
                font-size: 2.8rem;
                min-width: 35px;
                margin: 0 10px;
            }
        }
        
        @media (max-width: 768px) {
            .operator-center {
                font-size: 2.2rem;
                min-width: 30px;
                margin: 0 6px;
            }
        }
        
        @media (max-width: 480px) {
            .operator-center {
                font-size: 1.8rem;
                min-width: 25px;
                margin: 0 5px;
            }
        }
        
        .question-marker {
            font-size: 3rem;
            color: #FFD700; /* Warna kuning emas untuk tanda tanya */
            margin: 0 15px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
        }
        
        @media (max-width: 992px) {
            .question-marker {
                font-size: 2.8rem;
                margin: 0 10px;
            }
        }
        
        @media (max-width: 768px) {
            .question-marker {
                font-size: 2.2rem;
                margin: 0 6px;
            }
        }
        
        @media (max-width: 480px) {
            .question-marker {
                font-size: 1.8rem;
                margin: 0 5px;
            }
        }
        
        .object-total {
            margin-top: 15px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 800;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 20px;
            border-radius: 12px;
            border: 3px solid rgba(255, 255, 255, 0.4);
            width: 100%;
            max-width: 500px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        
        @media (max-width: 992px) {
            .object-total {
                font-size: 1.2rem;
                padding: 10px 18px;
                margin-top: 12px;
                border: 2px solid rgba(255, 255, 255, 0.4);
            }
        }
        
        @media (max-width: 768px) {
            .object-total {
                font-size: 1rem;
                padding: 8px 15px;
                margin-top: 10px;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }
        }
        
        @media (max-width: 480px) {
            .object-total {
                font-size: 0.9rem;
                padding: 7px 12px;
                margin-top: 8px;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
        }
        
        /* Object Item Styles */
        .object-item {
            width: 90px;
            height: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: grab;
            transition: all 0.2s;
            border: 2px solid transparent;
            padding: 12px;
            position: relative;
            flex-shrink: 0;
        }
        
        @media (max-width: 992px) {
            .object-item {
                width: 80px;
                height: 100px;
                padding: 10px;
                border-radius: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .object-item {
                width: 65px;
                height: 85px;
                padding: 8px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
        }
        
        @media (max-width: 480px) {
            .object-item {
                width: 50px;
                height: 70px;
                padding: 6px;
                border-radius: 6px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }
        }
        
        .object-item:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .object-item.dragging {
            opacity: 0.3;
            cursor: grabbing;
            transform: scale(0.9);
            box-shadow: none;
        }
        
        /* Drag Ghost Element - Gambar yang mengikuti cursor */
        .drag-ghost {
            position: fixed;
            pointer-events: none;
            z-index: 10000;
            opacity: 0.95;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.5);
            border-radius: 12px;
            transform: scale(1.15) rotate(-5deg);
            background: white;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--primary-color);
            animation: float-drag 0.3s ease-out forwards;
        }
        
        @keyframes float-drag {
            from {
                opacity: 0.7;
                transform: scale(0.9) rotate(0deg);
            }
            to {
                opacity: 0.95;
                transform: scale(1.15) rotate(-5deg);
            }
        }
        
        .drag-ghost-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        
        /* Tap Mode Selected State */
        .object-item.selected {
            border: 3px solid var(--success-color);
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(76, 201, 240, 0.5);
        }
        
        .object-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 8px;
            pointer-events: none;
        }
        
        @media (max-width: 992px) {
            .object-img {
                width: 45px;
                height: 45px;
                margin-bottom: 6px;
            }
        }
        
        @media (max-width: 768px) {
            .object-img {
                width: 35px;
                height: 35px;
                margin-bottom: 5px;
            }
        }
        
        @media (max-width: 480px) {
            .object-img {
                width: 28px;
                height: 28px;
                margin-bottom: 4px;
            }
        }
        
        .object-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--secondary-color);
            text-align: center;
            pointer-events: none;
            line-height: 1.1;
        }
        
        @media (max-width: 992px) {
            .object-name {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .object-name {
                font-size: 0.65rem;
                line-height: 1;
            }
        }
        
        @media (max-width: 480px) {
            .object-name {
                font-size: 0.55rem;
            }
        }
        
        .object-number {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--warning-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }
        
        @media (max-width: 992px) {
            .object-number {
                width: 22px;
                height: 22px;
                font-size: 0.8rem;
                top: 6px;
                right: 6px;
            }
        }
        
        @media (max-width: 768px) {
            .object-number {
                width: 18px;
                height: 18px;
                font-size: 0.7rem;
                top: 5px;
                right: 5px;
            }
        }
        
        @media (max-width: 480px) {
            .object-number {
                width: 14px;
                height: 14px;
                font-size: 0.6rem;
                top: 4px;
                right: 4px;
            }
        }
        
        /* 5. Drop Panel Styles - Dihapus karena sekarang terintegrasi dalam question-panel */
        
        /* Update drop-area agar sesuai dengan background papan tulis */
        .drop-area {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 3px dashed rgba(255, 255, 255, 0.4);
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-content: flex-start;
            justify-content: center;
            transition: all 0.3s;
            min-height: 300px;
            position: relative;
            overflow-y: auto;
        }
        
        @media (max-width: 992px) {
            .drop-area {
                min-height: 200px;
                padding: 12px;
                gap: 8px;
                border-radius: 12px;
                border: 2px dashed rgba(255, 255, 255, 0.4);
            }
        }
        
        @media (max-width: 768px) {
            .drop-area {
                min-height: 150px;
                padding: 10px;
                gap: 6px;
                border-radius: 10px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
                margin-bottom: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .drop-area {
                min-height: 120px;
                padding: 8px;
                gap: 5px;
                border-radius: 8px;
                border: 1px dashed rgba(255, 255, 255, 0.3);
                margin-bottom: 8px;
            }
        }
        
        .drop-area.active {
            background: rgba(76, 201, 240, 0.1);
            border-color: var(--success-color);
            border-style: solid;
        }
        
        /* Placeholder dengan warna putih untuk kontras */
        .drop-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            width: 90%;
            max-width: 300px;
            opacity: 0.8;
            pointer-events: none;
        }
        
        .drop-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
            display: block;
            color: rgba(255, 255, 255, 0.6);
            opacity: 0.7;
        }
        
        @media (max-width: 992px) {
            .drop-placeholder i {
                font-size: 2.5rem;
                margin-bottom: 8px;
            }
        }
        
        @media (max-width: 768px) {
            .drop-placeholder i {
                font-size: 2rem;
                margin-bottom: 6px;
            }
        }
        
        @media (max-width: 480px) {
            .drop-placeholder i {
                font-size: 1.5rem;
                margin-bottom: 5px;
            }
        }
        
        .drop-placeholder h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: white;
        }
        
        @media (max-width: 992px) {
            .drop-placeholder h4 {
                font-size: 1.1rem;
                margin-bottom: 4px;
            }
        }
        
        @media (max-width: 768px) {
            .drop-placeholder h4 {
                font-size: 0.95rem;
                margin-bottom: 3px;
            }
        }
        
        @media (max-width: 480px) {
            .drop-placeholder h4 {
                font-size: 0.8rem;
                margin-bottom: 2px;
            }
        }
        
        .drop-placeholder p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        @media (max-width: 992px) {
            .drop-placeholder p {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .drop-placeholder p {
                font-size: 0.7rem;
            }
        }
        
        @media (max-width: 480px) {
            .drop-placeholder p {
                font-size: 0.6rem;
            }
        }
        
        /* Counter display dengan warna yang cocok dengan papan tulis */
        .counter-display {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            display: inline-block;
            margin: 10px auto;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        @media (max-width: 768px) {
            .counter-display {
                font-size: 1rem;
                padding: 8px 15px;
            }
        }
        
        .dropped-object {
            width: 75px;
            height: 95px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            border: 2px solid #D2691E;
            padding: 10px;
            animation: dropAnimation 0.3s ease;
        }
        
        @media (max-width: 992px) {
            .dropped-object {
                width: 70px;
                height: 90px;
                padding: 9px;
                border-radius: 9px;
            }
        }
        
        @media (max-width: 768px) {
            .dropped-object {
                width: 58px;
                height: 75px;
                padding: 8px;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }
        }
        
        @media (max-width: 480px) {
            .dropped-object {
                width: 48px;
                height: 65px;
                padding: 6px;
                border-radius: 6px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border: 1px solid #D2691E;
            }
        }
        
        @keyframes dropAnimation {
            0% { transform: scale(0.5) rotate(-10deg); opacity: 0; }
            70% { transform: scale(1.1) rotate(5deg); }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }
        
        .dropped-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        
        @media (max-width: 992px) {
            .dropped-img {
                width: 38px;
                height: 38px;
                margin-bottom: 4px;
            }
        }
        
        @media (max-width: 768px) {
            .dropped-img {
                width: 30px;
                height: 30px;
                margin-bottom: 3px;
            }
        }
        
        @media (max-width: 480px) {
            .dropped-img {
                width: 25px;
                height: 25px;
                margin-bottom: 2px;
            }
        }
        
        .dropped-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #495057;
            text-align: center;
            line-height: 1.1;
        }
        
        @media (max-width: 992px) {
            .dropped-name {
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 768px) {
            .dropped-name {
                font-size: 0.65rem;
                line-height: 1;
            }
        }
        
        @media (max-width: 480px) {
            .dropped-name {
                font-size: 0.55rem;
            }
        }
        
        /* POPUP BERHASIL */
        .success-popup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
            padding: 15px;
        }
        
        .success-content {
            background: white;
            border-radius: 15px;
            padding: 20px 25px;
            text-align: center;
            max-width: 350px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border: 4px solid var(--success-color);
            position: relative;
            animation: popUp 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }
        
        @media (max-width: 480px) {
            .success-content {
                max-width: 300px;
                padding: 18px 20px;
            }
        }
        
        .success-icon {
            font-size: 3rem;
            color: var(--success-color);
            margin-bottom: 10px;
            animation: bounce 1s infinite alternate;
        }
        
        .success-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--secondary-color);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 480px) {
            .success-title {
                font-size: 1.6rem;
            }
        }
        
        .success-message {
            font-size: 0.9rem;
            line-height: 1.4;
            color: #495057;
            margin-bottom: 15px;
        }
        
        .success-message img {
            border-radius: 8px;
            border: 2px solid var(--accent-color);
            padding: 4px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 6px 0;
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        
        .success-equation {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 8px 0;
            padding: 6px 12px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
            display: inline-block;
        }
        
        @media (max-width: 480px) {
            .success-equation {
                font-size: 1.2rem;
                padding: 5px 10px;
            }
        }
        
        .success-details {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border-radius: 8px;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid var(--accent-color);
            font-size: 0.85rem;
        }
        
        .success-details p {
            margin-bottom: 5px;
            font-size: 0.85rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes popUp {
            0% { transform: scale(0.3); opacity: 0; }
            70% { transform: scale(1.02); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes bounce {
            from { transform: translateY(0) scale(1); }
            to { transform: translateY(-5px) scale(1.03); }
        }
        
        /* FEEDBACK */
        .feedback-container {
            text-align: center;
            min-height: 60px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .feedback-correct {
            color: var(--success-color);
            font-size: 1.8rem;
            font-weight: 800;
            animation: celebrate 1s ease infinite;
            text-shadow: 1px 1px 3px rgba(74, 222, 128, 0.3);
        }
        
        .feedback-incorrect {
            color: var(--danger-color);
            font-size: 1.5rem;
            font-weight: 700;
            animation: shake 0.5s ease;
        }
        
        @keyframes celebrate {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        /* PROGRESS & NAVIGATION */
        .controls-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            margin-top: 5px;
            border-left: 6px solid #8B4513;
            backdrop-filter: blur(10px);
        }
        
        @media (max-width: 768px) {
            .controls-container {
                padding: 15px;
            }
        }
        
        .progress-bar {
            height: 12px;
            border-radius: 6px;
            background-color: #e9ecef;
            overflow: hidden;
            margin-bottom: 12px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-color) 0%, var(--primary-color) 100%);
            border-radius: 6px;
            transition: width 0.4s ease-in-out;
        }
        
        /* Controls */
        .controls-row {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        @media (min-width: 768px) {
            .controls-row {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }
        
        .mobile-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        @media (min-width: 768px) {
            .mobile-controls {
                flex-direction: row;
                align-items: center;
            }
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
            white-space: nowrap;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(67, 97, 238, 0.35);
        }
        
        @media (max-width: 992px) {
            .btn-primary-custom {
                padding: 10px 18px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 768px) {
            .btn-primary-custom {
                padding: 8px 14px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 480px) {
            .btn-primary-custom {
                padding: 7px 12px;
                font-size: 0.75rem;
            }
        }
        
        .btn-secondary-custom {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(108, 117, 125, 0.25);
            white-space: nowrap;
        }
        
        .btn-secondary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.35);
        }
        
        @media (max-width: 992px) {
            .btn-secondary-custom {
                padding: 9px 16px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 768px) {
            .btn-secondary-custom {
                padding: 8px 13px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .btn-secondary-custom {
                padding: 7px 11px;
                font-size: 0.7rem;
            }
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, var(--success-color) 0%, #16a34a 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(74, 222, 128, 0.25);
            white-space: nowrap;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(74, 222, 128, 0.35);
        }
        
        @media (max-width: 992px) {
            .btn-success-custom {
                padding: 10px 18px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 768px) {
            .btn-success-custom {
                padding: 8px 14px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 480px) {
            .btn-success-custom {
                padding: 7px 12px;
                font-size: 0.75rem;
            }
        }
        
        /* TOMBOL TUTUP POPUP */
        .close-popup-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--danger-color);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 10;
            padding: 0;
        }
        
        .close-popup-btn:hover {
            background: #dc3545;
            transform: scale(1.05);
        }
        
        /* Button Groups */
        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }
        
        @media (min-width: 768px) {
            .button-group {
                justify-content: flex-start;
            }
        }
        
        .navigation-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 10px;
        }
        
        @media (min-width: 768px) {
            .navigation-group {
                justify-content: flex-end;
                margin-top: 0;
            }
        }
        
        /* Progress Info */
        .progress-info {
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        @media (min-width: 768px) {
            .progress-info {
                margin: 0;
                text-align: left;
            }
        }
        
        /* Score Badges */
        .score-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        @media (min-width: 768px) {
            .score-badges {
                justify-content: flex-end;
                margin-bottom: 0;
            }
        }
        
        .score-badge {
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 10px;
        }
        
        @media (max-width: 768px) {
            .score-badge {
                font-size: 0.85rem;
                padding: 6px 10px;
            }
        }
        
        /* Mobile Header Stack */
        @media (max-width: 768px) {
            .header-card .d-flex {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start !important;
            }
            
            .header-card .d-flex .badge {
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
            }
            
            .header-card .d-flex > div:last-child {
                width: 100%;
            }
        }
        
        /* AUDIO FEEDBACK */
        .audio-feedback {
            position: fixed;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            z-index: 1000;
            animation: slideIn 0.3s ease, fadeOut 0.3s ease 1.5s forwards;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            max-width: 250px;
            display: none;
        }
        
        /* DRAG AUDIO INDICATOR */
        .drag-audio-indicator {
            position: fixed;
            bottom: 80px;
            right: 15px;
            background: var(--primary-color);
            color: white;
            padding: 12px;
            border-radius: 50%;
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            animation: pulse 1s infinite;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            width: 50px;
            height: 50px;
        }
        
        @media (max-width: 768px) {
            .drag-audio-indicator {
                bottom: 70px;
                right: 12px;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }
        
        .drag-audio-indicator.active {
            display: flex;
        }
        
        /* Mobile Touch Improvements */
        @media (max-width: 768px) {
            .object-item {
                touch-action: manipulation;
            }
            
            .btn {
                touch-action: manipulation;
            }
            
            .btn, .object-item, .dropped-object {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            
            .btn {
                min-height: 44px;
            }
            
            .object-item {
                min-height: 44px;
                min-width: 44px;
            }
        }
        
        /* VOICE AGENT DRAGGABLE */
        .voice-agent-draggable {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: grab;
            z-index: 1000;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            transition: all 0.3s ease;
            border: 3px solid white;
            user-select: none;
            animation: float 3s ease-in-out infinite;
            touch-action: none;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            pointer-events: auto !important;
            tap-highlight-color: transparent;
            -webkit-tap-highlight-color: transparent;
        }
        
        .voice-agent-draggable:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(67, 97, 238, 0.6);
        }
        
        .voice-agent-draggable.recording {
            background: linear-gradient(135deg, #ef476f 0%, #f72585 100%);
            animation: pulse-recording 0.8s ease-in-out infinite;
        }
        
        .voice-agent-draggable.dragging {
            cursor: grabbing;
            opacity: 0.9;
            transform: scale(0.95);
        }
        
        .voice-agent-icon {
            color: white;
            font-size: 1.8rem;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-recording {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 71, 111, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(239, 71, 111, 0); }
        }
        
        /* STATUS VOICE AGENT */
        .voice-agent-status {
            position: fixed;
            bottom: 120px;
            right: 30px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 0.85rem;
            max-width: 200px;
            display: none;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .voice-agent-status.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        /* RECORDING STATUS */
        .recording-status {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(239, 71, 111, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(239, 71, 111, 0.3);
        }
        
        .recording-status.active {
            display: flex;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { transform: translate(-50%, -100%); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
        
        /* Audio Status */
        .audio-status {
            position: fixed;
            bottom: 20px;
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
        }
        
        .audio-status:hover {
            background: rgba(0, 0, 0, 0.9);
        }
        
        @media (max-width: 768px) {
            .audio-status {
                bottom: 10px;
                left: 10px;
                padding: 8px 12px;
                font-size: 0.8rem;
            }
        }
        
        /* VOICE RECORDING PROGRESS */
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
        
        /* Audio Progress Indicator */
        .audio-progress-indicator {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 10000;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .audio-progress-content {
            max-width: 400px;
            width: 90%;
        }
        
        .audio-progress-icon {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 20px;
            animation: pulse-audio 1.5s infinite;
        }
        
        .audio-progress-text {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .audio-progress-subtext {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 20px;
        }
        
        .audio-progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .audio-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            border-radius: 3px;
            transition: width 0.3s ease;
            width: 0%;
        }
        
        @keyframes pulse-audio {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        /* Orientation warning */
        .orientation-warning {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-color);
            color: white;
            z-index: 9999;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            flex-direction: column;
        }
        
        .orientation-warning i {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) and (orientation: portrait) {
            .orientation-warning {
                display: flex;
            }
        }
        
        /* Mobile Optimizations untuk Voice Agent */
        @media (max-width: 768px) {
            .voice-agent-draggable {
                width: 70px;
                height: 70px;
                bottom: 20px;
                right: 20px;
            }
            
            .voice-agent-icon {
                font-size: 1.5rem;
            }
            
            .voice-agent-status {
                bottom: 100px;
                right: 20px;
                font-size: 0.8rem;
                padding: 8px 12px;
                max-width: 150px;
            }
            
            .recording-status {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
        
        /* 7. Mobile Responsive Enhancements */
        @media (max-width: 992px) {
            .panel-content-wrapper {
                flex-direction: column;
                gap: 15px;
            }
            
            .visual-section {
                flex: 1;
                width: 100%;
                min-height: 250px;
            }
            
            .drop-section {
                flex: 1;
                width: 100%;
                min-height: 280px;
            }
            
            .game-area {
                flex-direction: column;
                gap: 15px;
                height: auto;
                min-height: auto;
            }
            
            .question-panel,
            .drop-panel {
                max-width: 100%;
                min-width: 100%;
            }
            
            .question-panel {
                border: 8px solid #8B4513;
                padding: 15px;
            }
            
            .drop-panel {
                border: 6px solid #8B4513;
            }
            
            .operation-container {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }
            
            .object-group {
                flex: 1;
                min-height: 140px;
                min-width: 140px;
                padding: 12px 8px;
                gap: 8px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 5px;
            }
            
            .cloud {
                transform: scale(0.6);
            }
            
            .game-container {
                margin: 0 2px;
            }
            
            .header-card {
                padding: 12px 15px;
                margin-bottom: 12px;
                border-radius: 12px;
            }
            
            .game-title {
                font-size: 1.4rem;
                margin-bottom: 8px;
            }
            
            .game-subtitle {
                font-size: 0.9rem;
            }
            
            .question-panel {
                border: 6px solid #8B4513;
                padding: 12px;
                border-radius: 15px;
            }
            
            .panel-title {
                font-size: 1.2rem;
                margin-bottom: 12px;
                padding-bottom: 8px;
            }
            
            .panel-content-wrapper {
                flex-direction: column;
                gap: 12px;
            }
            
            .visual-section {
                width: 100%;
                flex: none;
            }
            
            .drop-section {
                width: 100%;
                flex: none;
            }
            
            .operation-container {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-bottom: 15px;
            }
            
            .object-group {
                min-height: 130px;
                min-width: 130px;
                padding: 10px 8px;
                gap: 8px;
                border: 2px dashed rgba(255, 255, 255, 0.3);
            }
            
            .object-item {
                width: 70px;
                height: 90px;
                padding: 8px;
                min-width: 44px;
                min-height: 44px;
                touch-action: none;
            }
            
            .object-img {
                width: 38px;
                height: 38px;
            }
            
            .object-name {
                font-size: 0.7rem;
            }
            
            .group-label {
                font-size: 0.75rem;
                padding: 2px 8px;
                top: -10px;
            }
            
            .operator-center {
                font-size: 2rem;
                margin: 0 8px;
            }
            
            .question-marker {
                font-size: 2rem;
                margin: 0 8px;
            }
            
            .object-total {
                font-size: 1rem;
                padding: 10px 15px;
                margin: 10px 0;
            }
            
            .drop-section {
                min-height: 200px;
                padding: 12px;
                flex-direction: column;
            }
            
            .drop-area {
                min-height: 170px;
                padding: 10px;
                gap: 8px;
                margin-bottom: 12px;
                position: relative;
                border: 3px dashed rgba(255, 255, 255, 0.4);
                transition: all 0.3s;
            }
            
            .drop-area.dragging-active {
                border-color: var(--success-color);
                background: rgba(76, 201, 240, 0.15);
                border-style: solid;
            }
            
            .drop-area::before {
                content: '👆 Lepaskan di sini';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: rgba(255, 255, 255, 0.6);
                font-size: 0.85rem;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s;
                white-space: nowrap;
            }
            
            .drop-area.dragging-active::before {
                opacity: 1;
            }
            
            .drop-placeholder {
                pointer-events: none;
            }
            
            .drop-placeholder i {
                font-size: 2rem;
                margin-bottom: 8px;
            }
            
            .drop-placeholder h4 {
                font-size: 0.95rem;
                margin-bottom: 4px;
            }
            
            .drop-placeholder p {
                font-size: 0.8rem;
            }
            
            .dropped-object {
                width: 65px;
                height: 85px;
                padding: 8px;
                animation: dropAnimation 0.3s ease;
            }
            
            .dropped-img {
                width: 35px;
                height: 35px;
            }
            
            .counter-display {
                font-size: 1rem;
                padding: 8px 12px;
                margin: 8px auto;
            }
            
            /* Buttons touch friendly */
            .btn {
                min-height: 48px;
                padding: 12px 20px;
                font-size: 0.95rem;
            }
            
            .controls-container {
                padding: 12px;
                margin-top: 5px;
            }
            
            .controls-row {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .button-group {
                width: 100%;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            .button-group button {
                flex: 1;
                min-width: 80px;
            }
            
            .progress-bar {
                height: 10px;
                margin-bottom: 10px;
            }
            
            .controls-row {
                flex-direction: column;
                gap: 12px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 8px;
                width: 100%;
            }
            
            .btn-primary-custom,
            .btn-secondary-custom,
            .btn-success-custom {
                width: 100%;
                padding: 10px 12px;
                font-size: 0.9rem;
                min-height: 44px;
            }
            
            .navigation-group {
                flex-direction: column;
                gap: 8px;
                width: 100%;
                margin-top: 0;
            }
            
            .mobile-controls {
                width: 100%;
                flex-direction: column;
            }
            
            .progress-info {
                font-size: 0.9rem;
                text-align: center;
                margin: 8px 0;
            }
            
            .score-badges {
                flex-direction: column;
                gap: 6px;
                width: 100%;
                margin-bottom: 8px;
            }
            
            .score-badge {
                font-size: 0.8rem;
                padding: 6px 10px;
                width: 100%;
                text-align: center;
            }
            
            .voice-agent-draggable {
                width: 65px;
                height: 65px;
                bottom: 15px;
                right: 15px;
            }
            
            .voice-agent-icon {
                font-size: 1.4rem;
            }
            
            .success-popup {
                padding: 10px;
            }
            
            .success-content {
                max-width: 90%;
                padding: 15px 18px;
            }
            
            .success-title {
                font-size: 1.5rem;
            }
            
            .success-message {
                font-size: 0.85rem;
            }
            
            .object-item {
                width: 65px;
                height: 85px;
                padding: 8px;
            }
            
            .object-img {
                width: 32px;
                height: 32px;
            }
            
            .object-name {
                font-size: 0.7rem;
            }
            
            .object-number {
                width: 18px;
                height: 18px;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .header-card {
                padding: 10px 12px;
                margin-bottom: 10px;
            }
            
            .game-title {
                font-size: 1.2rem;
            }
            
            .score-badges {
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .score-badge {
                font-size: 0.7rem;
                padding: 4px 8px;
            }
            
            .question-panel {
                border: 5px solid #8B4513;
                padding: 10px;
            }
            
            .panel-content-wrapper {
                gap: 10px;
            }
            
            .operation-container {
                gap: 6px;
                margin-bottom: 12px;
            }
            
            .object-group {
                min-height: 110px;
                min-width: 110px;
                padding: 8px 6px;
            }
            
            .object-item {
                width: 60px;
                height: 80px;
                padding: 6px;
                min-width: 44px;
                min-height: 44px;
            }
            
            .object-img {
                width: 32px;
                height: 32px;
            }
            
            .object-name {
                font-size: 0.65rem;
            }
            
            .operator-center {
                font-size: 1.5rem;
                margin: 0 4px;
            }
            
            .question-marker {
                font-size: 1.5rem;
                margin: 0 4px;
            }
            
            .object-total {
                font-size: 0.9rem;
                padding: 8px 12px;
            }
            
            .drop-area {
                min-height: 150px;
                padding: 8px;
            }
            
            .drop-placeholder {
                transform: scale(0.9);
            }
            
            .dropped-object {
                width: 55px;
                height: 75px;
                padding: 6px;
            }
            
            .dropped-img {
                width: 30px;
                height: 30px;
            }
            
            .dropped-name {
                font-size: 0.6rem;
            }
            
            .counter-display {
                font-size: 0.9rem;
                padding: 6px 10px;
            }
            
            .btn {
                min-height: 44px;
                padding: 10px 16px;
                font-size: 0.9rem;
            }
            
            .controls-row {
                gap: 8px;
                padding: 0;
            }
            
            .button-group {
                gap: 8px;
            }
            
            .button-group button {
                font-size: 0.85rem;
            }
            
            .success-content {
                max-width: 85vw;
                width: 100%;
            }
            
            .success-icon {
                font-size: 3rem;
            }
            
            .success-title {
                font-size: 1.2rem;
                margin-bottom: 10px;
            }
            
            .success-message img {
                max-width: 80%;
                width: auto;
            }
        }
                padding: 10px 12px;
                margin-bottom: 10px;
            }
            
            .game-title {
                font-size: 1.2rem;
            }
            
            .game-subtitle {
                font-size: 0.85rem;
            }
            
            .question-panel {
                padding: 10px;
                border: 5px solid #8B4513;
            }
            
            .panel-title {
                font-size: 1.1rem;
                margin-bottom: 10px;
                padding-bottom: 6px;
            }
            
            .object-group {
                min-height: 100px;
                min-width: 100px;
                padding: 8px 4px;
                gap: 6px;
            }
            
            .operator-center,
            .question-marker {
                font-size: 1.5rem;
                margin: 0 4px;
            }
            
            .object-total {
                font-size: 0.9rem;
                padding: 8px 12px;
                margin: 8px 0;
            }
            
            .drop-area {
                min-height: 200px;
                padding: 8px;
                gap: 6px;
            }
            
            .counter-display {
                font-size: 0.9rem;
                padding: 6px 10px;
            }
            
            .dropped-object {
                width: 55px;
                height: 72px;
            }
            
            .btn-primary-custom,
            .btn-secondary-custom,
            .btn-success-custom {
                font-size: 0.85rem;
                padding: 9px 10px;
                min-height: 40px;
            }
            
            .object-item {
                width: 60px;
                height: 80px;
                padding: 6px;
            }
            
            .object-img {
                width: 28px;
                height: 28px;
                margin-bottom: 4px;
            }
            
            .object-name {
                font-size: 0.65rem;
            }
        }
    </style>
</head>
<body>
    <!-- 6. Tambahkan HTML untuk Awan -->
    <div class="clouds">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>
    
    <!-- Voice Agent Draggable -->
    <div class="voice-agent-draggable" id="voice-agent-draggable">
        <i class="fas fa-microphone voice-agent-icon" id="voice-agent-icon"></i>
    </div>
    
    <!-- Voice Agent Status -->
    <div class="voice-agent-status" id="voice-agent-status">
        <span id="status-text">Voice Agent siap</span>
    </div>
    
    <!-- Recording Status -->
    <div class="recording-status" id="recording-status">
        <i class="fas fa-circle" style="color: #ff6b6b;"></i>
        <span>Merekam...</span>
        <div class="recording-progress">
            <div class="recording-progress-fill" id="recording-progress-fill"></div>
        </div>
    </div>
    
    <!-- Audio Status Toggle -->
    <div class="audio-status" id="audio-toggle">
        <i class="fas fa-volume-up" id="audio-icon"></i>
        <span id="audio-status-text">Audio: Aktif</span>
    </div>
    
    <!-- Audio Progress Indicator -->
    <div class="audio-progress-indicator" id="audio-progress-indicator">
        <div class="audio-progress-content">
            <div class="audio-progress-icon">
                <i class="fas fa-music"></i>
            </div>
            <div class="audio-progress-text" id="audio-progress-text">Memutar Audio...</div>
            <div class="audio-progress-subtext" id="audio-progress-subtext">Harap tunggu sebentar</div>
            <div class="audio-progress-bar">
                <div class="audio-progress-fill" id="audio-progress-fill"></div>
            </div>
        </div>
    </div>
    
    <!-- Audio Feedback -->
    <div class="audio-feedback" id="audio-feedback">
        <i class="fas fa-volume-up"></i>
        <span id="feedback-text">Memutar audio...</span>
    </div>
    
    <!-- Drag Audio Indicator -->
    <div class="drag-audio-indicator" id="drag-audio-indicator">
        <i class="fas fa-volume-up"></i>
    </div>
    
    <!-- Orientation Warning for Mobile -->
    <div class="orientation-warning" id="orientation-warning">
        <i class="fas fa-mobile-alt"></i>
        <h3>Putar Perangkat</h3>
        <p>Untuk pengalaman terbaik, silakan putar perangkat Anda ke mode landscape (horizontal)</p>
        <div class="mt-3">
            <i class="fas fa-rotate fa-spin"></i>
        </div>
    </div>
    
    <div class="game-container">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="game-subtitle">
                    <i class="fas fa-layer-group me-2"></i>
                    Level {{ $level->order_number ?? 1 }} - {{ $module->name ?? 'Modul' }}
                </div>
                <div class="score-badges">
                    <div class="badge bg-primary score-badge">
                        Soal: <span id="current-question">1</span>/{{ count($countingItems) }}
                    </div>
                    <div class="badge bg-success score-badge">
                        Skor: <span id="score">0</span>
                    </div>
                    <div class="badge bg-warning score-badge">
                        Benar: <span id="correct-count">0</span>/{{ count($countingItems) }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Game Area -->
        <div class="game-area">
            <!-- PAPAN TULIS DENGAN DROP AREA DI KANAN -->
            <div class="question-panel">
                <div class="panel-content-wrapper">
                    <!-- Bagian Kiri: Visual Soal -->
                    <div class="visual-section">
                        <div class="visual-operation">
                            <!-- Baris 1: Kelompok Kiri - Operator - Kelompok Kanan -->
                            <div class="operation-container">
                                <!-- Kelompok Kiri -->
                                <div class="object-group" id="left-group">
                                </div>
                                
                                <!-- Operator di Tengah -->
                                <div class="operator-center" id="operator-display">
                                    <!-- + atau - -->
                                </div>
                                
                                <!-- Kelompok Kanan -->
                                <div class="object-group" id="right-group">
                                </div>
                                
                                <!-- Tanda Sama Dengan -->
                                <div class="operator-center">=</div>
                                
                                <!-- Tanda Tanya -->
                                <div class="question-marker">?</div>
                            </div>
                            
                            <!-- Baris 3: Total yang dibutuhkan -->
                            <div class="object-total">
                                <i class="fas fa-bullseye me-2"></i>
                               = <span id="target-count">0</span> 
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bagian Kanan: Drop Area -->
                    <div class="drop-section">
                        <div class="counter-display">
                            <i class="fas fa-calculator me-2"></i>
                            <span id="object-count">0</span>
                        </div>
                        
                        <div class="drop-area" id="drop-area">
                            <div class="drop-placeholder" id="drop-placeholder">
                                <i class="fas fa-hand-pointer"></i>
                                <h4>Letakkan Gambar Disini</h4>
                                <p>Tarik gambar ke sini</p>
                            </div>
                        </div>
                        
                        <div class="feedback-container" id="feedback-container"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Controls -->
        <div class="controls-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill" style="width: {{ (1/count($countingItems))*100 }}%"></div>
            </div>
            
            <div class="controls-row">
                <div class="mobile-controls">
                    <div class="button-group">
                        <button id="reset-btn" class="btn btn-secondary-custom">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                        <button id="hint-btn" class="btn btn-secondary-custom">
                            <i class="fas fa-lightbulb me-2"></i>Petunjuk
                        </button>
                        <button id="voice-help-btn" class="btn btn-primary-custom">
                            <i class="fas fa-microphone me-2"></i>Bantuan Suara
                        </button>
                    </div>
                    
                    <div class="progress-info">
                        Progress: Soal <span id="current-question-text">1</span> dari {{ count($countingItems) }}
                    </div>
                </div>
                
                <div class="navigation-group">
                    <button id="prev-btn" class="btn btn-secondary-custom" disabled>
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                    <button id="next-btn" class="btn btn-primary-custom">
                        Lanjut<i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button id="finish-btn" class="btn btn-success-custom d-none">
                        <i class="fas fa-trophy me-2"></i>Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- POPUP BERHASIL -->
    <div class="success-popup" id="success-popup">
        <div class="success-content">
            <button class="close-popup-btn" id="close-popup-btn">
                <i class="fas fa-times"></i>
            </button>
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">BERHASIL!</h1>
            <div class="success-message" id="success-dynamic-message">
            </div>
            <div id="success-audio-controls" class="mt-3">
                <div class="d-flex justify-content-center gap-2">
                    <button id="play-success-audio-btn" class="btn btn-primary-custom btn-sm">
                        <i class="fas fa-play me-1"></i>Pujian
                    </button>
                    <button class="btn btn-success-custom btn-sm" id="continue-btn">
                        <i class="fas fa-forward me-1"></i>Lanjut
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Hasil -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: 4px solid var(--warning-color);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
                    <h5 class="modal-title fw-bold fs-4">
                        <i class="fas fa-trophy me-2"></i>Level Selesai!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-trophy fa-beat-fade text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="mb-3" style="color: var(--secondary-color);">Selamat! 🎉</h4>
                    <p class="mb-4">Kamu telah menyelesaikan semua soal menghitung!</p>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="text-muted small">Skor Akhir</div>
                                <div class="fs-3 fw-bold text-primary" id="final-score">0</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="text-muted small">Soal Benar</div>
                                <div class="fs-3 fw-bold text-success" id="final-correct">0/{{ count($countingItems) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Pencapaian Kamu</h5>
                        <div class="stars-container" id="final-stars">
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary-custom btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Tutup
                        </button>
                        <a href="/calista/menghitung-calista/" class="btn btn-primary-custom btn-sm">
                            <i class="fas fa-home me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Audio Elements -->
    <audio id="audio-player" style="display: none;"></audio>
    <audio id="response-audio-player" style="display: none;"></audio>
    <audio id="progressAudio" style="display: none;" src="{{ asset('storage/music/hebat.mp3') }}"></audio>
    <audio id="backgroundMusicPlayer" style="display: none;" loop>
        <source src="{{ asset('storage/music/play.mp3') }}" type="audio/mpeg">
    </audio>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data soal dari PHP
        const countingItems = @json($countingItems);
        const storageBase = '{{ asset("storage") }}';
        const moduleSlug = '{{ $module->slug }}';
        const levelId = '{{ $level->id }}';
        const csrfToken = '{{ csrf_token() }}';
        const audioGreeting = '{{ $audioGreeting ?? "" }}';
        
        // AMBIL NAMA ANAK DARI USER YANG LOGIN (AMBIL YANG AKTIF)
        const currentChildName = '{{ auth()->user()->anaks()->where('is_active', true)->first()?->nama_anak ?? "Teman" }}';
        
        // Ambil nama objek dari soal pertama
        const firstObjectName = countingItems.length > 0 ? countingItems[0].nama_objek : 'benda';
        
        const greetingText = `Halo ${currentChildName}! Selamat datang di permainan menghitung ${firstObjectName}. Ayo kita hitung ada berapa ${firstObjectName} di bawah ya! Caranya mudah, kamu tinggal tarik gambar ke samping kanan untuk menghitung ${firstObjectName}-nya. Yuk kita mulai!`;
        
        // Game state
        let currentQuestionIndex = 0;
        let userAnswers = new Array(countingItems.length).fill(null);
        let score = 0;
        let correctCount = 0;
        let totalQuestions = countingItems.length;
        
        // State untuk drag & drop
        let droppedObjects = [];
        let draggedObject = null;
        let availableObjects = [];
        
        // Audio system
        const numberSounds = {};
        const objectSounds = {};
        let greetingAudio = null;
        let successAudio = null;
        let currentAudio = null;
        let isMuted = false;
        let globalVolume = 0.9;
        let isDragging = false;
        let audioCooldown = false;
        
        // Variables untuk menangani audio terakhir
        let isPlayingFinalAudio = false;
        let finalAudioComplete = false;
        let isWaitingForFinalAIResponse = false;
        let pendingSuccessData = null;
        
        // Cache untuk TTS
        const typecastCache = {};
        
        // Voice Agent state untuk WAV
        let isRecording = false;
        let audioContext = null;
        let microphoneStream = null;
        let isMicrophoneAvailable = false;
        let isDraggingVoiceAgent = false;
        let recordingTimer = null;
        let recordingStartTime = null;
        let audioBuffers = [];
        let audioProcessor = null;
        
        // Audio queue untuk urutan yang teratur
        let audioQueue = [];
        let isPlayingAudio = false;
        let currentAudioNumber = 1;
        
        // DOM Elements
        const voiceAgentDraggable = document.getElementById('voice-agent-draggable');
        const voiceAgentIcon = document.getElementById('voice-agent-icon');
        const voiceAgentStatus = document.getElementById('voice-agent-status');
        const statusText = document.getElementById('status-text');
        const recordingStatus = document.getElementById('recording-status');
        const recordingProgressFill = document.getElementById('recording-progress-fill');
        const audioToggle = document.getElementById('audio-toggle');
        const audioIcon = document.getElementById('audio-icon');
        const audioStatusText = document.getElementById('audio-status-text');
        const audioPlayer = document.getElementById('audio-player');
        const responseAudioPlayer = document.getElementById('response-audio-player');
        const voiceHelpBtn = document.getElementById('voice-help-btn');
        const backgroundMusicPlayer = document.getElementById('backgroundMusicPlayer');
        
        // ==================== BACKGROUND MUSIC SYSTEM ====================
        class BackgroundMusicSystem {
            constructor(audioElement) {
                this.audio = audioElement;
                this.audio.volume = 0.5;
                this.isPlaying = false;
            }
            
            play() {
                if (!this.isPlaying && this.audio) {
                    this.audio.play().catch(e => console.log('Music play skipped:', e));
                    this.isPlaying = true;
                }
            }
            
            pause() {
                if (this.isPlaying && this.audio) {
                    this.audio.pause();
                    this.isPlaying = false;
                }
            }
            
            resume() {
                if (!this.isPlaying && this.audio) {
                    this.audio.play().catch(e => console.log('Music resume skipped:', e));
                    this.isPlaying = true;
                }
            }
            
            stop() {
                if (this.audio) {
                    this.audio.pause();
                    this.audio.currentTime = 0;
                    this.isPlaying = false;
                }
            }
            
            setVolume(vol) {
                if (this.audio) {
                    this.audio.volume = vol;
                }
            }
        }
        
        let backgroundMusicSystem = null;
        
        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize background music system
            backgroundMusicSystem = new BackgroundMusicSystem(backgroundMusicPlayer);
            
            // Play background music
            setTimeout(() => {
                backgroundMusicSystem.play();
            }, 1000);
            
            // Check orientation
            checkOrientation();
            window.addEventListener('resize', checkOrientation);
            window.addEventListener('orientationchange', checkOrientation);
            
            // Setup voice agent draggable
            setupVoiceAgentDraggable();
            
            // Setup audio toggle
            audioToggle.addEventListener('click', toggleAudio);
            
            // Setup voice help button
            voiceHelpBtn.addEventListener('click', playCurrentQuestionAudio);
            
            // Preload number sounds (1-10)
            preloadNumberSounds();
            
            // Load greeting audio jika ada
            if (audioGreeting) {
                greetingAudio = new Audio(audioGreeting);
                greetingAudio.preload = 'auto';
                greetingAudio.volume = globalVolume;
                
                // Play greeting dengan sedikit delay
                setTimeout(() => {
                    if (!isMuted) {
                        greetingAudio.play().catch(e => console.log('Greeting audio skipped:', e));
                    }
                }, 800);
            }
            
            loadQuestion(currentQuestionIndex);
            
            // Setup event listeners untuk game
            document.getElementById('reset-btn').addEventListener('click', resetDropArea);
            document.getElementById('hint-btn').addEventListener('click', showHint);
            document.getElementById('prev-btn').addEventListener('click', prevQuestion);
            document.getElementById('next-btn').addEventListener('click', nextQuestion);
            document.getElementById('finish-btn').addEventListener('click', finishLevel);
            document.getElementById('close-popup-btn').addEventListener('click', closeSuccessPopup);
            document.getElementById('continue-btn').addEventListener('click', continueToNextQuestion);
            document.getElementById('play-success-audio-btn').addEventListener('click', playSuccessAudio);
            
            // Setup drag & drop untuk game
            setupOptimizedDragAndDrop();
            
            updateProgressBar();
            
            // Keyboard shortcuts
            setupKeyboardShortcuts();
            
            // Check microphone permission
            checkMicrophonePermission();
            
            // Play welcome audio
            setTimeout(() => {
                if (!isMuted) {
                    playWelcomeAudio();
                }
            }, 1000);
        });
        
        // ============ HELPER FUNCTIONS UNTUK MOBILE ============
        
        // Haptic vibration feedback
        function vibrate(duration = 50) {
            if ('vibrate' in navigator) {
                navigator.vibrate(duration);
            }
        }
        
        // Scroll lock untuk saat drag
        let isScrollLocked = false;
        
        function lockScroll() {
            if (!isScrollLocked) {
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
                isScrollLocked = true;
            }
        }
        
        function unlockScroll() {
            if (isScrollLocked) {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
                isScrollLocked = false;
            }
        }
        
        // Tap-to-Select Mode untuk device yang sulit drag
        let tapMode = false;
        let selectedObject = null;
        
        function toggleTapMode() {
            tapMode = !tapMode;
            const modeText = tapMode ? 'Mode: Tap untuk pilih' : 'Mode: Drag & Drop';
            showStatus(modeText);
            
            // Update visual indicator
            const objectItems = document.querySelectorAll('.object-item');
            objectItems.forEach(item => {
                if (tapMode) {
                    item.style.cursor = 'pointer';
                    item.addEventListener('click', handleObjectTap);
                } else {
                    item.style.cursor = 'grab';
                    item.removeEventListener('click', handleObjectTap);
                    if (item.classList.contains('selected')) {
                        item.classList.remove('selected');
                    }
                }
            });
        }
        
        function handleObjectTap(e) {
            if (!tapMode) return;
            
            const objectItem = e.target.closest('.object-item');
            if (!objectItem || objectItem.dataset.used === 'true') return;
            
            // Deselect previous
            if (selectedObject && selectedObject !== objectItem) {
                selectedObject.classList.remove('selected');
            }
            
            // Select or deselect current
            if (selectedObject === objectItem) {
                objectItem.classList.remove('selected');
                selectedObject = null;
            } else {
                selectedObject = objectItem;
                objectItem.classList.add('selected');
                vibrate(20);
                
                // Play audio
                if (!isMuted) {
                    playObjectAudioImmediately(objectItem.dataset.name, 'Dipilih');
                }
            }
        }
        
        function handleDropAreaTapMode(e) {
            if (!tapMode || !selectedObject) {
                showFeedback('Pilih gambar terlebih dahulu!', 'incorrect');
                return;
            }
            
            handleDrop(selectedObject);
            selectedObject.classList.remove('selected');
            selectedObject = null;
            vibrate(30);
        }
        
        // Add tap mode button (opsional - dapat ditambahkan ke UI jika diperlukan)
        // Uncomment jika ingin menambahkan tombol toggle tap mode ke halaman
        
        // ============ END HELPER FUNCTIONS ============
        
        // Check orientation for mobile
        function checkOrientation() {
            const warning = document.getElementById('orientation-warning');
            const isMobile = window.innerWidth <= 768;
            const isPortrait = window.innerHeight > window.innerWidth;
            
            if (isMobile && isPortrait) {
                warning.style.display = 'flex';
            } else {
                warning.style.display = 'none';
            }
        }
        
        // Setup voice agent draggable
        function setupVoiceAgentDraggable() {
            let startX, startY, initialX, initialY;
            let isDragMoveDetected = false;
            let dragStartTime = 0;
            
            // Detect if user is on mobile
            const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Mouse events
            voiceAgentDraggable.addEventListener('mousedown', startDrag);
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
            
            // Touch events dengan handling lebih baik
            voiceAgentDraggable.addEventListener('touchstart', function(e) {
                // Jangan preventDefault di touchstart - biar natural touch behavior
                if (e.touches.length === 1) {
                    isDragMoveDetected = false;
                    dragStartTime = Date.now();
                    startDrag(e.touches[0]);
                }
            }, {passive: true});
            
            document.addEventListener('touchmove', function(e) {
                if (isDraggingVoiceAgent && isDragMoveDetected) {
                    e.preventDefault();
                    drag(e.touches[0]);
                }
            }, {passive: false});
            
            document.addEventListener('touchend', stopDrag);
            
            // Click untuk recording - dengan debounce untuk menghindari double trigger
            let lastClickTime = 0;
            voiceAgentDraggable.addEventListener('click', function(e) {
                const now = Date.now();
                // Ignore click jika terjadi drag atau double click terlalu cepat
                if (!isDraggingVoiceAgent && (now - lastClickTime) > 300) {
                    lastClickTime = now;
                    toggleRecording();
                }
            });
            
            // Tap detection untuk mobile
            voiceAgentDraggable.addEventListener('pointerdown', function(e) {
                if (e.pointerType === 'touch') {
                    isDragMoveDetected = false;
                }
            });
            
            voiceAgentDraggable.addEventListener('pointermove', function(e) {
                if (e.pointerType === 'touch' && isDraggingVoiceAgent) {
                    const moveDistance = Math.sqrt(
                        Math.pow(e.clientX - startX, 2) + 
                        Math.pow(e.clientY - startY, 2)
                    );
                    if (moveDistance > 10) {
                        isDragMoveDetected = true;
                    }
                }
            });
            
            function startDrag(e) {
                isDraggingVoiceAgent = true;
                voiceAgentDraggable.classList.add('dragging');
                
                startX = e.clientX || e.pageX;
                startY = e.clientY || e.pageY;
                
                // Gunakan computed position, bukan offset
                const rect = voiceAgentDraggable.getBoundingClientRect();
                initialX = rect.left + window.scrollX;
                initialY = rect.top + window.scrollY;
                
                showStatus('Voice Agent bisa dipindahkan');
            }
            
            function drag(e) {
                if (!isDraggingVoiceAgent) return;
                
                const clientX = e.clientX || e.pageX;
                const clientY = e.clientY || e.pageY;
                
                const dx = clientX - startX;
                const dy = clientY - startY;
                
                // Update position dengan fixed coordinates
                voiceAgentDraggable.style.right = 'auto';
                voiceAgentDraggable.style.bottom = 'auto';
                voiceAgentDraggable.style.left = (initialX + dx) + 'px';
                voiceAgentDraggable.style.top = (initialY + dy) + 'px';
            }
            
            function stopDrag() {
                if (isDraggingVoiceAgent) {
                    isDraggingVoiceAgent = false;
                    isDragMoveDetected = false;
                    voiceAgentDraggable.classList.remove('dragging');
                    showStatus('Voice Agent siap');
                }
            }
        }
        
        // Toggle recording
        async function toggleRecording() {
            if (isRecording) {
                stopRecording();
            } else {
                await startRecording();
            }
        }
        
        // Start recording WAV
        async function startRecording() {
            if (!isMicrophoneAvailable) {
                showStatus('Mikrofon tidak tersedia');
                return;
            }
            
            try {
                // Pause background music saat mulai recording
                if (backgroundMusicSystem) {
                    backgroundMusicSystem.pause();
                }
                
                // Get microphone permission
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        sampleRate: 16000,
                        channelCount: 1
                    }
                });
                
                // Setup AudioContext untuk WAV
                audioContext = new (window.AudioContext || window.webkitAudioContext)({
                    sampleRate: 16000
                });
                
                microphoneStream = stream;
                audioBuffers = [];
                
                const source = audioContext.createMediaStreamSource(stream);
                audioProcessor = audioContext.createScriptProcessor(4096, 1, 1);
                
                audioProcessor.onaudioprocess = (e) => {
                    if (!isRecording) return;
                    
                    const inputData = e.inputBuffer.getChannelData(0);
                    audioBuffers.push(new Float32Array(inputData));
                };
                
                source.connect(audioProcessor);
                audioProcessor.connect(audioContext.destination);
                
                isRecording = true;
                recordingStartTime = Date.now();
                voiceAgentDraggable.classList.add('recording');
                voiceAgentIcon.className = 'fas fa-stop voice-agent-icon';
                recordingStatus.classList.add('active');
                
                showStatus('🎤 Merekam WAV... Klik lagi untuk berhenti');
                
                // Start recording progress
                updateRecordingProgress();
                
                // Auto stop setelah 15 detik
                recordingTimer = setTimeout(() => {
                    if (isRecording) {
                        stopRecording();
                    }
                }, 15000);
                
            } catch (error) {
                console.error('Recording error:', error);
                showStatus('❌ Gagal mengakses mikrofon');
                isMicrophoneAvailable = false;
                // Resume musik jika ada error
                if (backgroundMusicSystem) {
                    backgroundMusicSystem.resume();
                }
            }
        }
        
        // Update recording progress
        function updateRecordingProgress() {
            if (!isRecording) return;
            
            const elapsed = Date.now() - recordingStartTime;
            const progress = Math.min((elapsed / 15000) * 100, 100);
            
            recordingProgressFill.style.width = `${progress}%`;
            
            if (isRecording) {
                requestAnimationFrame(updateRecordingProgress);
            }
        }
        
        // Stop recording
        function stopRecording() {
            if (!isRecording) return;
            
            // Stop recording
            isRecording = false;
            
            // Disconnect audio nodes
            if (audioProcessor) {
                audioProcessor.disconnect();
                audioProcessor = null;
            }
            
            if (audioContext) {
                audioContext.close().then(() => {
                    console.log('AudioContext closed');
                }).catch(e => {
                    console.warn('Error closing AudioContext:', e);
                });
                audioContext = null;
            }
            
            // Stop microphone stream
            if (microphoneStream) {
                microphoneStream.getTracks().forEach(track => {
                    track.stop();
                });
                microphoneStream = null;
            }
            
            // Resume background music setelah selesai recording
            if (backgroundMusicSystem) {
                setTimeout(() => {
                    backgroundMusicSystem.resume();
                }, 500);
            }
            
            // Process captured audio
            if (audioBuffers.length > 0) {
                processWAVAudio();
            } else {
                console.warn('No audio recorded');
                showStatus('❌ Tidak ada suara yang direkam');
            }
            
            // Update UI
            voiceAgentDraggable.classList.remove('recording');
            voiceAgentIcon.className = 'fas fa-microphone voice-agent-icon';
            recordingStatus.classList.remove('active');
            recordingProgressFill.style.width = '0%';
            
            // Clear timer
            if (recordingTimer) {
                clearTimeout(recordingTimer);
                recordingTimer = null;
            }
            
            showStatus('Memproses suara WAV...');
        }
        
        // Process WAV audio
        function processWAVAudio() {
            try {
                // Merge all buffers
                let totalLength = 0;
                audioBuffers.forEach(buffer => {
                    totalLength += buffer.length;
                });
                
                const mergedBuffer = mergeBuffers(audioBuffers, totalLength);
                
                // Encode to WAV
                const wavBuffer = encodeWAV(mergedBuffer, 16000);
                const wavBlob = new Blob([wavBuffer], { type: 'audio/wav' });
                
                console.log('WAV created, size:', wavBlob.size, 'bytes');
                
                // Disable microphone button while waiting for AI response
                disableMicrophone();
                
                // Send to server
                sendWAVToServer(wavBlob);
                
                // Cleanup
                audioBuffers = [];
                
            } catch (error) {
                console.error('Error processing WAV audio:', error);
                showStatus('❌ Error memproses audio WAV');
            }
        }
        
        // Merge buffers
        function mergeBuffers(bufferList, length) {
            const result = new Float32Array(length);
            let offset = 0;
            for (let i = 0; i < bufferList.length; i++) {
                result.set(bufferList[i], offset);
                offset += bufferList[i].length;
            }
            return result;
        }
        
        // Encode WAV
        function encodeWAV(samples, sampleRate = 16000, numChannels = 1, bitsPerSample = 16) {
            const buffer = new ArrayBuffer(44 + samples.length * 2);
            const view = new DataView(buffer);
            
            // Write WAV header
            writeString(view, 0, 'RIFF');
            view.setUint32(4, 36 + samples.length * 2, true);
            writeString(view, 8, 'WAVE');
            writeString(view, 12, 'fmt ');
            view.setUint32(16, 16, true);
            view.setUint16(20, 1, true);
            view.setUint16(22, numChannels, true);
            view.setUint32(24, sampleRate, true);
            view.setUint32(28, sampleRate * numChannels * bitsPerSample / 8, true);
            view.setUint16(32, numChannels * bitsPerSample / 8, true);
            view.setUint16(34, bitsPerSample, true);
            writeString(view, 36, 'data');
            view.setUint32(40, samples.length * 2, true);
            
            // Write samples
            floatTo16BitPCM(view, 44, samples);
            
            return buffer;
        }
        
        function writeString(view, offset, string) {
            for (let i = 0; i < string.length; i++) {
                view.setUint8(offset + i, string.charCodeAt(i));
            }
        }
        
        function floatTo16BitPCM(view, offset, input) {
            for (let i = 0; i < input.length; i++, offset += 2) {
                const s = Math.max(-1, Math.min(1, input[i]));
                view.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
            }
        }
        
        // Send WAV to server
        async function sendWAVToServer(wavBlob) {
            const formData = new FormData();
            formData.append('audio', wavBlob, 'recording.wav');
            formData.append('user_id', 'user_{{ auth()->id() }}');
            formData.append('context', 'counting_game');
            formData.append('current_question', currentQuestionIndex);
            formData.append('current_item', JSON.stringify(countingItems[currentQuestionIndex]));
            formData.append('audio_format', 'wav');
            
            try {
                const response = await fetch('/voice-agent/process-voice', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                
                if (response.ok) {
                    const contentType = response.headers.get('content-type');
                    
                    if (contentType && contentType.includes('audio')) {
                        // Get audio response - jangan tambah ke queue, langsung play
                        const audioBlob = await response.blob();
                        const audioUrl = URL.createObjectURL(audioBlob);
                        
                        showStatus('🎵 AI merespons');
                        
                        // Play response SAJA, jangan ke queue
                        if (!isMuted) {
                            playResponseAudio(audioUrl, true);
                            
                            // Jika menunggu final AI response, tampilkan popup setelah audio selesai
                            if (isWaitingForFinalAIResponse) {
                                responseAudioPlayer.onended = () => {
                                    URL.revokeObjectURL(audioUrl);
                                    hideAudioProgressIndicator();
                                    showSuccessPopup();
                                    isWaitingForFinalAIResponse = false;
                                    pendingSuccessData = null;
                                };
                            }
                        } else {
                            // Re-enable microphone if audio is muted
                            enableMicrophone();
                        }
                        
                    } else {
                        const data = await response.json();
                        if (data.text) {
                            showStatus('AI: ' + data.text.substring(0, 50) + '...');
                            // Jangan duplicate - hanya tambah ke queue sekali
                            if (!audioQueue.includes(data.text)) {
                                addToAudioQueue(data.text);
                            }
                        }
                    }
                } else {
                    showStatus('❌ Server error');
                    // Re-enable microphone on error
                    enableMicrophone();
                }
                
            } catch (error) {
                console.error('Send error:', error);
                showStatus('❌ Gagal mengirim ke server');
                // Re-enable microphone on error
                enableMicrophone();
            }
        }
        
        // Add to audio queue untuk pemutaran teratur
        function addToAudioQueue(text) {
            // Cegah duplikasi - jangan tambah jika text yang sama sudah ada
            if (audioQueue.length > 0 && audioQueue[audioQueue.length - 1] === text) {
                console.warn('Duplicate audio skipped:', text);
                return;
            }
            
            audioQueue.push(text);
            if (!isPlayingAudio) {
                playNextAudio();
            }
        }
        
        // Play next audio in queue
        async function playNextAudio() {
            if (audioQueue.length === 0 || isMuted) {
                isPlayingAudio = false;
                
                // Jika menunggu final AI response dan semua audio selesai, tampilkan popup
                if (isWaitingForFinalAIResponse && audioQueue.length === 0) {
                    hideAudioProgressIndicator();
                    showSuccessPopup();
                    isWaitingForFinalAIResponse = false;
                    pendingSuccessData = null;
                } else {
                    // Re-enable microphone when audio queue is finished
                    if (audioQueue.length === 0) {
                        enableMicrophone();
                    }
                }
                return;
            }
            
            isPlayingAudio = true;
            // Disable microphone while playing TTS
            disableMicrophone();
            const text = audioQueue.shift();
            
            try {
                const response = await fetch('/voice-agent/text-to-speech', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ text: text })
                });
                
                if (response.ok) {
                    const audioBlob = await response.blob();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    audioPlayer.src = audioUrl;
                    audioPlayer.onended = () => {
                        // Cleanup object URL
                        URL.revokeObjectURL(audioUrl);
                        setTimeout(playNextAudio, 500);
                    };
                    
                    audioPlayer.onerror = () => {
                        URL.revokeObjectURL(audioUrl);
                        // Re-enable microphone on audio error
                        if (audioQueue.length === 0) {
                            enableMicrophone();
                        } else {
                            playNextAudio();
                        }
                    };
                    
                    audioPlayer.play().then(() => {
                        showAudioFeedback(text.substring(0, 50));
                    }).catch(e => {
                        console.log('Audio play skipped:', e);
                        URL.revokeObjectURL(audioUrl);
                        // Re-enable microphone on play error
                        if (audioQueue.length === 0) {
                            enableMicrophone();
                        } else {
                            playNextAudio();
                        }
                    });
                } else {
                    playNextAudio();
                }
            } catch (error) {
                console.error('TTS error:', error);
                // Re-enable microphone on error
                if (audioQueue.length === 0) {
                    enableMicrophone();
                } else {
                    playNextAudio();
                }
            }
        }
        
        // Play response audio
        function playResponseAudio(audioUrl, shouldEnableMicAfter = false) {
            if (isMuted) return;
            
            // Hentikan audio sebelumnya jika masih playing
            if (audioPlayer.src) {
                audioPlayer.pause();
                audioPlayer.currentTime = 0;
            }
            
            responseAudioPlayer.src = audioUrl;
            responseAudioPlayer.play().catch(e => {
                console.log('Response audio play skipped');
                URL.revokeObjectURL(audioUrl);
                // Re-enable microphone if audio failed to play
                if (shouldEnableMicAfter) {
                    enableMicrophone();
                }
            });
            
            responseAudioPlayer.onended = () => {
                URL.revokeObjectURL(audioUrl);
                // Re-enable microphone after response finishes
                if (shouldEnableMicAfter) {
                    enableMicrophone();
                }
            };
        }
        
        // Play welcome audio - jangan multiple add
        function playWelcomeAudio() {
            // Clear queue dulu
            audioQueue = [];
            addToAudioQueue(greetingText);
        }
        
        // Play current question audio - jangan duplicate
        function playCurrentQuestionAudio() {
            if (isMuted) return;
            
            const item = countingItems[currentQuestionIndex];
            const operation = item.jenis_operasi === 'tambah' ? 'ditambah' : 'dikurangi';
            const requiredCount = item.jenis_operasi === 'tambah' 
                ? item.nilai_kiri + item.nilai_kanan 
                : item.nilai_kiri;
            
            const question = `Soal ${currentQuestionIndex + 1}: ${item.nilai_kiri} ${operation} ${item.nilai_kanan}. Pindahkan ${requiredCount} ${item.nama_objek} ke kanan!`;
            
            // Clear queue dan add only this
            audioQueue = [];
            addToAudioQueue(question);
        }
        
        // Show status
        function showStatus(message) {
            statusText.textContent = message;
            voiceAgentStatus.classList.add('active');
            
            setTimeout(() => {
                voiceAgentStatus.classList.remove('active');
            }, 3000);
        }
        
        // Toggle audio
        function toggleAudio() {
            isMuted = !isMuted;
            
            if (isMuted) {
                audioIcon.className = 'fas fa-volume-mute';
                audioStatusText.textContent = 'Audio: Mati';
                
                audioPlayer.pause();
                audioPlayer.currentTime = 0;
                responseAudioPlayer.pause();
                responseAudioPlayer.currentTime = 0;
                
                showStatus('🔇 Audio dimatikan');
            } else {
                audioIcon.className = 'fas fa-volume-up';
                audioStatusText.textContent = 'Audio: Aktif';
                showStatus('🔊 Audio diaktifkan');
            }
        }
        
        // Disable microphone button
        function disableMicrophone() {
            if (voiceAgentDraggable) {
                voiceAgentDraggable.style.opacity = '0.5';
                voiceAgentDraggable.style.cursor = 'not-allowed';
                voiceAgentDraggable.style.pointerEvents = 'none';
                voiceAgentDraggable.disabled = true;
                showStatus('⏳ Menunggu respons AI...');
            }
        }
        
        // Enable microphone button
        function enableMicrophone() {
            if (voiceAgentDraggable) {
                voiceAgentDraggable.style.opacity = '1';
                voiceAgentDraggable.style.cursor = 'move';
                voiceAgentDraggable.style.pointerEvents = 'auto';
                voiceAgentDraggable.disabled = false;
                showStatus('🎤 Siap digunakan');
            }
        }
        
        // Check microphone permission
        async function checkMicrophonePermission() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const hasMicrophone = devices.some(device => device.kind === 'audioinput');
                
                if (!hasMicrophone) {
                    showStatus('Tidak ada mikrofon terdeteksi');
                    isMicrophoneAvailable = false;
                    return;
                }
                
                const permissionStatus = await navigator.permissions.query({ name: 'microphone' });
                isMicrophoneAvailable = permissionStatus.state === 'granted';
                
            } catch (error) {
                console.error('Permission check error:', error);
                isMicrophoneAvailable = false;
            }
        }
        
        // ============ GAME FUNCTIONS ============
        
        // Preload number sounds
        function preloadNumberSounds() {
            for (let i = 1; i <= 10; i++) {
                const sound = new Audio(`${storageBase}/counting-items/sound/${i}.mp3`);
                sound.preload = 'auto';
                sound.volume = globalVolume;
                numberSounds[i] = sound;
            }
        }
        
        // Fungsi untuk mendapatkan URL gambar
        function getCountingImageUrl(filename) {
            if (!filename) return '/images/placeholder.png';
            if (filename.startsWith('http') || filename.startsWith('/')) return filename;
            if (filename.includes('/')) return `${storageBase}/${filename}`;
            return `${storageBase}/counting-items/${filename}`;
        }
        
        // Memuat soal
        function loadQuestion(index) {
            const item = countingItems[index];
            
            // Update UI
            document.getElementById('current-question').textContent = index + 1;
            document.getElementById('current-question-text').textContent = index + 1;
            
            // Update operator display
            const operator = item.jenis_operasi === 'tambah' ? '+' : '-';
            document.getElementById('operator-display').textContent = operator;
            
            // Update target count
            let requiredCount;
            if (item.jenis_operasi === 'tambah') {
                requiredCount = item.nilai_kiri + item.nilai_kanan;
            } else {
                requiredCount = item.nilai_kiri;
            }
            document.getElementById('target-count').textContent = requiredCount;
           
            // Reset drop area
            resetDropArea();
            
            // Load gambar-gambar di kelompok kiri dan kanan
            loadObjectGroups(item);
            
            // Update tombol navigasi
            document.getElementById('prev-btn').disabled = index === 0;
            // Button next always enabled - akan handle di nextQuestion function
            
            // Reset feedback
            document.getElementById('feedback-container').innerHTML = '';
            
            // Update progress bar
            updateProgressBar();
            
            // Reset state audio terakhir
            isPlayingFinalAudio = false;
            finalAudioComplete = false;
            
            // Reset audio counter
            currentAudioNumber = 1;
            
            // Preload audio untuk objek ini
            preloadObjectAudioAsync(item.nama_objek);
            
            // Preload angka yang dibutuhkan
            preloadRequiredNumbers(requiredCount);
        }
        
        // Preload audio untuk objek secara async
        async function preloadObjectAudioAsync(objectName) {
            if (!objectName || objectSounds[objectName]) return;
            
            try {
                const res = await fetch(`/calista/${moduleSlug}/level/${levelId}/counting/typecast-tts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ text: objectName })
                });
                const data = await res.json();
                if (data.success && data.audio_url) {
                    const audio = new Audio(data.audio_url);
                    audio.preload = 'auto';
                    audio.volume = globalVolume;
                    objectSounds[objectName] = audio;
                    typecastCache[objectName] = data.audio_url;
                }
            } catch (err) {
                console.error('Preload object audio error:', err);
            }
        }
        
        // Preload angka yang dibutuhkan
        function preloadRequiredNumbers(requiredCount) {
            for (let i = 1; i <= Math.min(requiredCount, 10); i++) {
                if (!numberSounds[i] || numberSounds[i] === null) {
                    const sound = new Audio(`${storageBase}/counting-items/sound/${i}.mp3`);
                    sound.preload = 'auto';
                    sound.volume = globalVolume;
                    numberSounds[i] = sound;
                }
            }
        }
        
        // Memuat gambar di kelompok kiri dan kanan
        function loadObjectGroups(item) {
            // Clear existing
            document.getElementById('left-group').innerHTML = '<div class="group-label">Pertama</div>';
            document.getElementById('right-group').innerHTML = '<div class="group-label">Kedua</div>';
            
            // Reset available objects
            availableObjects = [];
            
            const imageUrl = getCountingImageUrl(item.gambar_objek);
            
            // Buat gambar untuk kelompok KIRI (nilai_kiri)
            for (let i = 0; i < item.nilai_kiri; i++) {
                const objectId = `left_${i}`;
                createObjectElement('left-group', objectId, imageUrl, item.nama_objek, i + 1);
                availableObjects.push(objectId);
            }
            
            // Buat gambar untuk kelompok KANAN (nilai_kanan)
            for (let i = 0; i < item.nilai_kanan; i++) {
                const objectId = `right_${i}`;
                createObjectElement('right-group', objectId, imageUrl, item.nama_objek, i + 1);
                availableObjects.push(objectId);
            }
        }
        
        // Membuat elemen objek
        function createObjectElement(groupId, objectId, imageUrl, objectName, number) {
            const group = document.getElementById(groupId);
            
            const objectDiv = document.createElement('div');
            objectDiv.className = 'object-item';
            objectDiv.draggable = true;
            objectDiv.dataset.id = objectId;
            objectDiv.dataset.group = groupId;
            objectDiv.dataset.used = 'false';
            objectDiv.dataset.number = number;
            objectDiv.dataset.name = objectName;
            
            const numberDiv = document.createElement('div');
            numberDiv.className = 'object-number';
            numberDiv.textContent = number;
            
            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = objectName;
            img.className = 'object-img';
            img.loading = 'lazy';
            
            const nameSpan = document.createElement('span');
            nameSpan.className = 'object-name';
            nameSpan.textContent = objectName;
            
            objectDiv.appendChild(numberDiv);
            objectDiv.appendChild(img);
            objectDiv.appendChild(nameSpan);
            
            group.appendChild(objectDiv);
        }
        
        // Setup drag & drop yang dioptimalkan untuk mobile
        function setupOptimizedDragAndDrop() {
            const dropArea = document.getElementById('drop-area');
            
            // Improved touch handling untuk mobile
            let touchStartX, touchStartY;
            let touchStartTime = 0;
            let touchMoved = false;
            let currentTouchElement = null;
            let dragGhost = null;
            
            // Function untuk create drag ghost
            function createDragGhost(objectItem) {
                if (dragGhost) dragGhost.remove();
                
                dragGhost = document.createElement('div');
                dragGhost.className = 'drag-ghost';
                
                const img = document.createElement('img');
                img.src = objectItem.querySelector('.object-img').src;
                img.alt = objectItem.dataset.name;
                img.className = 'drag-ghost-img';
                
                const name = document.createElement('span');
                name.style.fontSize = '0.75rem';
                name.style.fontWeight = 'bold';
                name.style.marginTop = '4px';
                name.style.color = 'var(--secondary-color)';
                name.textContent = objectItem.dataset.name;
                
                dragGhost.appendChild(img);
                dragGhost.appendChild(name);
                document.body.appendChild(dragGhost);
                
                return dragGhost;
            }
            
            // Function untuk update drag ghost position
            function updateDragGhostPosition(x, y) {
                if (dragGhost) {
                    dragGhost.style.left = (x - 40) + 'px';
                    dragGhost.style.top = (y - 40) + 'px';
                }
            }
            
            // Event untuk desktop
            document.addEventListener('dragstart', function(e) {
                const objectItem = e.target.closest('.object-item');
                if (objectItem && objectItem.dataset.used === 'false') {
                    handleDragStart(objectItem, e);
                    createDragGhost(objectItem);
                }
            });
            
            document.addEventListener('dragover', function(e) {
                if (dragGhost) {
                    updateDragGhostPosition(e.clientX, e.clientY);
                }
            });
            
            // Touch start event - improved
            document.addEventListener('touchstart', function(e) {
                const objectItem = e.target.closest('.object-item');
                if (objectItem && objectItem.dataset.used === 'false') {
                    touchStartTime = Date.now();
                    touchMoved = false;
                    currentTouchElement = objectItem;
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                    
                    // Visual feedback
                    objectItem.style.transform = 'scale(0.9)';
                    objectItem.style.zIndex = '500';
                    
                    // Play audio saat mulai touch
                    if (!audioCooldown && !isMuted) {
                        const objectName = objectItem.dataset.name;
                        playObjectAudioImmediately(objectName, 'Dimulai');
                        audioCooldown = true;
                        setTimeout(() => { audioCooldown = false; }, 200);
                    }
                }
            }, { passive: true });
            
            // Touch move event - improved drag detection
            document.addEventListener('touchmove', function(e) {
                if (!currentTouchElement) return;
                
                const touchX = e.touches[0].clientX;
                const touchY = e.touches[0].clientY;
                
                const deltaX = Math.abs(touchX - touchStartX);
                const deltaY = Math.abs(touchY - touchStartY);
                
                // Hanya trigger drag jika moved significantly (20px)
                if (deltaX > 20 || deltaY > 20) {
                    touchMoved = true;
                    currentTouchElement.classList.add('dragging');
                    isDragging = true;
                    
                    // Create drag ghost saat mulai drag
                    if (!dragGhost) {
                        createDragGhost(currentTouchElement);
                    }
                    
                    // Update position
                    updateDragGhostPosition(touchX, touchY);
                    
                    // Lock scroll saat dragging
                    lockScroll();
                    
                    // Show audio indicator
                    document.getElementById('drag-audio-indicator').classList.add('active');
                    dropArea.classList.add('dragging-active');
                    
                    // Prevent default scroll
                    e.preventDefault();
                }
            }, { passive: false });
            
            // Touch end event - improved drop detection
            document.addEventListener('touchend', function(e) {
                if (!currentTouchElement) {
                    unlockScroll();
                    if (dragGhost) {
                        dragGhost.remove();
                        dragGhost = null;
                    }
                    return;
                }
                
                const touchDuration = Date.now() - touchStartTime;
                
                // Reset visual
                currentTouchElement.style.transform = '';
                currentTouchElement.style.zIndex = '';
                
                if (isDragging && touchMoved) {
                    // Check if dropped in drop area
                    const dropAreaRect = dropArea.getBoundingClientRect();
                    const touchEndX = e.changedTouches[0].clientX;
                    const touchEndY = e.changedTouches[0].clientY;
                    
                    if (
                        touchEndX >= dropAreaRect.left &&
                        touchEndX <= dropAreaRect.right &&
                        touchEndY >= dropAreaRect.top &&
                        touchEndY <= dropAreaRect.bottom
                    ) {
                        handleDrop(currentTouchElement);
                        vibrate(30); // Haptic feedback
                    } else {
                        showFeedback('Lepaskan di area yang tepat!', 'incorrect');
                    }
                } else if (touchDuration < 300 && !touchMoved) {
                    // Quick tap - auto drop untuk kemudahan di mobile
                    handleDrop(currentTouchElement);
                    vibrate(30);
                }
                
                // Reset
                currentTouchElement.classList.remove('dragging');
                currentTouchElement = null;
                isDragging = false;
                touchMoved = false;
                document.getElementById('drag-audio-indicator').classList.remove('active');
                dropArea.classList.remove('dragging-active');
                if (dragGhost) {
                    dragGhost.remove();
                    dragGhost = null;
                }
                unlockScroll();
            });
            
            // Drag end event untuk desktop
            document.addEventListener('dragend', function() {
                isDragging = false;
                document.getElementById('drag-audio-indicator').classList.remove('active');
                dropArea.classList.remove('dragging-active');
                if (draggedObject) {
                    draggedObject.classList.remove('dragging');
                    draggedObject = null;
                }
                if (dragGhost) {
                    dragGhost.remove();
                    dragGhost = null;
                }
                unlockScroll();
            });
            
            // Drop area events untuk desktop
            dropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('active');
                this.classList.add('dragging-active');
            });
            
            dropArea.addEventListener('dragenter', function(e) {
                e.preventDefault();
                this.classList.add('active');
                this.classList.add('dragging-active');
            });
            
            dropArea.addEventListener('dragleave', function() {
                this.classList.remove('active');
                this.classList.remove('dragging-active');
            });
            
            dropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('active');
                this.classList.remove('dragging-active');
                
                if (!draggedObject) return;
                
                handleDrop(draggedObject);
                vibrate(30);
            });
            
            // Hover audio untuk desktop
            document.addEventListener('mouseover', function(e) {
                const objectItem = e.target.closest('.object-item');
                if (objectItem && objectItem.dataset.used === 'false' && !isDragging) {
                    if (!audioCooldown && !isMuted) {
                        const objectName = objectItem.dataset.name;
                        playObjectAudioImmediately(objectName, 'Objek');
                        audioCooldown = true;
                        setTimeout(() => { audioCooldown = false; }, 500);
                    }
                }
            });
        }
        
        // Handle drag start
        function handleDragStart(objectItem, e = null) {
            draggedObject = objectItem;
            objectItem.classList.add('dragging');
            isDragging = true;
            
            // Show audio indicator
            const indicator = document.getElementById('drag-audio-indicator');
            indicator.classList.add('active');
            
            // Play audio untuk objek yang sedang di-drag
            if (!isMuted) {
                const objectName = objectItem.dataset.name;
                playObjectAudioImmediately(objectName, 'Drag aktif');
            }
            
            if (e && e.dataTransfer) {
                e.dataTransfer.setData('text/plain', objectItem.dataset.id);
                e.dataTransfer.effectAllowed = 'move';
            }
        }
        
        function handleDrop(objectItem) {
            const objectId = objectItem.dataset.id;
            const objectName = objectItem.dataset.name;
            
            // Cek apakah objek sudah digunakan
            if (objectItem.dataset.used === 'true') {
                showFeedback('Gambar ini sudah dipindahkan!', 'incorrect');
                if (!isMuted) {
                    playTextImmediately('Gambar ini sudah dipindahkan!', 'Peringatan');
                }
                return;
            }
            
            // Mark as digunakan
            objectItem.dataset.used = 'true';
            objectItem.style.opacity = '0.3';
            objectItem.style.cursor = 'not-allowed';
            objectItem.draggable = false;
            
            // Tambah ke array
            droppedObjects.push(objectId);
            const seq = droppedObjects.length;
            
            // Play audio untuk objek yang di-drop dengan urutan teratur
            if (!isMuted) {
                playDroppedObjectAudioSequential(seq, objectName);
            }
            
            // Buat elemen di drop area
            createDroppedElement(objectItem, seq);
            
            // Sembunyikan placeholder
            if (document.getElementById('drop-placeholder')) {
                document.getElementById('drop-placeholder').style.display = 'none';
            }
            
            // Update counter
            updateCounter();
            
            // Cek apakah sudah sesuai dengan target
            checkCompletion();
            
            // Reset drag state
            isDragging = false;
            draggedObject = null;
            document.getElementById('drag-audio-indicator').classList.remove('active');
        }
        
        // Play dropped object audio dengan urutan teratur
        function playDroppedObjectAudioSequential(sequenceNumber, objectName) {
            if (isMuted) return;
            
            // Hanya play jika sesuai urutan
            if (sequenceNumber === currentAudioNumber) {
                const audioText = `${currentAudioNumber} ${objectName}`;
                addToAudioQueue(audioText);
                currentAudioNumber++;
            }
        }
        
        // Play number dan object audio secara berurutan (untuk drag biasa)
        function playNumberAndObjectAudio(number, objectName) {
            if (isMuted) return;
            
            const playAudioSequence = () => {
                // 1. Play angka
                playNumberImmediately(number);
                
                // 2. Setelah angka selesai, play object name
                setTimeout(() => {
                    playObjectAudioImmediately(objectName, 'Objek');
                }, 800);
            };
            
            playAudioSequence();
        }
        
        // Play number audio tanpa delay
        function playNumberImmediately(n) {
            if (isMuted) return;
            
            if (numberSounds[n]) {
                try {
                    const sound = numberSounds[n].cloneNode();
                    sound.volume = globalVolume;
                    sound.muted = isMuted;
                    sound.currentTime = 0;
                    sound.play().catch(e => {
                        console.log('Number sound failed:', e);
                        playTextImmediately(String(n), `Angka ${n}`);
                    });
                } catch (e) {
                    playTextImmediately(String(n), `Angka ${n}`);
                }
            } else {
                const sound = new Audio(`${storageBase}/counting-items/sound/${n}.mp3`);
                sound.volume = globalVolume;
                sound.muted = isMuted;
                sound.play().catch(e => {
                    console.log('Number sound lazy load failed:', e);
                    playTextImmediately(String(n), `Angka ${n}`);
                });
                numberSounds[n] = sound;
            }
        }
        
        // Play text immediately tanpa delay
        async function playTextImmediately(text, description = '') {
            if (!text || isMuted) return;
            
            if (typecastCache[text]) {
                try {
                    const audio = new Audio(typecastCache[text]);
                    audio.volume = globalVolume;
                    audio.muted = isMuted;
                    audio.currentTime = 0;
                    audio.play().then(() => {
                        showAudioFeedback(description || text);
                    }).catch(e => {
                        console.log('Cached audio play failed:', e);
                    });
                    return;
                } catch (e) {}
            }
            
            generateAndCacheAudio(text, description);
        }
        
        // Generate dan cache audio untuk masa depan
        async function generateAndCacheAudio(text, description) {
            try {
                const res = await fetch(`/calista/${moduleSlug}/level/${levelId}/counting/typecast-tts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ text })
                });
                const data = await res.json();
                if (data.success && data.audio_url) {
                    typecastCache[text] = data.audio_url;
                }
            } catch (err) {
                console.error('Generate audio error:', err);
            }
        }
        
        // Play object audio immediately
        function playObjectAudioImmediately(objectName, description = '') {
            if (!objectName || isMuted) return;
            
            if (objectSounds[objectName]) {
                try {
                    const audio = objectSounds[objectName].cloneNode();
                    audio.volume = globalVolume;
                    audio.muted = isMuted;
                    audio.currentTime = 0;
                    audio.play().then(() => {
                        showAudioFeedback(description || objectName);
                    }).catch(e => {
                        console.log('Cached audio play failed:', e);
                        playTextImmediately(objectName, description);
                    });
                    return;
                } catch (e) {}
            }
            
            playTextImmediately(objectName, description);
        }
        
        // Create dropped element
        function createDroppedElement(sourceElement, sequenceNumber) {
            const dropArea = document.getElementById('drop-area');
            const objectName = sourceElement.dataset.name;
            
            const droppedObject = document.createElement('div');
            droppedObject.className = 'dropped-object';
            droppedObject.dataset.id = sourceElement.dataset.id;
            
            const img = document.createElement('img');
            img.src = sourceElement.querySelector('img').src;
            img.alt = objectName;
            img.className = 'dropped-img';
            img.loading = 'lazy';
            
            const nameSpan = document.createElement('span');
            nameSpan.className = 'dropped-name';
            nameSpan.textContent = objectName;
            
            const numberSpan = document.createElement('div');
            numberSpan.className = 'object-number';
            numberSpan.textContent = sequenceNumber;
            
            droppedObject.appendChild(img);
            droppedObject.appendChild(nameSpan);
            droppedObject.appendChild(numberSpan);
            
            // Tambah ke drop area
            dropArea.appendChild(droppedObject);
        }
        
        // Update counter
        function updateCounter() {
            const count = droppedObjects.length;
            document.getElementById('object-count').textContent = count;
        }
        
        // Cek apakah sudah sesuai target
        async function checkCompletion() {
            const currentItem = countingItems[currentQuestionIndex];
            const operator = currentItem.jenis_operasi;
            const currentCount = droppedObjects.length;
            
            let requiredCount;
            if (operator === 'tambah') {
                requiredCount = currentItem.nilai_kiri + currentItem.nilai_kanan;
            } else {
                requiredCount = currentItem.nilai_kiri;
            }
            
            if (currentCount === requiredCount) {
                // TAMPILKAN INDICATOR AUDIO DULU
                showAudioProgressIndicator();
                
                // Beri skor
                if (!userAnswers[currentQuestionIndex]) {
                    score += 10;
                    correctCount++;
                    userAnswers[currentQuestionIndex] = 'correct';
                    
                    document.getElementById('score').textContent = score;
                    document.getElementById('correct-count').textContent = `${correctCount}/${totalQuestions}`;
                    
                    // Cek apakah ini soal terakhir
                    const isLastQuestion = currentQuestionIndex === countingItems.length - 1;
                    
                    // Jika soal terakhir dan audio aktif, tunggu respons AI dulu
                    if (isLastQuestion && !isMuted) {
                        isWaitingForFinalAIResponse = true;
                        pendingSuccessData = currentItem;
                        // Audio akan di-trigger dari sendWAVToServer saat AI response diterima
                    } else {
                        // Jika bukan soal terakhir atau audio muted, langsung tampilkan popup
                        hideAudioProgressIndicator();
                        showSuccessPopup();
                    }
                }
            } else if (currentCount > requiredCount) {
                showFeedback('Terlalu banyak gambar!', 'incorrect');
                if (!isMuted) {
                    playTextImmediately('Terlalu banyak, coba kurangi', 'Peringatan');
                }
            }
        }
        
        // Tampilkan progress indicator untuk audio
        function showAudioProgressIndicator() {
            const indicator = document.getElementById('audio-progress-indicator');
            indicator.style.display = 'flex';
            document.getElementById('audio-progress-text').textContent = 'Memutar Audio...';
            document.getElementById('audio-progress-subtext').textContent = 'Harap tunggu sebentar';
            
            // Animate progress bar
            const progressFill = document.getElementById('audio-progress-fill');
            progressFill.style.width = '0%';
            setTimeout(() => {
                progressFill.style.width = '30%';
            }, 300);
            setTimeout(() => {
                progressFill.style.width = '60%';
            }, 800);
            setTimeout(() => {
                progressFill.style.width = '90%';
            }, 1500);
        }
        
        // Sembunyikan progress indicator
        function hideAudioProgressIndicator() {
            const indicator = document.getElementById('audio-progress-indicator');
            indicator.style.display = 'none';
            document.getElementById('audio-progress-fill').style.width = '0%';
        }
        
        // Tampilkan popup BERHASIL
        function showSuccessPopup() {
            const currentItem = countingItems[currentQuestionIndex];
            
            // Autoplay hebat.mp3 jika tidak muted
            const progressAudio = document.getElementById('progressAudio');
            if (progressAudio && !isMuted) {
                progressAudio.volume = globalVolume;
                progressAudio.currentTime = 0;
                progressAudio.play().catch(e => {
                    console.log('Progress audio play skipped:', e);
                });
            }
            
            // Generate success audio untuk tombol play
            generateAndPlaySuccessAudio(currentItem);
            
            // Langsung lanjut ke soal berikutnya tanpa popup
            setTimeout(() => {
                continueToNextQuestion();
            }, 1500);
        }
        
        // Tutup popup BERHASIL
        function closeSuccessPopup() {
            const popup = document.getElementById('success-popup');
            popup.style.display = 'none';
            
            if (successAudio) {
                successAudio.pause();
                successAudio.currentTime = 0;
            }
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
        
        // Lanjut ke soal berikutnya
        function continueToNextQuestion() {
            if (currentQuestionIndex < totalQuestions - 1) {
                setTimeout(() => {
                    nextQuestion();
                }, 300);
            } else {
                finishLevel();
            }
        }
        
        // Generate dan putar audio pujian
        async function generateAndPlaySuccessAudio(item) {
            try {
                const response = await fetch(`/calista/${moduleSlug}/level/${levelId}/counting/success-audio`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        hasil: item.hasil,
                        nama_objek: item.nama_objek,
                        jenis_operasi: item.jenis_operasi,
                        nilai_kiri: item.nilai_kiri,
                        nilai_kanan: item.nilai_kanan
                    })
                });
                
                const data = await response.json();
                if (data.success && data.audio_url) {
                    successAudio = new Audio(data.audio_url);
                    successAudio.volume = globalVolume;
                    successAudio.muted = isMuted;
                }
            } catch (error) {
                console.error('Error generating success audio:', error);
            }
        }
        
        // Play success audio manually
        function playSuccessAudio() {
            if (successAudio && !isMuted) {
                successAudio.currentTime = 0;
                successAudio.play().then(() => {
                    showAudioFeedback("Pujian dari Calista");
                });
            } else {
                const item = countingItems[currentQuestionIndex];
                const operationText = item.jenis_operasi === 'tambah' ? 'ditambah' : 'dikurangi';
                const message = `Hore! ${item.nilai_kiri} ${operationText} ${item.nilai_kanan} sama dengan ${item.hasil} ${item.nama_objek}. Hebat sekali!`;
                playTextImmediately(message, "Pujian");
            }
        }
        
        // Reset drop area
        function resetDropArea() {
            const dropArea = document.getElementById('drop-area');
            
            // Hapus semua objek yang di-drop
            const droppedElements = dropArea.querySelectorAll('.dropped-object');
            droppedElements.forEach(el => el.remove());
            
            // Reset array
            droppedObjects = [];
            
            // Clear audio queue untuk reset
            if (!isPlayingAudio) {
                audioQueue = [];
            }
            
            // Tampilkan placeholder
            const placeholder = document.getElementById('drop-placeholder');
            if (placeholder) {
                placeholder.style.display = 'block';
            }
            
            // Reset semua objek di kelompok kiri dan kanan
            const draggableObjects = document.querySelectorAll('.object-item');
            draggableObjects.forEach(obj => {
                obj.dataset.used = 'false';
                obj.style.opacity = '1';
                obj.style.cursor = 'grab';
                obj.draggable = true;
                obj.classList.remove('dragging');
            });
            
            // Reset counter
            document.getElementById('object-count').textContent = '0';
            
            // Reset feedback
            document.getElementById('feedback-container').innerHTML = '';
            
            // Reset audio counter
            currentAudioNumber = 1;
            
            // Tutup popup jika terbuka
            closeSuccessPopup();
            
            // Hide audio progress indicator jika aktif
            hideAudioProgressIndicator();
        }
        
        // Tampilkan feedback
        function showFeedback(message, type) {
            const container = document.getElementById('feedback-container');
            const feedbackDiv = document.createElement('div');
            feedbackDiv.className = type === 'correct' ? 'feedback-correct' : 'feedback-incorrect';
            feedbackDiv.innerHTML = message;
            container.innerHTML = '';
            container.appendChild(feedbackDiv);
        }
        
        // Tampilkan petunjuk
        function showHint() {
            const currentItem = countingItems[currentQuestionIndex];
            const operator = currentItem.jenis_operasi === 'tambah' ? 'ditambah' : 'dikurangi';
            
            let requiredCount;
            if (currentItem.jenis_operasi === 'tambah') {
                requiredCount = currentItem.nilai_kiri + currentItem.nilai_kanan;
            } else {
                requiredCount = currentItem.nilai_kiri;
            }
            
            const hint = `Petunjuk: ${currentItem.nilai_kiri} ${operator} ${currentItem.nilai_kanan} = ${currentItem.hasil}. Pindahkan ${requiredCount} gambar ${currentItem.nama_objek}!`;
            
            if (!isMuted) {
                playTextImmediately(hint, 'Petunjuk');
            }
            
            // Gunakan alert mobile friendly
            if (window.innerWidth <= 768) {
                const modalHtml = `
                    <div class="modal fade" id="hintModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title"><i class="fas fa-lightbulb me-2"></i>Petunjuk</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>${hint}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                const existingModal = document.getElementById('hintModal');
                if (existingModal) existingModal.remove();
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const hintModal = new bootstrap.Modal(document.getElementById('hintModal'));
                hintModal.show();
            } else {
                alert(hint);
            }
        }
        
        // Navigasi soal
        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                loadQuestion(currentQuestionIndex);
            }
        }
        
        function nextQuestion() {
            if (currentQuestionIndex < totalQuestions - 1) {
                currentQuestionIndex++;
                loadQuestion(currentQuestionIndex);
            } else if (currentQuestionIndex === totalQuestions - 1) {
                document.getElementById('finish-btn').classList.remove('d-none');
                document.getElementById('next-btn').classList.add('d-none');
            }
        }
        
        // Update progress bar
        function updateProgressBar() {
            const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
            document.getElementById('progress-fill').style.width = `${progress}%`;
        }
        
        // Selesaikan level
        function finishLevel() {
            const percentage = (correctCount / totalQuestions) * 100;
            
            let stars = 1;
            if (percentage >= 80) stars = 3;
            else if (percentage >= 60) stars = 2;
            
            document.getElementById('final-score').textContent = score;
            document.getElementById('final-correct').textContent = `${correctCount}/${totalQuestions}`;
            
            const starElements = document.querySelectorAll('#final-stars .star');
            starElements.forEach((star, index) => {
                if (index < stars) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            
            saveProgress(stars);
            
            playCompletionAudio(percentage);
            
            // Show modal after audio finishes
            setTimeout(() => {
                const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            }, 2500);
        }
        
        // Play completion audio
        function playCompletionAudio(percentage) {
            let message;
            if (percentage >= 90) {
                message = "Luar biasa! Skor sempurna! Kamu hebat sekali!";
            } else if (percentage >= 75) {
                message = "Hebat! Skormu sangat bagus!";
            } else if (percentage >= 50) {
                message = "Bagus! Kamu sudah belajar dengan baik!";
            } else {
                message = "Tetap semangat! Latihan membuatmu makin pintar!";
            }
            
            if (!isMuted) {
                playTextImmediately(message, 'Selesai');
            }
        }
        
        // Simpan progress ke server
        function saveProgress(stars) {
            fetch(`/calista/${moduleSlug}/level/${levelId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    score: score,
                    stars: stars,
                    correct_count: correctCount
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Progress saved:', data);
            })
            .catch(error => {
                console.error('Error saving progress:', error);
            });
        }
        
        // Audio feedback
        function showAudioFeedback(message) {
            const feedback = document.getElementById('audio-feedback');
            const text = document.getElementById('feedback-text');
            
            text.textContent = message;
            feedback.style.display = 'flex';
            
            setTimeout(() => {
                feedback.style.display = 'none';
            }, 1500);
        }
        
        // Setup keyboard shortcuts untuk desktop
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                if (window.innerWidth > 768) {
                    if (e.key.toLowerCase() === 'm') {
                        e.preventDefault();
                        toggleAudio();
                    }
                    
                    if (e.key === 'ArrowLeft' && currentQuestionIndex > 0) {
                        e.preventDefault();
                        prevQuestion();
                    }
                    
                    if (e.key === 'ArrowRight' && currentQuestionIndex < totalQuestions - 1) {
                        e.preventDefault();
                        nextQuestion();
                    }
                    
                    if (e.key.toLowerCase() === 'r') {
                        e.preventDefault();
                        document.getElementById('reset-btn').click();
                    }
                    
                    if (e.key.toLowerCase() === 'h') {
                        e.preventDefault();
                        document.getElementById('hint-btn').click();
                    }
                    
                    if (e.key.toLowerCase() === 'v') {
                        e.preventDefault();
                        voiceHelpBtn.click();
                    }
                    
                    if (e.key.toLowerCase() === 'space') {
                        e.preventDefault();
                        toggleRecording();
                    }
                }
            });
        }
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