# 🤝 PANDUAN KOLABORASI TIM CALISTA

> **Status Saat Ini**: 🟡 Phase 2 (Development)
> **Repo URL**: `https://github.com/RizkiMuzaki/calista-app`

Dokumen ini adalah "Buku Suci" buat Rizki & Tasya biar codingan aman, rapi, dan gak bentrok. Ikuti step-by-step sesuai Fase.

---

## 🏁 PHASE 1: SETUP AWAL (Hanya Sekali)

Centang kalau sudah selesai!

- [x] **Rizki**: Bikin Repository di GitHub.
- [x] **Rizki**: Upload codingan pertama (Initial Commit).
- [ ] **Tasya**: Setup di Laptop Tasya (Lihat instruksi di bawah).

### 👩‍💻 Instruksi Khusus Buat Tasya

**JANGAN** gabungin codingan lama Tasya manual. Itu bahaya konflik.
Lakukan ini di laptop Tasya:

1.  **Backup**: Rename folder project lama Tasya jadi `calista-backup` (biar aman).
2.  **Clone**: Buka folder kosong baru, klik kanan "Git Bash Here", ketik:
    ```bash
    git clone https://github.com/RizkiMuzaki/calista-app.git
    ```
3.  **Setup Ulang**: Masuk folder `calista-app`, lalu jalanin di terminal:
    ```bash
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate:fresh --seed
    ```

Boom! 💥 Laptop Tasya sekarang isinya 100% sama dengan punya Rizki.

---

## 🛠️ PHASE 2: RUTINITAS CODING (Sehari-hari)

Setiap mau coding, **WAJIB** ikuti urutan ini. Jangan dilompati!

### 1️⃣ STEP 1: Persiapan (Sebelum Coding)

_"Jangan coding di branch `main`! Itu dosa besar."_

1.  **Tarik Update Terbaru**:
    ```bash
    git checkout main
    git pull origin main
    ```
2.  **Bikin Branch Baru** (Sesuai tugas di `TASKS.md`):
    ```bash
    # Contoh: Tasya mau kerjain fitur Menghitung
    git checkout -b fitur-menghitung
    ```

### 2️⃣ STEP 2: Coding Time

Silakan coding... ngopi... coding lagi... ☕
Kalau sudah selesai satu fitur kecil, simpan progress:

```bash
git add .
git commit -m "update: logika penjumlahan modul menghitung"
```

### 3️⃣ STEP 3: Setor Kerjaan (Push)

Upload codingan kamu ke "Awan" (GitHub):

```bash
git push origin fitur-menghitung
```

### 4️⃣ STEP 4: Gabungin (Merge)

1.  Buka GitHub di Browser.
2.  Klik **"Compare & Pull Request"**.
3.  Tulis judul jelas (misal: "Selesai Fitur Menghitung Level 1").
4.  Kabarin di Grup WA: _"Zki, tolong review PR gw dong!"_
5.  Kalau Rizki bilang oke, klik **Merge**.

---

## 🚦 PHASE 3: PROGRESS TRACKING & PEMBAGIAN TUGAS

Cek detail tugas di file `TASKS.md`.

### 👨‍💻 Rizki (Focus: AI & System)

- [ ] **AI Bridge**: Script `voiceagent.py` bisa ngobrol sama Laravel.
- [ ] **Voice Agent**: Fitur ngomong dan denger (TTS/STT).
- [ ] **Payment**: Integrasi Midtrans dummy.

### 👩‍💻 Tasya (Focus: Game Logic & Admin)

- [ ] **Modul Menulis**: Perbaiki halaman input tracing huruf.
- [ ] **Modul Menghitung**: Bikin soal matematika random.
- [ ] **Admin Panel**: Input data Soal & Materi via Admin.

---

## 🆘 DARURAT (Emergency Button)

**Kalau ada Error Git (Conflict / Gagal Push):**

1.  **STOP!** Jangan dipaksa.
2.  Screenshot error-nya.
3.  Kirim ke Grup.
4.  Tanya Rich atau Rizki.

_"Coding itu gampang, yang susah itu ngerjain skripsi sendirian. Makanya kita tim!"_ 😉
