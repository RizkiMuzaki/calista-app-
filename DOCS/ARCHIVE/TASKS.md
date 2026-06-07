# 📋 CALISTA: Task & Progress Tracker (Rich's Tracking)

## 📌 PHASE 1: UI/UX & Native Foundation (Bento Grid 2026)
- 🟢 **COMPLETED**: Standardisasi Tipografi (AppTextStyles) & Warna (primaryBlue).
- 🟢 **COMPLETED**: Redesign Splash Screen & Halaman Awal (Glossy Premium).
- 🟢 **COMPLETED**: Redesign Auth Screens & Landing Page (Glassmorphism, No Yellow Back Button).
- 🟢 **COMPLETED**: Redesign Home / Child Selection (Bento Grid 2026).
- 🟢 **COMPLETED**: Wireless Debugging Setup (ADB over Wi-Fi).

## 🛍️ PHASE 2: Toko & Monetisasi (Soft Gate Strategy)
- 🟢 **COMPLETED**: Rombak Total `ShopScreen` (UI Wardrobe 3D Avatar Card, Open Shop Concept).
- 🟢 **COMPLETED**: Ekstraksi Langganan ke `PremiumSubscriptionModal` (Popup Bottom Sheet).
- 🟢 **COMPLETED**: Integrasi Parental Gate (Double Layer Security) saat klik Beli.
- 🟢 **COMPLETED**: Routing Cleanup (Hapus folder sisa: `subscription/`, `wardrobe/`).
- 🟡 **IN PROGRESS (10%)**: Koneksi `ShopScreen` UI dengan Real API (`GET /api/shop/inventory`) di Laravel.

## 📊 PHASE 3: Parent Center & Profil
- 🟡 **IN PROGRESS (20%)**: Endpoint Laporan Progres Anak (`GET /progress/{child_id}`) sudah ada di Laravel.
- 🔴 **TODO**: Mengubah `ProfileScreen` jadi Parent Center (Tampilkan Rapor Visual, Auditory, Kinestetik).

## 🎮 PHASE 4: Core Game Engine (Prioritas Tesis)
- 🟢 **COMPLETED**: Fitur Navigasi Level & Rendering Menu per Modul.
- 🔴 **TODO**: Mekanik Drag & Drop untuk Soal Berhitung.
- 🔴 **TODO**: Mekanik Tracing (Custom Painter) untuk Menulis Huruf/Angka.
- 🔴 **TODO**: Integrasi Bintang (Reward 1-3) berdasarkan *Correct Answers* ke `POST /api/progress`.
- ⏸️ **BLOCKED**: Belum mulai karena fokus pada stabilisasi arsitektur UI dan Toko terlebih dahulu.
