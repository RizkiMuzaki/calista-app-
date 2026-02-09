<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calista Voice Agent - Bicara dengan AI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .voice-wave {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60px;
        }
        
        .voice-wave div {
            width: 6px;
            height: 20px;
            background: #4f46e5;
            margin: 0 3px;
            border-radius: 10px;
            animation: wave 1.2s infinite ease-in-out;
        }
        
        .voice-wave div:nth-child(2) {
            animation-delay: -1.1s;
        }
        
        .voice-wave div:nth-child(3) {
            animation-delay: -1.0s;
        }
        
        .voice-wave div:nth-child(4) {
            animation-delay: -0.9s;
        }
        
        .voice-wave div:nth-child(5) {
            animation-delay: -0.8s;
        }
        
        @keyframes wave {
            0%, 40%, 100% {
                transform: scaleY(0.4);
            }
            20% {
                transform: scaleY(1);
            }
        }
        
        .chat-bubble {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            margin-bottom: 10px;
            position: relative;
            animation: fadeIn 0.3s ease-in;
        }
        
        .user-bubble {
            background: #4f46e5;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        
        .ai-bubble {
            background: white;
            color: #333;
            margin-right: auto;
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        
        .recording {
            animation: recording 0.5s infinite alternate;
        }
        
        @keyframes recording {
            from { opacity: 0.7; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Calista Voice Agent</h1>
                        <p class="text-gray-600">Bicara dengan AI yang ramah anak</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <div class="flex items-center">
                                <i class="fas fa-child text-indigo-500 mr-2"></i>
                                <span class="text-sm">{{ $activeAnak->name ?? 'Anak' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>
                                <span class="text-sm">Usia: {{ $activeAnak->age ?? '5' }} tahun</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div id="connectionStatus" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle text-green-500 mr-2 text-xs"></i>
                        <span>Terhubung</span>
                    </div>
                    <button id="helpBtn" class="ml-3 p-2 text-gray-500 hover:text-indigo-600">
                        <i class="fas fa-question-circle text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Panel - Kontrol -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Kontrol Suara</h2>
                    
                    <!-- Recording Button -->
                    <div class="mb-6">
                        <button id="recordBtn" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold text-lg hover:opacity-90 transition-all duration-300 pulse">
                            <i class="fas fa-microphone mr-2"></i>
                            <span id="recordText">TEKAN UNTUK BICARA</span>
                        </button>
                        <p class="text-sm text-gray-500 text-center mt-2">Tahan tombol saat bicara, lepaskan untuk mengirim</p>
                        
                        <!-- Recording Status -->
                        <div id="recordingStatus" class="hidden mt-2 text-center">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-circle text-red-500 mr-2 text-xs recording"></i>
                                <span class="text-sm font-medium">Merekam...</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Voice Wave Visualizer -->
                    <div id="voiceVisualizer" class="voice-wave mb-6 hidden">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    
                    <!-- Microphone Permission -->
                    <div id="permissionAlert" class="hidden mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">Izin mikrofon diperlukan</p>
                                <button id="requestPermissionBtn" class="mt-1 text-sm text-yellow-700 hover:text-yellow-900">
                                    Klik untuk memberikan izin
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Age Group Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Usia Anak</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button class="age-btn py-2 rounded-lg border border-gray-300 text-center hover:bg-indigo-50 transition-colors" data-age="3-5">3-5 tahun</button>
                            <button class="age-btn py-2 rounded-lg border border-gray-300 text-center hover:bg-indigo-50 transition-colors active bg-indigo-600 text-white" data-age="5-7">5-7 tahun</button>
                            <button class="age-btn py-2 rounded-lg border border-gray-300 text-center hover:bg-indigo-50 transition-colors" data-age="7-9">7-9 tahun</button>
                            <button class="age-btn py-2 rounded-lg border border-gray-300 text-center hover:bg-indigo-50 transition-colors" data-age="9-12">9-12 tahun</button>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aksi Cepat</label>
                        <div class="space-y-2">
                            <button class="quick-action w-full py-3 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors" data-text="Halo Calista">
                                <i class="fas fa-hand-wave mr-2"></i>Sapa Calista
                            </button>
                            <button class="quick-action w-full py-3 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors" data-text="Ceritakan sebuah cerita">
                                <i class="fas fa-book-open mr-2"></i>Minta Cerita
                            </button>
                            <button class="quick-action w-full py-3 rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100 transition-colors" data-text="Ajariku berhitung">
                                <i class="fas fa-calculator mr-2"></i>Belajar Berhitung
                            </button>
                            <button class="quick-action w-full py-3 rounded-lg bg-pink-50 text-pink-700 hover:bg-pink-100 transition-colors" data-text="Apa warna kesukaanmu?">
                                <i class="fas fa-palette mr-2"></i>Tanya Warna
                            </button>
                        </div>
                    </div>
                    
                    <!-- Audio Controls -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontrol Audio</label>
                        <div class="flex items-center justify-between bg-gray-100 p-3 rounded-lg">
                            <button id="playPauseBtn" class="p-3 bg-white rounded-full shadow hover:shadow-md transition-shadow">
                                <i class="fas fa-play text-indigo-600"></i>
                            </button>
                            <div class="flex-1 mx-4">
                                <div class="text-sm text-gray-500">Volume</div>
                                <input type="range" id="volumeSlider" min="0" max="100" value="80" class="w-full">
                            </div>
                            <button id="repeatBtn" class="p-3 bg-white rounded-full shadow hover:shadow-md transition-shadow">
                                <i class="fas fa-redo text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Test Buttons -->
                    <div class="border-t border-gray-200 pt-4">
                        <button id="testMicrophoneBtn" class="w-full py-2 rounded-lg border border-blue-300 text-blue-600 hover:bg-blue-50 transition-colors mb-2">
                            <i class="fas fa-microphone-alt mr-2"></i>Test Mikrofon
                        </button>
                        <button id="testAudioBtn" class="w-full py-2 rounded-lg border border-green-300 text-green-600 hover:bg-green-50 transition-colors">
                            <i class="fas fa-volume-up mr-2"></i>Test Suara Calista
                        </button>
                    </div>
                </div>
                
                <!-- Status Panel -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Status</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status Koneksi:</span>
                            <span id="serverStatus" class="font-medium">Mengecek...</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pesan Terkirim:</span>
                            <span id="messageCount" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Waktu Respon:</span>
                            <span id="responseTime" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Usia Terpilih:</span>
                            <span id="selectedAge" class="font-medium">5-7 tahun</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mikrofon:</span>
                            <span id="microphoneStatus" class="font-medium text-green-600">Siap</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Format Audio:</span>
                            <span id="audioFormat" class="font-medium text-blue-600">WAV</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                        <button id="clearHistoryBtn" class="w-full py-2 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus Riwayat
                        </button>
                        <a href="{{ route('voice-agent.index') }}" class="block w-full py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Chat -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Percakapan dengan Calista</h2>
                        <div class="flex items-center space-x-2">
                            <button id="toggleAudio" class="p-2 text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                <i class="fas fa-volume-up"></i>
                            </button>
                            <div class="relative">
                                <button id="settingsBtn" class="p-2 text-gray-500 hover:text-indigo-600">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div id="settingsMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-10">
                                    <div class="p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm">Auto Play</span>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" id="autoPlayToggle" class="sr-only peer" checked>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm">Show Timestamps</span>
                                            <input type="checkbox" id="showTimestamps" class="rounded text-indigo-600" checked>
                                        </div>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm">Audio Format:</span>
                                            <span class="text-sm font-medium text-blue-600">WAV</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Container -->
                    <div id="chatContainer" class="flex-grow overflow-y-auto mb-6 p-4 bg-gray-50 rounded-xl max-h-[500px]">
                        <!-- Initial greeting -->
                        <div class="chat-bubble ai-bubble">
                            <div class="font-semibold text-indigo-600 mb-1">
                                <i class="fas fa-robot mr-2"></i>Calista
                            </div>
                            <p>Halo! Saya Calista, teman belajarmu yang ceria! Bicaralah padaku dengan menekan tombol mikrofon.</p>
                            <div class="text-xs text-gray-500 mt-2" data-timestamp="{{ now()->format('H:i') }}">Sekarang</div>
                        </div>
                    </div>
                    
                    <!-- Text Input (Fallback) -->
                    <div class="mt-4">
                        <div class="flex">
                            <input type="text" id="textInput" placeholder="Ketik pesan di sini..." 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   autocomplete="off">
                            <button id="sendTextBtn" class="px-6 bg-indigo-600 text-white rounded-r-xl hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Atau gunakan input teks jika tidak bisa menggunakan mikrofon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Player (Hidden) -->
    <audio id="audioPlayer" controls class="hidden"></audio>
    
    <!-- Test Audio Player -->
    <audio id="testAudioPlayer" class="hidden"></audio>
    
    <!-- Microphone Test Modal -->
    <div id="micTestModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Test Mikrofon</h3>
                <button id="closeMicTestModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center">
                    <i class="fas fa-microphone text-indigo-600 text-3xl"></i>
                </div>
                <p class="text-gray-600 mb-6">Klik tombol di bawah dan ucapkan sesuatu untuk menguji mikrofon.</p>
                <div class="space-y-4">
                    <button id="startMicTestBtn" class="w-full py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-record-vinyl mr-2"></i>Mulai Test
                    </button>
                    <button id="playbackTestBtn" class="w-full py-3 rounded-lg border border-indigo-600 text-indigo-600 hover:bg-indigo-50 transition-colors hidden">
                        <i class="fas fa-play mr-2"></i>Dengarkan Rekaman
                    </button>
                </div>
                <div id="micTestStatus" class="mt-6 text-sm text-gray-500"></div>
            </div>
        </div>
    </div>
    
    <!-- Help Modal -->
    <div id="helpModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Bantuan Voice Agent</h3>
                <button id="closeHelpModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Cara Menggunakan:</h4>
                    <ol class="list-decimal pl-5 space-y-2 text-gray-600">
                        <li>Klik tombol <span class="font-semibold">"TEKAN UNTUK BICARA"</span> (tombol ungu besar)</li>
                        <li>Tahan tombol sambil berbicara ke mikrofon</li>
                        <li>Lepaskan tombol untuk mengirim suara</li>
                        <li>Calista akan merespons dengan suara</li>
                    </ol>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Tips Suara Jernih:</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Gunakan ruangan yang tenang</li>
                        <li>Jaga jarak mikrofon 15-30 cm dari mulut</li>
                        <li>Bicaralah dengan kecepatan normal dan jelas</li>
                        <li>Hindari suara latar yang keras</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Format Audio:</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Format rekaman: WAV (kualitas terbaik)</li>
                        <li>Sample rate: 16kHz</li>
                        <li>Channel: Mono</li>
                        <li>Bit depth: 16-bit</li>
                    </ul>
                </div>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-gray-600 text-sm">Untuk bantuan lebih lanjut, hubungi tim support kami.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Processing Indicator -->
    <div id="processingIndicator" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center">
                <i class="fas fa-spinner fa-spin text-indigo-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Memproses Suara</h3>
            <p class="text-gray-600">Calista sedang mendengarkan dan memproses ucapan Anda...</p>
            <div class="mt-4">
                <div class="voice-wave">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Konfigurasi
        const API_BASE_URL = 'http://localhost:5000';
        const LARAVEL_BASE_URL = '/voice-agent';
        let selectedAgeGroup = '5-7';
        let isRecording = false;
        let mediaRecorder = null;
        let audioChunks = [];
        let autoPlayAudio = true;
        let messageCount = 0;
        let currentAudioUrl = null;
        let microphoneStream = null;
        let isMicrophoneAvailable = false;
        let audioContext = null;
        let analyser = null;
        let microphone = null;
        let audioFormat = 'wav'; // Format default
        
        // DOM Elements
        const recordBtn = document.getElementById('recordBtn');
        const recordText = document.getElementById('recordText');
        const voiceVisualizer = document.getElementById('voiceVisualizer');
        const chatContainer = document.getElementById('chatContainer');
        const textInput = document.getElementById('textInput');
        const sendTextBtn = document.getElementById('sendTextBtn');
        const audioPlayer = document.getElementById('audioPlayer');
        const testAudioPlayer = document.getElementById('testAudioPlayer');
        const volumeSlider = document.getElementById('volumeSlider');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const repeatBtn = document.getElementById('repeatBtn');
        const helpBtn = document.getElementById('helpBtn');
        const helpModal = document.getElementById('helpModal');
        const closeHelpModal = document.getElementById('closeHelpModal');
        const clearHistoryBtn = document.getElementById('clearHistoryBtn');
        const connectionStatus = document.getElementById('connectionStatus');
        const serverStatus = document.getElementById('serverStatus');
        const messageCountEl = document.getElementById('messageCount');
        const responseTimeEl = document.getElementById('responseTime');
        const selectedAgeEl = document.getElementById('selectedAge');
        const microphoneStatusEl = document.getElementById('microphoneStatus');
        const audioFormatEl = document.getElementById('audioFormat');
        const permissionAlert = document.getElementById('permissionAlert');
        const requestPermissionBtn = document.getElementById('requestPermissionBtn');
        const recordingStatus = document.getElementById('recordingStatus');
        const testMicrophoneBtn = document.getElementById('testMicrophoneBtn');
        const testAudioBtn = document.getElementById('testAudioBtn');
        const micTestModal = document.getElementById('micTestModal');
        const closeMicTestModal = document.getElementById('closeMicTestModal');
        const startMicTestBtn = document.getElementById('startMicTestBtn');
        const playbackTestBtn = document.getElementById('playbackTestBtn');
        const micTestStatus = document.getElementById('micTestStatus');
        const processingIndicator = document.getElementById('processingIndicator');
        const autoPlayToggle = document.getElementById('autoPlayToggle');
        
        // Fungsi untuk encode WAV
        function encodeWAV(samples, sampleRate = 16000, numChannels = 1, bitsPerSample = 16) {
            const buffer = new ArrayBuffer(44 + samples.length * 2);
            const view = new DataView(buffer);
            
            // Write WAV header
            writeString(view, 0, 'RIFF');
            view.setUint32(4, 36 + samples.length * 2, true);
            writeString(view, 8, 'WAVE');
            writeString(view, 12, 'fmt ');
            view.setUint32(16, 16, true); // PCM format size
            view.setUint16(20, 1, true); // PCM format
            view.setUint16(22, numChannels, true); // Number of channels
            view.setUint32(24, sampleRate, true); // Sample rate
            view.setUint32(28, sampleRate * numChannels * bitsPerSample / 8, true); // Byte rate
            view.setUint16(32, numChannels * bitsPerSample / 8, true); // Block align
            view.setUint16(34, bitsPerSample, true); // Bits per sample
            writeString(view, 36, 'data');
            view.setUint32(40, samples.length * 2, true); // Data size
            
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
        
        function mergeBuffers(bufferList, length) {
            const result = new Float32Array(length);
            let offset = 0;
            for (let i = 0; i < bufferList.length; i++) {
                result.set(bufferList[i], offset);
                offset += bufferList[i].length;
            }
            return result;
        }
        
        function downsampleBuffer(buffer, sampleRate, outSampleRate = 16000) {
            if (outSampleRate === sampleRate) {
                return buffer;
            }
            
            if (outSampleRate > sampleRate) {
                throw new Error('Downsampling rate must be less than original sample rate');
            }
            
            const sampleRateRatio = sampleRate / outSampleRate;
            const newLength = Math.round(buffer.length / sampleRateRatio);
            const result = new Float32Array(newLength);
            let offsetResult = 0;
            let offsetBuffer = 0;
            
            while (offsetResult < result.length) {
                const nextOffsetBuffer = Math.round((offsetResult + 1) * sampleRateRatio);
                let accum = 0;
                let count = 0;
                
                for (let i = offsetBuffer; i < nextOffsetBuffer && i < buffer.length; i++) {
                    accum += buffer[i];
                    count++;
                }
                
                result[offsetResult] = accum / count;
                offsetResult++;
                offsetBuffer = nextOffsetBuffer;
            }
            
            return result;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Voice Agent initialized - WAV Format');
            checkServerStatus();
            setupEventListeners();
            updateSelectedAge();
            checkMicrophonePermission();
            updateAudioFormatStatus();
            
            // Load chat history
            loadChatHistory();
            
            // Set auto play from localStorage
            const savedAutoPlay = localStorage.getItem('calista_auto_play');
            if (savedAutoPlay !== null) {
                autoPlayAudio = savedAutoPlay === 'true';
                autoPlayToggle.checked = autoPlayAudio;
            }
            
            // Show welcome message after a delay
            setTimeout(() => {
                if (messageCount === 0) {
                    playWelcomeAudio();
                }
            }, 1500);
        });
        
        function setupEventListeners() {
            // Age group selection
            document.querySelectorAll('.age-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    selectedAgeGroup = this.dataset.age;
                    updateSelectedAge();
                    
                    // Update active button
                    document.querySelectorAll('.age-btn').forEach(b => {
                        b.classList.remove('bg-indigo-600', 'text-white');
                        b.classList.add('border', 'border-gray-300');
                    });
                    this.classList.remove('border', 'border-gray-300');
                    this.classList.add('bg-indigo-600', 'text-white');
                    
                    // Save preference
                    localStorage.setItem('calista_age_group', selectedAgeGroup);
                });
            });
            
            // Load saved age group
            const savedAgeGroup = localStorage.getItem('calista_age_group');
            if (savedAgeGroup) {
                selectedAgeGroup = savedAgeGroup;
                updateSelectedAge();
                
                // Update button
                document.querySelectorAll('.age-btn').forEach(btn => {
                    if (btn.dataset.age === savedAgeGroup) {
                        btn.classList.remove('border', 'border-gray-300');
                        btn.classList.add('bg-indigo-600', 'text-white');
                    }
                });
            }
            
            // Quick actions
            document.querySelectorAll('.quick-action').forEach(btn => {
                btn.addEventListener('click', function() {
                    const text = this.dataset.text;
                    sendTextMessage(text);
                });
            });
            
            // Text input
            sendTextBtn.addEventListener('click', sendTextFromInput);
            textInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendTextFromInput();
                }
            });
            
            // Audio controls
            volumeSlider.addEventListener('input', function() {
                const volume = this.value / 100;
                audioPlayer.volume = volume;
                testAudioPlayer.volume = volume;
                localStorage.setItem('calista_volume', this.value);
            });
            
            // Load saved volume
            const savedVolume = localStorage.getItem('calista_volume');
            if (savedVolume) {
                volumeSlider.value = savedVolume;
                audioPlayer.volume = savedVolume / 100;
            }
            
            playPauseBtn.addEventListener('click', function() {
                if (audioPlayer.paused) {
                    audioPlayer.play();
                    this.innerHTML = '<i class="fas fa-pause text-indigo-600"></i>';
                } else {
                    audioPlayer.pause();
                    this.innerHTML = '<i class="fas fa-play text-indigo-600"></i>';
                }
            });
            
            repeatBtn.addEventListener('click', function() {
                if (currentAudioUrl) {
                    audioPlayer.src = currentAudioUrl;
                    audioPlayer.currentTime = 0;
                    audioPlayer.play();
                    playPauseBtn.innerHTML = '<i class="fas fa-pause text-indigo-600"></i>';
                }
            });
            
            // Help modal
            helpBtn.addEventListener('click', () => {
                helpModal.classList.remove('hidden');
            });
            
            closeHelpModal.addEventListener('click', () => {
                helpModal.classList.add('hidden');
            });
            
            // Microphone permission
            requestPermissionBtn.addEventListener('click', requestMicrophonePermission);
            
            // Clear history
            clearHistoryBtn.addEventListener('click', clearChatHistory);
            
            // Test buttons
            testMicrophoneBtn.addEventListener('click', () => {
                micTestModal.classList.remove('hidden');
            });
            
            closeMicTestModal.addEventListener('click', () => {
                micTestModal.classList.add('hidden');
                stopMicrophoneTest();
            });
            
            startMicTestBtn.addEventListener('click', startMicrophoneTest);
            playbackTestBtn.addEventListener('click', playMicrophoneTest);
            
            testAudioBtn.addEventListener('click', testCalistaAudio);
            
            // Auto play toggle
            autoPlayToggle.addEventListener('change', function() {
                autoPlayAudio = this.checked;
                localStorage.setItem('calista_auto_play', autoPlayAudio);
            });
            
            // Settings menu
            const settingsBtn = document.getElementById('settingsBtn');
            const settingsMenu = document.getElementById('settingsMenu');
            
            settingsBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                settingsMenu.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                settingsMenu.classList.add('hidden');
            });
            
            // Audio player events
            audioPlayer.addEventListener('play', function() {
                playPauseBtn.innerHTML = '<i class="fas fa-pause text-indigo-600"></i>';
            });
            
            audioPlayer.addEventListener('pause', function() {
                playPauseBtn.innerHTML = '<i class="fas fa-play text-indigo-600"></i>';
            });
            
            audioPlayer.addEventListener('ended', function() {
                playPauseBtn.innerHTML = '<i class="fas fa-play text-indigo-600"></i>';
            });
            
            // Setup voice recording
            setupVoiceRecording();
        }
        
        function setupVoiceRecording() {
            // Mouse events
            recordBtn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                if (!isRecording) {
                    startWAVRecording();
                }
            });
            
            recordBtn.addEventListener('mouseup', function(e) {
                e.preventDefault();
                if (isRecording) {
                    stopWAVRecording();
                }
            });
            
            recordBtn.addEventListener('mouseleave', function(e) {
                if (isRecording) {
                    stopWAVRecording();
                }
            });
            
            // Touch events
            recordBtn.addEventListener('touchstart', function(e) {
                e.preventDefault();
                if (!isRecording) {
                    startWAVRecording();
                }
            }, { passive: false });
            
            recordBtn.addEventListener('touchend', function(e) {
                e.preventDefault();
                if (isRecording) {
                    stopWAVRecording();
                }
            }, { passive: false });
            
            // Prevent context menu on long press
            recordBtn.addEventListener('contextmenu', e => e.preventDefault());
        }
        
        async function checkMicrophonePermission() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const hasMicrophone = devices.some(device => device.kind === 'audioinput');
                
                if (!hasMicrophone) {
                    microphoneStatusEl.textContent = 'Tidak ada';
                    microphoneStatusEl.className = 'font-medium text-red-600';
                    permissionAlert.classList.remove('hidden');
                    isMicrophoneAvailable = false;
                    return;
                }
                
                // Check permission
                const permissionStatus = await navigator.permissions.query({ name: 'microphone' });
                
                if (permissionStatus.state === 'granted') {
                    microphoneStatusEl.textContent = 'Siap';
                    microphoneStatusEl.className = 'font-medium text-green-600';
                    permissionAlert.classList.add('hidden');
                    isMicrophoneAvailable = true;
                    
                    // Try to get stream to ensure it works
                    try {
                        const testStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        testStream.getTracks().forEach(track => track.stop());
                    } catch (e) {
                        console.warn('Microphone access test failed:', e);
                        isMicrophoneAvailable = false;
                    }
                    
                } else if (permissionStatus.state === 'denied') {
                    microphoneStatusEl.textContent = 'Ditolak';
                    microphoneStatusEl.className = 'font-medium text-red-600';
                    permissionAlert.classList.remove('hidden');
                    isMicrophoneAvailable = false;
                } else {
                    microphoneStatusEl.textContent = 'Minta izin';
                    microphoneStatusEl.className = 'font-medium text-yellow-600';
                    permissionAlert.classList.remove('hidden');
                    isMicrophoneAvailable = false;
                }
                
            } catch (error) {
                console.error('Error checking microphone:', error);
                microphoneStatusEl.textContent = 'Error';
                microphoneStatusEl.className = 'font-medium text-red-600';
                permissionAlert.classList.remove('hidden');
                isMicrophoneAvailable = false;
            }
        }
        
        function updateAudioFormatStatus() {
            audioFormatEl.textContent = audioFormat.toUpperCase();
        }
        
        async function requestMicrophonePermission() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    } 
                });
                
                // Stop the stream immediately since we just wanted permission
                stream.getTracks().forEach(track => track.stop());
                
                microphoneStatusEl.textContent = 'Siap';
                microphoneStatusEl.className = 'font-medium text-green-600';
                permissionAlert.classList.add('hidden');
                isMicrophoneAvailable = true;
                
                addMessageToChat('system', '✅ Mikrofon siap digunakan!');
                
            } catch (error) {
                console.error('Microphone permission denied:', error);
                microphoneStatusEl.textContent = 'Ditolak';
                microphoneStatusEl.className = 'font-medium text-red-600';
                permissionAlert.classList.remove('hidden');
                isMicrophoneAvailable = false;
                
                addMessageToChat('system', '❌ Izin mikrofon ditolak. Gunakan input teks.');
            }
        }
        
        async function startWAVRecording() {
            if (isRecording) return;
            
            if (!isMicrophoneAvailable) {
                addMessageToChat('system', '❌ Mikrofon tidak tersedia. Berikan izin terlebih dahulu.');
                permissionAlert.classList.remove('hidden');
                return;
            }
            
            try {
                console.log('Starting WAV recording...');
                
                // Get microphone stream
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true,
                        channelCount: 1,
                        sampleRate: 16000
                    }
                });
                
                microphoneStream = stream;
                
                // Setup AudioContext for WAV processing
                audioContext = new (window.AudioContext || window.webkitAudioContext)({
                    sampleRate: 16000
                });
                
                const source = audioContext.createMediaStreamSource(stream);
                const processor = audioContext.createScriptProcessor(4096, 1, 1);
                
                // Store audio data in chunks
                const audioBuffers = [];
                
                processor.onaudioprocess = (e) => {
                    if (!isRecording) return;
                    
                    const inputData = e.inputBuffer.getChannelData(0);
                    audioBuffers.push(new Float32Array(inputData));
                };
                
                // Store processor for cleanup
                window.audioProcessor = processor;
                window.audioBuffers = audioBuffers;
                
                // Connect nodes
                source.connect(processor);
                processor.connect(audioContext.destination);
                
                isRecording = true;
                
                // Update UI
                recordText.innerHTML = '<i class="fas fa-stop mr-2"></i>LEPASKAN UNTUK KIRIM';
                recordBtn.classList.remove('pulse');
                recordBtn.classList.add('bg-gradient-to-r', 'from-red-500', 'to-pink-600');
                recordingStatus.classList.remove('hidden');
                voiceVisualizer.classList.remove('hidden');
                
                // Start visualizer animation
                startVisualizer(stream);
                
                console.log('WAV recording started');
                
            } catch (error) {
                console.error('Error starting WAV recording:', error);
                isMicrophoneAvailable = false;
                microphoneStatusEl.textContent = 'Error';
                microphoneStatusEl.className = 'font-medium text-red-600';
                
                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    addMessageToChat('system', '❌ Izin mikrofon ditolak. Silakan klik "Klik untuk memberikan izin".');
                    permissionAlert.classList.remove('hidden');
                } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                    addMessageToChat('system', '❌ Tidak ada mikrofon yang ditemukan.');
                } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                    addMessageToChat('system', '❌ Mikrofon sedang digunakan oleh aplikasi lain.');
                } else {
                    addMessageToChat('system', '❌ Tidak dapat mengakses mikrofon: ' + error.message);
                }
            }
        }
        
        function stopWAVRecording() {
            if (!isRecording) return;
            
            console.log('Stopping WAV recording...');
            
            // Stop recording
            isRecording = false;
            
            // Disconnect audio nodes
            if (window.audioProcessor) {
                window.audioProcessor.disconnect();
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
            
            // Stop visualizer
            stopVisualizer();
            
            // Process captured audio
            if (window.audioBuffers && window.audioBuffers.length > 0) {
                processWAVAudio(window.audioBuffers);
            } else {
                console.warn('No audio recorded');
                addMessageToChat('system', '❌ Tidak ada suara yang direkam. Coba lagi.');
            }
            
            // Update UI
            recordText.innerHTML = '<i class="fas fa-microphone mr-2"></i>TEKAN UNTUK BICARA';
            recordBtn.classList.add('pulse');
            recordBtn.classList.remove('bg-gradient-to-r', 'from-red-500', 'to-pink-600');
            recordBtn.classList.add('bg-gradient-to-r', 'from-indigo-500', 'to-purple-600');
            recordingStatus.classList.add('hidden');
            voiceVisualizer.classList.add('hidden');
            
            // Cleanup
            window.audioBuffers = null;
            window.audioProcessor = null;
            
            console.log('WAV recording stopped');
        }
        
        function processWAVAudio(audioBuffers) {
            // Show processing indicator
            processingIndicator.classList.remove('hidden');
            
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
                
                // Send to server
                sendWAVToServer(wavBlob);
                
            } catch (error) {
                console.error('Error processing WAV audio:', error);
                processingIndicator.classList.add('hidden');
                addMessageToChat('system', '❌ Error memproses audio: ' + error.message);
            }
        }
        
        async function sendWAVToServer(wavBlob) {
            // Add user message to chat
            const userMsgId = addMessageToChat('user', '🎤 [Merekam suara...]');
            
            // Create form data
            const formData = new FormData();
            formData.append('audio', wavBlob, 'recording.wav');
            formData.append('age_group', selectedAgeGroup);
            formData.append('user_id', 'user_{{ auth()->id() }}');
            formData.append('audio_format', 'wav');
            
            // Add CSRF token for Laravel
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const headers = {};
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const startTime = Date.now();
            
            try {
                console.log('Sending WAV audio to server...');
                const response = await fetch(`${LARAVEL_BASE_URL}/process-voice`, {
                    method: 'POST',
                    headers: headers,
                    body: formData
                });
                
                const responseTime = Date.now() - startTime;
                updateResponseTime(responseTime);
                
                console.log('Server response:', response.status, response.statusText);
                
                if (response.ok) {
                    const contentType = response.headers.get('content-type');
                    
                    // Check if response is audio
                    if (contentType && contentType.includes('audio')) {
                        const audioBlob = await response.blob();
                        const audioUrl = URL.createObjectURL(audioBlob);
                        
                        // Get text from headers
                        const userText = response.headers.get('X-STT-Text') || '🎤 [Suara direkam]';
                        const aiResponse = response.headers.get('X-AI-Response') || 'Halo!';
                        
                        // Update user message with actual text
                        updateUserMessage(userMsgId, userText);
                        
                        // Add AI response
                        addMessageToChat('ai', aiResponse);
                        
                        // Play audio
                        if (autoPlayAudio) {
                            playAudio(audioUrl, aiResponse);
                        }
                        
                        currentAudioUrl = audioUrl;
                        
                        // Update message count
                        messageCount += 2;
                        updateMessageCount();
                        
                    } else {
                        // Handle JSON response (fallback)
                        const data = await response.json();
                        console.log('Fallback response:', data);
                        
                        updateUserMessage(userMsgId, '[Suara terdeteksi]');
                        
                        if (data.text) {
                            addMessageToChat('ai', data.text);
                            speakText(data.text);
                        }
                    }
                    
                } else {
                    console.error('Server error:', response.status);
                    
                    updateUserMessage(userMsgId, '[Gagal mengirim]');
                    
                    try {
                        const error = await response.json();
                        addMessageToChat('ai', `Maaf, ada masalah: ${error.error || 'Server error ' + response.status}`);
                    } catch {
                        addMessageToChat('ai', 'Maaf, server tidak merespons. Coba lagi nanti.');
                    }
                }
                
            } catch (error) {
                console.error('Error sending audio:', error);
                
                updateUserMessage(userMsgId, '[Koneksi error]');
                addMessageToChat('ai', 'Maaf, terjadi kesalahan jaringan. Coba lagi nanti.');
                
            } finally {
                // Hide processing indicator
                processingIndicator.classList.add('hidden');
            }
        }
        
        function startVisualizer(stream) {
            try {
                if (!audioContext) {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                
                if (analyser) {
                    analyser.disconnect();
                }
                
                analyser = audioContext.createAnalyser();
                microphone = audioContext.createMediaStreamSource(stream);
                microphone.connect(analyser);
                
                analyser.fftSize = 256;
                const bufferLength = analyser.frequencyBinCount;
                const dataArray = new Uint8Array(bufferLength);
                
                const bars = voiceVisualizer.querySelectorAll('div');
                
                function updateVisualizer() {
                    if (!isRecording) return;
                    
                    analyser.getByteFrequencyData(dataArray);
                    
                    // Update bar heights
                    bars.forEach((bar, i) => {
                        const value = dataArray[i * Math.floor(bufferLength / bars.length)];
                        const height = Math.max(10, (value / 255) * 60);
                        bar.style.height = `${height}px`;
                    });
                    
                    requestAnimationFrame(updateVisualizer);
                }
                
                updateVisualizer();
            } catch (error) {
                console.warn('Visualizer error:', error);
            }
        }
        
        function stopVisualizer() {
            if (microphone) {
                microphone.disconnect();
                microphone = null;
            }
            
            if (analyser) {
                analyser.disconnect();
                analyser = null;
            }
            
            // Reset bar heights
            const bars = voiceVisualizer.querySelectorAll('div');
            bars.forEach(bar => {
                bar.style.height = '20px';
            });
        }
        
        function updateUserMessage(messageId, newText) {
            const messageElement = document.getElementById(messageId);
            if (messageElement) {
                const textElement = messageElement.querySelector('p');
                if (textElement) {
                    textElement.textContent = newText;
                    
                    // Update in history
                    updateMessageInHistory(messageId, newText);
                }
            }
        }
        
        function sendTextFromInput() {
            const text = textInput.value.trim();
            if (!text) return;
            
            sendTextMessage(text);
            textInput.value = '';
            textInput.focus();
        }
        
        async function sendTextMessage(text) {
            // Add user message
            addMessageToChat('user', text);
            
            const startTime = Date.now();
            
            try {
                const response = await fetch(`${LARAVEL_BASE_URL}/text-chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'audio/mpeg, application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        message: text,
                        age_group: selectedAgeGroup,
                        user_id: 'user_{{ auth()->id() }}'
                    })
                });
                
                const responseTime = Date.now() - startTime;
                updateResponseTime(responseTime);
                
                if (response.ok) {
                    const contentType = response.headers.get('content-type');
                    
                    if (contentType && contentType.includes('audio')) {
                        const audioBlob = await response.blob();
                        const audioUrl = URL.createObjectURL(audioBlob);
                        
                        // Get AI response from headers
                        const aiResponse = response.headers.get('X-AI-Response') || text;
                        
                        // Add AI response
                        addMessageToChat('ai', aiResponse);
                        
                        // Play audio
                        if (autoPlayAudio) {
                            playAudio(audioUrl, aiResponse);
                        }
                        
                        currentAudioUrl = audioUrl;
                        
                        // Update message count
                        messageCount += 2;
                        updateMessageCount();
                        
                    } else {
                        const data = await response.json();
                        if (data.text) {
                            addMessageToChat('ai', data.text);
                            speakText(data.text);
                        }
                    }
                    
                } else {
                    const error = await response.json();
                    addMessageToChat('ai', `Maaf: ${error.error || 'Tidak dapat mendapatkan respons'}`);
                }
                
            } catch (error) {
                console.error('Error sending text:', error);
                addMessageToChat('ai', 'Maaf, terjadi kesalahan. Coba lagi nanti.');
            }
        }
        
        function addMessageToChat(sender, message) {
            const messageId = 'msg_' + Date.now();
            const timestamp = new Date().toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const messageDiv = document.createElement('div');
            messageDiv.id = messageId;
            messageDiv.className = `chat-bubble ${sender === 'user' ? 'user-bubble' : 'ai-bubble'}`;
            
            if (sender === 'ai') {
                messageDiv.innerHTML = `
                    <div class="font-semibold text-indigo-600 mb-1">
                        <i class="fas fa-robot mr-2"></i>Calista
                    </div>
                    <p>${message}</p>
                    <div class="text-xs text-gray-500 mt-2" data-timestamp="${timestamp}">${timestamp}</div>
                `;
            } else if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="font-semibold text-white mb-1">
                        <i class="fas fa-user mr-2"></i>Anda
                    </div>
                    <p>${message}</p>
                    <div class="text-xs text-white/80 mt-2" data-timestamp="${timestamp}">${timestamp}</div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="font-semibold text-gray-600 mb-1">
                        <i class="fas fa-info-circle mr-2"></i>Sistem
                    </div>
                    <p>${message}</p>
                `;
            }
            
            chatContainer.appendChild(messageDiv);
            scrollToBottom();
            
            // Save to local storage
            saveMessageToHistory(sender, message, timestamp, messageId);
            
            return messageId;
        }
        
        function playAudio(audioUrl, text) {
            try {
                audioPlayer.src = audioUrl;
                audioPlayer.play().then(() => {
                    playPauseBtn.innerHTML = '<i class="fas fa-pause text-indigo-600"></i>';
                }).catch(e => {
                    console.error('Error playing audio:', e);
                    // Fallback: Use Web Speech API
                    speakText(text);
                });
            } catch (error) {
                console.error('Audio play error:', error);
                speakText(text);
            }
        }
        
        function speakText(text) {
            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech
                window.speechSynthesis.cancel();
                
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1.2;
                utterance.volume = volumeSlider.value / 100;
                
                // Get available voices and try to find Indonesian voice
                const voices = window.speechSynthesis.getVoices();
                const indonesianVoice = voices.find(voice => 
                    voice.lang.includes('id') || voice.name.includes('Indonesian')
                );
                
                if (indonesianVoice) {
                    utterance.voice = indonesianVoice;
                }
                
                window.speechSynthesis.speak(utterance);
            }
        }
        
        async function checkServerStatus() {
            try {
                const response = await fetch(`${LARAVEL_BASE_URL}/health`);
                const data = await response.json();
                
                // Check both Laravel and Python server
                const pythonOnline = data.status === 'online';
                
                if (pythonOnline) {
                    serverStatus.textContent = 'Online';
                    serverStatus.className = 'font-medium text-green-600';
                    connectionStatus.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    connectionStatus.innerHTML = '<i class="fas fa-circle text-green-500 mr-2 text-xs"></i><span>Terhubung</span>';
                } else {
                    serverStatus.textContent = 'Python Offline';
                    serverStatus.className = 'font-medium text-yellow-600';
                    connectionStatus.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800';
                    connectionStatus.innerHTML = '<i class="fas fa-circle text-yellow-500 mr-2 text-xs"></i><span>Terbatas</span>';
                    addMessageToChat('system', '⚠️ Server Python offline. Fitur suara terbatas.');
                }
                
            } catch (error) {
                console.error('Error checking server status:', error);
                serverStatus.textContent = 'Error';
                serverStatus.className = 'font-medium text-red-600';
                connectionStatus.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
                connectionStatus.innerHTML = '<i class="fas fa-circle text-red-500 mr-2 text-xs"></i><span>Error</span>';
            }
        }
        
        async function playWelcomeAudio() {
            try {
                const response = await fetch(`${LARAVEL_BASE_URL}/test-audio?age_group=${selectedAgeGroup}&text=Halo!%20Saya%20Calista,%20teman%20belajarmu%20yang%20ceria.%20Mari%20kita%20belajar%20bersama!`);
                
                if (response.ok) {
                    const audioBlob = await response.blob();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    // Play quietly
                    testAudioPlayer.volume = 0.5;
                    testAudioPlayer.src = audioUrl;
                    testAudioPlayer.play().catch(() => {
                        // Ignore autoplay errors
                    });
                    
                }
            } catch (error) {
                // Ignore welcome audio errors
            }
        }
        
        async function testCalistaAudio() {
            try {
                const testText = "Halo! Ini adalah test suara Calista. Jika Anda mendengar ini, berarti suara berfungsi dengan baik!";
                
                const response = await fetch(`${LARAVEL_BASE_URL}/test-audio`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        text: testText,
                        age_group: selectedAgeGroup
                    })
                });
                
                if (response.ok) {
                    const audioBlob = await response.blob();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    testAudioPlayer.src = audioUrl;
                    testAudioPlayer.play();
                    
                    addMessageToChat('system', '🔊 Memutar test suara Calista...');
                    
                } else {
                    addMessageToChat('system', '❌ Gagal memainkan test suara');
                }
                
            } catch (error) {
                console.error('Test audio error:', error);
                addMessageToChat('system', '❌ Error test suara: ' + error.message);
            }
        }
        
        async function startMicrophoneTest() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true
                    } 
                });
                
                const mediaRecorder = new MediaRecorder(stream);
                const chunks = [];
                
                mediaRecorder.ondataavailable = e => chunks.push(e.data);
                
                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(chunks, { type: 'audio/webm' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    // Store for playback
                    window.testRecordingUrl = audioUrl;
                    
                    playbackTestBtn.classList.remove('hidden');
                    micTestStatus.textContent = '✅ Rekaman berhasil! Klik "Dengarkan Rekaman" untuk memutar.';
                    micTestStatus.className = 'text-green-600';
                    
                    // Stop all tracks
                    stream.getTracks().forEach(track => track.stop());
                };
                
                mediaRecorder.start();
                
                // Start test
                startMicTestBtn.disabled = true;
                startMicTestBtn.innerHTML = '<i class="fas fa-circle mr-2 recording"></i>Rekaman... (3 detik)';
                micTestStatus.textContent = '🔴 Sedang merekam... ucapkan sesuatu!';
                micTestStatus.className = 'text-red-600';
                
                // Stop after 3 seconds
                setTimeout(() => {
                    if (mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }
                    
                    startMicTestBtn.disabled = false;
                    startMicTestBtn.innerHTML = '<i class="fas fa-record-vinyl mr-2"></i>Mulai Test';
                    
                }, 3000);
                
            } catch (error) {
                console.error('Microphone test error:', error);
                micTestStatus.textContent = '❌ Gagal mengakses mikrofon: ' + error.message;
                micTestStatus.className = 'text-red-600';
                startMicTestBtn.disabled = false;
            }
        }
        
        function playMicrophoneTest() {
            if (window.testRecordingUrl) {
                testAudioPlayer.src = window.testRecordingUrl;
                testAudioPlayer.play();
                micTestStatus.textContent = '🔊 Memutar rekaman...';
            }
        }
        
        function stopMicrophoneTest() {
            if (window.testRecordingUrl) {
                URL.revokeObjectURL(window.testRecordingUrl);
                window.testRecordingUrl = null;
            }
            
            playbackTestBtn.classList.add('hidden');
            micTestStatus.textContent = '';
        }
        
        function updateSelectedAge() {
            selectedAgeEl.textContent = `${selectedAgeGroup} tahun`;
        }
        
        function updateMessageCount() {
            messageCountEl.textContent = messageCount;
        }
        
        function updateResponseTime(time) {
            responseTimeEl.textContent = `${time}ms`;
        }
        
        function scrollToBottom() {
            setTimeout(() => {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }, 100);
        }
        
        // Local storage functions
        function saveMessageToHistory(sender, message, timestamp, messageId) {
            const history = JSON.parse(localStorage.getItem('calista_chat_history') || '[]');
            history.push({ 
                id: messageId,
                sender, 
                message, 
                timestamp,
                date: new Date().toISOString()
            });
            
            // Keep only last 50 messages
            if (history.length > 50) {
                history.splice(0, history.length - 50);
            }
            
            localStorage.setItem('calista_chat_history', JSON.stringify(history));
        }
        
        function updateMessageInHistory(messageId, newText) {
            const history = JSON.parse(localStorage.getItem('calista_chat_history') || '[]');
            const messageIndex = history.findIndex(msg => msg.id === messageId);
            
            if (messageIndex !== -1) {
                history[messageIndex].message = newText;
                localStorage.setItem('calista_chat_history', JSON.stringify(history));
            }
        }
        
        function loadChatHistory() {
            const history = JSON.parse(localStorage.getItem('calista_chat_history') || '[]');
            
            // Clear existing messages except initial greeting
            const initialGreeting = chatContainer.querySelector('.ai-bubble');
            chatContainer.innerHTML = '';
            if (initialGreeting) {
                chatContainer.appendChild(initialGreeting);
            }
            
            // Add history
            history.forEach(msg => {
                if (msg.message !== '🎤 [Merekam suara...]' && 
                    msg.message !== '🎤 [Suara direkam]' && 
                    msg.message !== '[Suara terdeteksi]' &&
                    msg.message !== '[Gagal mengirim]' &&
                    msg.message !== '[Koneksi error]') {
                    
                    const messageDiv = document.createElement('div');
                    messageDiv.id = msg.id;
                    messageDiv.className = `chat-bubble ${msg.sender === 'user' ? 'user-bubble' : 'ai-bubble'}`;
                    
                    if (msg.sender === 'ai') {
                        messageDiv.innerHTML = `
                            <div class="font-semibold text-indigo-600 mb-1">
                                <i class="fas fa-robot mr-2"></i>Calista
                            </div>
                            <p>${msg.message}</p>
                            <div class="text-xs text-gray-500 mt-2" data-timestamp="${msg.timestamp}">${msg.timestamp}</div>
                        `;
                    } else if (msg.sender === 'user') {
                        messageDiv.innerHTML = `
                            <div class="font-semibold text-white mb-1">
                                <i class="fas fa-user mr-2"></i>Anda
                            </div>
                            <p>${msg.message}</p>
                            <div class="text-xs text-white/80 mt-2" data-timestamp="${msg.timestamp}">${msg.timestamp}</div>
                        `;
                    } else {
                        messageDiv.innerHTML = `
                            <div class="font-semibold text-gray-600 mb-1">
                                <i class="fas fa-info-circle mr-2"></i>Sistem
                            </div>
                            <p>${msg.message}</p>
                        `;
                    }
                    
                    chatContainer.appendChild(messageDiv);
                }
            });
            
            messageCount = history.length + 1; // +1 for initial greeting
            updateMessageCount();
            scrollToBottom();
        }
        
        function clearChatHistory() {
            if (confirm('Apakah Anda yakin ingin menghapus semua riwayat percakapan?')) {
                localStorage.removeItem('calista_chat_history');
                
                // Clear chat UI except initial greeting
                const initialGreeting = chatContainer.querySelector('.ai-bubble');
                chatContainer.innerHTML = '';
                if (initialGreeting) {
                    chatContainer.appendChild(initialGreeting);
                }
                
                messageCount = 1;
                updateMessageCount();
                
                // Also clear server history
                fetch(`${LARAVEL_BASE_URL}/clear-history`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                addMessageToChat('system', '🗑️ Riwayat percakapan telah dihapus.');
            }
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+Enter to send message
            if (e.ctrlKey && e.key === 'Enter' && textInput.value.trim()) {
                sendTextFromInput();
            }
            
            // Space to toggle recording (when not in input field)
            if (e.key === ' ' && !e.target.matches('input, textarea')) {
                e.preventDefault();
                if (!isRecording) {
                    startWAVRecording();
                } else {
                    stopWAVRecording();
                }
            }
            
            // Escape to stop recording
            if (e.key === 'Escape' && isRecording) {
                stopWAVRecording();
            }
        });
        
        // Export functions for debugging
        window.voiceAgentDebug = {
            startRecording: startWAVRecording,
            stopRecording: stopWAVRecording,
            sendTextMessage,
            testMicrophone: startMicrophoneTest,
            testCalistaAudio,
            checkServerStatus,
            clearHistory: clearChatHistory,
            getState: () => ({
                isRecording,
                isMicrophoneAvailable,
                selectedAgeGroup,
                messageCount,
                autoPlayAudio,
                audioFormat
            }),
            encodeWAV // Export for testing
        };
        
        console.log('Voice Agent ready! Using WAV format for recording.');
        console.log('Use window.voiceAgentDebug for testing.');
    </script>
</body>
</html>