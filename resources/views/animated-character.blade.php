<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typecast API Debug - Complete</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #1a202c; 
            color: #e2e8f0;
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #63b3ed; margin-bottom: 20px; text-align: center; }
        h2 { color: #68d391; margin: 30px 0 15px 0; border-bottom: 2px solid #4a5568; padding-bottom: 10px; }
        
        .panel {
            background: #2d3748;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #4a5568;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .card {
            background: #4a5568;
            padding: 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .card:hover {
            background: #5a6578;
            transform: translateY(-2px);
        }
        
        .btn {
            display: inline-block;
            background: #4299e1;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin: 5px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #3182ce;
            transform: scale(1.05);
        }
        
        .btn-success { background: #48bb78; }
        .btn-success:hover { background: #38a169; }
        
        .btn-danger { background: #f56565; }
        .btn-danger:hover { background: #e53e3e; }
        
        .btn-warning { background: #ed8936; }
        .btn-warning:hover { background: #dd6b20; }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-good { background: #48bb78; box-shadow: 0 0 10px #48bb78; }
        .status-bad { background: #f56565; box-shadow: 0 0 10px #f56565; }
        .status-warning { background: #ed8936; box-shadow: 0 0 10px #ed8936; }
        .status-neutral { background: #a0aec0; }
        
        .log-container {
            background: #1a202c;
            border: 1px solid #4a5568;
            border-radius: 8px;
            padding: 15px;
            height: 300px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin-top: 20px;
        }
        
        .log-entry {
            padding: 8px;
            border-bottom: 1px solid #2d3748;
            word-wrap: break-word;
        }
        
        .log-time { color: #a0aec0; font-size: 12px; }
        .log-info { color: #63b3ed; }
        .log-success { color: #68d391; }
        .log-error { color: #fc8181; }
        .log-warning { color: #f6e05e; }
        
        .input-group {
            margin: 15px 0;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            background: #4a5568;
            border: 1px solid #718096;
            border-radius: 6px;
            color: white;
            margin: 5px 0;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #63b3ed;
            box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.2);
        }
        
        .api-response {
            background: #2d3748;
            border: 1px solid #4a5568;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            max-height: 300px;
            overflow-y: auto;
            font-family: monospace;
            white-space: pre-wrap;
        }
        
        .test-section {
            margin: 25px 0;
            padding: 20px;
            background: #2d3748;
            border-radius: 10px;
        }
        
        .audio-player {
            width: 100%;
            margin: 15px 0;
            background: #4a5568;
            border-radius: 6px;
        }
        
        .progress-bar {
            height: 5px;
            background: #4a5568;
            border-radius: 3px;
            margin: 10px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: #4299e1;
            width: 0%;
            transition: width 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Typecast API Debug Dashboard</h1>
        
        <!-- Status Panel -->
        <div class="panel">
            <h2>System Status</h2>
            <div class="grid">
                <div class="card">
                    <h3>CSRF Token <span class="status-indicator" id="csrfStatusIcon"></span></h3>
                    <p id="csrfStatusText">Checking...</p>
                </div>
                <div class="card">
                    <h3>API Connection <span class="status-indicator" id="apiStatusIcon"></span></h3>
                    <p id="apiStatusText">Not tested</p>
                </div>
                <div class="card">
                    <h3>API Key <span class="status-indicator" id="keyStatusIcon"></span></h3>
                    <p id="keyStatusText">Unknown</p>
                </div>
                <div class="card">
                    <h3>Laravel Route <span class="status-indicator" id="routeStatusIcon"></span></h3>
                    <p id="routeStatusText">Checking...</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Tests -->
        <div class="panel">
            <h2>Quick Tests</h2>
            <div class="test-section">
                <button class="btn btn-success" onclick="testSimple()">Test Simple Endpoint</button>
                <button class="btn" onclick="testConnection()">Test Typecast Connection</button>
                <button class="btn btn-warning" onclick="testApiKey()">Test API Key Directly</button>
                <button class="btn btn-danger" onclick="testFullAudio()">Test Full Audio Generation</button>
                
                <div id="testResults" class="api-response" style="display: none;"></div>
            </div>
        </div>
        
        <!-- Audio Test -->
        <div class="panel">
            <h2>Audio Generation Test</h2>
            <div class="input-group">
                <label>Text to Speak:</label>
                <textarea id="testText" rows="3">Halo! Ini adalah tes suara dari Typecast API. Apakah Anda bisa mendengar saya?</textarea>
                
                <label>Emotion:</label>
                <select id="testEmotion">
                    <option value="normal">Normal</option>
                    <option value="happy">Happy</option>
                    <option value="excited">Excited</option>
                    <option value="calm">Calm</option>
                    <option value="friendly">Friendly</option>
                </select>
                
                <label>Intensity (0.5 - 2.0):</label>
                <input type="range" id="testIntensity" min="0.5" max="2.0" step="0.1" value="1.0">
                <span id="intensityValue">1.0</span>
            </div>
            
            <button class="btn btn-success" onclick="generateAudio()">Generate & Play Audio</button>
            <button class="btn" onclick="downloadAudio()">Download Audio</button>
            
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            
            <audio id="audioPlayer" controls class="audio-player"></audio>
            
            <div id="audioInfo" class="api-response" style="display: none;"></div>
        </div>
        
        <!-- Manual API Test -->
        <div class="panel">
            <h2>Manual API Test</h2>
            <div class="input-group">
                <label>Endpoint URL:</label>
                <input type="text" id="endpointUrl" value="/api/generate-audio" readonly>
                
                <label>Request Body (JSON):</label>
                <textarea id="requestBody" rows="6">{
    "text": "Halo testing",
    "emotion": "normal",
    "intensity": 1.0,
    "language": "ind"
}</textarea>
            </div>
            
            <button class="btn" onclick="sendManualRequest()">Send Manual Request</button>
            <button class="btn btn-warning" onclick="copyCurlCommand()">Copy cURL Command</button>
            
            <div id="manualResponse" class="api-response" style="display: none;"></div>
        </div>
        
        <!-- Debug Log -->
        <div class="panel">
            <h2>Debug Log</h2>
            <button class="btn" onclick="clearLog()">Clear Log</button>
            <button class="btn btn-danger" onclick="clearAll()">Clear Everything</button>
            
            <div class="log-container" id="logContainer">
                <!-- Log entries will appear here -->
            </div>
        </div>
    </div>

    <script>
        // ==================== UTILITY FUNCTIONS ====================
        function log(message, type = 'info') {
            const logContainer = document.getElementById('logContainer');
            const time = new Date().toLocaleTimeString();
            const entry = document.createElement('div');
            entry.className = `log-entry log-${type}`;
            entry.innerHTML = `<span class="log-time">[${time}]</span> ${message}`;
            logContainer.appendChild(entry);
            logContainer.scrollTop = logContainer.scrollHeight;
            console.log(`[${type.toUpperCase()}] ${message}`);
        }
        
        function updateStatus(elementId, text, iconType = 'neutral') {
            const element = document.getElementById(elementId);
            if (element) element.textContent = text;
            
            const icon = document.getElementById(elementId + 'Icon');
            if (icon) {
                icon.className = 'status-indicator';
                icon.classList.add(`status-${iconType}`);
            }
        }
        
        function showResponse(containerId, data, isError = false) {
            const container = document.getElementById(containerId);
            const text = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
            container.innerHTML = `<pre class="${isError ? 'log-error' : 'log-info'}">${text}</pre>`;
            container.style.display = 'block';
        }
        
        function updateProgress(percentage) {
            const fill = document.getElementById('progressFill');
            if (fill) fill.style.width = percentage + '%';
        }
        
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        }
        
        // ==================== INITIAL CHECKS ====================
        window.onload = function() {
            log('System initialized', 'info');
            
            // Check CSRF Token
            const csrfToken = getCsrfToken();
            if (csrfToken) {
                updateStatus('csrfStatusText', `Found (${csrfToken.length} chars)`, 'good');
                log(`CSRF Token: ${csrfToken.substring(0, 20)}...`, 'success');
            } else {
                updateStatus('csrfStatusText', 'Not found!', 'bad');
                log('CSRF Token not found!', 'error');
            }
            
            // Update intensity display
            document.getElementById('testIntensity').addEventListener('input', function() {
                document.getElementById('intensityValue').textContent = this.value;
            });
            
            // Run initial tests
            setTimeout(() => testSimpleEndpoint(), 1000);
        };
        
        // ==================== TEST FUNCTIONS ====================
        async function testSimpleEndpoint() {
            log('Testing simple endpoint...', 'info');
            updateStatus('routeStatusText', 'Testing...', 'warning');
            
            try {
                const response = await fetch('/api/typecast/simple');
                if (response.ok) {
                    const data = await response.json();
                    updateStatus('routeStatusText', 'Working ✓', 'good');
                    log(`Simple endpoint: ${data.message}`, 'success');
                    
                    // Update API key status
                    updateStatus('keyStatusText', 
                        data.api_key_set ? `Set (${data.api_key_length} chars)` : 'Not set', 
                        data.api_key_set ? 'good' : 'bad'
                    );
                } else {
                    updateStatus('routeStatusText', `Error ${response.status}`, 'bad');
                    log(`Simple endpoint failed: ${response.status}`, 'error');
                }
            } catch (error) {
                updateStatus('routeStatusText', 'Network error', 'bad');
                log(`Network error: ${error.message}`, 'error');
            }
        }
        
        async function testConnection() {
            log('Testing Typecast API connection...', 'info');
            updateStatus('apiStatusText', 'Testing...', 'warning');
            
            try {
                const response = await fetch('/api/typecast/test');
                const data = await response.json();
                
                showResponse('testResults', data, !data.success);
                
                if (data.success) {
                    updateStatus('apiStatusText', 'Connected ✓', 'good');
                    log(`API Connection: ${data.message}`, 'success');
                    log(`Voices available: ${data.voices_count}`, 'info');
                } else {
                    updateStatus('apiStatusText', 'Failed ✗', 'bad');
                    log(`API Connection failed: ${data.message}`, 'error');
                }
            } catch (error) {
                updateStatus('apiStatusText', 'Network error', 'bad');
                log(`Connection test error: ${error.message}`, 'error');
            }
        }
        
        async function testApiKey() {
            log('Testing API Key directly...', 'info');
            
            try {
                // Test with Typecast voices endpoint
                const response = await fetch('https://api.typecast.ai/v1/voices', {
                    headers: {
                        'Authorization': 'Bearer __plt9JtmAwUWdwgJ98QfoQa7H79SFPbRStPLJ3dqp6eH'
                    }
                });
                
                const data = await response.json();
                showResponse('testResults', data, !response.ok);
                
                if (response.ok) {
                    log('API Key is valid!', 'success');
                    log(`Available voices: ${data.data?.length || 0}`, 'info');
                } else {
                    log(`API Key error: ${data.message || 'Unknown error'}`, 'error');
                }
            } catch (error) {
                log(`API Key test failed: ${error.message}`, 'error');
            }
        }
        
        async function testSimple() {
            log('Testing with simple request...', 'info');
            
            try {
                const response = await fetch('/api/generate-audio', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        text: "Test",
                        emotion: "normal",
                        intensity: 1.0
                    })
                });
                
                log(`Response status: ${response.status}`, response.ok ? 'success' : 'error');
                log(`Content-Type: ${response.headers.get('Content-Type')}`, 'info');
                
                if (response.ok) {
                    const blob = await response.blob();
                    log(`Audio generated: ${blob.size} bytes`, 'success');
                    
                    // Try to play it
                    const url = URL.createObjectURL(blob);
                    const audio = document.getElementById('audioPlayer');
                    audio.src = url;
                    audio.play();
                    
                    showResponse('testResults', {
                        status: response.status,
                        size: blob.size,
                        type: blob.type,
                        url: url.substring(0, 50) + '...'
                    });
                } else {
                    const error = await response.text();
                    log(`Error response: ${error}`, 'error');
                    showResponse('testResults', error, true);
                }
            } catch (error) {
                log(`Test failed: ${error.message}`, 'error');
            }
        }
        
        async function testFullAudio() {
            log('Testing full audio generation...', 'info');
            
            const text = document.getElementById('testText').value;
            const emotion = document.getElementById('testEmotion').value;
            const intensity = document.getElementById('testIntensity').value;
            
            updateProgress(10);
            
            try {
                const response = await fetch('/api/generate-audio', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'audio/mpeg',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        text: text,
                        emotion: emotion,
                        intensity: parseFloat(intensity),
                        language: 'ind'
                    })
                });
                
                updateProgress(50);
                
                log(`Full test status: ${response.status}`, response.ok ? 'success' : 'error');
                
                if (response.ok) {
                    const blob = await response.blob();
                    updateProgress(80);
                    
                    log(`Audio generated successfully: ${blob.size} bytes`, 'success');
                    
                    const url = URL.createObjectURL(blob);
                    const audio = document.getElementById('audioPlayer');
                    audio.src = url;
                    audio.play();
                    
                    updateProgress(100);
                    
                    showResponse('audioInfo', {
                        status: 'Success',
                        size: `${(blob.size / 1024).toFixed(2)} KB`,
                        duration: `${(blob.size / 16000).toFixed(2)}s (estimated)`,
                        emotion: emotion,
                        intensity: intensity
                    });
                    
                    setTimeout(() => updateProgress(0), 1000);
                } else {
                    const error = await response.text();
                    log(`Full test error: ${error}`, 'error');
                    showResponse('audioInfo', error, true);
                    updateProgress(0);
                }
            } catch (error) {
                log(`Full test failed: ${error.message}`, 'error');
                updateProgress(0);
            }
        }
        
        // ==================== MAIN FUNCTIONS ====================
        async function generateAudio() {
            const text = document.getElementById('testText').value;
            const emotion = document.getElementById('testEmotion').value;
            const intensity = document.getElementById('testIntensity').value;
            
            if (!text.trim()) {
                alert('Please enter text to speak');
                return;
            }
            
            log(`Generating audio: "${text.substring(0, 50)}..."`, 'info');
            updateProgress(10);
            
            try {
                const response = await fetch('/api/generate-audio', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        text: text,
                        emotion: emotion,
                        intensity: parseFloat(intensity),
                        language: 'ind'
                    })
                });
                
                updateProgress(50);
                
                if (response.ok) {
                    const blob = await response.blob();
                    updateProgress(80);
                    
                    const url = URL.createObjectURL(blob);
                    const audio = document.getElementById('audioPlayer');
                    
                    audio.src = url;
                    audio.play();
                    
                    updateProgress(100);
                    
                    log(`Audio played: ${blob.size} bytes`, 'success');
                    
                    showResponse('audioInfo', {
                        success: true,
                        size: blob.size,
                        type: blob.type,
                        emotion: emotion,
                        intensity: intensity
                    });
                    
                    setTimeout(() => updateProgress(0), 1000);
                } else {
                    const error = await response.json();
                    log(`Generation failed: ${error.message}`, 'error');
                    showResponse('audioInfo', error, true);
                    updateProgress(0);
                }
            } catch (error) {
                log(`Generation error: ${error.message}`, 'error');
                updateProgress(0);
            }
        }
        
        async function downloadAudio() {
            const text = document.getElementById('testText').value;
            const emotion = document.getElementById('testEmotion').value;
            
            try {
                const response = await fetch('/api/generate-audio', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        text: text,
                        emotion: emotion,
                        intensity: 1.0
                    })
                });
                
                if (response.ok) {
                    const blob = await response.blob();
                    const url = URL.createObjectURL(blob);
                    
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `typecast_${Date.now()}.mp3`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    
                    URL.revokeObjectURL(url);
                    log(`Audio downloaded: ${blob.size} bytes`, 'success');
                }
            } catch (error) {
                log(`Download failed: ${error.message}`, 'error');
            }
        }
        
        async function sendManualRequest() {
            const url = document.getElementById('endpointUrl').value;
            const body = document.getElementById('requestBody').value;
            
            log(`Sending manual request to ${url}`, 'info');
            
            try {
                const parsedBody = JSON.parse(body);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(parsedBody)
                });
                
                const contentType = response.headers.get('Content-Type');
                
                if (contentType && contentType.includes('audio')) {
                    const blob = await response.blob();
                    const audioUrl = URL.createObjectURL(blob);
                    
                    showResponse('manualResponse', {
                        status: response.status,
                        type: 'audio',
                        size: blob.size,
                        url: audioUrl
                    });
                    
                    const audio = new Audio(audioUrl);
                    audio.play();
                } else {
                    const text = await response.text();
                    showResponse('manualResponse', {
                        status: response.status,
                        body: text
                    }, !response.ok);
                }
                
                log(`Manual request completed: ${response.status}`, response.ok ? 'success' : 'error');
            } catch (error) {
                log(`Manual request failed: ${error.message}`, 'error');
                showResponse('manualResponse', { error: error.message }, true);
            }
        }
        
        function copyCurlCommand() {
            const body = document.getElementById('requestBody').value;
            const csrfToken = getCsrfToken();
            
            const curl = `curl -X POST ${window.location.origin}/api/generate-audio \\
  -H "Content-Type: application/json" \\
  -H "X-CSRF-TOKEN: ${csrfToken}" \\
  -d '${body}' \\
  --output audio.mp3`;
            
            navigator.clipboard.writeText(curl).then(() => {
                log('cURL command copied to clipboard', 'success');
            });
        }
        
        function clearLog() {
            document.getElementById('logContainer').innerHTML = '';
            log('Log cleared', 'info');
        }
        
        function clearAll() {
            clearLog();
            document.getElementById('testResults').style.display = 'none';
            document.getElementById('audioInfo').style.display = 'none';
            document.getElementById('manualResponse').style.display = 'none';
            updateProgress(0);
        }
        
        // ==================== GLOBAL ERROR HANDLING ====================
        window.addEventListener('error', function(e) {
            log(`Global error: ${e.message} at ${e.filename}:${e.lineno}`, 'error');
        });
        
        // Make functions available globally
        window.debug = {
            log,
            testConnection,
            generateAudio,
            clearAll
        };
        
        log('Debug tools ready. Use window.debug in console.', 'success');
    </script>
</body>
</html>