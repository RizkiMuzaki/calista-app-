import os
import json
import io
import time
import logging
import tempfile
from flask import Flask, request, jsonify, send_file, Response
from flask_cors import CORS
import requests
import speech_recognition as sr
from datetime import datetime
from dotenv import load_dotenv

# Load .env file
load_dotenv()

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

# ==================== KONFIGURASI ====================

# Typecast TTS Configuration
TYPECAST_CONFIG = {
    "api_key": os.getenv("TYPECAST_API_KEY"),
    "base_url": "https://api.typecast.ai",
    "voices": {
        "child_female": "tc_641c10bfb62ae5eee6db3f9e"
    }
}

# Groq AI Configuration
GROQ_CONFIG = {
    "api_key": os.getenv("GROQ_API_KEY"),
    "base_url": "https://api.groq.com/openai/v1",
    "model": "llama-3.3-70b-versatile"
}

# ==================== TYPECAST TTS CLASS ====================

class TypecastTTS:
    def __init__(self, config=None):
        self.config = config or TYPECAST_CONFIG
        self.headers = {
            "X-API-KEY": self.config["api_key"],
            "Content-Type": "application/json"
        }
        self.base_url = self.config["base_url"]
        logger.info("✅ Typecast TTS initialized")
        
    def text_to_speech(self, text: str, voice_id: str = None, emotion="happy", 
                      emotion_intensity=1.0, pitch=6, tempo=0.9) -> dict:
        """Convert text to speech with child-friendly settings"""
        try:
            voice_id = voice_id or self.config["voices"]["child_female"]
            url = f"{self.base_url}/v1/text-to-speech"
            
            # Truncate text if too long
            if len(text) > 1000:
                text = text[:1000]
                logger.warning(f"Text truncated to 1000 characters")
            
            payload = {
                "voice_id": voice_id,
                "text": text,
                "model": "ssfm-v21",
                "language": "ind",
                "output": {
                    "volume": 110,
                    "pitch": pitch,
                    "tempo": tempo,
                    "format": "mp3"
                }
            }
            
            logger.info(f"🔊 Generating TTS for text: {text[:50]}...")
            response = requests.post(url, headers=self.headers, json=payload, timeout=60)
            
            if response.status_code == 200:
                audio_size = len(response.content)
                logger.info(f"✅ TTS generated: {audio_size} bytes")
                return {
                    "success": True,
                    "audio_content": response.content,
                    "content_type": "audio/mpeg"
                }
            else:
                logger.error(f"❌ TTS API Error {response.status_code}: {response.text}")
                return {
                    "success": False,
                    "error": f"API Error: {response.status_code}",
                    "details": response.text
                }
                
        except Exception as e:
            logger.error(f"💥 TTS Exception: {str(e)}")
            return {"success": False, "error": str(e)}

# ==================== GROQ AI CLASS ====================

class GroqAI:
    def __init__(self, config=None):
        self.config = config or GROQ_CONFIG
        self.headers = {
            "Authorization": f"Bearer {self.config['api_key']}",
            "Content-Type": "application/json"
        }
        self.conversation_memory = {}
        logger.info(f"✅ Groq AI initialized with model: {self.config['model']}")
        
    def get_age_specific_prompt(self, age_group="5-7"):
        """Get persona based on age group"""
        personas = {
            "3-5": """Kamu adalah Nusa, teman belajar yang ceria untuk anak usia 3-5 tahun. 
            PERATURAN:
            1. Gunakan bahasa SANGAT sederhana, kata pendek
            2. Banyak ekspresi senang: Wah!, Hore!, Asyik!
            3. Respons MAXIMAL 2 KALIMAT saja
            4. Selalu positif dan ramah
            5. Jangan gunakan kalimat kompleks
            6. Gunakan kata pengulangan untuk anak kecil""",
            
            "5-7": """Kamu adalah Nusa, teman belajar yang ramah untuk anak usia 5-7 tahun.
            PERATURAN:
            1. Gunakan bahasa mudah dipahami
            2. Tambahkan ekspresi positif
            3. Respons MAXIMAL 3 KALIMAT saja
            4. Buat belajar terasa seperti bermain
            5. Gunakan kata-kata yang familiar
            6. Beri semangat belajar""",
            
            "7-9": """Kamu adalah Calista, teman belajar yang menyenangkan untuk anak usia 7-9 tahun.
            PERATURAN:
            1. Bisa menjelaskan konsep sederhana
            2. Respons MAXIMAL 3 KALIMAT
            3. Tetap dengan bahasa yang menarik
            4. Dorong rasa ingin tahu""",
            
            "9-12": """Kamu adalah Nusa, teman belajar yang membantu untuk anak usia 9-12 tahun.
            PERATURAN:
            1. Bisa memberi penjelasan lebih detail
            2. Respons MAXIMAL 4 KALIMAT
            3. Tetap dengan bahasa mudah
            4. Dorong untuk berpikir kritis"""
        }
        
        persona = personas.get(age_group, personas["5-7"])
        base_prompt = f"{persona}\n\nPENTING: Selalu gunakan Bahasa Indonesia. Respons harus ramah anak dan edukatif."
        return base_prompt
    
    def chat(self, message: str, user_id: str = "default", age_group="5-7") -> str:
        """Chat with Groq AI with age-specific responses"""
        try:
            url = f"{self.config['base_url']}/chat/completions"
            
            # Get age-appropriate system prompt
            system_prompt = self.get_age_specific_prompt(age_group)
            
            messages = [{"role": "system", "content": system_prompt}]
            
            # Add conversation history for context
            if user_id in self.conversation_memory:
                history = self.conversation_memory[user_id][-4:]
                for msg in history:
                    messages.append(msg)
            
            messages.append({"role": "user", "content": message})
            
            payload = {
                "model": self.config["model"],
                "messages": messages,
                "temperature": 0.8,
                "max_tokens": 200,
                "top_p": 0.9
            }
            
            logger.info(f"🤖 Sending to Groq AI (age {age_group}): {message[:50]}...")
            response = requests.post(url, headers=self.headers, json=payload, timeout=30)
            
            if response.status_code == 200:
                result = response.json()
                ai_response = result["choices"][0]["message"]["content"]
                
                # Update memory
                if user_id not in self.conversation_memory:
                    self.conversation_memory[user_id] = []
                
                self.conversation_memory[user_id].extend([
                    {"role": "user", "content": message},
                    {"role": "assistant", "content": ai_response}
                ])
                
                # Keep only last 6 messages (3 exchanges)
                if len(self.conversation_memory[user_id]) > 6:
                    self.conversation_memory[user_id] = self.conversation_memory[user_id][-6:]
                
                logger.info(f"✅ AI Response ({age_group}): {ai_response[:80]}...")
                
                # Ensure response is not too long
                sentences = ai_response.split('. ')
                if len(sentences) > 4:
                    ai_response = '. '.join(sentences[:4]) + '.'
                
                return ai_response
            else:
                error_msg = f"Groq API Error {response.status_code}: {response.text[:100]}"
                logger.error(error_msg)
                return f"Halo! Saya Calista. Mari kita belajar bersama!"
                
        except Exception as e:
            logger.error(f"💥 Error in Groq chat: {e}")
            return "Halo! Saya Calista, teman belajarmu yang ceria!"

# ==================== SPEECH TO TEXT ====================

class SpeechToText:
    def __init__(self):
        self.recognizer = sr.Recognizer()
        # Configure recognizer settings
        self.recognizer.energy_threshold = 300
        self.recognizer.dynamic_energy_threshold = True
        logger.info("✅ SpeechToText initialized (Google STT - Free)")
    
    def audio_to_text(self, audio_bytes: bytes) -> str:
        """Convert audio bytes to text using Google Speech Recognition (Free)"""
        try:
            logger.info("🎤 Processing audio with Google STT...")
            
            # Simpan ke file temporary
            with tempfile.NamedTemporaryFile(suffix='.wav', delete=False) as tmp_file:
                tmp_file.write(audio_bytes)
                tmp_file_path = tmp_file.name
            
            try:
                # Gunakan Google Speech Recognition
                with sr.AudioFile(tmp_file_path) as source:
                    # Adjust for ambient noise
                    self.recognizer.adjust_for_ambient_noise(source, duration=0.5)
                    
                    # Record audio
                    audio = self.recognizer.record(source)
                    
                    # Recognize using Google Web Speech API (FREE)
                    text = self.recognizer.recognize_google(
                        audio, 
                        language="id-ID",  # Bahasa Indonesia
                        show_all=False
                    )
                
                if text:
                    logger.info(f"✅ STT Result: {text}")
                    return text
                else:
                    logger.warning("STT: No speech detected")
                    return None
                    
            except sr.UnknownValueError:
                logger.warning("⚠️ STT: Could not understand audio")
                return None
            except sr.RequestError as e:
                logger.error(f"❌ STT Service error: {e}")
                # Fallback: return a friendly message
                return "Halo Calista"
            finally:
                # Cleanup temporary file
                if os.path.exists(tmp_file_path):
                    os.unlink(tmp_file_path)
                    
        except Exception as e:
            logger.error(f"💥 STT Error: {e}")
            return None
    
    def get_audio_duration(self, audio_bytes):
        """Get approximate audio duration"""
        # Very rough estimate: 16kHz, 16-bit mono
        # Each sample is 2 bytes, so bytes / 32000 = seconds
        return len(audio_bytes) / 32000  # Rough estimation

# ==================== VOICE AGENT ====================

class VoiceAgent:
    def __init__(self):
        self.tts = TypecastTTS()
        self.ai = GroqAI()
        self.stt = SpeechToText()
        logger.info("✅ Voice Agent initialized")
    
    def get_voice_settings(self, age_group="5-7"):
        """Get voice settings based on age group"""
        settings = {
            "3-5": {
                "voice_id": TYPECAST_CONFIG["voices"]["child_female"],
                "pitch": 8,       # Very high for toddlers
                "tempo": 0.85,    # Slow
                "emotion": "excited",
                "emotion_intensity": 1.5
            },
            "5-7": {
                "voice_id": TYPECAST_CONFIG["voices"]["child_female"],
                "pitch": 6,       # High
                "tempo": 0.9,     # Slightly slow
                "emotion": "happy",
                "emotion_intensity": 1.3
            },
            "7-9": {
                "voice_id": TYPECAST_CONFIG["voices"]["child_female"],
                "pitch": 4,       # Medium-high
                "tempo": 0.95,    # Normal-slow
                "emotion": "friendly",
                "emotion_intensity": 1.2
            },
            "9-12": {
                "voice_id": TYPECAST_CONFIG["voices"]["child_female"],
                "pitch": 2,       # Medium
                "tempo": 1.0,     # Normal
                "emotion": "friendly",
                "emotion_intensity": 1.0
            }
        }
        return settings.get(age_group, settings["5-7"])
    
    def format_for_children(self, text):
        """Format text to be more child-friendly"""
        # Simple formatting: add excitement for children
        child_phrases = ["Wah!", "Hore!", "Asyik!", "Keren!", "Luar biasa!"]
        
        import random
        sentences = text.split('. ')
        
        # Add child phrases to some sentences
        if len(sentences) > 1 and random.random() > 0.5:
            phrase = random.choice(child_phrases)
            sentences[0] = f"{phrase} {sentences[0]}"
        
        return '. '.join(sentences)
    
    def process_audio_pipeline(self, audio_bytes: bytes, age_group="5-7", user_id="default") -> dict:
        """Full pipeline: Audio → STT → AI → TTS → Audio"""
        print("\n" + "="*70)
        print("🎤 CALISTA VOICE PIPELINE STARTED")
        print("="*70)
        
        # 1. STT: Audio → Text (GRATIS dengan Google Speech Recognition)
        print("1. 🎤 CONVERTING AUDIO TO TEXT (Google STT - FREE)...")
        start_time = time.time()
        
        user_text = self.stt.audio_to_text(audio_bytes)
        
        stt_time = time.time() - start_time
        print(f"   ⏱️  STT Time: {stt_time:.1f}s")
        
        if not user_text:
            return {
                "success": False,
                "error": "Tidak dapat mengenali suara. Coba lagi dengan suara yang lebih jelas.",
                "pipeline_step": "stt"
            }
        
        print(f"   📝 Anda berkata: \"{user_text}\"")
        
        # 2. GROQ AI: Text → AI Response (Child-friendly)
        print(f"\n2. 🤖 GETTING AI RESPONSE (Age: {age_group})...")
        ai_response = self.ai.chat(user_text, user_id, age_group)
        print(f"   💬 Calista: \"{ai_response}\"")
        
        # 3. Format untuk anak-anak
        formatted_response = self.format_for_children(ai_response)
        
        # 4. Get voice settings based on age
        voice_settings = self.get_voice_settings(age_group)
        
        # 5. TTS: AI Response → Audio (Child voice)
        print(f"\n3. 🔊 CONVERTING TO CHILD VOICE (Pitch: {voice_settings['pitch']})...")
        tts_result = self.tts.text_to_speech(
            text=formatted_response,
            voice_id=voice_settings["voice_id"],
            emotion=voice_settings["emotion"],
            emotion_intensity=voice_settings["emotion_intensity"],
            pitch=voice_settings["pitch"],
            tempo=voice_settings["tempo"]
        )
        
        if not tts_result["success"]:
            return {
                "success": False,
                "error": tts_result.get("error", "Gagal menghasilkan suara"),
                "user_text": user_text,
                "ai_response": ai_response,
                "pipeline_step": "tts"
            }
        
        total_time = time.time() - start_time
        print(f"   ✅ Audio generated: {len(tts_result['audio_content']):,} bytes")
        print(f"   ⏱️  Total Pipeline Time: {total_time:.1f}s")
        print("="*70)
        print("🎉 PIPELINE COMPLETE!")
        print("="*70)
        
        return {
            "success": True,
            "user_text": user_text,
            "ai_response": ai_response,
            "formatted_response": formatted_response,
            "audio_content": tts_result["audio_content"],
            "content_type": tts_result["content_type"],
            "age_group": age_group,
            "processing_time": total_time
        }

# ==================== INITIALIZE ====================

agent = VoiceAgent()

# ==================== FLASK ROUTES ====================

@app.route('/')
def home():
    return jsonify({
        "status": "success",
        "message": "🎤 Calista Voice Agent - STT Gratis Edition",
        "version": "1.0.0",
        "pipeline": "Audio → Google STT (FREE) → Groq AI → Typecast TTS → Audio",
        "features": [
            "Speech Recognition: Google STT (Gratis)",
            "AI: Groq Llama 3.3 70B",
            "TTS: Typecast AI (Suara anak-anak)",
            "Age-specific responses (3-12 tahun)",
            "Real-time audio processing"
        ],
        "endpoints": {
            "POST /api/voice_chat": "Audio file → Audio response (FULL PIPELINE)",
            "POST /api/chat": "Text → Audio response",
            "POST /api/tts": "Text → Audio (Child voice)",
            "POST /api/stt": "Audio → Text",
            "GET /health": "Health check"
        }
    })

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        "status": "healthy",
        "service": "Calista Voice Agent",
        "timestamp": datetime.now().isoformat(),
        "components": {
            "stt": "Google Speech Recognition (FREE)",
            "ai": f"Groq {agent.ai.config['model']}",
            "tts": "Typecast AI",
            "memory": f"{len(agent.ai.conversation_memory)} active conversations"
        },
        "age_groups": ["3-5", "5-7", "7-9", "9-12"]
    })

@app.route('/api/voice_chat', methods=['POST'])
def voice_chat():
    """
    FULL PIPELINE: Audio → STT → AI → TTS → Audio
    Expects: audio file in form-data, optional age_group
    Returns: MP3 audio response
    """
    try:
        print("\n" + "="*60)
        print("🔔 VOICE CHAT REQUEST RECEIVED")
        print("="*60)
        
        # Check for audio file
        if 'audio' not in request.files:
            return jsonify({
                "status": "error",
                "message": "Tidak ada file audio. Kirim file audio dengan field 'audio'."
            }), 400
        
        audio_file = request.files['audio']
        
        if audio_file.filename == '':
            return jsonify({
                "status": "error",
                "message": "File audio tidak valid"
            }), 400
        
        logger.info(f"🎧 Received audio file: {audio_file.filename}")
        
        # Read audio bytes
        audio_bytes = audio_file.read()
        logger.info(f"📏 Audio size: {len(audio_bytes)} bytes")
        
        # Get age group (default 5-7 years)
        age_group = request.form.get('age_group', '5-7')
        user_id = request.form.get('user_id', 'default')
        
        print(f"👶 Age group: {age_group}")
        print(f"👤 User ID: {user_id}")
        
        # Process through full pipeline
        result = agent.process_audio_pipeline(audio_bytes, age_group, user_id)
        
        if result["success"]:
            # Return audio response with metadata headers
            return Response(
                result["audio_content"],
                mimetype='audio/mpeg',
                headers={
                    'Content-Disposition': 'inline',
                    'Cache-Control': 'no-cache',
                    'X-STT-Text': result.get("user_text", "")[:100],
                    'X-AI-Response': result.get("ai_response", "")[:150],
                    'X-Age-Group': result.get("age_group", "5-7"),
                    'X-Processing-Time': str(result.get("processing_time", 0))
                }
            )
        else:
            error_msg = result.get("error", "Processing failed")
            pipeline_step = result.get("pipeline_step", "unknown")
            
            logger.error(f"❌ Pipeline failed at {pipeline_step}: {error_msg}")
            
            # Try to send error as audio
            error_audio = agent.tts.text_to_speech(
                f"Maaf, ada masalah: {error_msg}. Coba lagi ya!",
                pitch=6,
                tempo=0.9
            )
            
            if error_audio["success"]:
                return Response(
                    error_audio["audio_content"],
                    mimetype='audio/mpeg',
                    headers={'Cache-Control': 'no-cache'}
                )
            else:
                return jsonify({
                    "status": "error",
                    "message": error_msg,
                    "user_text": result.get("user_text"),
                    "ai_response": result.get("ai_response"),
                    "step": pipeline_step
                }), 400
            
    except Exception as e:
        logger.error(f"💥 Error in voice_chat: {e}")
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

@app.route('/api/chat', methods=['POST'])
def text_chat():
    """Text → AI → Audio (with age-specific voice)"""
    try:
        data = request.json or {}
        message = data.get('message', '').strip()
        age_group = data.get('age_group', '5-7')
        user_id = data.get('user_id', 'default')
        
        if not message:
            return jsonify({"status": "error", "message": "Message required"}), 400
        
        logger.info(f"💬 Text chat (age {age_group}): {message[:50]}...")
        
        # Get AI response
        ai_response = agent.ai.chat(message, user_id, age_group)
        logger.info(f"🤖 AI: {ai_response[:50]}...")
        
        # Format for children
        formatted_response = agent.format_for_children(ai_response)
        
        # Get voice settings
        voice_settings = agent.get_voice_settings(age_group)
        
        # Convert to audio with child voice
        tts_result = agent.tts.text_to_speech(
            text=formatted_response,
            voice_id=voice_settings["voice_id"],
            emotion=voice_settings["emotion"],
            emotion_intensity=voice_settings["emotion_intensity"],
            pitch=voice_settings["pitch"],
            tempo=voice_settings["tempo"]
        )
        
        if tts_result["success"]:
            return Response(
                tts_result["audio_content"],
                mimetype='audio/mpeg',
                headers={
                    'Content-Disposition': 'inline',
                    'Cache-Control': 'no-cache',
                    'X-AI-Response': ai_response[:150],
                    'X-Age-Group': age_group
                }
            )
        else:
            return jsonify({
                "status": "error",
                "message": "Gagal menghasilkan suara",
                "ai_response": ai_response
            }), 500
            
    except Exception as e:
        logger.error(f"Error in text_chat: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

@app.route('/api/tts', methods=['POST'])
def tts():
    """Text → Audio (direct TTS with child voice)"""
    try:
        data = request.json or {}
        text = data.get('text', '').strip()
        age_group = data.get('age_group', '5-7')
        
        if not text:
            return jsonify({"status": "error", "message": "Text required"}), 400
        
        logger.info(f"🔊 TTS request (age {age_group}): {text[:50]}...")
        
        # Format for children
        formatted_text = agent.format_for_children(text)
        
        # Get voice settings
        voice_settings = agent.get_voice_settings(age_group)
        
        tts_result = agent.tts.text_to_speech(
            text=formatted_text,
            voice_id=voice_settings["voice_id"],
            emotion=voice_settings["emotion"],
            emotion_intensity=voice_settings["emotion_intensity"],
            pitch=voice_settings["pitch"],
            tempo=voice_settings["tempo"]
        )
        
        if tts_result["success"]:
            return Response(
                tts_result["audio_content"],
                mimetype='audio/mpeg',
                headers={'Cache-Control': 'no-cache'}
            )
        else:
            return jsonify({
                "status": "error",
                "message": "TTS failed",
                "details": tts_result.get("error")
            }), 400
            
    except Exception as e:
        logger.error(f"Error in TTS: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

@app.route('/api/stt', methods=['POST'])
def stt():
    """Audio → Text only (FREE Google Speech Recognition)"""
    try:
        # Check for audio file
        if 'audio' not in request.files:
            return jsonify({
                "status": "error",
                "message": "No audio file provided"
            }), 400
        
        audio_file = request.files['audio']
        
        if audio_file.filename == '':
            return jsonify({"status": "error", "message": "Invalid audio file"}), 400
        
        # Read audio bytes
        audio_bytes = audio_file.read()
        logger.info(f"🎧 STT request: {len(audio_bytes)} bytes")
        
        # Convert to text
        start_time = time.time()
        text = agent.stt.audio_to_text(audio_bytes)
        processing_time = time.time() - start_time
        
        if text:
            return jsonify({
                "status": "success",
                "text": text,
                "processing_time": f"{processing_time:.2f}s",
                "engine": "Google Speech Recognition (FREE)"
            })
        else:
            return jsonify({
                "status": "error",
                "message": "Could not understand audio",
                "processing_time": f"{processing_time:.2f}s"
            }), 400
            
    except Exception as e:
        logger.error(f"Error in STT: {e}")
        return jsonify({"status": "error", "message": str(e)}), 500

@app.route('/api/test', methods=['GET'])
def test_endpoint():
    """Test endpoint with sample audio generation"""
    test_text = "Halo! Aku Calista, teman belajarmu. Yuk kita belajar bersama!"
    
    tts_result = agent.tts.text_to_speech(
        text=test_text,
        pitch=6,
        tempo=0.9
    )
    
    if tts_result["success"]:
        return Response(
            tts_result["audio_content"],
            mimetype='audio/mpeg',
            headers={'Content-Disposition': 'inline'}
        )
    else:
        return jsonify({
            "status": "error",
            "message": "Test failed"
        }), 500

# ==================== STORY AUDIO ENDPOINTS (CERITA RAKYAT) ====================

@app.route('/api/story/audio', methods=['POST'])
def story_audio():
    """Generate audio for story page text"""
    try:
        data = request.json or {}
        story_id = data.get('story_id', 'story')
        page_number = data.get('page_number', 1)
        story_text = data.get('story_text', '').strip()
        age_group = data.get('age_group', '5-7')
        save_to_cache = data.get('save_to_cache', False)
        
        if not story_text:
            return jsonify({
                "status": "error",
                "message": "Story text required"
            }), 400
        
        logger.info(f"🎨 Story Audio Request - Story: {story_id}, Page: {page_number}, Age: {age_group}")
        logger.info(f"   📝 Text: {story_text[:60]}...")
        
        # Get voice settings based on age group
        voice_settings = agent.get_voice_settings(age_group)
        
        # Generate TTS
        tts_result = agent.tts.text_to_speech(
            text=story_text,
            voice_id=voice_settings["voice_id"],
            emotion=voice_settings["emotion"],
            emotion_intensity=voice_settings["emotion_intensity"],
            pitch=voice_settings["pitch"],
            tempo=voice_settings["tempo"]
        )
        
        if tts_result["success"]:
            logger.info(f"✅ Story audio generated: {len(tts_result['audio_content'])} bytes")
            return Response(
                tts_result["audio_content"],
                mimetype='audio/mpeg',
                headers={
                    'Content-Disposition': 'inline',
                    'Cache-Control': 'no-cache',
                    'X-Story-ID': story_id,
                    'X-Page-Number': str(page_number),
                    'X-Age-Group': age_group,
                    'X-Audio-Type': 'story-page'
                }
            )
        else:
            logger.error(f"❌ Failed to generate story audio: {tts_result.get('error')}")
            return jsonify({
                "status": "error",
                "message": "Failed to generate audio",
                "details": tts_result.get('error')
            }), 500
            
    except Exception as e:
        logger.error(f"💥 Error in story_audio: {e}")
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

@app.route('/api/story/question', methods=['POST'])
def story_question():
    """Generate audio for story question (real-time TTS)"""
    try:
        data = request.json or {}
        question = data.get('question', '').strip()
        age_group = data.get('age_group', '5-7')
        
        if not question:
            return jsonify({
                "status": "error",
                "message": "Question text required"
            }), 400
        
        logger.info(f"❓ Story Question Audio - Age: {age_group}")
        logger.info(f"   📝 Question: {question[:60]}...")
        
        # Get voice settings based on age group
        voice_settings = agent.get_voice_settings(age_group)
        
        # Generate TTS for question
        tts_result = agent.tts.text_to_speech(
            text=question,
            voice_id=voice_settings["voice_id"],
            emotion="friendly",
            emotion_intensity=1.2,
            pitch=voice_settings["pitch"],
            tempo=0.85  # Slightly slower for clarity
        )
        
        if tts_result["success"]:
            logger.info(f"✅ Question audio generated: {len(tts_result['audio_content'])} bytes")
            return Response(
                tts_result["audio_content"],
                mimetype='audio/mpeg',
                headers={
                    'Content-Disposition': 'inline',
                    'Cache-Control': 'no-cache',
                    'X-Question-Text': question[:100],
                    'X-Age-Group': age_group,
                    'X-Audio-Type': 'question'
                }
            )
        else:
            logger.error(f"❌ Failed to generate question audio: {tts_result.get('error')}")
            return jsonify({
                "status": "error",
                "message": "Failed to generate audio",
                "details": tts_result.get('error')
            }), 500
            
    except Exception as e:
        logger.error(f"💥 Error in story_question: {e}")
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

@app.route('/api/story/choice', methods=['POST'])
def story_choice():
    """Generate audio for story choice (real-time TTS)"""
    try:
        data = request.json or {}
        choice_text = data.get('choice_text', '').strip()
        age_group = data.get('age_group', '5-7')
        
        if not choice_text:
            return jsonify({
                "status": "error",
                "message": "Choice text required"
            }), 400
        
        logger.info(f"🎯 Story Choice Audio - Age: {age_group}")
        logger.info(f"   📝 Choice: {choice_text[:60]}...")
        
        # Get voice settings based on age group
        voice_settings = agent.get_voice_settings(age_group)
        
        # Generate TTS for choice
        tts_result = agent.tts.text_to_speech(
            text=choice_text,
            voice_id=voice_settings["voice_id"],
            emotion="happy",
            emotion_intensity=1.1,
            pitch=voice_settings["pitch"],
            tempo=0.9
        )
        
        if tts_result["success"]:
            logger.info(f"✅ Choice audio generated: {len(tts_result['audio_content'])} bytes")
            return Response(
                tts_result["audio_content"],
                mimetype='audio/mpeg',
                headers={
                    'Content-Disposition': 'inline',
                    'Cache-Control': 'no-cache',
                    'X-Choice-Text': choice_text[:100],
                    'X-Age-Group': age_group,
                    'X-Audio-Type': 'choice'
                }
            )
        else:
            logger.error(f"❌ Failed to generate choice audio: {tts_result.get('error')}")
            return jsonify({
                "status": "error",
                "message": "Failed to generate audio",
                "details": tts_result.get('error')
            }), 500
            
    except Exception as e:
        logger.error(f"💥 Error in story_choice: {e}")
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

@app.route('/api/story/voice', methods=['POST'])
def story_voice():
    """Process voice interaction with story character - FULL PIPELINE"""
    try:
        print("\n" + "="*70)
        print("🎤 STORY VOICE INTERACTION REQUEST")
        print("="*70)
        
        # Check for audio file
        if 'audio' not in request.files:
            return jsonify({
                "status": "error",
                "message": "Audio file required"
            }), 400
        
        audio_file = request.files['audio']
        story_context = request.form.get('story_context', '')
        current_page = request.form.get('current_page', '1')
        age_group = request.form.get('age_group', '5-7')
        user_id = request.form.get('user_id', 'default')
        character_name = request.form.get('character_name', 'Calista')
        
        if audio_file.filename == '':
            return jsonify({
                "status": "error",
                "message": "Invalid audio file"
            }), 400
        
        logger.info(f"👤 Character: {character_name}")
        logger.info(f"📖 Story Context: {story_context[:60]}...")
        logger.info(f"📄 Current Page: {current_page}")
        logger.info(f"👶 Age Group: {age_group}")
        
        # Read audio bytes
        audio_bytes = audio_file.read()
        logger.info(f"🎧 Audio size: {len(audio_bytes)} bytes")
        
        # Process through full pipeline
        result = agent.process_audio_pipeline(audio_bytes, age_group, user_id)
        
        if result["success"]:
            # Customize response with story context
            ai_response = result["ai_response"]
            
            # Optionally enhance response with story context
            if story_context:
                print(f"   📖 Story Context applied: {story_context[:50]}...")
            
            print(f"   💬 {character_name}: {ai_response}")
            print("="*70)
            
            return Response(
                result["audio_content"],
                mimetype='audio/mpeg',
                headers={
                    'Content-Disposition': 'inline',
                    'Cache-Control': 'no-cache',
                    'X-STT-Text': result.get("user_text", "")[:100],
                    'X-AI-Response': ai_response[:150],
                    'X-Character-Name': character_name,
                    'X-Age-Group': age_group,
                    'X-Current-Page': current_page,
                    'X-Audio-Type': 'story-voice'
                }
            )
        else:
            error_msg = result.get("error", "Processing failed")
            logger.error(f"❌ Voice processing failed: {error_msg}")
            
            # Try to send error message as audio
            error_audio = agent.tts.text_to_speech(
                f"Maaf, ada masalah: {error_msg}. Coba lagi ya!",
                pitch=6,
                tempo=0.9
            )
            
            if error_audio["success"]:
                return Response(
                    error_audio["audio_content"],
                    mimetype='audio/mpeg',
                    headers={'Cache-Control': 'no-cache'}
                )
            else:
                return jsonify({
                    "status": "error",
                    "message": error_msg,
                    "user_text": result.get("user_text"),
                    "ai_response": result.get("ai_response")
                }), 400
            
    except Exception as e:
        logger.error(f"💥 Error in story_voice: {e}")
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

@app.route('/api/story/cache/clear', methods=['POST'])
def story_cache_clear():
    """Clear audio cache for a story"""
    try:
        data = request.json or {}
        story_id = data.get('story_id', '')
        
        logger.info(f"🗑️  Clearing cache for story: {story_id}")
        
        # In this simple version, we just return success
        # In a more complex setup, you might manage actual cache files
        
        return jsonify({
            "status": "success",
            "message": f"Cache cleared for story: {story_id}",
            "story_id": story_id,
            "cleared_at": datetime.now().isoformat()
        })
        
    except Exception as e:
        logger.error(f"Error clearing cache: {e}")
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

# ==================== ERROR HANDLERS ====================

@app.errorhandler(404)
def not_found(error):
    return jsonify({
        "status": "error",
        "message": "Endpoint not found",
        "available_endpoints": [
            "POST /api/voice_chat - Full voice chat",
            "POST /api/chat - Text to audio chat",
            "POST /api/tts - Text to speech",
            "POST /api/stt - Speech to text",
            "GET /health - Health check",
            "GET /api/test - Test endpoint",
            "POST /api/story/audio - Generate story audio",
            "POST /api/story/question - Generate question audio",
            "POST /api/story/choice - Generate choice audio",
            "POST /api/story/voice - Voice interaction with story",
            "POST /api/story/cache/clear - Clear audio cache"
        ]
    }), 404

@app.errorhandler(500)
def internal_error(error):
    logger.error(f"Internal Server Error: {error}")
    return jsonify({
        "status": "error",
        "message": "Internal server error"
    }), 500

# ==================== RUN SERVER ====================

if __name__ == '__main__':
    print("\n" + "="*70)
    print("🎤 CALISTA VOICE AGENT SERVER - STT GRATIS")
    print("="*70)
    print("📡 Pipeline: Audio → Google STT (FREE) → Groq AI → Typecast TTS → Audio")
    print(f"🤖 AI Model: {agent.ai.config['model']}")
    print("💰 STT: FREE (Google Speech Recognition)")
    print("👶 Age Groups: 3-5, 5-7, 7-9, 9-12 tahun")
    print("="*70)
    print("\n🌐 ENDPOINTS:")
    print("1. POST /api/voice_chat        - Audio file → Audio response (FULL PIPELINE)")
    print("2. POST /api/chat              - Text → Audio response")
    print("3. POST /api/tts               - Text → Audio (Child voice)")
    print("4. POST /api/stt               - Audio → Text (FREE)")
    print("5. GET  /health                - Health check")
    print("6. GET  /api/test              - Test endpoint")
    print("\n🎨 CERITA RAKYAT AI ENDPOINTS:")
    print("7. POST /api/story/audio       - Generate story page audio")
    print("8. POST /api/story/question    - Generate question audio")
    print("9. POST /api/story/choice      - Generate choice audio")
    print("10. POST /api/story/voice      - Voice interaction with story")
    print("11. POST /api/story/cache/clear - Clear audio cache")
    print("\n📝 EXAMPLE CURL COMMANDS:")
    print('   # Full voice chat:')
    print('   curl -X POST http://localhost:5000/api/voice_chat \\')
    print('        -F "audio=@recording.wav" \\')
    print('        -F "age_group=5-7" \\')
    print('        -o response.mp3')
    print('')
    print('   # STT only:')
    print('   curl -X POST http://localhost:5000/api/stt \\')
    print('        -F "audio=@recording.wav"')
    print('')
    print('   # Text to speech:')
    print('   curl -X POST http://localhost:5000/api/tts \\')
    print('        -H "Content-Type: application/json" \\')
    print('        -d \'{"text":"Halo Calista!", "age_group":"5-7"}\' \\')
    print('        -o calista.mp3')
    print("="*70)
    print("🚀 Server starting on http://0.0.0.0:5003")
    print("="*70)
    
    # Run the server
    app.run(host='0.0.0.0', port=5003, debug=False, threaded=True)