hi# 🗺️ MASTER ROADMAP CALISTA — PANDUAN LENGKAP DARI NOL SAMPAI RILIS

> **Dokumen ini adalah "Kitab Suci" tim Hacker (Rizki & Tasya).**
> Setiap phase WAJIB dikerjakan 100% sebelum lanjut ke phase berikutnya.
> Simbol 🔑 menandakan task yang butuh **konfirmasi/koordinasi langsung dengan Tasya**.

---

## 📊 LEGENDA STATUS & OWNERSHIP

| Simbol                  | Arti                                     |
| :---------------------- | :--------------------------------------- |
| 👤 **RIZKI**            | Dikerjakan oleh Rizki (Backend/Laravel)  |
| 👩‍💻 **TASYA**            | Dikerjakan oleh Tasya (Frontend/Flutter) |
| 🤝 **BERDUA**           | Dikerjakan bareng / butuh koordinasi     |
| 🔑 **KONFIRMASI TASYA** | Tasya harus confirm sebelum lanjut       |
| 🔴                      | Belum dikerjakan                         |
| 🟡                      | Sedang dikerjakan                        |
| 🟢                      | Selesai 100%                             |

---

## ═══════════════════════════════════════════

## 📦 PHASE 0: PERSIAPAN ENVIRONMENT (Hari 1-2)

**Tujuan**: Semua tools siap, semua orang bisa ngoding tanpa hambat.

### 0.1 — Setup Laravel Backend 👤 RIZKI (80% DONE ✅)

- [x] � Pastikan XAMPP/Laragon berjalan dan database MySQL aktif.
- [x] � Clone repo CALISTA dari GitHub ke local (`git clone`).
- [x] � Jalankan `composer install` untuk install dependency PHP.
- [x] � Copy `.env.example` ke `.env` dan isi konfigurasi database.
- [x] � Jalankan `php artisan key:generate`.
- [x] � Jalankan `php artisan migrate --seed` untuk buat tabel & data dummy.
- [x] � Test: Buka `localhost/CALISTA/public` di browser, pastikan jalan.
- [x] � **CLEANUP**: ~~Hapus 30+ file MD legacy & 3 SQL dump~~ **SELESAI! 37 file dihapus ✅**

### 0.2 — Setup Flutter Frontend 👩‍💻 TASYA

- [ ] 🔴 Install Flutter SDK (versi stabil terbaru).
- [ ] 🔴 Install Android Studio + Android SDK.
- [ ] 🔴 Jalankan `flutter doctor` dan pastikan semua centang hijau.
- [ ] 🔴 Buat project baru: `flutter create calista_app`.
- [ ] 🔴 Pastikan bisa menjalankan `flutter run` di emulator/HP fisik.

### 0.3 — Setup Ngrok (Jembatan) 🤝 BERDUA (100% DONE ✅)

- [x] 🟢 👤 RIZKI: Download & install `ngrok` di laptop. **Ngrok v3.36.1 via winget ✅**
- [x] 🟢 👤 RIZKI: Jalankan `ngrok http 8000` untuk ekspos localhost. **DONE 14 Feb ✅**
- [x] 🟢 👤 RIZKI: Catat URL ngrok: `https://ena-uncanned-loyce.ngrok-free.dev` **DONE ✅**
- [x] 🟢 🔑 **KONFIRMASI TASYA**: `HANDOVER_TASYA.md` dibuat dengan URL + Flutter integration guide. **DONE 14 Feb ✅**
- [ ] 🔴 👩‍💻 TASYA: Test buka URL ngrok di browser HP, pastikan API CALISTA muncul.

### 0.4 — Setup GitHub Workflow 🤝 BERDUA

- [ ] 🔴 Buat repository GitHub baru untuk Flutter (`calista-flutter`).
- [ ] 🔴 Tentukan aturan branching:
    - `main` = Kode stabil.
    - `dev-rizki` = Branch kerja Rizki.
    - `dev-tasya` = Branch kerja Tasya.
- [ ] 🔴 🔑 **KONFIRMASI TASYA**: Pastikan Tasya sudah bisa push/pull ke repo.

**✅ PHASE 0 SELESAI JIKA**: Laravel jalan, Flutter jalan, Ngrok nyambung, GitHub siap.

---

## ═══════════════════════════════════════════

## 🗄️ PHASE 1: DATABASE & API DESIGN (Hari 3-5)

**Tujuan**: Semua "Pintu" (API Endpoint) sudah direncanakan dan database sudah benar.

### 1.1 — Desain Database (ERD) 👤 RIZKI (70% DONE ✅)

- [x] � Tabel `users` — Data akun orang tua (email, password, nama). **SUDAH ADA**
- [x] � Tabel `anaks` — Data profil anak (nama, umur, avatar). **SUDAH ADA**
- [x] � Tabel `progres_anaks` — Catatan progres belajar per modul. **SUDAH ADA**
- [x] � Tabel `modules` — Daftar materi (Membaca, Menulis, Berhitung). **SUDAH ADA**
- [x] � Tabel `levels` — Level kesulitan dalam setiap modul. **SUDAH ADA**
- [x] � ~~Tabel `timer_settings`~~ **TIDAK PERLU** — Timer sudah built-in di tabel `anaks`! ✅
- [x] � Tabel `character_items` — Daftar baju untuk Nusa. **MIGRATION READY** ✅
- [x] � Tabel `child_items` — Item yang sudah dimiliki anak. **MIGRATION READY** ✅
- [x] 🟢 ~~Tabel `coins`~~ **TIDAK PERLU** — Model Hybrid pakai reward + subscription, bukan koin.
- [x] � Buat migration file untuk 2 tabel baru (`character_items`, `child_items`). **DONE**
- [x] 🟢 Jalankan `php artisan migrate` — **DONE 13 Feb 2026** ✅

### 1.2 — Desain API Endpoint 👤 RIZKI

Daftar "Pintu" yang akan digunakan Flutter untuk ngobrol sama Laravel:

| No  | Method | Endpoint                   | Fungsi                        |
| :-- | :----- | :------------------------- | :---------------------------- |
| 1   | POST   | `/api/register`            | Daftar akun orang tua         |
| 2   | POST   | `/api/login`               | Login & dapat token           |
| 3   | POST   | `/api/logout`              | Logout & hapus token          |
| 4   | GET    | `/api/children`            | Ambil daftar anak             |
| 5   | POST   | `/api/children`            | Tambah profil anak baru       |
| 6   | PUT    | `/api/children/{id}`       | Update profil anak            |
| 7   | GET    | `/api/modules`             | Ambil daftar modul belajar    |
| 8   | GET    | `/api/modules/{id}/levels` | Ambil level dalam modul       |
| 9   | POST   | `/api/progress`            | Simpan progres belajar        |
| 10  | GET    | `/api/progress/{child_id}` | Ambil laporan progres anak    |
| 11  | GET    | `/api/timer/{child_id}`    | Ambil setting timer anak      |
| 12  | PUT    | `/api/timer/{child_id}`    | Update setting timer          |
| 13  | GET    | `/api/shop/items`          | Ambil daftar item toko        |
| 14  | POST   | `/api/shop/claim`          | Claim baju reward             |
| 15  | GET    | `/api/shop/inventory`      | Ambil baju yang dimiliki anak |
| 16  | POST   | `/api/shop/equip`          | Pasang baju ke karakter Nusa  |

### 1.3 — Review & Konfirmasi 🔑 KONFIRMASI TASYA

- [ ] 🔴 Kirim daftar endpoint di atas ke Tasya.
- [ ] 🔴 Tanya Tasya: "Ada data lain yang kamu butuhkan di Flutter?"
- [ ] 🔴 Finalisasi daftar endpoint setelah feedback Tasya.

**✅ PHASE 1 SELESAI JIKA**: Database siap, daftar API final, Tasya sudah approve.

---

## ═══════════════════════════════════════════

## ⚙️ PHASE 2: BACKEND DEVELOPMENT — API (Hari 6-14)

**Tujuan**: Semua API bisa dipanggil dan mengembalikan data yang benar.

### 2.1 — Authentication API 👤 RIZKI (100% DONE ✅✅)

- [x] � Install Laravel Sanctum (`composer require laravel/sanctum`). **v4.0 SUDAH TERINSTALL**
- [x] � Publish config Sanctum dan migrate. **SUDAH ADA `config/sanctum.php`**
- [x] Install Laravel Sanctum (`composer require laravel/sanctum`). **v4.0 SUDAH TERINSTALL**
- [x] Publish config Sanctum dan migrate. **SUDAH ADA `config/sanctum.php`**
- [x] Buat `AuthController` dengan method: **SUDAH ADA di `app/Http/Controllers/Api/AuthController.php`**
    - `register()` ✅
    - `login()` ✅
    - `logout()` ✅
    - `me()` ✅
    - `checkAuth()` ✅ (bonus)
- [x] 🟢 🔑 **KONFIRMASI TASYA**: `API_DOCUMENTATION.md` dibuat dengan contoh JSON lengkap ✅
- [ ] 🔴 Test di Postman/Insomnia (perlu re-test setelah cleanup).
- [x] 🟢 🔑 **KONFIRMASI TASYA**: `HANDOVER_TASYA.md` berisi contoh JSON lengkap untuk semua endpoint. **DONE 14 Feb ✅**

### 2.2 — Children API 👤 RIZKI (100% DONE ✅✅)

- [x] Model `Anak` **SUDAH ADA** (277 baris, sangat matang — timer + progress built-in!).
- [x] Buat `ChildController` (API) — **`app/Http/Controllers/Api/ChildController.php`**:
    - `GET /api/children` ✅ — Daftar anak milik user login.
    - `POST /api/children` ✅ — Tambah anak baru.
    - `GET /api/children/{id}` ✅ — Detail anak + progres + timer.
    - `PUT /api/children/{id}` ✅ — Edit profil anak.
    - `DELETE /api/children/{id}` ✅ — Hapus profil anak.
    - `POST /api/children/{id}/timer/start` ✅ — Mulai timer.
    - `POST /api/children/{id}/timer/stop` ✅ — Stop timer.
    - `GET /api/children/{id}/timer` ✅ — Cek status timer.
- [x] 🟢 Routes di `routes/api.php` — 8 endpoint baru (protected Sanctum).
- [x] `ChildSeeder` — 3 anak dummy (Aisyah 5th, Budi 7th, Cinta 4th).
- [ ] 🔴 Test di Postman: CRUD anak berfungsi dengan benar.

> ⚡ **BONUS**: Timer API juga selesai di sini! Tabel `anaks` sudah punya field timer — TIDAK perlu tabel `timer_settings` terpisah.

### 2.3 — Module & Level API 👤 RIZKI (100% DONE ✅✅)

- [x] ⚪ Model `Module` dan `Level` **SUDAH ADA**.
- [x] ⚪ `ModuleController` (API) **SUDAH ADA di `app/Http/Controllers/Api/ModuleController.php`**:
    - `index()` ✅ — Return semua modul.
    - `show()` ✅ — Detail modul.
    - `store()` ✅ — Tambah modul (admin).
    - `update()` ✅ — Edit modul (admin).
    - `destroy()` ✅ — Hapus modul (admin).
- [x] 🟢 Tambah endpoint `levels()` — Return level dalam satu modul + optional progress. **DONE 13 Feb** ✅
- [x] 🟢 Database sudah punya 5 modul dengan 21 levels. ✅

### 2.4 — Progress API 👤 RIZKI (100% DONE ✅✅)

- [x] 🟢 Model `ProgresAnak` sudah ada (anak_id, level_id, score, bintang, selesai).
- [x] 🟢 Buat `ProgressController` (`app/Http/Controllers/Api/ProgressController.php`): **DONE 13 Feb** ✅
    - `store()` ✅ — Simpan hasil belajar + auto-unlock reward baju.
    - `report()` ✅ — Laporan lengkap (V-A-K learning style, modul terkuat/terlemah, rekomendasi).
- [ ] 🔴 Test: Kirim data progres → cek apakah report berubah.

### 2.5 — Timer API 👤 RIZKI (100% DONE ✅✅ — Merged ke Children API)

- [x] ~~Buat Model `TimerSetting`~~ **TIDAK PERLU** — Timer sudah di Model `Anak`.
- [x] Timer endpoints sudah ada di `ChildController`:
    - `POST /api/children/{id}/timer/start` ✅ — Mulai timer.
    - `POST /api/children/{id}/timer/stop` ✅ — Stop timer.
    - `GET /api/children/{id}/timer` ✅ — Status + sisa waktu + should_lock.
    - Timer limit bisa diubah via `PUT /api/children/{id}` (field `limit_detik`).
- [x] 🟢 Tambah endpoint `POST /api/children/{id}/timer/verify-pin` untuk PIN unlock. **DONE 13 Feb** ✅

### 2.6 — Shop & Customization API 👤 RIZKI (Model C: Hybrid — CODE READY ✅)

- [x] Model `CharacterItem` (name, type, image_url, unlock_type: free/reward/premium). **DONE**
- [x] Model `ChildItem` (anak_id, character_item_id, is_equipped). **DONE**
- [x] `ShopController` (`app/Http/Controllers/Api/ShopController.php`): **DONE**
    - `GET /api/shop` ✅ — Daftar semua baju (+ status: owned/locked/premium).
    - `POST /api/shop/claim` ✅ — Claim baju reward setelah selesai level.
    - `GET /api/shop/inventory` ✅ — Daftar baju milik anak (lemari).
    - `POST /api/shop/equip` ✅ — Pasang baju ke karakter Nusa.
- [x] 🟢 `CharacterItemSeeder` — 6 baju (1 free + 2 reward + 3 premium). **UPDATED**
- [x] Routes di `api.php` — 4 endpoint Shop baru (protected Sanctum). **DONE**
- [x] 🟢 Relasi `childItems()` di Model `Anak`. **DONE**
- [x] 🟢 Jalankan `php artisan migrate` + `php artisan db:seed --class=CharacterItemSeeder` **DONE 13 Feb** ✅
- [ ] 🔴 Test di Postman: Shop flow berfungsi.

> 💳 **PAYMENT SUDAH ADA!** `PaymentController` (1310 baris), `Plan` model, `Subscription` model, tabel `payments` + `subscriptions` semua ready. Tinggal hubungkan premium items ke subscription system.

### 2.7 — Handover ke Tasya 🔑 KONFIRMASI TASYA (90% DONE ✅)

- [x] 🟢 Buat file `API_DOCUMENTATION.md` berisi: **DONE 13 Feb** ✅
    - 28 endpoint + method + contoh request body + contoh response JSON.
- [x] 🟢 Nyalakan Ngrok dan kirim URL final. **DONE 14 Feb — `https://ena-uncanned-loyce.ngrok-free.dev`** ✅
- [x] 🟢 Buat `HANDOVER_TASYA.md` — Flutter integration guide lengkap (Dio + http examples, auth flow, semua endpoint). **DONE 14 Feb** ✅
- [ ] 🔴 **Meeting singkat** (30 menit) dengan Tasya untuk walkthrough semua API.
- [ ] 🔴 Tasya test panggil 1-2 API dari Flutter → pastikan nyambung.

**✅ PHASE 2 SELESAI JIKA**: Semua 28 endpoint berfungsi, Tasya sudah terima dokumentasi & bisa panggil API.

---

## ═══════════════════════════════════════════

## 📱 PHASE 3: FLUTTER DEVELOPMENT — UI & INTEGRASI (Hari 10-21)

> **⚠️ Phase ini PARALEL dengan akhir Phase 2.**
> Tasya bisa mulai bikin UI dasar sejak Phase 2.3 selesai (Module API ready).

### 3.1 — Arsitektur Flutter 👩‍💻 TASYA (🔑 Konsultasi RIZKI)

- [ ] 🔴 Tentukan state management (Provider / Riverpod / BLoC).
- [ ] 🔴 Setup folder structure:
    ```
    lib/
    ├── models/        # Data class (User, Child, Module, dll)
    ├── services/      # API call handler (AuthService, ChildService)
    ├── screens/       # Halaman UI (LoginScreen, HomeScreen)
    ├── widgets/       # Komponen reusable (NusaAvatar, TimerWidget)
    └── utils/         # Helper & constants (ApiConfig, DesignTokens)
    ```
- [ ] 🔴 Buat `ApiConfig` class: Base URL (ngrok URL) + token storage.

### 3.2 — Halaman Auth (Login & Register) 👩‍💻 TASYA

- [ ] 🔴 UI: Form login (email + password) + tombol daftar.
- [ ] 🔴 Integrasi: Panggil `POST /api/login` → simpan token.
- [ ] 🔴 UI: Form register (nama + email + password + konfirmasi).
- [ ] 🔴 Integrasi: Panggil `POST /api/register`.
- [ ] 🔴 Navigasi: Setelah login sukses → masuk Home Screen.

### 3.3 — Halaman Home & Profil Anak 👩‍💻 TASYA

- [ ] 🔴 UI: Tampilkan daftar anak (avatar, nama, umur).
- [ ] 🔴 UI: Tombol "Tambah Anak" → form input.
- [ ] 🔴 Integrasi: `GET /api/children` dan `POST /api/children`.
- [ ] 🔴 UI: Pilih anak → masuk ke dashboard belajar anak tersebut.

### 3.4 — Halaman Belajar (Modul & Level) 👩‍💻 TASYA

- [ ] 🔴 UI: Grid modul (Membaca 📖, Menulis ✏️, Berhitung 🔢).
- [ ] 🔴 UI: Daftar level dalam modul (Level 1–5, dengan status bintang).
- [ ] 🔴 Integrasi: `GET /api/modules` dan `GET /api/modules/{id}/levels`.
- [ ] 🔴 Navigasi: Klik level → masuk ke halaman game/aktivitas.

### 3.5 — Halaman Karakter Nusa & Toko 👩‍💻 TASYA

- [ ] 🔴 UI: Tampilkan karakter Nusa full-body di tengah layar.
- [ ] 🔴 UI: Tab "Lemari" (inventory) → daftar baju yang dimiliki.
- [ ] 🔴 UI: Tab "Toko" → daftar baju (free/reward/premium) + status unlock.
- [ ] 🔴 UI: Toko Misterius (area premium tertutup awan).
- [ ] 🔴 Integrasi: `GET /api/shop`, `POST /api/shop/claim`, `POST /api/shop/equip`, `GET /api/shop/inventory`.
- [ ] 🔴 Animasi: Saat baju di-equip → karakter Nusa berubah tampilannya.

### 3.6 — Halaman Orang Tua (Parent Center) 🤝 BERDUA

- [ ] 🔴 UI: Dashboard progres anak (grafik, skor rata-rata).
- [ ] 🔴 UI: Pengaturan timer (input menit + set PIN).
- [ ] 🔴 UI: Laporan gaya belajar (V-A-K chart).
- [ ] 🔴 Integrasi: `GET /api/progress/{child_id}`, `PUT /api/timer/{child_id}`.
- [ ] 🔴 🔑 **KONFIRMASI TASYA**: Diskusikan layout dashboard bareng Rizki.

### 3.7 — Review UI/UX Berdua 🔑 KONFIRMASI TASYA

- [ ] 🔴 Tasya demo semua halaman ke Rizki via screenshare.
- [ ] 🔴 Catat bug atau kekurangan.
- [ ] 🔴 Finalisasi flow navigasi.

**✅ PHASE 3 SELESAI JIKA**: Semua halaman bisa diakses, semua API terhubung, data tampil di UI.

---

## ═══════════════════════════════════════════

## 🤖 PHASE 4: AI INTEGRATION (Hari 15-25)

**Tujuan**: Karakter Nusa "hidup" — bisa ngobrol, cek tulisan, dan dengerin suara anak.

### 4.1 — AI Voice (Friend-like Interaction) 👤 RIZKI (90% DONE ✅)

- [x] `voiceagent.py` sudah ada (1300+ baris, sangat matang!):
    - `TypecastTTS` — Text-to-Speech (suara anak) ✅
    - `GroqAI` — Chat AI pakai Llama 3.3 70B ✅
    - `SpeechToText` — Google STT (gratis) ✅
    - `VoiceAgent` — Full Pipeline (Audio→STT→AI→TTS→Audio) ✅
- [x] Endpoint sudah ada: `POST /api/text_chat` ✅
- [x] Endpoint sudah ada: `POST /api/voice_chat` ✅
- [x] 🟢 Endpoint story: `/api/story/audio`, `/api/story/voice`, dll ✅
- [x] 🟢 **Content Safety Filter**: `is_safe_for_children()` — 5 kategori blocked (kekerasan, dewasa, narkoba, self-harm, hate speech). **DONE 14 Feb ✅**
- [x] 🟢 System prompt ditambah instruksi redirect topik berbahaya ke topik belajar. **DONE 14 Feb ✅**
- [ ] 🔴 Upgrade persona menjadi "Teman Sebaya" (bahasa santai, empati).
- [x] 🟢 🔑 **KONFIRMASI TASYA**: Format response JSON sudah di `HANDOVER_TASYA.md` ✅

### 4.2 — AI Stroke Analysis (Menulis) 👤 RIZKI (100% DONE ✅✅)

- [x] 🟢 Buat endpoint Flask: `POST /api/stroke/analyze` — Terima data goresan (target_char, strokes[], age_group). **DONE 14 Feb ✅**
- [x] 🟢 Implementasi AI-based pengecekan: Groq AI evaluasi stroke data, skor 0-100, toleransi per umur. **DONE ✅**
- [x] 🟢 Return: `score`, `correct`, `stroke_order_ok`, `feedback_child`, `feedback_parent`, `tips`. **DONE ✅**
- [ ] 🔴 Test dengan 5 huruf dasar (A, B, C, D, E) — perlu Python server running.

### 4.3 — Integrasi AI ke Flutter 🤝 BERDUA

- [ ] 🔴 👩‍💻 TASYA: Buat `AiService` di Flutter untuk panggil endpoint `/ai/*`.
- [ ] 🔴 👩‍💻 TASYA: Implementasi UI chat bubble (Nusa bicara ↔ anak bicara).
- [ ] 🔴 👩‍💻 TASYA: Implementasi canvas menulis + kirim data goresan ke API.
- [ ] 🔴 👤 RIZKI: Pastikan Flask berjalan dan ngrok meneruskan port Flask (5000).
- [ ] 🔴 🔑 **KONFIRMASI TASYA**: Test end-to-end (anak nulis huruf di HP → AI kasih feedback).

**✅ PHASE 4 SELESAI JIKA**: Nusa bisa ngobrol (chat/voice) dan bisa menilai tulisan anak.

---

## ═══════════════════════════════════════════

## 🔒 PHASE 5: APP LOCKING & TIMER (Hari 20-28)

**Tujuan**: Orang tua bisa set batas waktu, dan HP terkunci otomatis.

### 5.1 — Timer Logic 👩‍💻 TASYA (🔑 Konsultasi RIZKI)

- [ ] 🔴 Implementasi countdown timer di Flutter (background-safe).
- [ ] 🔴 Ambil `daily_limit_minutes` dari API saat anak login.
- [ ] 🔴 Tampilkan timer di pojok layar (format: `23:45` tersisa).
- [ ] 🔴 Warning: Saat sisa 5 menit → Nusa bilang "Bentar lagi waktu habis loh!"
- [ ] 🔴 Saat waktu habis → trigger lock screen.

### 5.2 — Kiosk Mode / Screen Pinning 🤝 BERDUA

- [ ] 🔴 👤 RIZKI: Riset Android `startLockTask()` API untuk Screen Pinning.
- [ ] 🔴 👩‍💻 TASYA: Implementasi overlay lock screen (background gelap + form PIN).
- [x] 🟢 👤 RIZKI: Buat API `POST /api/children/{id}/timer/verify-pin` untuk verifikasi PIN orang tua. **DONE 13 Feb** ✅
- [ ] 🔴 👩‍💻 TASYA: Panggil verify-pin API → jika benar, unlock layar.
- [ ] 🔴 Test skenario:
    - ✅ Timer habis → layar terkunci.
    - ✅ Anak coba pencet tombol Home/Back → tetap di CALISTA.
    - ✅ Orang tua masukkan PIN benar → layar terbuka.
    - ✅ PIN salah 3x → tampilkan pesan "Hubungi Orang Tua".

### 5.3 — Focus Mode (Opsional Lanjutan) 👩‍💻 TASYA

- [ ] 🔴 Cegah anak berpindah aplikasi selama sesi belajar aktif.
- [ ] 🔴 Gunakan `WillPopScope` atau `SystemNavigator` di Flutter.

**✅ PHASE 5 SELESAI JIKA**: Timer berjalan, layar terkunci otomatis, PIN unlock berfungsi.

---

## ═══════════════════════════════════════════

## 👗 PHASE 6: CUSTOMIZATION & MONETIZATION (Hari 25-30)

**Tujuan**: Anak bisa ganti baju Nusa. Model: Free + Reward + Premium (Hybrid).

> 💡 Keputusan Boss (12 Feb 2026): **TANPA koin**. Baju didapat dari reward game atau beli premium.
> 🎨 Asset: AI Generated.
> 💳 Payment: Beneran (Tripay/pakai sistem payment yang SUDAH ADA).

### 6.1 — Sistem Reward Baju 👤 RIZKI (100% DONE ✅✅)

- [x] 🟢 Logika pemberian reward baju: **DONE 13 Feb** ✅
    - Selesai Level 5 modul apapun → Unlock 1 baju reward.
    - Selesai semua level 1 modul → Unlock 1 baju reward spesial.
- [x] 🟢 Update `ProgressController::store()` → cek milestone → auto-unlock reward. ✅
- [ ] 🔴 Test: Selesai 5 level → baju reward ter-unlock otomatis.

### 6.2 — Premium & Subscription 👤 RIZKI (100% DONE ✅✅)

- [x] ⚪ `PaymentController.php` (1310 baris) — Payment gateway + callback + GA4.
- [x] 🟢 `PaymentPlanController.php` (385 baris) — Plan-based payment.
- [x] 🟢 Model `Payment`, `Plan`, `Subscription` — semua ready.
- [x] 🟢 Tabel `payments` + `subscriptions` — sudah migrated.
- [x] 🟢 Buat Plan "CALISTA Premium" (Rp19.900/bulan) di seeder. **DONE 13 Feb** ✅
- [x] 🟢 Hubungkan subscription aktif → unlock semua baju premium di Shop. **DONE 14 Feb ✅**
- [x] 🟢 Endpoint `POST /api/shop/buy-premium` — subscriber beli baju premium. **DONE 14 Feb ✅**
- [x] 🟢 Buat API endpoint cek subscription status untuk Flutter. **DONE 13 Feb** ✅
- [x] 🟢 **Parental Gate API** (3 endpoints): challenge, verify, verify-password — **DONE 14 Feb ✅**
    - `POST /api/parental-gate/challenge` — Layer 1: Generate angka-dari-kata.
    - `POST /api/parental-gate/verify` — Layer 1: Cek jawaban.
    - `POST /api/parental-gate/verify-password` — Layer 2: Password orang tua.

### 6.3 — Aset Karakter Nusa (BAJU ONLY) 🤝 BERDUA

- [ ] 🔴 Generate 6 baju dengan AI:
    - 1 Baju Default — GRATIS.
    - 2 Baju Reward (Batik + Kebaya) — Game completion.
    - 3 Baju Premium (Adat Minang + Jas Kecil + Adat Bali) — Subscription.
- [ ] 🔴 Image Swap approach (1 gambar per outfit, bukan layer).
- [ ] 🔴 🔑 **KONFIRMASI TASYA**: Kirim ukuran & format gambar yang dibutuhkan Flutter.

### 6.4 — UI Toko & Inventory 👩‍💻 TASYA

- [ ] 🔴 Animasi ganti baju (transisi smooth saat equip item baru).
- [ ] 🔴 Label: 🟢 GRATIS / 🌟 REWARD / 💎 PREMIUM pada tiap baju.
- [ ] 🔴 Tombol "Upgrade Premium" yang mengarah ke payment.

**✅ PHASE 6 SELESAI JIKA**: 1 baju gratis ready, reward unlock works, premium subscription works.

---

## ═══════════════════════════════════════════

## 📊 PHASE 7: PARENT CENTER & REPORTING (Hari 28-35)

**Tujuan**: Orang tua mendapatkan laporan bermakna tentang progres anak.

### 7.1 — Dashboard Orang Tua 👤 RIZKI (100% DONE ✅✅)

- [x] 🟢 API `GET /api/progress/{child_id}` sudah mengembalikan data lengkap: **DONE 13 Feb** ✅
    - `child_name`, `total_sessions`, `avg_score`
    - `strongest_module`, `weakest_module`
    - `learning_style` (V-A-K percentage)
    - `recommendations` (rule-based)
    - `recent_activity` (10 terakhir)
    - `modules` (per-module breakdown)
- [x] 🟢 Implementasi logika `learning_style` — rule-based V-A-K berdasarkan tipe modul. ✅
- [x] 🟢 Implementasi `recommendations` — rule-based logic (modul lemah, gaya belajar, belum mulai). ✅

### 7.2 — UI Report 👩‍💻 TASYA

- [ ] 🔴 Grafik progres mingguan (line chart).
- [ ] 🔴 Pie chart gaya belajar (V-A-K).
- [ ] 🔴 Kartu rekomendasi dengan ikon & warna menarik.
- [ ] 🔴 Tombol "Share Report" (export sebagai gambar/PDF).

### 7.3 — Profil & Data Diri 🤝 BERDUA

- [ ] 🔴 Halaman edit profil orang tua (nama, email, foto).
- [ ] 🔴 Halaman edit profil anak (nama, umur, avatar).
- [ ] 🔴 Multi-child support: Switch antar anak di dropdown.

**✅ PHASE 7 SELESAI JIKA**: Orang tua bisa lihat report lengkap, edit profil, dan manage banyak anak.

---

## ═══════════════════════════════════════════

## 🧪 PHASE 8: TESTING & QA (Hari 33-38)

**Tujuan**: Semua fitur stabil, tidak ada bug critical.

### 8.1 — Backend Testing 👤 RIZKI

- [ ] 🔴 Test semua API endpoint di Postman (happy path + error case).
- [ ] 🔴 Test autentikasi (token expired, invalid token, dll).
- [ ] 🔴 Test edge case: Beli baju tanpa subscription? Claim reward belum unlock? Timer minus?
- [ ] 🔴 Test keamanan: User A tidak bisa akses data User B.

### 8.2 — Frontend Testing 👩‍💻 TASYA

- [ ] 🔴 Test di 3+ perangkat berbeda (Low-end, Mid, High-end).
- [ ] 🔴 Test orientasi layar (portrait only? atau support landscape?).
- [ ] 🔴 Test offline behavior (apa yang terjadi kalau internet mati?).
- [ ] 🔴 Test App Locking: Coba "kabur" dari aplikasi saat terkunci.

### 8.3 — AI Safety Testing 👤 RIZKI

- [ ] 🔴 Test LLM Filter: Kirim pertanyaan "berbahaya" → pastikan Nusa menolak menjawab.
- [ ] 🔴 Test voice recognition: Cek akurasi suara anak (bukan orang dewasa).
- [ ] 🔴 Test stroke analysis: Cek akurasi penilaian goresan huruf.

### 8.4 — User Acceptance Testing (UAT) 🤝 BERDUA + TIM

- [ ] 🔴 Minta 2-3 anak (usia 4-7) untuk mencoba aplikasi.
- [ ] 🔴 Observasi: Apa yang membingungkan? Tombol mana yang susah dipencet?
- [ ] 🔴 Catat feedback → buat list perbaikan.

**✅ PHASE 8 SELESAI JIKA**: Tidak ada bug critical, UAT feedback sudah diterapkan.

---

## ═══════════════════════════════════════════

## 🚀 PHASE 9: DEPLOYMENT & PRESENTASI (Hari 37-42)

**Tujuan**: Aplikasi siap untuk di-demo ke dosen/penguji.

### 9.1 — Deploy Backend 👤 RIZKI

- [ ] 🔴 Deploy Laravel ke hosting/VPS (Contoh: Railway, DigitalOcean, atau Niagahoster).
- [ ] 🔴 Setup domain `calistakids.id` mengarah ke server baru.
- [ ] 🔴 Pastikan HTTPS aktif.
- [ ] 🔴 Migrate database production.

### 9.2 — Build APK 👩‍💻 TASYA

- [ ] 🔴 Update `ApiConfig` base URL dari ngrok → domain production.
- [ ] 🔴 Jalankan `flutter build apk --release`.
- [ ] 🔴 Test APK di HP fisik (bukan emulator).
- [ ] 🔴 (Opsional) Upload ke Google Play Console (Internal Testing).

### 9.3 — Presentasi Skripsi 🤝 BERDUA + TIM

- [ ] 🔴 Siapkan slide presentasi (Problem → Solution → Demo → Hasil).
- [ ] 🔴 Siapkan HP yang sudah ter-install CALISTA untuk demo live.
- [ ] 🔴 Siapkan "Skenario Demo":
    1. Login sebagai orang tua.
    2. Tambah profil anak.
    3. Anak belajar → selesai level → progress naik.
    4. Baju reward ter-unlock → pasang ke Nusa → Nusa berubah.
    5. Timer habis → HP terkunci → unlock pakai password.
    6. Lihat laporan V-A-K di Parent Center.
    7. (Premium) Buka Toko Misterius → beli baju premium.
- [ ] 🔴 Latihan presentasi minimal 2x.

**✅ PHASE 9 SELESAI JIKA**: APK siap, server live, demo berjalan lancar. 🎓🏆

---

## 📅 RINGKASAN TIMELINE

| Phase                          | Durasi     | PIC Utama           |
| :----------------------------- | :--------- | :------------------ |
| Phase 0: Environment           | Hari 1-2   | 🤝 Berdua           |
| Phase 1: Database & API Design | Hari 3-5   | 👤 Rizki            |
| Phase 2: Backend API           | Hari 6-14  | 👤 Rizki            |
| Phase 3: Flutter UI            | Hari 10-21 | 👩‍💻 Tasya            |
| Phase 4: AI Integration        | Hari 15-25 | 👤 Rizki + 👩‍💻 Tasya |
| Phase 5: App Locking           | Hari 20-28 | 🤝 Berdua           |
| Phase 6: Customization         | Hari 25-30 | 🤝 Berdua + Hipster |
| Phase 7: Parent Center         | Hari 28-35 | 🤝 Berdua           |
| Phase 8: Testing               | Hari 33-38 | 🤝 Berdua + Tim     |
| Phase 9: Deploy & Demo         | Hari 37-42 | 🤝 Berdua + Tim     |

> **Total Estimasi: ~6 Minggu** (dengan kerja paralel di beberapa phase).

---

## 💬 CATATAN PENTING UNTUK TASYA

Tasya, setiap kali kamu lihat simbol 🔑 di dokumen ini, artinya **Rizki butuh feedback/konfirmasi dari kamu** sebelum lanjut. Jangan sungkan untuk bilang kalau ada yang kurang atau perlu diubah. Komunikasi = kunci sukses project ini! 🤜🤛

---

_Dokumen ini akan terus diperbarui seiring progress. Last updated: 14 Februari 2026, 15:46 WIB — Full sync: Ngrok DONE, Safety Filter DONE, Stroke Analysis DONE, Handover Tasya DONE, Parental Gate DONE ✅_
