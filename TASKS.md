# 📋 TASKS BREAKDOWN - CALISTA SKRIPSI

> **Status**: 🟢 Database Fully Restored & Verified
> **Focus**: Testing AI Integration & UI Polish
> **Rule**: One task at a time. Move from 🔴 TODO to 🟡 IN PROGRESS to 🟢 DONE.

## 🧑‍💻 HACKER (Rizki & Tasya)

### 🚨 Critical Infrastructure

- [x] 🟢 **Setup Git & GitHub**: Initialize repo & create `COLLABORATION_GUIDE.md`.
- [x] 🟢 **Database Migration & Import**: Fixed SQL collation and imported `calistaadmin.sql`. Verified data integrity.
- [/] 🟡 **Security & GitHub Sync**:
    - [x] Fix hardcoded secrets in `voiceagent.py`.
    - [ ] Clean Git history to remove leaked secrets.
    - [ ] Push to GitHub (Push Protection bypass/fix).
- [ ] 🟡 **Consolidate AI Services**: Gabungkan logic Menghitung, Menulis, & Cerita ke satu `voiceagent.py` (Port 5000).
- [ ] 🔴 **Add Missing Endpoints**: Implement `/api/writing/*` di dalam `voiceagent.py`.
- [ ] 🔴 **Admin Panel**: Setup Filament resource untuk management `Modules` dan `Books`.

### 📚 Education Modules (Tasya)

- [ ] 🔴 **Feature Menulis**: Logic validasi input text/tracing.
- [ ] 🔴 **Feature Menghitung**: Logic generate soal matematika random.
- [ ] 🔥 **Progress Tracking**: Simpan score anak ke DB `progres_anaks`.

### 🤖 AI Integrations (Rizki)

- [ ] 🔴 **Voice Agent Controller**: Bikin bridge PHP <-> Python yg stabil.
- [ ] 🔥 **TTS (Text-to-Speech)**: Pastikan suara Calista keluar jernih.
- [ ] 🌤️ **Story AI**: Generate cerita simple via OpenAI/local LLM.

---

## 🎨 HIPSTER (UI/UX Designer)

### 🖌️ Frontend Polish

- [ ] 🔴 **Landing Page**: Perbaiki layout `welcome.blade.php` biar "Jual Mahal".
- [ ] 🔥 **Animations**: Implementasi animasi di folder `ANIMASI_*.md`.
- [ ] 🌤️ **Mobile Responsive**: Cek tampilan di HP (Penting buat demo!).
- [ ] 🌤️ **Asset Opt**: Kompres gambar biar loading cepat.
- [ ] 🔥 **Fix Broken Images**: Cek semua gambar di Landing Page & Dashboard.

### 🧩 Components

- [ ] 🔥 **Card Component**: Design card modul belajar yang lucu.
- [ ] 🌤️ **Button Styles**: Standarisasi tombol (Primary, Secondary, Danger).

---

## 💼 HUSTLER (Business/Ops)

### 💰 Monetization

- [ ] 🔴 **Subscription Plans**: Tentukan harga & fitur Premium vs Free.
- [ ] 🔥 **Check Payment**: Cek `PaymentController`, apakah logic Midtrans sudah benar?
- [ ] 🔥 **Pitch Deck**: Buat slide presentasi skripsi yang "Menjual".

### 🧪 Quality Assurance

- [ ] 🔥 **User Testing**: Coba kasih ke anak kecil beneran, lihat responnya.
- [ ] 🌤️ **Bug Hunting**: Catat semua error 500/404.
- [ ] 🌤️ **User Scenario Testing**: Bikin skenario "Anak Salah Menjawab" vs "Anak Benar".
