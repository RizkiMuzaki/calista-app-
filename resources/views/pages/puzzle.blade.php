<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Puzzle - Level {{ $level->order_number }} - {{ $module->name }} - Calista</title>
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
            overflow-x: hidden;
        }

        /* Header */
        .puzzle-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #3b82f6;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: 2px solid #3b82f6;
        }

        .back-btn:hover {
            background: white;
            color: #3b82f6;
            transform: translateY(-2px);
        }

        .level-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .level-badge {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Main Container */
        .puzzle-container {
            display: grid;
            grid-template-columns: 350px 1fr 400px;
            gap: 20px;
            padding: 30px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Touch-friendly adjustments */
        .puzzle-piece,
        .puzzle-slot {
            touch-action: none;
        }

        /* Left Panel - Reference Image */
        .reference-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 3px solid white;
            display: flex;
            flex-direction: column;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .reference-title {
            font-family: 'Fredoka One', cursive;
            font-size: 1.8rem;
            color: #3b82f6;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
        }

        .reference-image-container {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 3px dashed #3b82f6;
            margin-bottom: 20px;
        }

        .reference-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 10px;
        }

        .reference-text {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 900;
            color: #1e40af;
            padding: 15px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 15px;
            border: 2px solid rgba(59, 130, 246, 0.3);
        }

        /* Center Panel - Puzzle Board */
        .puzzle-board-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .puzzle-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            color: white;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.2);
        }

        .puzzle-instruction {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px 30px;
            border-radius: 20px;
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e40af;
            text-align: center;
            margin-bottom: 30px;
            max-width: 800px;
            border: 3px solid #3b82f6;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .puzzle-board-container {
            width: 100%;
            max-width: 700px;
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 5px solid white;
            position: relative;
            overflow: hidden;
        }

        .puzzle-board {
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat({{ $puzzleItem->grid_size }}, 1fr);
            grid-template-rows: repeat({{ $puzzleItem->grid_size }}, 1fr);
            gap: 2px;
            background: #dbeafe;
            border-radius: 10px;
            padding: 5px;
            position: relative;
        }

        .puzzle-slot {
            background: rgba(59, 130, 246, 0.1);
            border: 2px dashed #93c5fd;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .puzzle-slot.hovered {
            background: rgba(59, 130, 246, 0.3);
            border-color: #3b82f6;
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), inset 0 0 10px rgba(59, 130, 246, 0.2);
        }

        .puzzle-slot.filled {
            background: rgba(16, 185, 129, 0.2);
            border: 2px solid #10b981;
            padding: 0;
        }

        .slot-number {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(255, 255, 255, 0.9);
            color: #1e40af;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            font-weight: 800;
            z-index: 1;
            border: 2px solid #3b82f6;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .puzzle-slot.filled .slot-number {
            display: none;
        }

        .puzzle-piece-in-slot {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .puzzle-piece-in-slot:hover {
            transform: scale(1.05);
        }

        .puzzle-piece-in-slot .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 24px;
            height: 24px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10;
            border: 2px solid white;
            min-width: 44px;
            min-height: 44px;
            padding: 0;
        }

        .puzzle-piece-in-slot:hover .remove-btn {
            opacity: 1;
        }

        @media (hover: none) {
            .puzzle-piece-in-slot .remove-btn {
                opacity: 1;
            }
        }

        .complete-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(16, 185, 129, 0.95);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            z-index: 10;
        }

        .complete-overlay.show {
            display: flex;
            animation: popIn 0.5s ease-out;
        }

        .complete-icon {
            font-size: 6rem;
            color: white;
            margin-bottom: 20px;
            animation: bounce 1s infinite;
        }

        .complete-message {
            font-size: 2.5rem;
            color: white;
            font-weight: 900;
            font-family: 'Fredoka One', cursive;
            margin-bottom: 10px;
            text-align: center;
        }

        .complete-submessage {
            font-size: 1.5rem;
            color: white;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Right Panel - Puzzle Pieces */
        .pieces-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 3px solid white;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .pieces-title {
            font-family: 'Fredoka One', cursive;
            font-size: 1.8rem;
            color: #3b82f6;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
        }

        .pieces-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .pieces-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .puzzle-piece {
            aspect-ratio: 1;
            background: linear-gradient(135deg, #f0f9ff, #dbeafe);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: grab;
            border: 3px solid #93c5fd;
            transition: all 0.3s;
            overflow: hidden;
            position: relative;
            padding: 0;
        }

        .puzzle-piece:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border-color: #3b82f6;
            z-index: 5;
        }

        @media (hover: none) {
            .puzzle-piece:active {
                transform: scale(1.05);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                border-color: #3b82f6;
                z-index: 5;
            }
        }

        .puzzle-piece.dragging {
            opacity: 0.9;
            cursor: grabbing;
            transform: scale(1.1) rotate(5deg) translateY(-20px);
            z-index: 100;
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4), 0 0 0 2px rgba(59, 130, 246, 0.3);
            animation: liftedShadow 0.3s ease-out;
        }

        .puzzle-piece.used {
            opacity: 0.4;
            cursor: default;
            border-color: #94a3b8;
            background: #f1f5f9;
        }

        .puzzle-piece.used:hover {
            transform: none;
            box-shadow: none;
            border-color: #94a3b8;
        }

        .puzzle-piece.selected {
            box-shadow: 0 0 0 4px #fbbf24, 0 0 0 6px #f59e0b;
            border-color: #fbbf24;
            z-index: 50;
        }

        .piece-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background-size: cover;
            background-position: center;
            transition: all 0.3s;
        }

        .piece-number {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(59, 130, 246, 0.95);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: bold;
            z-index: 2;
            border: 2px solid white;
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 16px 25px;
            border-radius: 15px;
            border: none;
            font-family: 'Nunito', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            text-align: center;
            min-height: 44px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 6px 0 #1d4ed8;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            transform: translateY(-3px);
            box-shadow: 0 9px 0 #1d4ed8;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 6px 0 #059669;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #34d399, #10b981);
            transform: translateY(-3px);
            box-shadow: 0 9px 0 #059669;
        }

        .btn-reset {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 6px 0 #dc2626;
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #f87171, #ef4444);
            transform: translateY(-3px);
            box-shadow: 0 9px 0 #dc2626;
        }

        /* Animations */
        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes liftedShadow {
            0% {
                transform: scale(1.1) rotate(5deg) translateY(0);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(59, 130, 246, 0.3);
            }
            100% {
                transform: scale(1.1) rotate(5deg) translateY(-20px);
                box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4), 0 0 0 2px rgba(59, 130, 246, 0.3);
            }
        }

        @keyframes dropPlace {
            0% {
                transform: scale(1.1) translateY(-20px);
                opacity: 0.8;
            }
            70% {
                transform: scale(1.02) translateY(-2px);
            }
            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slotAccept {
            0% {
                background: rgba(59, 130, 246, 0.3);
                transform: scale(1.02);
            }
            50% {
                background: rgba(16, 185, 129, 0.4);
                transform: scale(1.05);
            }
            100% {
                background: rgba(16, 185, 129, 0.2);
                transform: scale(1);
            }
        }

        /* Success Animation */
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
            display: none;
        }

        .confetti.show {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .puzzle-container {
                grid-template-columns: 300px 1fr 350px;
                gap: 15px;
                padding: 20px;
            }
            
            .reference-image-container {
                height: 250px;
            }
            
            .puzzle-board-container {
                max-width: 600px;
            }
        }

        @media (max-width: 1200px) {
            .puzzle-container {
                grid-template-columns: 1fr 400px;
                grid-template-rows: auto 1fr;
            }
            
            .reference-panel {
                grid-column: 1 / span 2;
                grid-row: 1;
                position: static;
                margin-bottom: 20px;
                flex-direction: row;
                align-items: center;
                gap: 30px;
            }
            
            .reference-title {
                margin-bottom: 0;
                min-width: 150px;
            }
            
            .reference-image-container {
                width: 200px;
                height: 200px;
                margin-bottom: 0;
            }
            
            .puzzle-board-panel {
                grid-column: 1;
                grid-row: 2;
            }
            
            .pieces-panel {
                grid-column: 2;
                grid-row: 2;
            }
        }

        @media (max-width: 992px) {
            .puzzle-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto;
            }
            
            .reference-panel {
                grid-column: 1;
                grid-row: 1;
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .reference-image-container {
                width: 100%;
                max-width: 300px;
                height: 200px;
            }
            
            .puzzle-board-panel {
                grid-column: 1;
                grid-row: 2;
                margin-bottom: 30px;
            }
            
            .pieces-panel {
                grid-column: 1;
                grid-row: 3;
                position: static;
            }
            
            .puzzle-header {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .header-left, .header-right {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
                -webkit-user-select: none;
                user-select: none;
                -webkit-touch-callout: none;
            }

            .puzzle-container {
                padding: 12px;
                gap: 12px;
            }
            
            .puzzle-header {
                padding: 12px 15px;
                gap: 10px;
            }

            .back-btn {
                padding: 8px 15px;
                font-size: 0.95rem;
                min-height: 44px;
            }

            .level-badge {
                font-size: 1rem;
                padding: 6px 15px;
            }

            .puzzle-title {
                font-size: 1.8rem;
                margin-bottom: 15px;
            }
            
            .puzzle-instruction {
                padding: 15px 20px;
                font-size: 1rem;
                margin-bottom: 20px;
            }
            
            .puzzle-board-container {
                max-width: 100%;
                padding: 12px;
                border-width: 3px;
            }

            .puzzle-board {
                gap: 1px;
                padding: 3px;
            }

            .slot-number {
                width: 24px;
                height: 24px;
                font-size: 0.85rem;
            }

            .piece-number {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
            }
            
            .pieces-container {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
                margin-bottom: 20px;
            }
            
            .reference-panel, .pieces-panel {
                padding: 15px;
                border-radius: 15px;
            }

            .reference-title, .pieces-title {
                font-size: 1.4rem;
                margin-bottom: 15px;
            }

            .reference-image-container {
                height: 180px;
                border-radius: 12px;
            }

            .puzzle-piece {
                border-radius: 10px;
                border-width: 2px;
                min-height: 80px;
            }

            .puzzle-slot {
                border-radius: 6px;
                border-width: 2px;
            }
            
            .action-buttons {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .btn {
                flex: 1;
                min-width: 130px;
                padding: 12px 15px;
                font-size: 1rem;
                min-height: 44px;
                border-radius: 12px;
            }

            .btn-primary {
                box-shadow: 0 4px 0 #1d4ed8;
            }

            .btn-primary:active {
                box-shadow: 0 2px 0 #1d4ed8;
                transform: translateY(2px);
            }

            .btn-secondary {
                box-shadow: 0 4px 0 #059669;
            }

            .btn-secondary:active {
                box-shadow: 0 2px 0 #059669;
                transform: translateY(2px);
            }

            .btn-reset {
                box-shadow: 0 4px 0 #dc2626;
            }

            .btn-reset:active {
                box-shadow: 0 2px 0 #dc2626;
                transform: translateY(2px);
            }

            .puzzle-piece-in-slot {
                min-height: 80px;
            }

            .piece-image {
                font-size: 2rem !important;
            }

            .reference-text {
                font-size: 1.2rem;
            }

            .pieces-subtitle {
                font-size: 1rem;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 576px) {
            .puzzle-board-container {
                aspect-ratio: 1;
                padding: 10px;
            }

            .puzzle-board {
                gap: 1px;
                padding: 2px;
            }
            
            .pieces-container {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin-bottom: 15px;
            }
            
            .reference-title, .pieces-title {
                font-size: 1.2rem;
            }

            .reference-image-container {
                max-width: 100%;
                height: 150px;
            }

            .level-badge {
                font-size: 0.9rem;
                padding: 5px 12px;
            }
            
            .score-value, .time-value {
                font-size: 1.3rem;
            }
            
            .complete-icon {
                font-size: 4rem;
            }
            
            .complete-message {
                font-size: 1.8rem;
            }
            
            .complete-submessage {
                font-size: 1.1rem;
            }

            .puzzle-instruction {
                padding: 12px 15px;
                font-size: 0.95rem;
                margin-bottom: 15px;
            }

            .puzzle-piece {
                min-height: 70px;
                border-radius: 8px;
            }

            .slot-number {
                width: 20px;
                height: 20px;
                font-size: 0.75rem;
            }

            .piece-number {
                width: 24px;
                height: 24px;
                font-size: 0.8rem;
            }

            .btn {
                min-width: 100px;
                padding: 11px 12px;
                font-size: 0.95rem;
            }

            .action-buttons {
                gap: 8px;
            }

            .header-left {
                flex-wrap: wrap;
                gap: 10px;
            }

            .back-btn {
                padding: 7px 12px;
                font-size: 0.9rem;
                min-height: 40px;
            }

            .puzzle-title {
                font-size: 1.5rem;
                margin-bottom: 12px;
            }

            .reference-panel, .pieces-panel {
                padding: 12px;
                border-radius: 12px;
            }

            .puzzle-slot {
                min-height: 70px;
            }
        }

        @media (max-width: 400px) {
            .pieces-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            
            .reference-panel {
                padding: 10px;
            }
            
            .puzzle-instruction {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .btn {
                min-width: 90px;
                padding: 10px 10px;
                font-size: 0.9rem;
                flex: 0 1 auto;
            }

            .action-buttons {
                gap: 8px;
            }

            .puzzle-container {
                padding: 10px;
                gap: 10px;
            }

            .level-badge {
                font-size: 0.85rem;
            }

            .puzzle-title {
                font-size: 1.3rem;
            }

            .reference-title, .pieces-title {
                font-size: 1.1rem;
            }

            .back-btn i {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="puzzle-header">
        <div class="header-left">
            <a href="{{ route('calista.level.show', ['slug' => $module->slug, 'level' => $level->id]) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div class="level-info">
                <span class="level-badge">Level {{ $level->order_number }}</span>
                <h1 style="font-size: 1.3rem; color: #3b82f6;">{{ $puzzleItem->title }}</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="puzzle-container">
        <!-- Left Panel - Reference Image -->
        <div class="reference-panel">
            <div class="reference-image-container">
                @if($puzzleItem->image)
                    <img src="{{ asset('storage/' . $puzzleItem->image) }}" alt="{{ $puzzleItem->title }}" class="reference-image" id="referenceImage">
                @else
                    <div style="color: #3b82f6; font-size: 4rem;">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                @endif
            </div>
            <div class="reference-text">{{ $puzzleItem->title }}</div>
        </div>

        <!-- Center Panel - Puzzle Board -->
        <div class="puzzle-board-panel">
            <h2 class="puzzle-title">Susun Puzzle!</h2>
            
            <div class="puzzle-instruction" id="puzzleInstruction">
                <i class="fas fa-mouse"></i> Drag potongan ke papan, atau <i class="fas fa-hand-paper"></i> Tap potongan lalu slot
            </div>
            
            <div class="puzzle-board-container">
                <div class="puzzle-board" id="puzzleBoard">
                    <!-- Puzzle slots will be generated by JavaScript -->
                </div>
                
                <!-- Completion Overlay -->
                <div class="complete-overlay" id="completeOverlay">
                    <div class="complete-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2 class="complete-message">Selamat!</h2>
                    <p class="complete-submessage">Puzzle berhasil disusun!</p>
                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button class="btn btn-primary" id="nextLevelBtn">
                            <i class="fas fa-forward"></i> Level Berikutnya
                        </button>
                        <button class="btn btn-secondary" id="playAgainBtn">
                            <i class="fas fa-redo"></i> Main Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Puzzle Pieces -->
        <div class="pieces-panel">
            <h2 class="pieces-title">Potongan Puzzle</h2>
            <p class="pieces-subtitle">Drag ke papan atau klik untuk ambil kembali</p>
            
            <div class="pieces-container" id="piecesContainer">
                <!-- Puzzle pieces will be generated by JavaScript -->
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-secondary" id="hintBtn">
                    <i class="fas fa-lightbulb"></i> Petunjuk
                </button>
                <button class="btn btn-primary" id="checkBtn">
                    <i class="fas fa-check-circle"></i> Periksa
                </button>
                <button class="btn btn-reset" id="resetBtn">
                    <i class="fas fa-redo-alt"></i> Mulai Ulang
                </button>
            </div>
        </div>
    </div>

    <!-- Confetti Effect -->
    <div class="confetti" id="confetti"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Game configuration
            const config = {
                gridSize: {{ $puzzleItem->grid_size }},
                totalPieces: {{ $puzzleItem->grid_size * $puzzleItem->grid_size }}
            };
            
            // Game state
            let gameState = {
                isComplete: false,
                placedPieces: 0,
                pieces: [],
                boardSlots: [],
                imageUrl: "{{ $puzzleItem->image ? asset('storage/' . $puzzleItem->image) : '' }}",
                loadedImages: [],
                selectedPiece: null
            };
            
            // DOM Elements
            const puzzleBoard = document.getElementById('puzzleBoard');
            const piecesContainer = document.getElementById('piecesContainer');
            const completeOverlay = document.getElementById('completeOverlay');
            const resetBtn = document.getElementById('resetBtn');
            const hintBtn = document.getElementById('hintBtn');
            const checkBtn = document.getElementById('checkBtn');
            const nextLevelBtn = document.getElementById('nextLevelBtn');
            const playAgainBtn = document.getElementById('playAgainBtn');
            const confetti = document.getElementById('confetti');
            
            // Detect device type
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const isTouchDevice = () => {
                return (('ontouchstart' in window) ||
                        (navigator.maxTouchPoints > 0) ||
                        (navigator.msMaxTouchPoints > 0));
            };
            
            // Update instruction based on device
            const puzzleInstruction = document.getElementById('puzzleInstruction');
            if (isMobile || isTouchDevice()) {
                puzzleInstruction.innerHTML = '<i class="fas fa-hand-paper"></i> Tap potongan untuk pilih, lalu tap slot untuk tempatkan';
            }
            
            // Initialize the game
            initGame();
            
            // Prevent default touch behaviors
            document.addEventListener('touchmove', function(e) {
                if (e.target.closest('.puzzle-piece, .puzzle-slot, .pieces-container, .puzzle-board')) {
                    // Izinkan scroll normal untuk area puzzle
                } else {
                    // Cegah bounce scrolling di luar puzzle area
                    if (e.touches.length === 1) {
                        // Single touch only
                    }
                }
            }, { passive: true });
            
            // Initialize the game board and pieces
            function initGame() {
                // Clear previous state
                puzzleBoard.innerHTML = '';
                piecesContainer.innerHTML = '';
                gameState.placedPieces = 0;
                gameState.isComplete = false;
                gameState.pieces = [];
                gameState.boardSlots = [];
                gameState.loadedImages = [];
                
                // Create puzzle board slots
                createBoardSlots();
                
                // Create puzzle pieces with actual image slices
                createPuzzlePieces();
                
                // Hide completion overlay
                completeOverlay.classList.remove('show');
            }
            
            // Create the puzzle board slots dengan nomor
            function createBoardSlots() {
                puzzleBoard.style.gridTemplateColumns = `repeat(${config.gridSize}, 1fr)`;
                puzzleBoard.style.gridTemplateRows = `repeat(${config.gridSize}, 1fr)`;
                
                for (let row = 0; row < config.gridSize; row++) {
                    for (let col = 0; col < config.gridSize; col++) {
                        const slotId = `slot-${row}-${col}`;
                        const slot = document.createElement('div');
                        slot.className = 'puzzle-slot';
                        slot.id = slotId;
                        slot.dataset.row = row;
                        slot.dataset.col = col;
                        
                        // Tambahkan nomor slot
                        const slotNumber = document.createElement('div');
                        slotNumber.className = 'slot-number';
                        slotNumber.textContent = (row * config.gridSize + col) + 1;
                        slot.appendChild(slotNumber);
                        
                        // Add drag and drop events
                        slot.addEventListener('dragover', handleDragOver);
                        slot.addEventListener('dragenter', handleDragEnter);
                        slot.addEventListener('dragleave', handleDragLeave);
                        slot.addEventListener('drop', handleDrop);
                        
                        // Add touch events untuk slot (mobile)
                        slot.addEventListener('touchover', function(e) {
                            if (!window.touchPiece) return;
                            if (!this.classList.contains('filled')) {
                                this.classList.add('hovered');
                            }
                        });
                        
                        slot.addEventListener('touchleave', function(e) {
                            this.classList.remove('hovered');
                        });

                        // Add click handler untuk mobile click mode
                        slot.addEventListener('click', function() {
                            if (gameState.selectedPiece && !this.classList.contains('filled')) {
                                placePieceInSlot(gameState.selectedPiece, this);
                                gameState.selectedPiece.classList.remove('selected');
                                gameState.selectedPiece = null;
                                updateGameState();
                            }
                        });
                        
                        puzzleBoard.appendChild(slot);
                        gameState.boardSlots.push({
                            id: slotId,
                            row: row,
                            col: col,
                            pieceId: null,
                            element: slot,
                            pieceElement: null
                        });
                    }
                }
            }
            
            // Create the puzzle pieces
            function createPuzzlePieces() {
                // If we have an image URL, create pieces from it
                if (gameState.imageUrl) {
                    createImagePuzzlePieces();
                } else {
                    // Fallback to high-quality emoji pieces
                    createFallbackPuzzlePieces();
                }
            }
            
            // Create puzzle pieces from actual image
            function createImagePuzzlePieces() {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.src = gameState.imageUrl;
                
                img.onload = function() {
                    // Buat potongan puzzle dari gambar
                    for (let i = 0; i < config.totalPieces; i++) {
                        const pieceId = `piece-${i}`;
                        const row = Math.floor(i / config.gridSize);
                        const col = i % config.gridSize;
                        
                        // Create the puzzle piece element
                        createPuzzlePieceElement(pieceId, row, col, {
                            row: row,
                            col: col,
                            imageUrl: gameState.imageUrl
                        });
                    }
                    
                    // Shuffle the pieces
                    shufflePieces();
                };
                
                img.onerror = function() {
                    console.error('Gagal memuat gambar, menggunakan fallback');
                    createFallbackPuzzlePieces();
                };
            }
            
            // Create fallback puzzle pieces dengan emoji
            function createFallbackPuzzlePieces() {
                // Kategori gambar untuk anak-anak
                const categories = {
                    animals: ['🐱', '🐶', '🐰', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵'],
                    fruits: ['🍎', '🍌', '🍇', '🍓', '🍉', '🥝', '🍑', '🍒', '🥭'],
                    vehicles: ['🚗', '✈️', '🚂', '🚀', '🚲', '🚑', '🚒', '🚁', '🛶'],
                    objects: ['🏠', '🌳', '🌻', '🌈', '☀️', '🌙', '⭐', '🎈', '⚽'],
                    food: ['🍕', '🍦', '🍪', '🍩', '🎂', '🧁', '🍭', '🍫', '🍿']
                };
                
                // Pilih kategori secara acak
                const categoryKeys = Object.keys(categories);
                const randomCategory = categories[categoryKeys[Math.floor(Math.random() * categoryKeys.length)]];
                
                for (let i = 0; i < config.totalPieces; i++) {
                    const pieceId = `piece-${i}`;
                    const row = Math.floor(i / config.gridSize);
                    const col = i % config.gridSize;
                    
                    // Pilih emoji dari kategori yang dipilih
                    const emoji = randomCategory[i % randomCategory.length];
                    
                    // Create the puzzle piece element
                    createPuzzlePieceElement(pieceId, row, col, {
                        emoji: emoji,
                        row: row,
                        col: col,
                        color: getRandomColor()
                    });
                }
                
                // Shuffle the pieces
                shufflePieces();
            }
            
            // Create a single puzzle piece element
            function createPuzzlePieceElement(pieceId, row, col, data) {
                const piece = document.createElement('div');
                piece.className = 'puzzle-piece';
                piece.id = pieceId;
                piece.draggable = true;
                piece.dataset.pieceId = pieceId;
                piece.dataset.correctRow = row;
                piece.dataset.correctCol = col;
                piece.dataset.isUsed = 'false';
                
                // Create piece number
                const pieceNumber = document.createElement('div');
                pieceNumber.className = 'piece-number';
                pieceNumber.textContent = (row * config.gridSize + col) + 1;
                
                // Create piece image/content
                const pieceImage = document.createElement('div');
                pieceImage.className = 'piece-image';
                
                if (data.emoji) {
                    // Jika menggunakan emoji fallback
                    pieceImage.style.display = 'flex';
                    pieceImage.style.alignItems = 'center';
                    pieceImage.style.justifyContent = 'center';
                    pieceImage.style.fontSize = '2.5rem';
                    pieceImage.style.fontWeight = 'normal';
                    pieceImage.style.background = data.color || getRandomColor();
                    pieceImage.textContent = data.emoji;
                } else if (data.imageUrl) {
                    // Jika ada gambar asli
                    pieceImage.style.background = getRandomColor();
                    pieceImage.style.backgroundImage = `url('${data.imageUrl}')`;
                    pieceImage.style.backgroundSize = `${config.gridSize * 100}%`;
                    
                    // Hitung posisi background
                    const bgX = col * (100 / (config.gridSize - 1));
                    const bgY = row * (100 / (config.gridSize - 1));
                    pieceImage.style.backgroundPosition = `${bgX}% ${bgY}%`;
                } else {
                    // Fallback ultimate
                    pieceImage.style.display = 'flex';
                    pieceImage.style.alignItems = 'center';
                    pieceImage.style.justifyContent = 'center';
                    pieceImage.style.fontSize = '2rem';
                    pieceImage.style.fontWeight = 'bold';
                    pieceImage.style.color = 'white';
                    pieceImage.style.background = getRandomColor();
                    pieceImage.textContent = (row * config.gridSize + col) + 1;
                }
                
                piece.appendChild(pieceNumber);
                piece.appendChild(pieceImage);
                
                // Add drag events
                piece.addEventListener('dragstart', handleDragStart);
                piece.addEventListener('dragend', handleDragEnd);
                
                // Add touch events untuk mobile
                piece.addEventListener('touchstart', handleTouchStart, { passive: false });
                piece.addEventListener('touchmove', handleTouchMove, { passive: false });
                piece.addEventListener('touchend', handleTouchEnd, { passive: false });
                
                // Add click event untuk backup (jika drag tidak berfungsi)
                piece.addEventListener('click', function() {
                    if (!piece.dataset.isUsed || piece.dataset.isUsed === 'false') {
                        // Select piece untuk mobile click mode
                        if (gameState.selectedPiece === piece) {
                            // Deselect
                            piece.classList.remove('selected');
                            gameState.selectedPiece = null;
                        } else {
                            // Select dan deselect yang lama
                            if (gameState.selectedPiece) {
                                gameState.selectedPiece.classList.remove('selected');
                            }
                            gameState.selectedPiece = piece;
                            piece.classList.add('selected');
                        }
                    }
                });
                
                piecesContainer.appendChild(piece);
                
                gameState.pieces.push({
                    id: pieceId,
                    row: row,
                    col: col,
                    isUsed: false,
                    element: piece,
                    data: data,
                    slot: null // Menyimpan slot di mana piece berada
                });
            }
            
            // Shuffle the puzzle pieces
            function shufflePieces() {
                const piecesArray = Array.from(piecesContainer.children);
                
                // Fisher-Yates shuffle algorithm
                for (let i = piecesArray.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    piecesContainer.appendChild(piecesArray[j]);
                }
            }
            
            // Drag and Drop Handlers
            function handleDragStart(e) {
                if (gameState.isComplete) {
                    e.preventDefault();
                    return;
                }
                
                const piece = e.target.closest('.puzzle-piece');
                if (piece && piece.dataset.isUsed === 'false') {
                    e.dataTransfer.setData('text/plain', piece.id);
                    piece.classList.add('dragging');
                    
                    // Set drag image untuk preview yang lebih baik
                    const dragImage = piece.cloneNode(true);
                    dragImage.style.width = '150px';
                    dragImage.style.height = '150px';
                    dragImage.style.position = 'absolute';
                    dragImage.style.top = '-1000px';
                    document.body.appendChild(dragImage);
                    
                    e.dataTransfer.setDragImage(dragImage, 75, 75);
                    
                    // Clean up setelah drag selesai
                    setTimeout(() => {
                        document.body.removeChild(dragImage);
                    }, 0);
                    
                    // Store dragging piece globally untuk touch events
                    window.draggingPiece = piece;
                }
            }

            function handleTouchStart(e) {
                if (gameState.isComplete) return;
                
                const piece = e.target.closest('.puzzle-piece');
                if (piece && piece.dataset.isUsed === 'false') {
                    window.touchPiece = piece;
                    window.touchStartX = e.touches[0].clientX;
                    window.touchStartY = e.touches[0].clientY;
                    
                    piece.classList.add('dragging');
                }
            }

            function handleTouchMove(e) {
                if (!window.touchPiece) return;
                e.preventDefault();
                
                const touch = e.touches[0];
                const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
                const slot = elementBelow?.closest('.puzzle-slot');
                
                // Update visual feedback
                document.querySelectorAll('.puzzle-slot').forEach(s => {
                    s.classList.remove('hovered');
                });
                
                if (slot && !slot.classList.contains('filled')) {
                    slot.classList.add('hovered');
                    window.targetSlot = slot;
                }
            }

            function handleTouchEnd(e) {
                if (!window.touchPiece) return;
                
                const piece = window.touchPiece;
                const slot = window.targetSlot;
                
                piece.classList.remove('dragging');
                document.querySelectorAll('.puzzle-slot').forEach(s => {
                    s.classList.remove('hovered');
                });
                
                if (slot && !slot.classList.contains('filled')) {
                    placePieceInSlot(piece, slot);
                    updateGameState();
                }
                
                window.touchPiece = null;
                window.targetSlot = null;
            }
            
            function handleDragEnd(e) {
                const piece = e.target.closest('.puzzle-piece');
                if (piece) {
                    piece.classList.remove('dragging');
                }
            }
            
            function handleDragOver(e) {
                e.preventDefault();
            }
            
            function handleDragEnter(e) {
                e.preventDefault();
                const slot = e.target.closest('.puzzle-slot');
                if (slot && !slot.classList.contains('filled')) {
                    slot.classList.add('hovered');
                }
            }
            
            function handleDragLeave(e) {
                const slot = e.target.closest('.puzzle-slot');
                if (slot) {
                    slot.classList.remove('hovered');
                }
            }
            
            function handleDrop(e) {
                e.preventDefault();
                
                const slot = e.target.closest('.puzzle-slot');
                if (!slot || slot.classList.contains('filled')) {
                    return;
                }
                
                slot.classList.remove('hovered');
                
                const pieceId = e.dataTransfer.getData('text/plain');
                const piece = document.getElementById(pieceId);
                
                if (!piece || piece.dataset.isUsed === 'true') {
                    return;
                }
                
                // Place the piece in the slot
                placePieceInSlot(piece, slot);
                
                // Update game state
                updateGameState();
            }
            
            // Place a piece in a slot
            function placePieceInSlot(piece, slot) {
                // Mark piece as used
                piece.dataset.isUsed = 'true';
                piece.classList.add('used');
                piece.draggable = false;
                
                // Find the piece data
                const pieceIndex = gameState.pieces.findIndex(p => p.id === piece.id);
                if (pieceIndex === -1) return;
                
                const pieceData = gameState.pieces[pieceIndex];
                const pieceNumber = (pieceData.row * config.gridSize + pieceData.col) + 1;
                const slotRow = parseInt(slot.dataset.row);
                const slotCol = parseInt(slot.dataset.col);
                const slotNumber = (slotRow * config.gridSize + slotCol) + 1;
                
                // Create a copy of the piece for the slot
                const pieceCopy = document.createElement('div');
                pieceCopy.className = 'puzzle-piece-in-slot';
                pieceCopy.style.width = '100%';
                pieceCopy.style.height = '100%';
                pieceCopy.draggable = true;
                
                // Add drag events untuk piece di slot
                pieceCopy.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', piece.id);
                    this.classList.add('dragging');
                });
                
                pieceCopy.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                });

                // Add touch events untuk piece di slot (mobile)
                pieceCopy.addEventListener('touchstart', function(e) {
                    if (gameState.isComplete) return;
                    window.touchPiece = piece;
                    window.touchSlot = slot;
                    window.touchStartX = e.touches[0].clientX;
                    window.touchStartY = e.touches[0].clientY;
                    this.style.opacity = '0.7';
                }, { passive: false });

                pieceCopy.addEventListener('touchmove', function(e) {
                    if (!window.touchPiece) return;
                    e.preventDefault();
                    
                    const touch = e.touches[0];
                    const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
                    const targetSlot = elementBelow?.closest('.puzzle-slot');
                    
                    document.querySelectorAll('.puzzle-slot').forEach(s => {
                        s.classList.remove('hovered');
                    });
                    
                    if (targetSlot && targetSlot !== slot) {
                        if (!targetSlot.classList.contains('filled')) {
                            targetSlot.classList.add('hovered');
                            window.targetRemovalSlot = targetSlot;
                        }
                    }
                }, { passive: false });

                pieceCopy.addEventListener('touchend', function(e) {
                    this.style.opacity = '1';
                    
                    if (!window.touchPiece) return;
                    
                    const targetSlot = window.targetRemovalSlot;
                    document.querySelectorAll('.puzzle-slot').forEach(s => {
                        s.classList.remove('hovered');
                    });
                    
                    if (targetSlot && targetSlot !== slot) {
                        removePieceFromSlot(piece, slot);
                        setTimeout(() => {
                            placePieceInSlot(piece, targetSlot);
                            updateGameState();
                        }, 100);
                    }
                    
                    window.touchPiece = null;
                    window.targetRemovalSlot = null;
                }, { passive: false });
                
                // Tampilkan gambar atau emoji
                if (pieceData.data.emoji) {
                    // Jika emoji
                    pieceCopy.style.display = 'flex';
                    pieceCopy.style.alignItems = 'center';
                    pieceCopy.style.justifyContent = 'center';
                    pieceCopy.style.fontSize = '2.5rem';
                    pieceCopy.style.fontWeight = 'normal';
                    pieceCopy.style.background = pieceData.data.color || getRandomColor();
                    pieceCopy.textContent = pieceData.data.emoji;
                } else if (pieceData.data.imageUrl) {
                    // Jika ada gambar asli
                    pieceCopy.style.backgroundImage = `url('${pieceData.data.imageUrl}')`;
                    pieceCopy.style.backgroundSize = `${config.gridSize * 100}%`;
                    
                    // Hitung posisi background untuk potongan yang benar
                    const bgX = pieceData.col * (100 / (config.gridSize - 1));
                    const bgY = pieceData.row * (100 / (config.gridSize - 1));
                    pieceCopy.style.backgroundPosition = `${bgX}% ${bgY}%`;
                    
                    // Tampilkan juga nomor di atas gambar
                    const numberOverlay = document.createElement('div');
                    numberOverlay.style.position = 'absolute';
                    numberOverlay.style.top = '5px';
                    numberOverlay.style.left = '5px';
                    numberOverlay.style.background = 'rgba(59, 130, 246, 0.9)';
                    numberOverlay.style.color = 'white';
                    numberOverlay.style.width = '28px';
                    numberOverlay.style.height = '28px';
                    numberOverlay.style.borderRadius = '50%';
                    numberOverlay.style.display = 'flex';
                    numberOverlay.style.alignItems = 'center';
                    numberOverlay.style.justifyContent = 'center';
                    numberOverlay.style.fontSize = '0.9rem';
                    numberOverlay.style.fontWeight = 'bold';
                    numberOverlay.style.zIndex = '2';
                    numberOverlay.style.border = '2px solid white';
                    numberOverlay.textContent = pieceNumber;
                    pieceCopy.appendChild(numberOverlay);
                } else {
                    // Fallback
                    pieceCopy.style.display = 'flex';
                    pieceCopy.style.alignItems = 'center';
                    pieceCopy.style.justifyContent = 'center';
                    pieceCopy.style.fontSize = '1.8rem';
                    pieceCopy.style.fontWeight = 'bold';
                    pieceCopy.style.color = 'white';
                    pieceCopy.style.background = getRandomColor();
                    pieceCopy.textContent = pieceNumber;
                }
                
                // Tambahkan tombol remove
                const removeBtn = document.createElement('div');
                removeBtn.className = 'remove-btn';
                removeBtn.innerHTML = '✕';
                removeBtn.title = 'Kembalikan ke panel kanan';
                removeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    removePieceFromSlot(piece, slot);
                });
                pieceCopy.appendChild(removeBtn);
                
                // Clear the slot and add the piece
                slot.innerHTML = '';
                slot.appendChild(pieceCopy);
                slot.classList.add('filled');
                
                // Add drop animation
                pieceCopy.style.animation = 'dropPlace 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
                slot.style.animation = 'slotAccept 0.4s ease-out';
                
                // Tampilkan feedback apakah nomor cocok
                const feedbackElement = document.createElement('div');
                feedbackElement.style.position = 'absolute';
                feedbackElement.style.top = '50%';
                feedbackElement.style.left = '50%';
                feedbackElement.style.transform = 'translate(-50%, -50%)';
                feedbackElement.style.fontSize = '0.8rem';
                feedbackElement.style.fontWeight = 'bold';
                feedbackElement.style.padding = '2px 8px';
                feedbackElement.style.borderRadius = '10px';
                feedbackElement.style.zIndex = '3';
                
                if (pieceNumber === slotNumber) {
                    feedbackElement.style.background = '#10b981';
                    feedbackElement.style.color = 'white';
                    feedbackElement.textContent = '✓';
                    slot.style.borderColor = '#10b981';
                } else {
                    feedbackElement.style.background = '#ef4444';
                    feedbackElement.style.color = 'white';
                    feedbackElement.textContent = '✗';
                    slot.style.borderColor = '#ef4444';
                }
                
                pieceCopy.appendChild(feedbackElement);
                
                // Store which piece is in which slot
                const slotIndex = gameState.boardSlots.findIndex(s => s.id === slot.id);
                
                if (slotIndex !== -1) {
                    gameState.pieces[pieceIndex].isUsed = true;
                    gameState.pieces[pieceIndex].slot = slot;
                    gameState.boardSlots[slotIndex].pieceId = piece.id;
                    gameState.boardSlots[slotIndex].pieceCorrectRow = pieceData.row;
                    gameState.boardSlots[slotIndex].pieceCorrectCol = pieceData.col;
                    gameState.boardSlots[slotIndex].pieceElement = pieceCopy;
                    
                    gameState.placedPieces++;
                    
                    // Hilangkan feedback setelah 1 detik
                    setTimeout(() => {
                        feedbackElement.style.opacity = '0';
                        feedbackElement.style.transition = 'opacity 0.5s';
                        setTimeout(() => {
                            if (feedbackElement.parentNode) {
                                feedbackElement.remove();
                            }
                        }, 500);
                    }, 1000);
                }
            }
            
            // Remove piece from slot and return to panel
            function removePieceFromSlot(piece, slot) {
                // Find the piece data
                const pieceIndex = gameState.pieces.findIndex(p => p.id === piece.id);
                if (pieceIndex === -1) return;
                
                // Find the slot data
                const slotIndex = gameState.boardSlots.findIndex(s => s.id === slot.id);
                if (slotIndex === -1) return;
                
                // Reset piece state
                piece.dataset.isUsed = 'false';
                piece.classList.remove('used');
                piece.draggable = true;
                piece.style.display = 'block';
                
                // Reset slot state
                slot.classList.remove('filled');
                slot.innerHTML = '';
                
                // Add slot number back
                const slotNumber = document.createElement('div');
                slotNumber.className = 'slot-number';
                slotNumber.textContent = (parseInt(slot.dataset.row) * config.gridSize + parseInt(slot.dataset.col)) + 1;
                slot.appendChild(slotNumber);
                
                // Reset game state
                gameState.pieces[pieceIndex].isUsed = false;
                gameState.pieces[pieceIndex].slot = null;
                gameState.boardSlots[slotIndex].pieceId = null;
                gameState.boardSlots[slotIndex].pieceElement = null;
                
                // Move piece back to pieces container
                piecesContainer.appendChild(piece);
                
                gameState.placedPieces--;
                
                // Show feedback
                showFeedback('Potongan dikembalikan ke panel kanan');
            }

            // Update game state and check for completion
            function updateGameState() {
                // Check if all pieces are placed
                if (gameState.placedPieces === config.totalPieces) {
                    setTimeout(checkSolution, 300); // Small delay for visual effect
                }
            }
            
            // Check if the puzzle is solved correctly
            function checkSolution() {
                let correctCount = 0;
                
                for (const slot of gameState.boardSlots) {
                    const correctRow = parseInt(slot.element.dataset.row);
                    const correctCol = parseInt(slot.element.dataset.col);
                    
                    if (slot.pieceCorrectRow === correctRow && slot.pieceCorrectCol === correctCol) {
                        correctCount++;
                        slot.element.style.borderColor = '#10b981';
                        slot.element.style.background = 'rgba(16, 185, 129, 0.3)';
                        slot.element.style.boxShadow = '0 0 10px rgba(16, 185, 129, 0.5)';
                        
                        // Add success animation
                        slot.element.style.animation = 'pulse 0.5s';
                        setTimeout(() => {
                            slot.element.style.animation = '';
                        }, 500);
                    } else {
                        slot.element.style.borderColor = '#ef4444';
                        slot.element.style.background = 'rgba(239, 68, 68, 0.2)';
                        slot.element.style.boxShadow = '0 0 10px rgba(239, 68, 68, 0.5)';
                    }
                }
                
                // If all pieces are correct
                if (correctCount === config.totalPieces) {
                    setTimeout(completeGame, 800); // Delay untuk animasi
                } else {
                    // Show feedback
                    showFeedback(`${correctCount} dari ${config.totalPieces} potongan benar!`);
                }
            }
            
            // Complete the game
            function completeGame() {
                gameState.isComplete = true;
                
                // Show completion overlay
                setTimeout(() => {
                    completeOverlay.classList.add('show');
                    showConfetti();
                    
                    // Submit result to server
                    submitScore();
                }, 500);
            }
            
            // Submit result to server (no score/time sent)
            function submitScore() {
                // Prepare data to send
                const scoreData = {
                    pieces_correct: config.totalPieces,
                    total_pieces: config.totalPieces,
                    _token: "{{ csrf_token() }}"
                };
                
                // Send AJAX request
                fetch("{{ route('calista.level.puzzle.submit', ['slug' => $module->slug, 'level' => $level->id]) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(scoreData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Result submitted:', data);
                })
                .catch(error => {
                    console.error('Error submitting result:', error);
                });
            }
            
            // Show feedback message
            function showFeedback(message) {
                // Create feedback element
                const feedback = document.createElement('div');
                feedback.style.position = 'fixed';
                feedback.style.top = '100px';
                feedback.style.left = '50%';
                feedback.style.transform = 'translateX(-50%)';
                feedback.style.background = 'rgba(59, 130, 246, 0.9)';
                feedback.style.color = 'white';
                feedback.style.padding = '15px 25px';
                feedback.style.borderRadius = '50px';
                feedback.style.fontWeight = 'bold';
                feedback.style.zIndex = '1000';
                feedback.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
                feedback.textContent = message;
                
                document.body.appendChild(feedback);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    feedback.style.opacity = '0';
                    feedback.style.transition = 'opacity 0.5s';
                    setTimeout(() => {
                        document.body.removeChild(feedback);
                    }, 500);
                }, 3000);
            }
            
            // Show a hint (removed score penalty)
            function showHint() {
                if (gameState.isComplete) return;
                
                // Find first incorrect or unplaced piece
                for (const slot of gameState.boardSlots) {
                    if (!slot.pieceId) {
                        // Highlight the empty slot
                        slot.element.style.boxShadow = '0 0 0 3px #fbbf24';
                        slot.element.style.animation = 'pulse 1s 3';
                        
                        const correctRow = parseInt(slot.element.dataset.row);
                        const correctCol = parseInt(slot.element.dataset.col);
                        
                        const correctPiece = gameState.pieces.find(p => 
                            p.row === correctRow && p.col === correctCol && !p.isUsed
                        );
                        
                        if (correctPiece && correctPiece.element) {
                            correctPiece.element.style.boxShadow = '0 0 0 3px #fbbf24';
                            correctPiece.element.style.animation = 'pulse 1s 3';
                            setTimeout(() => {
                                correctPiece.element.style.boxShadow = '';
                                correctPiece.element.style.animation = '';
                            }, 3000);
                        }
                        
                        setTimeout(() => {
                            slot.element.style.boxShadow = '';
                            slot.element.style.animation = '';
                        }, 3000);
                        
                        break;
                    }
                }
            }

            // Reset the game
            function resetGame() {
                // simply re-init (removed timer clear)
                initGame();
            }

            // Show confetti effect
            function showConfetti() {
                confetti.classList.add('show');
                
                // Create confetti particles
                for (let i = 0; i < 200; i++) {
                    const particle = document.createElement('div');
                    particle.style.position = 'absolute';
                    particle.style.width = Math.random() * 15 + 5 + 'px';
                    particle.style.height = Math.random() * 15 + 5 + 'px';
                    particle.style.background = getRandomColor();
                    particle.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    particle.style.top = '-20px';
                    particle.style.left = Math.random() * 100 + 'vw';
                    particle.style.opacity = '0.9';
                    particle.style.zIndex = '1001';
                    
                    confetti.appendChild(particle);
                    
                    // Animate the particle
                    const animation = particle.animate([
                        { transform: 'translateY(0) rotate(0deg)', opacity: 0.9 },
                        { transform: `translateY(${window.innerHeight + 100}px) rotate(${Math.random() * 720}deg)`, opacity: 0 }
                    ], {
                        duration: Math.random() * 3000 + 2000,
                        easing: 'cubic-bezier(0.215, 0.61, 0.355, 1)'
                    });
                    
                    // Remove particle after animation
                    animation.onfinish = () => {
                        particle.remove();
                    };
                }
                
                // Hide confetti after 5 seconds
                setTimeout(() => {
                    confetti.classList.remove('show');
                    confetti.innerHTML = '';
                }, 5000);
            }
            
            // Helper function to generate random colors
            function getRandomColor() {
                const colors = [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                    '#8b5cf6', '#ec4899', '#14b8a6', '#f97316',
                    '#0ea5e9', '#84cc16', '#eab308', '#d946ef'
                ];
                return colors[Math.floor(Math.random() * colors.length)];
            }
            
            // Event Listeners
            resetBtn.addEventListener('click', resetGame);
            
            hintBtn.addEventListener('click', showHint);
            
            checkBtn.addEventListener('click', function() {
                if (gameState.placedPieces === config.totalPieces) {
                    checkSolution();
                } else {
                    showFeedback(`Masih ada ${config.totalPieces - gameState.placedPieces} potongan puzzle yang belum ditempatkan!`);
                }
            });
            
            nextLevelBtn.addEventListener('click', function() {
                // Navigate back to level page
                window.location.href = "{{ route('calista.level.show', ['slug' => $module->slug, 'level' => $level->id]) }}";
            });
            
            playAgainBtn.addEventListener('click', function() {
                completeOverlay.classList.remove('show');
                resetGame();
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'r' || e.key === 'R') {
                    resetGame();
                } else if (e.key === 'h' || e.key === 'H') {
                    showHint();
                } else if (e.key === ' ' && gameState.isComplete) {
                    completeOverlay.classList.remove('show');
                    resetGame();
                }
            });
        });
    </script>

    <script>
        // Keep music playing across navigation
        (function() {
            function requestPlayerState() {
                try {
                    const w = window.open('', 'calista_audio_player');
                    if (w && !w.closed) w.postMessage({ type: 'getState' }, '*');
                } catch (e) {}
            }
            document.addEventListener('DOMContentLoaded', requestPlayerState);
            document.addEventListener('visibilitychange', function() { if (!document.hidden) requestPlayerState(); });
            document.addEventListener('keydown', function(e) {
                if (e.key.toLowerCase() === 'm') {
                    try {
                        const muted = localStorage.getItem('bgMusicMuted') === '1';
                        const w = window.open('', 'calista_audio_player');
                        if (w && !w.closed) w.postMessage({ type: 'setMuted', value: !muted }, '*');
                        localStorage.setItem('bgMusicMuted', !muted ? '1' : '0');
                    } catch (err) {}
                }
            });
        })();
    </script>

    <script>
        // Play click sound on button/link interactions
        (function(){
            const CLICK_SRC = "{{ asset('storage/music/klik.mp3') }}";
            const clickAudio = new Audio(CLICK_SRC);
            clickAudio.preload = 'auto';
            function playClick() {
                try {
                    const snd = clickAudio.cloneNode();
                    snd.play().catch(()=>{});
                } catch(e){}
            }
            document.addEventListener('click', function(e){
                const el = e.target;
                const btn = el.closest('button, a, input[type="button"], input[type="submit"], .btn, .module-btn, .start-btn, .control-btn, .music-btn');
                if (btn && btn.dataset.noSound !== '1') playClick();
            }, true);
        })();
    </script>
</body>
</html>