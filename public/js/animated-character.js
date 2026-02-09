// ==================== ANIMATED CHARACTER CLASS (VERSION 4 - WITH VOICE) ====================
class AnimatedCharacter {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error(`Container with id "${containerId}" not found`);
            return;
        }

        this.images = {
            idle: options.images?.idle || '{{ asset("storage/AI/orangdiammelek.png") }}',
            blink: options.images?.blink || '{{ asset("storage/AI/orangdiammerem.png") }}',
            talk: options.images?.talk || '{{ asset("storage/AI/orangngomonga.png") }}'
        };

        this.settings = {
            blinkInterval: options.blinkInterval || 3000,
            talkSpeed: options.talkSpeed || 200,
            autoBlink: options.autoBlink !== false,
            abdomenMessages: options.abdomenMessages || ["Hallo!", "Semangat aja!"],
            abdomenDisplayTime: options.abdomenDisplayTime || 2400,
            useVoice: options.useVoice !== false,
            voiceApiUrl: options.voiceApiUrl || '/api/generate-audio',
            voiceEmotion: options.voiceEmotion || 'normal',
            voiceIntensity: options.voiceIntensity || 1.0,
            voiceLanguage: options.voiceLanguage || 'ind',
            ...options
        };

        this.isTalking = false;
        this.talkInterval = null;
        this.blinkInterval = null;
        this.isMouthOpen = false;
        this.isBlinking = false;
        this.topBubble = null;
        this.isEntered = false;
        this.abdomenBubble = null;
        this.abdomenTextElement = null;
        this.abdomenLoopInterval = null;
        this.abdomenTimeout = null;
        this.audio = null;
        this.currentAudio = null;
        this.audioQueue = [];
        this.isProcessingAudio = false;

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
        
        // Set initial state - SELALU IDLE (DIEM)
        this.setImage(this.images.idle);
        this.container.appendChild(this.imgElement);

        // Add click event
        this.imgElement.addEventListener('click', () => {
            this.speak("Hai! Ayo belajar menulis dengan baik!");
        });

        // Start auto blink if enabled
        if (this.settings.autoBlink) {
            this.startAutoBlink();
        }

        // try to bind abdomen elements if present
        this.abdomenBubble = document.getElementById('abdomenBubble');
        this.abdomenTextElement = document.getElementById('abdomenText');

        // Create audio element for playing voice
        this.audio = document.createElement('audio');
        this.audio.style.display = 'none';
        document.body.appendChild(this.audio);

        // Listen for audio events
        this.audio.addEventListener('ended', () => {
            this.stopTalking();
            this.processAudioQueue();
        });

        this.audio.addEventListener('error', (e) => {
            console.error('Audio playback error:', e);
            this.stopTalking();
            this.processAudioQueue();
        });

        console.log('Character initialized with voice support:', this.container.id);
    }

    enterFromRight() {
        if (this.isEntered) return;
        
        const wrapper = document.getElementById('characterWrapper');
        if (!wrapper) return;
        
        // Reset posisi awal
        wrapper.classList.remove('enter');
        wrapper.classList.remove('exit');
        
        // Trigger reflow
        void wrapper.offsetWidth;
        
        // Animasi masuk dari kanan
        setTimeout(() => {
            wrapper.classList.add('enter');
            this.isEntered = true;
            setTimeout(() => {
                // start abdomen loop if bubble exists
                if (this.abdomenBubble && this.abdomenTextElement) {
                    this.startAbdomenLoop();
                }
                this.speak("Halo! Ayo belajar menulis " + (typeof targetText !== 'undefined' ? targetText : '') + "!");
            }, 800);
        }, 1000);
    }

    showAbdomenText(text, withAnimation = true) {
        if (!this.abdomenBubble || !this.abdomenTextElement) return;
        if (this.abdomenTimeout) { clearTimeout(this.abdomenTimeout); this.abdomenTimeout = null; }
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
                    this.abdomenTimeout = setTimeout(()=>{}, this.settings.abdomenDisplayTime);
                }
            };
            typeWriter();
        } else {
            this.abdomenTextElement.textContent = text;
            this.abdomenTimeout = setTimeout(()=>{}, this.settings.abdomenDisplayTime);
        }
    }

    hideAbdomenText() {
        if (this.abdomenBubble) {
            if (this.abdomenTimeout) { clearTimeout(this.abdomenTimeout); this.abdomenTimeout = null; }
            this.abdomenBubble.classList.remove('show');
            if (this.abdomenTextElement) {
                this.abdomenTextElement.textContent = '';
                this.abdomenTextElement.classList.remove('typing');
            }
        }
    }

    startAbdomenLoop() {
        this.stopAbdomenLoop();
        let idx = 0;
        const showNext = () => {
            const msg = this.settings.abdomenMessages[idx % this.settings.abdomenMessages.length];
            this.showAbdomenText(msg, true);
            idx++;
        };
        showNext();
        this.abdomenLoopInterval = setInterval(showNext, this.settings.abdomenDisplayTime + 1200);
    }

    stopAbdomenLoop() {
        if (this.abdomenLoopInterval) { clearInterval(this.abdomenLoopInterval); this.abdomenLoopInterval = null; }
        if (this.abdomenTimeout) { clearTimeout(this.abdomenTimeout); this.abdomenTimeout = null; }
    }

    setImage(src) {
        if (this.imgElement.src !== src) {
            this.imgElement.src = src;
        }
    }

    blink() {
        if (this.isBlinking) return;
        
        this.isBlinking = true;
        
        // Set to blink image (mata tertutup)
        this.setImage(this.images.blink);
        
        // After blink duration, return to idle
        setTimeout(() => {
            this.isBlinking = false;
            this.setImage(this.images.idle); // SELALU KEMBALI KE IDLE
        }, 150);
    }

    startAutoBlink() {
        this.blinkInterval = setInterval(() => {
            // Don't blink if already blinking
            if (!this.isBlinking) {
                this.blink();
            }
        }, this.settings.blinkInterval);
    }

    // NEW: Speak with voice
    async speak(text, options = {}) {
        // Show speech bubble
        this.showSpeech(text);
        
        // Add to queue
        this.audioQueue.push({
            text: text,
            options: options
        });
        
        // Process queue if not already processing
        if (!this.isProcessingAudio) {
            await this.processAudioQueue();
        }
    }

    // NEW: Generate and play audio
    async generateAndPlayAudio(text, options = {}) {
        if (!this.settings.useVoice) {
            // Fallback to text-only if voice is disabled
            const duration = options.duration || 2000;
            setTimeout(() => {
                this.hideSpeech();
            }, duration);
            return;
        }

        try {
            this.startTalking();
            
            const emotion = options.emotion || this.settings.voiceEmotion;
            const intensity = options.intensity || this.settings.voiceIntensity;
            const language = options.language || this.settings.voiceLanguage;

            console.log('Generating audio for:', text);
            
            const response = await fetch(this.settings.voiceApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    text: text,
                    emotion: emotion,
                    intensity: intensity,
                    language: language
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Get audio blob
            const audioBlob = await response.blob();
            const audioUrl = URL.createObjectURL(audioBlob);
            
            // Stop any current audio
            if (this.currentAudio) {
                this.currentAudio.pause();
                this.currentAudio = null;
            }
            
            // Create new audio element
            this.currentAudio = new Audio(audioUrl);
            this.currentAudio.volume = 1.0;
            
            // Play audio
            this.currentAudio.play();
            
            // Clean up URL after playing
            this.currentAudio.addEventListener('ended', () => {
                URL.revokeObjectURL(audioUrl);
                this.stopTalking();
                this.hideSpeech();
            });
            
            this.currentAudio.addEventListener('error', (e) => {
                console.error('Audio playback error:', e);
                URL.revokeObjectURL(audioUrl);
                this.stopTalking();
                this.hideSpeech();
                
                // Fallback to text-only on error
                const duration = options.duration || 2000;
                setTimeout(() => {
                    this.hideSpeech();
                }, duration);
            });

        } catch (error) {
            console.error('Error generating/playing audio:', error);
            this.stopTalking();
            
            // Fallback to text-only
            const duration = options.duration || 2000;
            setTimeout(() => {
                this.hideSpeech();
            }, duration);
        }
    }

    // NEW: Process audio queue
    async processAudioQueue() {
        if (this.isProcessingAudio || this.audioQueue.length === 0) {
            return;
        }

        this.isProcessingAudio = true;
        
        while (this.audioQueue.length > 0) {
            const { text, options } = this.audioQueue.shift();
            await this.generateAndPlayAudio(text, options);
            
            // Wait for audio to finish before playing next
            if (this.currentAudio) {
                await new Promise(resolve => {
                    const checkAudio = () => {
                        if (!this.currentAudio || this.currentAudio.paused || this.currentAudio.ended) {
                            resolve();
                        } else {
                            setTimeout(checkAudio, 100);
                        }
                    };
                    checkAudio();
                });
            }
        }
        
        this.isProcessingAudio = false;
    }

    // NEW: Legacy say method (text-only)
    say(text, duration = 2000) {
        this.showSpeech(text);
        
        // TIDAK ADA ANIMASI MULUT BICARA, HANYA BUBBLE
        setTimeout(() => {
            this.hideSpeech();
        }, duration);
    }

    showSpeech(text) {
        // Remove existing bubble if any
        if (this.topBubble) {
            this.topBubble.remove();
        }
        
        // Create new speech bubble at top
        this.topBubble = document.createElement('div');
        this.topBubble.className = 'speech-bubble-top';
        this.topBubble.textContent = text;
        this.container.appendChild(this.topBubble);
        
        // Show with animation
        setTimeout(() => {
            this.topBubble.classList.add('show');
        }, 10);
    }

    hideSpeech() {
        if (this.topBubble) {
            this.topBubble.classList.remove('show');
            setTimeout(() => {
                if (this.topBubble && this.topBubble.parentNode) {
                    this.topBubble.parentNode.removeChild(this.topBubble);
                    this.topBubble = null;
                }
            }, 300);
        }
    }

    // NEW: Start talking animation
    startTalking() {
        this.isTalking = true;
        this.setImage(this.images.talk);
        
        // Blink animation while talking
        if (this.talkInterval) {
            clearInterval(this.talkInterval);
        }
        
        this.talkInterval = setInterval(() => {
            this.setImage(this.isMouthOpen ? this.images.talk : this.images.idle);
            this.isMouthOpen = !this.isMouthOpen;
        }, this.settings.talkSpeed);
    }

    // NEW: Stop talking animation
    stopTalking() {
        this.isTalking = false;
        if (this.talkInterval) {
            clearInterval(this.talkInterval);
            this.talkInterval = null;
        }
        this.setImage(this.images.idle);
        
        // Stop any playing audio
        if (this.currentAudio) {
            this.currentAudio.pause();
            this.currentAudio = null;
        }
    }

    destroy() {
        this.stopTalking();
        this.stopAbdomenLoop();
        if (this.blinkInterval) {
            clearInterval(this.blinkInterval);
        }
        if (this.imgElement && this.imgElement.parentNode) {
            this.imgElement.parentNode.removeChild(this.imgElement);
        }
        if (this.audio && this.audio.parentNode) {
            this.audio.parentNode.removeChild(this.audio);
        }
        this.hideSpeech();
        this.hideAbdomenText();
        
        // Clear audio queue
        this.audioQueue = [];
        this.isProcessingAudio = false;
    }
}

// ==================== MODIFIKASI INISIALISASI ====================
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan CSRF token tersedia
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.warn('CSRF token not found. Voice features may not work.');
    }

    // Initialize character dengan delay
    setTimeout(() => {
        try {
            window.character = new AnimatedCharacter('characterContainer', {
                blinkInterval: 2500,
                talkSpeed: 200,
                autoBlink: true,
                useVoice: true, // Aktifkan suara
                voiceApiUrl: '/api/generate-audio',
                voiceEmotion: 'normal',
                voiceIntensity: 1.0,
                voiceLanguage: 'ind',
                images: {
                    idle: '{{ asset("storage/AI/orangdiammelek.png") }}',
                    blink: '{{ asset("storage/AI/orangdiammerem.png") }}',
                    talk: '{{ asset("storage/AI/orangngomonga.png") }}'
                }
            });
            
            // Karakter masuk dari kanan setelah 1 detik
            setTimeout(() => {
                if (window.character) {
                    window.character.enterFromRight();
                }
            }, 1000);
            
            // Setup drawing encouragement
            if (typeof canvas !== 'undefined') {
                canvas.addEventListener('mouseup', onDrawingStop);
                canvas.addEventListener('touchend', onDrawingStop);
            }
            
        } catch (error) {
            console.error('Failed to initialize character:', error);
        }
    }, 1500);
});

function onDrawingStop() {
    if (typeof drawingState !== 'undefined' && drawingState.totalStrokes > 3 && Math.random() > 0.6 && window.character) {
        const messages = [
            "Bagus! Teruskan menulisnya!",
            "Wah, tulisanmu semakin rapi!",
            "Hebat! Ayo teruskan!",
            "Keren! Perhatikan bentuk hurufnya ya!",
            "Mantap! Skormu sudah " + (Math.round(drawingState.score) || 0) + "%!"
        ];
        
        const randomMessage = messages[Math.floor(Math.random() * messages.length)];
        
        setTimeout(() => {
            if (window.character) {
                window.character.speak(randomMessage, { emotion: 'excited', intensity: 1.2 });
            }
        }, 500);
    }
}

// ==================== FUNGSI UTILITAS TAMBAHAN ====================
/**
 * Fungsi untuk menguji suara
 */
function testVoice() {
    if (window.character) {
        window.character.speak("Halo! Ini adalah uji suara dari Typecast API.");
    }
}

/**
 * Fungsi untuk mengucapkan pesan dengan emosi tertentu
 */
function speakWithEmotion(text, emotion = 'normal', intensity = 1.0) {
    if (window.character) {
        window.character.speak(text, { emotion: emotion, intensity: intensity });
    }
}

/**
 * Fungsi untuk mengantri pesan
 */
function queueSpeak(text, delay = 0) {
    if (window.character) {
        if (delay > 0) {
            setTimeout(() => {
                window.character.speak(text);
            }, delay);
        } else {
            window.character.speak(text);
        }
    }
}