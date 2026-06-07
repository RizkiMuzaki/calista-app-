# 🎓 LEARNING NOTES - CALISTA PROJECT

## 📋 Concepts Learned

### 1. Dynamic Navigation & State (Flutter)
- **Problem**: Hardcoded constants (seperti `childId = 1`) membuat aplikasi tidak fleksibel untuk multiple profile.
- **Solution**: Menggunakan Constructor parameters di Flutter untuk mem-passing data antar screen (`HomeScreen -> GameSelectionScreen`).
- **Analogy**: Seperti membawa kunci yang benar ke pintu yang benar, bukan menggunakan kunci duplikat yang sama untuk semua pintu.

### 2. Secure Logout Flow (Flutter)
- **Concept**: User session harus di-clear secara lokal (`SharedPreferences.clear()`) dan navigasi harus di-reset (`pushNamedAndRemoveUntil`) agar user tidak bisa memencet tombol "Back" ke halaman sensitif setelah logout.
- **Security Check**: Penambahan confirmation dialog sangat krusial untuk mencegah accidental logouts.

### 3. Backend Validation (Laravel)
- **Concept**: "Never trust the frontend". Selalu validasi kondisi bisnis di server.
- **Implementation**: Menggunakan logic di Controller (`claimReward`) untuk mengecek apakah level completion anak sudah mencukupi target `reward_condition`.
- **Analogy**: Seperti kasir yang mengecek struk belanja sebelum memberikan hadiah gratis, bukan sekadar percaya kata pembeli.

### 4. Type-Hinting & Code Quality (PHP)
- **Benefit**: Menambahkan tipe data pada properti dan return value meningkatkan readability dan mencegah bug "Unexpected Type".
- **Ref**: Menggunakan `int`, `string`, `bool` pada Laravel Controllers.

### 5. Canvas Tracing & Guided Animating (Flutter)
- **Problem**: Balita kesulitan menggambar huruf tanpa contoh arah tarikan garis, dan kanvas statis membosankan bagi mereka.
- **Solution**: Merancang normalized 2D key points (0.0 - 1.0) untuk seluruh huruf A-Z, lalu memetakan titik koordinat tersebut secara real-time berdasarkan TextPainter bounds dari huruf raksasa. Menambahkan kursor pembimbing bergerak berbentuk 👆 dengan glowing trail saat kanvas kosong yang langsung disembunyikan begitu anak mulai menggambar.

### 6. Instant Audio Cancellation & Walkthrough Auto-Play (TTS Engine)
- **Concept**: User experience (UX) balita membutuhkan respons suara tanpa delay dan interaksi klik tambahan. 
- **Implementation**: Mengaktifkan `NusaAiService` di semua story walkthrough secara otomatis di `initState`. Menggunakan pemanggilan `stopPlayback()` di awal proses pemutaran suara baru guna meng-increment `playbackRequestId`, mematikan pemutar audio yang sedang berjalan seketika, dan membatalkan antrean network fetch suara dari halaman sebelumnya. 
- **Analogy**: Seperti menekan tombol 'Next' di CD player lama yang langsung memutus track saat ini dan memulai lagu berikutnya tanpa menunggu buffer, mencegah tumpang tindih suara saat anak membalik halaman dengan cepat.

### 7. Port Alignment & Automatic Hybrid Fallback (TTS Engine)
- **Problem**: Inkonsistensi port server di berbagai game controller (port 5000, 5002) dan URL ngrok yang mati di dongeng menyebabkan suara hilang, hang, atau delay tinggi. Ketergantungan penuh pada satu engine (Typecast) berisiko jika kuota API key habis atau error.
- **Solution**: Penyelarasan seluruh controller Laravel ke single-source-of-truth port 5003 (`services.voice_agent.url`) dan implementasi wrapper `FallbackTTS` di server Python yang memprioritaskan Typecast premium (suara anak imut), lalu otomatis secara instan fallback ke EdgeTTS dengan penyesuaian pitch/tempo ramah anak jika terjadi error/limit kuota.
- **Analogy**: Seperti mobil hybrid yang berjalan dengan motor listrik premium namun secara instan memicu mesin bensin cadangan jika baterai habis, memastikan perjalanan tidak pernah mogok di tengah jalan.

---
## 🚀 FUTURE UPGRADE ROADMAP: WEBSOCKET AUDIO STREAMING

Berdasarkan insight luar biasa dari Boss, berikut adalah panduan arsitektur masa depan untuk meningkatkan responsivitas chat interaktif (Nusa Chat) menggunakan **WebSocket Audio Streaming** secara langsung dari Flutter ke Python (bypassing Laravel proxy untuk dynamic chat):

### 1. Arsitektur WebSocket Python (FastAPI)
Mengganti Flask dengan FastAPI untuk mendukung WebSocket asinkron berkecepatan tinggi:
```python
@app.websocket("/ws/tts")
async def websocket_endpoint(websocket: WebSocket):
    await websocket.accept()
    while True:
        text = await websocket.receive_text()
        communicate = edge_tts.Communicate(text, "id-ID-GadisNeural")
        async for chunk in communicate.stream():
            if chunk["type"] == "audio":
                await websocket.send_bytes(chunk["data"])
        await websocket.send_bytes(b"EOF")
```

### 2. Implementasi Flutter Player
Menggunakan `web_socket_channel` dan `audioplayers` dengan `BytesSource` di Flutter untuk langsung memutar potongan bytes audio begitu diterima tanpa menunggu seluruh paragraf selesai dibuat:
```dart
_channel.stream.listen((message) {
  if (message is List<int>) {
    _audioPlayer.play(BytesSource(Uint8List.fromList(message)));
  }
});
```

### 3. Analisis Kapan Harus Menggunakan WebSocket vs HTTP Cache
- **Latihan & Soal (HTTP Cache - Saat Ini)**: Karena teksnya statis dan berulang (seperti instruksi menggambar, soal berhitung), **HTTP Cache SHA-256 lokal** jauh lebih efisien karena mengembalikan suara instan **(<5ms)** langsung dari disk tanpa perlu koneksi WebSocket.
- **Chat Bebas (WebSocket Streaming - Upgrade Mendatang)**: Untuk percakapan bebas dengan AI Nusa (Nusa Chat) di mana teksnya dinamis hasil keluaran LLM, **WebSocket Streaming** adalah metode terbaik untuk menurunkan latensi TTFB hingga di bawah 1 detik.

### 8. CLI Zip Fallback & Storage Disk Separation (Offline Sync)
- **Problem**: Laravel queue listener di CLI PHP XAMPP tidak memiliki ekstensi `ZipArchive` terpasang, menyebabkan error fatal saat kompresi pack audio anak. Selain itu, penyimpanan audio ke disk `local` default (privat) memicu error 404 saat diakses via URL publik.
- **Solution**:
  1. Menambahkan pengecekan `class_exists('ZipArchive')` dan fallback dinamis menggunakan CLI command `tar` atau PowerShell `Compress-Archive` di Windows.
  2. Mengalihkan seluruh proses penyimpanan audio pack dan ZIP ke disk `public` (`Storage::disk('public')`) agar tersimpan di direktori `storage/app/public` yang terhubung via symlink ke folder akses web.
  3. Memperbaiki logika penanganan error di Flutter (`audio_sync_screen.dart`) dengan mendeteksi status `'failed'` secara eksplisit untuk langsung menavigasi ke `HomeScreen` (mode toleransi) daripada melakukan loop tanpa henti pada progress 0%.

### 9. Louvin Subscription Price & ID Alignment & SQL 500 Fix (June 2026)
- **Problem 1**: Swapped Weekly and Yearly Louvin plan IDs in `.env` caused wrong API resolution. Prices and names in `PlanSeeder` and Flutter `premium_subscription_modal.dart` were also outdated compared to Louvin's dashboard.
- **Problem 2**: Endpoint `/api/subscription/subscribe` threw a `500 Internal Server Error` due to `SQLSTATE[HY000]: General error: 1364 Field 'reference' doesn't have a default value` because the generated transaction reference was never passed to the `Payment::create` query.
- **Solution**:
  1. Corrected the swapped environment variables (`LOUVIN_WEEKLY_PLAN_ID` and `LOUVIN_YEARLY_PLAN_ID`) in `.env`.
  2. Fixed the SQL constraint bug by adding `'reference' => $reference,` to the `Payment::create` model instantiation query inside `SubscriptionController.php`.
  3. Synced `PlanSeeder.php` to seed the exact prices and names (e.g. `Calista Edu Plan Mingguan` at `26000`, `Calista Plus Bulanan` at `130000`, and `Calista Plus Tahunan` at `866000`), then purged and re-seeded the database.
  4. Updated hardcoded display prices in Flutter's `premium_subscription_modal.dart` (`Rp 26.000` for weekly and `Rp 130.000` for monthly) to prevent user pricing mismatch display.

---
*Next Topic Idea: State Management, Cache Invalidation & API Middleware Performance.*

