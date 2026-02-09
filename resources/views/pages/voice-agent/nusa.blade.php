<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Nusa AI - Voice Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: transparent;
            min-height: 100vh;
            position: relative;
            cursor: default;
            overflow: hidden;
            font-family: 'Comic Sans MS', 'Chalkboard SE', 'Arial Rounded MT Bold', sans-serif;
        }

        /* ANIMATED CHARACTER - SAMA PERSIS DENGAN KODE ASLI */
        .character-wrapper {
            position: fixed;
            bottom: 30px;
            right: 0;
            transform: translateX(100%);
            z-index: 9999;
            width: 320px;
            height: 400px;
            cursor: grab;
            user-select: none;
            touch-action: none;
            transition: transform 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .character-wrapper.enter {
            transform: translateX(0);
            right: 20px;
        }

        .character-wrapper.exit {
            transform: translateX(100%);
            right: 0;
        }

        .character-wrapper.dragging {
            cursor: grabbing;
            z-index: 10000;
            transform: scale(1.05) !important;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
            transition: transform 0.1s ease-out !important;
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
            pointer-events: none;
        }

        .character-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            filter: drop-shadow(0 14px 30px rgba(0,0,0,0.22));
            animation: characterPulse 3.5s ease-in-out infinite;
            transform-origin: center center;
            transition: transform 250ms ease, filter 200ms ease;
        }

        .character-wrapper.dragging .character-image {
            animation: none;
            transform: scale(1.08);
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));
        }

        .character-image:hover {
            transform: scale(1.08);
        }

        @keyframes characterPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.06); }
        }

        /* VOICE AGENT DRAAGGABLE - SAMA PERSIS */
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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

        @keyframes slideDown {
            from { transform: translateX(-50%) translateY(-100%); opacity: 0; }
            to { transform: translateX(-50%) translateY(0); opacity: 1; }
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

        /* KOTAK TEKS DI ATAS PERUT KARAKTER - SAMA PERSIS */
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

        /* RESPONSIVE STYLES */
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
            
            .voice-agent-wrapper {
                width: 70px;
                height: 70px;
                bottom: 100px;
                right: 15px;
            }
            
            .voice-agent-icon {
                font-size: 1.5rem;
            }
            
            .voice-agent-status {
                bottom: 180px;
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
            .character-wrapper { 
                width: 180px; 
                height: 230px; 
                bottom: 15px;
            }
            
            .speech-bubble-abdomen { 
                width: 130px; 
                height: 45px; 
                top: 65%; 
                padding: 6px;
            }
            
            .abdomen-text { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <!-- ANIMATED CHARACTER -->
    <div class="character-wrapper" id="characterWrapper">
        <div id="characterContainer" class="character-container">
            <!-- Kotak teks di perut -->
            <div class="speech-bubble-abdomen" id="abdomenBubble">
                <div class="abdomen-text" id="abdomenText"></div>
            </div>
        </div>
    </div>

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

    <!-- RECORDING STATUS -->
    <div class="recording-status" id="recordingStatus">
        <i class="fas fa-circle" style="color: #ff6b6b;"></i>
        <span>Merekam...</span>
        <div class="recording-progress">
            <div class="recording-progress-fill" id="recordingProgressFill"></div>
        </div>
    </div>

    <!-- AUDIO FEEDBACK -->
    <div class="audio-feedback" id="audioFeedback">
        <i class="fas fa-volume-up"></i>
        <span id="feedbackText">Memutar audio...</span>
    </div>

    <!-- Hidden Audio Elements -->
    <audio id="audioPlayer" class="audio-player"></audio>
    <audio id="responseAudioPlayer" class="audio-player"></audio>

    <script>
        // ==================== DRAGGABLE ANIMATED CHARACTER ====================
        // CLASS CHARACTER SAMA PERSIS DENGAN KODE ASLI
        class DraggableAnimatedCharacter {
            constructor(containerId, options = {}) {
                this.container = document.getElementById(containerId);
                if (!this.container) return;

                // GANTI DENGAN URL GAMBAR ANDA YANG SAMA
                // Sesuaikan dengan path gambar di server Anda
                this.baseImageUrl = '/storage/AI/'; // Ubah sesuai kebutuhan
                
                this.images = {
                    idle: this.baseImageUrl + 'orangdiammelek.png',
                    blink: this.baseImageUrl + 'orangdiammerem.png',
                    talk: this.baseImageUrl + 'orangngomonga.png',
                    talk2: this.baseImageUrl + 'orangngomongi.png',
                    waveLeft: this.baseImageUrl + 'oranglambaitangana.png',
                    waveRight: this.baseImageUrl + 'oranglambaitangano.png',
                    lifted: this.baseImageUrl + 'orangngomongo.png'
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

                // Pastikan audio hallo.mp3 ada di path yang benar
                this.halloAudio = new Audio('/storage/music/hallo.mp3');
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
                    e.preventDefault();
                    const touch = e.touches[0];
                    this.startInteraction(touch);
                }, { passive: false });

                // Event untuk drag movement (mouse)
                document.addEventListener('mousemove', (e) => this.handleMove(e));
                
                // Event untuk drag movement (touch)
                document.addEventListener('touchmove', (e) => {
                    e.preventDefault();
                    const touch = e.touches[0];
                    this.handleMove(touch);
                }, { passive: false });

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

            handleMove(e) {
                if (!this.isDragging && this.isClick) {
                    const moveX = Math.abs(e.clientX - this.clickStartX);
                    const moveY = Math.abs(e.clientY - this.clickStartY);
                    
                    if (moveX > 5 || moveY > 5) {
                        this.startDrag({
                            clientX: this.clickStartX,
                            clientY: this.clickStartY
                        });
                        this.isClick = false;
                    }
                }
                
                if (this.isDragging) {
                    this.drag(e);
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
                        await this.halloAudio.play();
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

        // ==================== VOICE AGENT SYSTEM ====================
        // CLASS VOICE AGENT DENGAN ROUTES YANG SAMA PERSIS
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
                this.csrfToken = this.getCsrfToken();
                this.isAudioEnabled = true;
                
                // DOM Elements
                this.voiceAgentWrapper = document.getElementById('voiceAgentWrapper');
                this.voiceAgentButton = document.getElementById('voiceAgentButton');
                this.voiceAgentIcon = document.getElementById('voiceAgentIcon');
                this.voiceAgentDragArea = document.getElementById('voiceAgentDragArea');
                this.voiceAgentStatus = document.getElementById('voiceAgentStatus');
                this.statusText = document.getElementById('statusText');
                this.recordingStatus = document.getElementById('recordingStatus');
                this.recordingProgressFill = document.getElementById('recordingProgressFill');
                this.audioFeedback = document.getElementById('audioFeedback');
                this.feedbackText = document.getElementById('feedbackText');
                this.audioPlayer = document.getElementById('audioPlayer');
                this.responseAudioPlayer = document.getElementById('responseAudioPlayer');
                
                // Drag variables
                this.startX = 0;
                this.startY = 0;
                this.initialX = 0;
                this.initialY = 0;
                this.isDrag = false;
                this.dragTimeout = null;
                
                this.init();
            }
            
            getCsrfToken() {
                // Mengambil CSRF token dari meta tag jika ada
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) {
                    return csrfMeta.getAttribute('content');
                }
                
                // Jika tidak ada meta tag, coba dari cookie
                const cookieMatch = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
                if (cookieMatch) {
                    return decodeURIComponent(cookieMatch[1]);
                }
                
                console.warn('CSRF token tidak ditemukan, menggunakan dummy token untuk demo');
                return 'dummy-csrf-token-for-demo';
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
                    this.updateStatus('Mikrofon tidak tersedia');
                    return;
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
                    this.updateStatus('❌ Gagal mengakses mikrofon');
                    this.isMicrophoneAvailable = false;
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
                } else {
                    this.updateStatus('❌ Tidak ada suara yang direkam');
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
            
            // ROUTES YANG SAMA PERSIS DENGAN KODE ASLI
            async sendWAVToServer(wavBlob) {
                const formData = new FormData();
                formData.append('audio', wavBlob, 'recording.wav');
                formData.append('user_id', 'user_' + (this.getUserId() || 'guest'));
                formData.append('context', 'voice_assistant');
                formData.append('audio_format', 'wav');
                
                try {
                    const response = await fetch('/voice-agent/process-voice', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrfToken },
                        body: formData
                    });
                    
                    if (response.ok) {
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.includes('audio')) {
                            const audioBlob = await response.blob();
                            const audioUrl = URL.createObjectURL(audioBlob);
                            
                            this.updateStatus('🎵 AI merespons');
                            
                            if (this.isAudioEnabled) {
                                this.playResponseAudio(audioUrl);
                            }
                            
                        } else {
                            const data = await response.json();
                            if (data.text) {
                                this.updateStatus('AI: ' + data.text.substring(0, 50) + '...');
                                this.addToAudioQueue(data.text);
                            }
                        }
                    } else {
                        this.updateStatus('❌ Server error');
                    }
                    
                } catch (error) {
                    console.error('Send error:', error);
                    this.updateStatus('❌ Gagal mengirim ke server');
                }
            }
            
            getUserId() {
                // Coba ambil user ID dari data attribute atau localStorage
                const userId = localStorage.getItem('user_id') || 'guest';
                return userId;
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
                    return;
                }
                
                this.isPlayingAudio = true;
                const text = this.audioQueue.shift();
                
                try {
                    // Menggunakan route yang sama persis dengan kode asli
                    const response = await fetch('/voice-agent/text-to-speech', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({ 
                            text: text,
                            voice: 'id-ID-Standard-A',
                            speaking_rate: 0.9,
                            pitch: 0.2
                        })
                    });
                    
                    if (response.ok) {
                        const audioBlob = await response.blob();
                        const audioUrl = URL.createObjectURL(audioBlob);
                        
                        this.audioPlayer.src = audioUrl;
                        this.audioPlayer.volume = this.globalVolume;
                        
                        this.audioPlayer.onended = () => {
                            URL.revokeObjectURL(audioUrl);
                            setTimeout(() => this.playNextAudio(), 500);
                        };
                        
                        this.audioPlayer.onerror = () => {
                            URL.revokeObjectURL(audioUrl);
                            this.playNextAudio();
                        };
                        
                        this.audioPlayer.play().then(() => {
                            this.showAudioFeedback(text.substring(0, 50));
                        }).catch(e => {
                            console.log('Audio play skipped:', e);
                            URL.revokeObjectURL(audioUrl);
                            this.playNextAudio();
                        });
                    } else {
                        this.playNextAudio();
                    }
                } catch (error) {
                    console.error('TTS error:', error);
                    this.playNextAudio();
                }
            }
            
            playResponseAudio(audioUrl) {
                if (!this.isAudioEnabled) return;
                
                if (this.audioPlayer.src) {
                    this.audioPlayer.pause();
                    this.audioPlayer.currentTime = 0;
                }
                
                this.responseAudioPlayer.src = audioUrl;
                this.responseAudioPlayer.volume = this.globalVolume;
                this.responseAudioPlayer.play().catch(e => {
                    console.log('Response audio play skipped');
                    URL.revokeObjectURL(audioUrl);
                });
                
                this.responseAudioPlayer.onended = () => {
                    URL.revokeObjectURL(audioUrl);
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
            
            async checkMicrophonePermission() {
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const hasMicrophone = devices.some(device => device.kind === 'audioinput');
                    
                    if (!hasMicrophone) {
                        this.updateStatus('Tidak ada mikrofon terdeteksi');
                        this.isMicrophoneAvailable = false;
                        return;
                    }
                    
                    const permissionStatus = await navigator.permissions.query({ name: 'microphone' });
                    this.isMicrophoneAvailable = permissionStatus.state === 'granted';
                    
                } catch (error) {
                    console.error('Permission check error:', error);
                    this.isMicrophoneAvailable = false;
                }
            }
            
            // Fungsi untuk memainkan instruksi umum
            playInstructions(text) {
                if (!this.isAudioEnabled) return;
                
                this.addToAudioQueue(text);
            }
        }

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function() {
            let voiceAgentSystem = null;
            let character = null;
            
            try {
                voiceAgentSystem = new VoiceAgentSystem();
                window.voiceAgentSystem = voiceAgentSystem;
                
                // Initialize character
                setTimeout(() => {
                    try {
                        character = new DraggableAnimatedCharacter('characterContainer');
                        window.character = character;
                        
                        // Enter character
                        setTimeout(() => {
                            if (character) {
                                character.enterFromRight();
                            }
                        }, 1500);
                        
                    } catch (error) {
                        console.error('Failed to initialize character:', error);
                    }
                }, 1000);
                
                // Show welcome message
                setTimeout(() => {
                    if (voiceAgentSystem) {
                        voiceAgentSystem.updateStatus('Nusa AI siap membantu!');
                        
                        // Play welcome message
                        setTimeout(() => {
                            voiceAgentSystem.playInstructions('Halo! Saya Nusa AI. Klik tombol mikrofon untuk berbicara dengan saya!');
                        }, 2000);
                    }
                }, 3000);
                
            } catch (error) {
                console.error('Failed to initialize voice agent:', error);
            }
            
            // Setup keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.key.toLowerCase() === 'r' && voiceAgentSystem) {
                    voiceAgentSystem.toggleRecording();
                }
                if (e.key.toLowerCase() === ' ') {
                    if (voiceAgentSystem && voiceAgentSystem.isRecording) {
                        voiceAgentSystem.toggleRecording();
                    }
                }
            });

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
                if (e.target.classList.contains('character-wrapper') ||
                    e.target.classList.contains('voice-agent-wrapper')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>