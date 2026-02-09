<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calista - Belajar Budaya Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #67b0ff 0%, #3a8cff 100%);
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Matahari di tengah atas */
        .small-sun {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, #ffdd00 0%, #ff9500 100%);
            border-radius: 50%;
            box-shadow: 0 0 25px 15px rgba(255, 221, 0, 0.5),
                        0 0 50px 30px rgba(255, 149, 0, 0.4);
            z-index: 1;
            animation: sunPulse 3s infinite alternate ease-in-out;
        }

        @keyframes sunPulse {
            0% {
                transform: translateX(-50%) scale(1);
                box-shadow: 0 0 25px 15px rgba(255, 221, 0, 0.5),
                            0 0 50px 30px rgba(255, 149, 0, 0.4);
            }
            100% {
                transform: translateX(-50%) scale(1.1);
                box-shadow: 0 0 30px 20px rgba(255, 221, 0, 0.6),
                            0 0 60px 35px rgba(255, 149, 0, 0.5);
            }
        }

        /* Container awan yang diperbaiki agar tidak terpotong */
        .clouds-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 180px; /* Tinggi ditambah agar awan tidak terpotong */
            pointer-events: none;
            z-index: 0;
            overflow: visible; /* Ubah dari hidden ke visible */
        }

        /* AWAN BULAT YANG SMOOTH */
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

        /* Cloud 1 - Awan besar */
        .cloud-1 {
            width: 120px;
            height: 50px;
            top: 30px; /* Posisi lebih rendah dari sebelumnya */
            right: -120px;
            animation-delay: 0s;
            animation-duration: 50s;
            background: radial-gradient(circle at 30px 20px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-1::before {
            content: '';
            position: absolute;
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            top: -25px;
            left: 20px;
            filter: blur(0.5px);
        }

        .cloud-1::after {
            content: '';
            position: absolute;
            width: 70px;
            height: 70px;
            background: #ffffff;
            border-radius: 50%;
            top: -30px;
            right: 10px;
            filter: blur(0.5px);
        }

        /* Cloud 2 */
        .cloud-2 {
            width: 100px;
            height: 40px;
            top: 50px; /* Posisi lebih rendah */
            right: -100px;
            animation-delay: 5s;
            animation-duration: 55s;
            background: radial-gradient(circle at 25px 15px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-2::before {
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

        .cloud-2::after {
            content: '';
            position: absolute;
            width: 55px;
            height: 55px;
            background: #ffffff;
            border-radius: 50%;
            top: -25px;
            right: 10px;
            filter: blur(0.5px);
        }

        /* Cloud 3 */
        .cloud-3 {
            width: 90px;
            height: 35px;
            top: 20px; /* Posisi lebih rendah */
            right: -90px;
            animation-delay: 10s;
            animation-duration: 60s;
            background: radial-gradient(circle at 20px 12px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-3::before {
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

        .cloud-3::after {
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

        /* Cloud 4 */
        .cloud-4 {
            width: 80px;
            height: 30px;
            top: 60px; /* Posisi lebih rendah */
            right: -80px;
            animation-delay: 15s;
            animation-duration: 50s;
            background: radial-gradient(circle at 18px 10px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-4::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: #ffffff;
            border-radius: 50%;
            top: -15px;
            left: 10px;
            filter: blur(0.5px);
        }

        .cloud-4::after {
            content: '';
            position: absolute;
            width: 45px;
            height: 45px;
            background: #ffffff;
            border-radius: 50%;
            top: -18px;
            right: 5px;
            filter: blur(0.5px);
        }

        /* AWAN TAMBAHAN - menambah jumlah awan */
        /* Cloud 5 */
        .cloud-5 {
            width: 110px;
            height: 45px;
            top: 15px;
            right: -110px;
            animation-delay: 20s;
            animation-duration: 65s;
            background: radial-gradient(circle at 28px 18px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-5::before {
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

        .cloud-5::after {
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

        /* Cloud 6 */
        .cloud-6 {
            width: 95px;
            height: 38px;
            top: 70px;
            right: -95px;
            animation-delay: 25s;
            animation-duration: 45s;
            background: radial-gradient(circle at 22px 16px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-6::before {
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

        .cloud-6::after {
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

        /* Cloud 7 */
        .cloud-7 {
            width: 85px;
            height: 32px;
            top: 40px;
            right: -85px;
            animation-delay: 30s;
            animation-duration: 70s;
            background: radial-gradient(circle at 19px 11px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-7::before {
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

        .cloud-7::after {
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

        /* Cloud 8 */
        .cloud-8 {
            width: 130px;
            height: 55px;
            top: 10px;
            right: -130px;
            animation-delay: 35s;
            animation-duration: 75s;
            background: radial-gradient(circle at 35px 25px, rgba(255,255,255,1) 40%, rgba(255,255,255,0.9) 100%);
        }

        .cloud-8::before {
            content: '';
            position: absolute;
            width: 65px;
            height: 65px;
            background: #ffffff;
            border-radius: 50%;
            top: -30px;
            left: 25px;
            filter: blur(0.5px);
        }

        .cloud-8::after {
            content: '';
            position: absolute;
            width: 75px;
            height: 75px;
            background: #ffffff;
            border-radius: 50%;
            top: -35px;
            right: 15px;
            filter: blur(0.5px);
        }

        @keyframes cloudFloatLeft {
            0% { 
                transform: translateX(0) translateY(0); 
            }
            50% {
                transform: translateX(-50vw) translateY(5px);
            }
            100% { 
                transform: translateX(calc(-100vw - 400px)) translateY(0); 
            }
        }

        /* RUMPUT YANG LEBIH BANYAK & TANPA POJOK */
        .grass-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px; /* Ditinggikan */
            z-index: 1;
            overflow: hidden;
        }

        .grass-background {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(to top, 
                #1b5e20 0%,
                #2e7d32 20%,
                #4caf50 40%,
                #8bc34a 60%,
                #aed581 80%,
                #dcedc8 100%
            );
            z-index: 1;
        }

        /* Blade grass yang lebih banyak */
        .grass-blade-container {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 150px;
            z-index: 2;
        }

        .grass-blade {
            position: absolute;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to top, 
                transparent 0%,
                #1b5e20 20%,
                #4caf50 50%,
                #8bc34a 70%,
                #c5e1a5 90%
            );
            transform-origin: bottom center;
        }

        /* Efek angin pada rumput */
        .wind-effect {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(90deg, 
                transparent,
                rgba(255, 255, 255, 0.1),
                rgba(255, 255, 255, 0.2),
                rgba(255, 255, 255, 0.1),
                transparent
            );
            animation: windBlow 12s infinite linear;
            z-index: 3;
            pointer-events: none;
            opacity: 0.4;
        }

        @keyframes windBlow {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Container untuk horizontal scroll */
        .modules-container {
            padding: 120px 20px 120px; /* Padding bawah ditambah untuk rumput yang lebih tinggi */
            position: relative;
            z-index: 4;
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* Horizontal scroll container */
        .horizontal-scroll {
            display: flex;
            gap: 30px;
            padding: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x mandatory;
        }

        .horizontal-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Module Cards - DIMODIFIKASI: TINGGI DITINGKATKAN LAGI */
        .module-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            border-radius: 25px;
            width: 380px;
            min-width: 380px;
            height: 500px; /* DITINGKATKAN LAGI dari 520px menjadi 580px */
            border: 4px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2),
                        inset 0 1px 0 rgba(255, 255, 255, 0.5);
            display: flex;
            flex-direction: column;
            scroll-snap-align: center;
        }

        .module-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        /* TINGGI FOTO DITINGKATKAN LAGI - SEMAKIN TINGGI */
        .module-image {
            width: 100%;
            height: 800px; /* DITINGKATKAN LAGI dari 280px menjadi 350px */
            overflow: hidden;
            position: relative;
            border-bottom: 3px solid rgba(0, 0, 0, 0.1);
        }

        .module-image img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Menampilkan seluruh foto */
            transition: transform 0.5s ease;
            background-color: rgba(0, 0, 0, 0.05); /* Latar belakang jika ada ruang kosong */
        }
        .module-card:hover .module-image img {
            transform: scale(1.1);
        }

        .module-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
        }

        /* MODULE HEADER DIMODIFIKASI - HAPUS MODULE ICON */
        .module-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        /* MODULE ICON DIHAPUS */
        .module-icon {
            display: none; /* Menyembunyikan ikon */
        }

        /* MODULE NAME DIUBAH */
        .module-name {
            font-family: 'Fredoka One', cursive;
            font-size: 2.2rem; /* Sedikit diperbesar lagi */
            color: #1e293b;
            width: 100%; /* Mengisi seluruh lebar */
            text-align: center; /* Teks di tengah */
            text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
            line-height: 1.2;
            margin-bottom: 15px;
        }

        /* DESKRIPSI DITAMBAHKAN KEMBALI DENGAN STYLE BARU */
        .module-description {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 1.1rem;
            font-weight: 500;
            text-align: center;
            flex-grow: 1;
            padding: 0 10px;
        }

        .module-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--module-color, #3b82f6), var(--module-color-dark, #1d4ed8));
            color: white;
            padding: 16px 28px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2rem;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 8px 0 var(--module-color-dark, #1d4ed8);
            margin-top: auto;
        }

        .module-btn:hover {
            background: linear-gradient(135deg, var(--module-color-light, #60a5fa), var(--module-color, #3b82f6));
            transform: translateY(-3px);
            box-shadow: 0 11px 0 var(--module-color-dark, #1d4ed8);
        }

        .module-btn:active {
            transform: translateY(2px);
            box-shadow: 0 6px 0 var(--module-color-dark, #1d4ed8);
        }

        /* Scroll arrows */
        .scroll-arrows {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-top: 30px;
            z-index: 2;
            position: relative;
        }

        .scroll-arrow {
            background: rgba(255, 255, 255, 0.95);
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #2c5282;
            cursor: pointer;
            transition: all 0.3s;
            border: 3px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .scroll-arrow:hover {
            background: #2c5282;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Instructions */
        .instructions {
            text-align: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.3);
            animation: pulse 2s infinite;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* VOLUME CONTROL STYLES */
        .volume-control {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.95);
            padding: 10px 16px;
            border-radius: 999px;
            backdrop-filter: blur(6px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 180px;
        }

        .volume-slider-container {
            flex: 1;
            min-width: 80px;
            position: relative;
        }

        .volume-slider {
            width: 100%;
            height: 6px;
            -webkit-appearance: none;
            appearance: none;
            background: linear-gradient(to right, #3b82f6, #60a5fa);
            border-radius: 3px;
            outline: none;
            cursor: pointer;
        }

        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #1d4ed8;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            border: 3px solid white;
            transition: all 0.2s;
        }

        .volume-slider::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            background: #3b82f6;
        }

        .volume-slider::-moz-range-thumb {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #1d4ed8;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .volume-percent {
            font-size: 0.9rem;
            font-weight: 700;
            color: #374151;
            min-width: 40px;
            text-align: center;
        }

        /* VOLUME POPUP - untuk tampilan yang lebih baik */
        .volume-popup {
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: rgba(255,255,255,0.98);
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            animation: slideInUp 0.3s ease;
            max-width: 220px;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .volume-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .volume-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .volume-close {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 4px;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .volume-close:hover {
            background: #f1f5f9;
            color: #475569;
        }

        /* Music control widget dengan volume */
        .music-widget {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 9999;
            display: flex;
            gap: 8px;
            align-items: center;
            background: rgba(255,255,255,0.95);
            padding: 8px 12px;
            border-radius: 999px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            backdrop-filter: blur(6px);
            transition: all 0.3s ease;
        }

        .music-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            cursor: pointer;
            background: linear-gradient(135deg,#3b82f6,#1d4ed8);
            color: white;
            box-shadow: 0 4px 0 #1d4ed8;
            transition: all 0.2s;
        }

        .music-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #1d4ed8;
        }

        .music-btn.secondary {
            background: linear-gradient(135deg,#10b981,#059669);
            box-shadow: 0 4px 0 #059669;
        }

        .music-btn.secondary:hover {
            box-shadow: 0 6px 0 #059669;
        }

        .music-btn.tertiary {
            background: linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow: 0 4px 0 #d97706;
        }

        .music-btn.tertiary:hover {
            box-shadow: 0 6px 0 #d97706;
        }

        .music-status {
            font-size: 0.9rem;
            color: #374151;
            font-weight: 700;
            margin-left: 6px;
        }

        /* VOLUME BUTTON EXPAND */
        .volume-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            cursor: pointer;
            background: linear-gradient(135deg,#8b5cf6,#7c3aed);
            color: white;
            box-shadow: 0 4px 0 #7c3aed;
            transition: all 0.3s ease;
        }

        .volume-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #7c3aed;
        }

        /* Responsive Design - UPDATE UNTUK TINGGI BARU */
        @media (max-width: 1024px) {
            .module-card {
                width: 340px;
                min-width: 340px;
                height: 540px; /* Disesuaikan untuk tablet */
            }
            
            /* Tinggi foto responsive */
            .module-image {
                height: 320px; /* Disesuaikan untuk tablet */
            }
            
            .module-name {
                font-size: 2rem;
            }
            
            .small-sun {
                width: 70px;
                height: 70px;
                top: 15px;
            }
            
            .clouds-container {
                height: 150px;
            }
            
            .grass-container {
                height: 120px;
            }
            
            .grass-background {
                height: 120px;
            }
        }

        @media (max-width: 768px) {
            .modules-container {
                padding: 100px 15px 100px;
            }
            
            .module-card {
                width: 320px;
                min-width: 320px;
                height: 520px; /* Disesuaikan untuk mobile landscape */
            }
            
            /* Tinggi foto responsive */
            .module-image {
                height: 300px; /* Disesuaikan untuk mobile landscape */
            }
            
            .module-name {
                font-size: 1.8rem;
            }
            
            .module-description {
                font-size: 1rem;
            }
            
            .scroll-arrow {
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }
            
            .instructions {
                font-size: 1.1rem;
            }
            
            .small-sun {
                width: 60px;
                height: 60px;
                top: 10px;
            }
            
            .clouds-container {
                height: 130px;
            }
            
            .grass-container {
                height: 100px;
            }
            
            .grass-background {
                height: 100px;
            }
            
            /* Volume control responsive */
            .music-widget {
                right: 15px;
                bottom: 15px;
                padding: 6px 10px;
            }
            
            .volume-popup {
                left: 15px;
                bottom: 15px;
                max-width: 200px;
                padding: 14px;
            }
        }

        @media (max-width: 480px) {
            .modules-container {
                padding: 90px 10px calc(80px + env(safe-area-inset-bottom));
            }
            
            .module-card {
                width: 90vw;
                min-width: 90vw;
                height: auto;
                max-height: 85vh;
            }
            
            /* Tinggi foto responsive */
            .module-image {
                height: 35vh;
                max-height: 300px;
            }
            
            .module-content {
                padding: 15px;
                min-height: auto;
            }
            
            .module-name {
                font-size: 1.4rem;
                margin-bottom: 10px;
            }
            
            .module-description {
                font-size: 0.9rem;
                margin-bottom: 15px;
                line-height: 1.4;
            }
            
            .module-btn {
                padding: 12px 20px;
                font-size: 1rem;
            }
            
            .instructions {
                font-size: 0.85rem;
                padding: 6px 12px;
                display: none;
            }
            
            .scroll-arrows {
                gap: 15px;
                margin-top: 15px;
            }
            
            .scroll-arrow {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }
            
            .small-sun {
                width: 50px;
                height: 50px;
                top: 8px;
            }
            
            .clouds-container {
                height: 110px;
            }
            
            .grass-container {
                height: 80px;
            }
            
            .grass-background {
                height: 80px;
            }
            
            /* Volume control untuk mobile kecil */
            .music-widget {
                right: 10px;
                bottom: 80px;
                padding: 5px 8px;
                gap: 5px;
                flex-wrap: wrap;
            }
            
            .music-btn, .volume-btn {
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
            
            .volume-popup {
                left: 10px;
                bottom: 80px;
                max-width: calc(100vw - 20px);
                padding: 12px;
            }
            
            .volume-title {
                font-size: 1rem;
            }
        }

        /* Responsive design untuk layar sangat kecil */
        @media (max-width: 360px) {
            .module-card {
                width: 90vw;
                min-width: 90vw;
                height: auto;
                max-height: 85vh;
            }
            
            .module-image {
                height: 35vh;
                max-height: 280px;
            }
            
            .module-name {
                font-size: 1.4rem;
            }
            
            .music-widget {
                gap: 4px;
                padding: 5px;
            }
        }

        /* Landscape mode di mobile */
        @media (max-width: 896px) and (orientation: landscape) {
            .module-card {
                width: 70vw;
                min-width: 70vw;
                height: 85vh;
            }
            
            .module-image {
                height: 45vh;
            }
            
            .modules-container {
                padding: 80px 15px calc(60px + env(safe-area-inset-bottom));
            }
            
            .small-sun {
                width: 40px;
                height: 40px;
                top: 10px;
            }
            
            .clouds-container {
                height: 80px;
            }
        }

        /* Dark mode support untuk volume control */
        @media (prefers-color-scheme: dark) {
            .volume-popup {
                background: rgba(30, 41, 59, 0.95);
                border-color: rgba(51, 65, 85, 0.5);
            }
            
            .volume-title {
                color: #f1f5f9;
            }
            
            .volume-close {
                color: #94a3b8;
            }
            
            .volume-close:hover {
                background: #334155;
                color: #cbd5e1;
            }
            
            .volume-percent {
                color: #e2e8f0;
            }
            
            .music-widget {
                background: rgba(30, 41, 59, 0.95);
            }
            
            .music-status {
                color: #e2e8f0;
            }
        }

        /* GAME EVENTS SECTION */
        .game-events-section {
            margin-top: 60px;
            padding: 0 20px;
            margin-bottom: 100px;
            position: relative;
            z-index: 10;
        }

        .section-title {
            font-size: 28px;
            font-weight: 900;
            color: white;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            font-family: 'Fredoka One', sans-serif;
        }

        .game-events-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 20px;
            padding-top: 10px;
            scroll-snap-type: x mandatory;
        }

        .game-events-container::-webkit-scrollbar {
            height: 6px;
        }

        .game-events-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .game-events-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
        }

        .game-events-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.6);
        }

        /* Game Event Card */
        .game-event-card {
            flex: 0 0 220px;
            background: linear-gradient(135deg, var(--game-color), var(--game-color-light));
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            transform: translateZ(0);
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            scroll-snap-align: start;
            position: relative;
        }

        .game-event-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .game-event-card:active {
            transform: translateY(-4px) scale(1.02);
        }

        .game-event-image {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, var(--game-color-light), var(--game-color));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .game-event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .game-event-card:hover .game-event-image img {
            transform: scale(1.1);
        }

        .game-event-placeholder {
            font-size: 60px;
            color: rgba(255, 255, 255, 0.6);
            animation: gamepadBounce 2s infinite;
        }

        @keyframes gamepadBounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .game-event-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 59, 48, 0.9);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: badgePulse 2s infinite;
        }

        @keyframes badgePulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .game-event-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 1;
            background: rgba(0, 0, 0, 0.1);
        }

        .game-event-name {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
            line-height: 1.3;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .game-event-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--game-color-dark);
            padding: 10px 16px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .game-event-btn:hover {
            background: white;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .game-event-btn:active {
            transform: scale(0.98);
        }

        .game-event-btn i {
            font-size: 16px;
        }

        /* No Games Message */
        .no-games-message {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            color: white;
        }

        .no-games-message i {
            font-size: 60px;
            margin-bottom: 15px;
            opacity: 0.6;
        }

        .no-games-message p {
            font-size: 16px;
            opacity: 0.8;
        }

        /* Game Scroll Arrows */
        .game-scroll-arrows {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            padding: 0 20px;
        }

        /* Responsive - Game Events */
        @media (max-width: 768px) {
            .game-event-card {
                flex: 0 0 180px;
            }

            .game-event-image {
                height: 120px;
            }

            .game-event-placeholder {
                font-size: 45px;
            }

            .section-title {
                font-size: 22px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 480px) {
            .game-event-card {
                flex: 0 0 160px;
            }

            .game-event-image {
                height: 100px;
            }

            .game-event-placeholder {
                font-size: 35px;
            }

            .game-event-name {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .game-event-btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .game-event-badge {
                top: 5px;
                right: 5px;
                font-size: 10px;
                padding: 4px 8px;
            }

            .section-title {
                font-size: 20px;
            }

            .game-events-section {
                margin-top: 40px;
                padding: 0 15px;
            }

            .game-scroll-arrows {
                gap: 15px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Matahari di tengah atas -->
    <div class="small-sun"></div>

    <!-- Awan yang sudah ditambah jumlahnya dan tidak terpotong -->
    <div class="clouds-container">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
        <div class="cloud cloud-4"></div>
        <div class="cloud cloud-5"></div>
        <div class="cloud cloud-6"></div>
        <div class="cloud cloud-7"></div>
        <div class="cloud cloud-8"></div>
    </div>

    <!-- Rumput yang lebih banyak tanpa pohon di pojok -->
    <div class="grass-container">
        <div class="grass-background"></div>
        <div class="grass-blade-container" id="grassBlades"></div>
        <div class="wind-effect"></div>
        <!-- POJOK TANPA POHON - dihapus sesuai permintaan -->
    </div>

    <!-- Modules Container -->
    <div class="modules-container">
        <div class="horizontal-scroll" id="modulesScroll">
            @foreach($modules as $module)
            <div class="module-card" 
                 style="--module-color: {{ ['#3b82f6', '#ef4444', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899'][$loop->index % 6] }};
                        --module-color-light: {{ ['#60a5fa', '#f87171', '#4ade80', '#fbbf24', '#a78bfa', '#f472b6'][$loop->index % 6] }};
                        --module-color-dark: {{ ['#1d4ed8', '#dc2626', '#16a34a', '#d97706', '#7c3aed', '#db2777'][$loop->index % 6] }};"
                 onclick="window.location.href='{{ route('calista.show', $module->slug) }}'">
                
                <!-- Menampilkan foto dari database -->
                <div class="module-image">
                    @if($module->foto)
                        <img src="{{ asset('storage/' . $module->foto) }}" alt="{{ $module->name }}">
                    @else
                        <img src="https://via.placeholder.com/600x350/{{ substr(md5($module->name), 0, 6) }}/ffffff?text={{ urlencode($module->name) }}" alt="{{ $module->name }}">
                    @endif
                </div>
                
                <div class="module-content">
                    <div class="module-header">
                        <h3 class="module-name">{{ $module->name }}</h3>
                    </div>   
                    <a href="{{ route('calista.show', $module->slug) }}" class="module-btn">
                        <i class="fas fa-play"></i> Mulai Belajar
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Scroll Arrows -->
        <div class="scroll-arrows">
            <div class="scroll-arrow left-arrow" onclick="scrollLeft()">
                <i class="fas fa-chevron-left"></i>
            </div>
            <p class="instructions">Geser ke samping untuk melihat lebih banyak!</p>
            <div class="scroll-arrow right-arrow" onclick="scrollRight()">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    <!-- Game Events Section -->
    <div class="game-events-section">
        <h2 class="section-title">🎮 Mini Game Pameran Palcomtech</h2>
        <div class="game-events-container" id="gamesScroll">
            @if($games && $games->count() > 0)
                @foreach($games as $game)
                <div class="game-event-card" 
                     style="--game-color: {{ ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe'][$loop->index % 6] }};
                            --game-color-light: {{ ['#ff8787', '#6ee7de', '#74c0fc', '#feca57', '#a29bfe', '#c7b3ff'][$loop->index % 6] }};
                            --game-color-dark: {{ ['#ee5a52', '#38a169', '#1098ad', '#e8b708', '#5f3dc4', '#7950f2'][$loop->index % 6] }};">
                    
                    <div class="game-event-image">
                        @if($game->foto)
                            <img src="{{ asset('storage/' . $game->foto) }}" alt="{{ $game->nama_game }}">
                        @else
                            <div class="game-event-placeholder">
                                <i class="fas fa-gamepad"></i>
                            </div>
                        @endif
                        <div class="game-event-badge">EVENT</div>
                    </div>
                    
                    <div class="game-event-content">
                        <h3 class="game-event-name">{{ $game->nama_game }}</h3>
                        <a href="{{ route('game.show', $game->id) }}" class="game-event-btn">
                            <i class="fas fa-play"></i> Main Game
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-games-message">
                    <i class="fas fa-inbox"></i>
                    <p>Tidak ada event game saat ini</p>
                </div>
            @endif
        </div>

        <!-- Game Scroll Arrows -->
        <div class="game-scroll-arrows">
            <div class="scroll-arrow left-arrow" onclick="scrollGamesLeft()">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="scroll-arrow right-arrow" onclick="scrollGamesRight()">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>

    <!-- Autoplay bird sound -->
    <audio id="birdAudio" autoplay loop>
        <source src="{{ asset('storage/music/burung.mp3') }}" type="audio/mpeg">
    </audio>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to module cards
            const moduleCards = document.querySelectorAll('.module-card');
            moduleCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'translateY(-15px) scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-15px) scale(1.03)';
                    }, 200);
                });
            });

            // Generate lebih banyak blade grass
            generateGrassBlades();
        });

        function generateGrassBlades() {
            const container = document.getElementById('grassBlades');
            const bladeCount = Math.floor(window.innerWidth / 2.5); // LEBIH BANYAK!!!
            
            for (let i = 0; i < bladeCount; i++) {
                const blade = document.createElement('div');
                blade.className = 'grass-blade';
                
                // Random properties
                const left = Math.random() * 100;
                const height = 40 + Math.random() * 110; // Lebih tinggi
                const lean = (Math.random() - 0.5) * 45; // Lebih miring
                const delay = Math.random() * 8;
                const duration = 1.5 + Math.random() * 4; // Variasi durasi
                
                blade.style.left = `${left}%`;
                blade.style.height = `${height}px`;
                blade.style.transform = `skew(${lean}deg)`;
                blade.style.animation = `bladeSway ${duration}s ease-in-out ${delay}s infinite alternate`;
                
                // Random color variations
                const hue = 100 + Math.random() * 40; // Hijau variations
                const saturation = 50 + Math.random() * 30;
                const lightness = 30 + Math.random() * 30;
                blade.style.background = `linear-gradient(to top, 
                    transparent 0%,
                    hsl(${hue}, ${saturation}%, ${lightness}%) 30%,
                    hsl(${hue + 10}, ${saturation + 10}%, ${lightness + 15}%) 60%,
                    hsl(${hue + 20}, ${saturation + 5}%, ${lightness + 25}%) 90%
                )`;
                
                container.appendChild(blade);
            }
        }

        // Add CSS for blade swaying
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bladeSway {
                0%, 100% { 
                    transform: skew(0deg) translateX(0); 
                }
                25% { 
                    transform: skew(10deg) translateX(-3px); 
                }
                50% { 
                    transform: skew(-7deg) translateX(2px); 
                }
                75% { 
                    transform: skew(4deg) translateX(-2px); 
                }
            }
            
            .grass-blade {
                animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);
            }
        `;
        document.head.appendChild(style);

        // Horizontal scroll functions
        function scrollLeft() {
            const scrollContainer = document.getElementById('modulesScroll');
            scrollContainer.scrollBy({
                left: -400,
                behavior: 'smooth'
            });
        }

        function scrollRight() {
            const scrollContainer = document.getElementById('modulesScroll');
            scrollContainer.scrollBy({
                left: 400,
                behavior: 'smooth'
            });
        }

        // Game Events Scroll Functions
        function scrollGamesLeft() {
            const gamesContainer = document.getElementById('gamesScroll');
            if (gamesContainer) {
                gamesContainer.scrollBy({
                    left: -400,
                    behavior: 'smooth'
                });
            }
        }

        function scrollGamesRight() {
            const gamesContainer = document.getElementById('gamesScroll');
            if (gamesContainer) {
                gamesContainer.scrollBy({
                    left: 400,
                    behavior: 'smooth'
                });
            }
        }

        // Auto scroll disabled
        const scrollContainer = document.getElementById('modulesScroll');

        // Touch swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        scrollContainer.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        scrollContainer.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        scrollContainer.addEventListener('touchmove', e => {
            if (Math.abs(touchStartX - e.changedTouches[0].screenX) > 10) {
                // Allow horizontal swipe
            }
        }, { passive: false });

        function handleSwipe() {
            const swipeThreshold = 30;
            
            if (touchStartX - touchEndX > swipeThreshold) {
                scrollRight();
            } else if (touchEndX - touchStartX > swipeThreshold) {
                scrollLeft();
            }
        }

        // Regenerate grass on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const container = document.getElementById('grassBlades');
                container.innerHTML = '';
                generateGrassBlades();
            }, 250);
        });

        // Autoplay bird sound
        (function() {
            const birdAudio = document.getElementById('birdAudio');
            if (birdAudio) {
                // Coba play saat load
                birdAudio.play().catch(() => {
                    // Jika autoplay gagal (misal ada user gesture requirement)
                    // Trigger play pada first user interaction
                    const playOnInteraction = () => {
                        birdAudio.play().catch(() => {});
                        document.removeEventListener('click', playOnInteraction);
                        document.removeEventListener('touchstart', playOnInteraction);
                    };
                    document.addEventListener('click', playOnInteraction);
                    document.addEventListener('touchstart', playOnInteraction);
                });
            }
        })();

        // --- Persistent audio player manager dengan VOLUME CONTROL ---
        (function() {
            // Dummy untuk kompatibilitas dengan script click sound
            const KEY_VOLUME = 'bgMusicVolume';

            const btn = document.getElementById('musicToggle');
            const btnIcon = document.getElementById('musicToggleIcon');
            const muteBtn = document.getElementById('musicMute');
            const muteIcon = document.getElementById('musicMuteIcon');
            const volumeSlider = document.getElementById('volumeSlider');
            const volumePercent = document.getElementById('volumePercent');
            const volumeToggleBtn = document.getElementById('volumeToggleBtn');
            const volumePopup = document.getElementById('volumePopup');
            const volumeCloseBtn = document.getElementById('volumeCloseBtn');

            // Inisialisasi volume dari localStorage atau default 70%
            let currentVolume = parseInt(localStorage.getItem(KEY_VOLUME)) || 70;
            if (volumeSlider) {
                volumeSlider.value = currentVolume;
                volumePercent.textContent = `${currentVolume}%`;
            }

            // Close volume popup
            if (volumeCloseBtn) {
                volumeCloseBtn.addEventListener('click', function() {
                    if (volumePopup) volumePopup.style.display = 'none';
                });
            }

            // Close volume popup when clicking outside
            document.addEventListener('click', function(event) {
                if (volumePopup && volumePopup.style.display === 'flex') {
                    if (!volumePopup.contains(event.target) && 
                        !volumeToggleBtn.contains(event.target) &&
                        event.target !== volumeToggleBtn) {
                        volumePopup.style.display = 'none';
                    }
                }
            });

            // Volume slider change
            if (volumeSlider) {
                volumeSlider.addEventListener('input', function() {
                    const vol = parseInt(this.value);
                    if (volumePercent) volumePercent.textContent = `${vol}%`;
                    currentVolume = vol;
                    localStorage.setItem(KEY_VOLUME, vol.toString());
                });
                
                volumeSlider.addEventListener('change', function() {
                    const vol = parseInt(this.value);
                    localStorage.setItem(KEY_VOLUME, vol.toString());
                });
            }

            // Volume toggle button - show/hide volume popup
            if (volumeToggleBtn) {
                volumeToggleBtn.addEventListener('click', function() {
                    if (volumePopup) {
                        if (volumePopup.style.display === 'none' || volumePopup.style.display === '') {
                            volumePopup.style.display = 'flex';
                        } else {
                            volumePopup.style.display = 'none';
                        }
                    }
                });
            }
        })();
    </script>

    <script>
        (function(){
            const CLICK_SRC = "{{ asset('storage/music/klik.mp3') }}";
            const clickAudio = new Audio(CLICK_SRC);
            clickAudio.preload = 'auto';
            
            // Set volume untuk efek klik (50% dari volume musik utama)
            function getClickVolume() {
                const musicVol = parseInt(localStorage.getItem('bgMusicVolume')) || 70;
                return (musicVol / 100) * 0.5; // 50% dari volume musik
        }
            
            function playClick() {
                try { 
                    const snd = clickAudio.cloneNode();
                    snd.volume = getClickVolume();
                    snd.play().catch(()=>{}); 
                } catch(e){}
            }
            
            document.addEventListener('click', function(e){
                const el = e.target;
                const btn = el.closest('button, a, input[type="button"], input[type="submit"], .btn, .module-btn, .start-btn, .music-btn, .volume-btn, .scroll-arrow');
                if (btn && btn.dataset.noSound !== '1') {
                    playClick();
                }
            }, true);
        })();
    </script>

    <!-- Time Out Modal -->
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
            // Data dari server
            const childId = {{ $child_id ?? 'null' }};
            const remainingSeconds = {{ $remaining_seconds ?? 0 }};
            let serverTimestamp = Math.floor(Date.now() / 1000);
            let isTimeoutShown = false;

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

            // Cek waktu setiap 2 detik
            setInterval(checkRemainingTime, 2000);

            // Cek juga saat page dimulai
            document.addEventListener('DOMContentLoaded', function() {
                checkRemainingTime();
            });
        })();
    </script>
</body>
</html>