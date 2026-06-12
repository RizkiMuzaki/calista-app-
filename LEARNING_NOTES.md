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

### 10. Livewire/Filament Spatie Media Library Upload Size Crash Fix (June 2026)
- **Problem**: Jika validasi form gagal (misal: duplikasi slug dongeng), Livewire melakukan re-render. Selama re-render ini, file temporary yang sudah terupload dibersihkan atau dipindahkan dari path aslinya. Validasi berikutnya mencoba mengecek file size atau mime type pada path temporary (yang ter-fallback menjadi `livewire-tmp/livewire-tmp`), menyebabkan Flysystem melempar exception fatal `UnableToRetrieveMetadata` yang memicu HTTP 500 alih-alih menampilkan error validasi normal.
- **Solution**:
  1. Mengkonfigurasi Spatie Media Library secara proaktif di [Story.php](file:///d:/CALISTA%20MOBILE/calista_backend/CALISTA/app/Models/Story.php) menggunakan `singleFile()` pada koleksi `cover`, `full_narration`, dan `full_animation`.
  2. Menghapus validator default `maxSize()` dan `acceptedFileTypes()` di [StoryResource.php](file:///d:/CALISTA%20MOBILE/calista_backend/CALISTA/app/Filament/Resources/StoryResource.php) yang memicu panggilan langsung ke metadata Flysystem.
  3. Menggantinya dengan custom validation rules menggunakan penanganan `try-catch`. Jika path temporary corrupt/hilang, validator akan menangkap exception secara aman dan melempar pesan error validasi yang bersih ke UI ("File tidak valid atau gagal diupload. Silakan upload ulang.") alih-alih membuat server crash 500.

### 11. Star Reward Lock, Instant Tap Interaction, and Random Chest Celebration (June 2026)
- **Problem**: 
  1. Baju "Sahabat Gajah" (Nusa Elephant Ranger) tidak sinkron antara logika perolehan (12 bintang via chest) dengan database seeder dan model subscription (weekly bonus) yang memicu status auto-unlock instan.
  2. Klik item yang sudah dimiliki dan item premium terkunci mengalami delay karena membuka detail screen terlebih dahulu.
  3. Efek visual saat equip baju dinilai monoton dan kurang menyerupai sensasi membuka peti harta karun (chest).
  4. Frame gameplay level 4 counting berwarna hijau bertabrakan dengan visual identity game.
- **Solution**:
  1. **Backend Database & Seeder**: Menambahkan `star_reward` ke kolom ENUM `unlock_type` pada tabel `character_items` menggunakan raw migration query, memodifikasi `CharacterItemSeeder` agar Nusa Elephant Ranger bertipe `star_reward` dengan syarat 12 bintang, serta membersihkan record existing kepemilikannya.
  2. **Subscription & Shop Controller**: Mengalihkan bonus plan mingguan ke `Nusa Panda Scout`, dan memperbarui `claimReward` untuk memvalidasi akumulasi `sum('bintang')` dari progres belajar anak di backend.
  3. **Instant Tap Interaction**: Memodifikasi handler `_openDetail` di mobile agar mem-bypass detail screen; owned items langsung di-equip secara instan, dan locked premium items langsung memicu modal subscription.
  4. **Silent Claim untuk Star Reward**: Jika anak memiliki $\ge 12$ bintang tapi item belum ter-claim di database, client memicu silent POST `/api/shop/claim` before melakukan equip untuk menghindari error 404.
  5. **Random Chest Background & Animation**: Memperbarui visual perayaan equip dengan rotating sweep gradient, pulsing glow, dan pemilihan background chest secara acak dari folder asset (`6.webp`, `7.webp`, `8.webp`).
  6. **Visual Gameplay**: Mengubah accent color level 4 counting dari hijau (`0xFF7BD88F`) menjadi pink (`0xFFFF5E9A`).

### 12. Deletion of Story "Pulo Kemarau" and Louvin Webhook Verification (June 2026)
- **Problem**:
  1. Dongeng "Pulo Kemarau" harus dihapus bersih dari database beserta seluruh file media-nya.
  2. Perlu memverifikasi keamanan dan integritas penanganan webhook dari Louvin.
- **Solution**:
  1. **Story Deletion Migration**: Dibuat database migration `2026_06_10_104500_delete_pulo_kemarau_story.php` yang memanggil delete melalui Eloquent model `Story::where(...)` untuk memastikan event lifecycle `deleting` terpicu. Hal ini menjamin file media Spatie (cover, audio narasi, video animasi) di storage terhapus bersih dari disk, selain menghapus record relasi (pages, progress, reviews, likes) melalui constraint database `cascadeOnDelete`.
  2. **Louvin Webhook Audit**: Memverifikasi `louvinWebhook` di `SubscriptionController.php` aman menggunakan `hash_equals` timing-attack-resistant token verification. Jalur route `/api/payments/louvin/webhook` berada di luar proteksi middleware Sanctum dan CSRF secara default (aman untuk third-party API callbacks).

---
*Next Topic Idea: State Management, Cache Invalidation & API Middleware Performance.*


