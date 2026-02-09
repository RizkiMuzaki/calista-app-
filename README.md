<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="CALISTA Logo">
</p>

# 🌟 CALISTA (Character Aided Learning & Interactive Storytelling Assistant)

> **Project Skripsi** - Platform Edukasi Anak Berbasis AI 🤖📚

## 👋 Welcome Team!

Ini adalah repository resmi project CALISTA. Mohon baca dokumen ini sebelum mulai coding ya!

---

## 🚀 Quick Start (Wajib Baca!)

### 1. System Requirements

- **PHP**: 8.2 atau lebih baru.
- **Composer**: Latest version.
- **Node.js**: v18+ & NPM.
- **Python**: 3.10+ (Untuk AI Engine).
- **Database**: MySQL (XAMPP default port 3306).

### 2. Installation Steps

Clone/Download repo ini, lalu jalankan command berikut di terminal:

```bash
# 1. Install PHP Dependencies
composer install

# 2. Install Frontend Dependencies
npm install

# 3. Setup Environment
cp .env.example .env
# EDIT file .env sesuaikan database kamu (DB_DATABASE=calista, dsb)

# 4. Generate Key
php artisan key:generate

# 5. Setup Database & Storage
php artisan migrate:fresh --seed
php artisan storage:link

# 6. Build Frontend
npm run build
```

### 3. Running the App

Buka 2 terminal berbeda:

**Terminal 1 (Laravel Server):**

```bash
php artisan serve
```

**Terminal 2 (Python AI Service - Optional):**

```bash
# Pastikan sudah install library python
pip install -r requirements.txt
python voiceagent.py
```

Akses web di: `http://127.0.0.1:8000`

---

## 📂 Struktur Project Penting

- `app/Http/Controllers`: Logic backend ada di sini.
- `app/Filament`: Logic Admin Panel (Filament).
- `resources/views`: Tampilan web (Blade).
- `routes/web.php`: Daftar alamat web.
- `voiceagent.py`: Script otak AI Calista.

---

## 🤝 Rules of Contribution

1.  **Jangan Commit .env**: File ini rahasia!
2.  **Satu Fitur = Satu Branch**: Jangan coding rame-rame di `main`.
3.  **Test Dulu**: Sebelum push, pastikan jalan di local.

**Happy Coding! 🚀**
