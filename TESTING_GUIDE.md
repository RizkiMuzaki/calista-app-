# 🧪 CALISTA API Testing Guide

> **Untuk**: Rizki Muzaki (Backend Testing)
> **Base URL**: `http://localhost:8000/api`
> **Ngrok URL**: `https://ena-uncanned-loyce.ngrok-free.dev/api`
> **Total Test Cases**: 28 endpoint + error scenarios
> **Estimasi Waktu**: ~45 menit
> **Last Updated**: 14 Februari 2026

---

## 📖 Cara Baca Dokumentasi Ini

```
🟢 = Test yang harus PASS (expected success)
🔴 = Test yang SENGAJA ERROR (pastikan error handling jalan)
📝 = Catat value dari response ini, dipakai di test berikutnya
⚡ = Endpoint ini publik (tidak perlu token)
🔒 = Endpoint ini butuh Authorization header
```

### Setup Awal (Pilih Salah Satu)

**Postman:**

1. Download dari https://www.postman.com/downloads/
2. Buat Collection baru: "CALISTA API Tests"
3. Set variable: `base_url` = `http://localhost:8000/api`

**Terminal (curl/PowerShell):**

```powershell
$BASE = "http://localhost:8000/api"
$HEADERS = @{ "Content-Type" = "application/json"; "Accept" = "application/json" }
```

---

## ═══════════════════════════════════════════

## 🧪 TEST 1: Authentication Flow (5 menit)

### 1.1 — Register ⚡

```
POST /api/auth/register
```

**Body:**

```json
{
    "name": "Test Bunda",
    "email": "testbunda@calista.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**🟢 Expected (201):**

```json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Test Bunda",
            "email": "testbunda@calista.com"
        },
        "token": "1|abc123..."
    }
}
```

> 📝 **CATAT**: Copy value `token` — ini dipakai untuk SEMUA test selanjutnya!

**PowerShell:**

```powershell
$body = '{"name":"Test Bunda","email":"testbunda@calista.com","password":"password123","password_confirmation":"password123"}'
$response = Invoke-RestMethod -Uri "$BASE/auth/register" -Method POST -Body $body -ContentType "application/json"
$TOKEN = $response.data.token
Write-Host "TOKEN: $TOKEN"
```

---

### 1.2 — Register Duplikat 🔴

**Kirim request SAMA persis** seperti 1.1 lagi.

**🔴 Expected Error (422):**

```json
{
    "status": "error",
    "message": "The email has already been taken."
}
```

> ✅ **PASS jika**: Error 422 dengan pesan jelas, BUKAN error 500.

---

### 1.3 — Login ⚡

```
POST /api/auth/login
```

**Body:**

```json
{
    "email": "testbunda@calista.com",
    "password": "password123"
}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "user": { "id": 1, "name": "Test Bunda" },
        "token": "2|xyz789..."
    }
}
```

> 📝 **CATAT**: Token baru! Update token yang disimpan.

**PowerShell:**

```powershell
$body = '{"email":"testbunda@calista.com","password":"password123"}'
$response = Invoke-RestMethod -Uri "$BASE/auth/login" -Method POST -Body $body -ContentType "application/json"
$TOKEN = $response.data.token
Write-Host "TOKEN: $TOKEN"
```

---

### 1.4 — Login Salah Password 🔴

```json
{
    "email": "testbunda@calista.com",
    "password": "salahpassword"
}
```

**🔴 Expected (401):**

```json
{
    "status": "error",
    "message": "Email atau password salah"
}
```

---

### 1.5 — Get Current User 🔒

```
GET /api/auth/me
```

**Headers:**

```
Authorization: Bearer {TOKEN dari step 1.3}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Test Bunda",
        "email": "testbunda@calista.com"
    }
}
```

**PowerShell:**

```powershell
$headers = @{ Authorization = "Bearer $TOKEN"; Accept = "application/json" }
Invoke-RestMethod -Uri "$BASE/auth/me" -Method GET -Headers $headers
```

---

### 1.6 — Akses Tanpa Token 🔴

```
GET /api/auth/me
```

**TANPA** header Authorization.

**🔴 Expected (401):**

```json
{
    "message": "Unauthenticated."
}
```

> ✅ **PASS jika**: Error 401, bukan data user bocor!

---

### 1.7 — Check Auth ⚡

```
GET /api/auth/check
```

**🟢 Expected (200):**

```json
{
    "authenticated": true/false
}
```

---

## ═══════════════════════════════════════════

## 🧪 TEST 2: Children CRUD (10 menit)

> 🔒 Semua endpoint butuh `Authorization: Bearer {TOKEN}`

### 2.1 — Tambah Anak 🔒

```
POST /api/children
```

**Body:**

```json
{
    "nama_anak": "Aldi",
    "tanggal_lahir": "2019-05-15",
    "jenis_kelamin": "L",
    "pin": "1234"
}
```

**🟢 Expected (201):**

```json
{
    "status": "success",
    "message": "Profil anak berhasil ditambahkan",
    "data": {
        "id": 1,
        "nama": "Aldi",
        "umur": "5 tahun"
    }
}
```

> 📝 **CATAT** `id` anak — dipakai di semua test Children, Timer, Progress, Shop!

**PowerShell:**

```powershell
$headers = @{ Authorization = "Bearer $TOKEN"; Accept = "application/json"; "Content-Type" = "application/json" }
$body = '{"nama":"Aldi","tanggal_lahir":"2019-05-15","jenis_kelamin":"L","pin":"1234"}'
$child = Invoke-RestMethod -Uri "$BASE/children" -Method POST -Body $body -Headers $headers
$CHILD_ID = $child.data.id
Write-Host "CHILD_ID: $CHILD_ID"
```

---

### 2.2 — Tambah Anak Kedua 🔒

```json
{
    "nama": "Siti",
    "tanggal_lahir": "2020-08-20",
    "jenis_kelamin": "P",
    "pin": "5678"
}
```

> 📝 Multi-child support — 1 orang tua bisa punya banyak anak.

---

### 2.3 — Daftar Semua Anak 🔒

```
GET /api/children
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "data": [
        { "id": 1, "nama": "Aldi", "umur": "5 tahun" },
        { "id": 2, "nama": "Siti", "umur": "4 tahun" }
    ]
}
```

> ✅ **PASS jika**: Response menampilkan SEMUA anak yang dimiliki user ini.

---

### 2.4 — Detail Anak 🔒

```
GET /api/children/{CHILD_ID}
```

**🟢 Expected**: Data anak lengkap dengan progres dan timer info.

---

### 2.5 — Edit Profil Anak 🔒

```
PUT /api/children/{CHILD_ID}
```

**Body:**

```json
{
    "nama_anak": "Aldi Juara",
    "limit_detik": 7200
}
```

**🟢 Expected**: Nama berubah, limit timer ter-update.

---

### 2.6 — Hapus Anak 🔒

```
DELETE /api/children/{CHILD_ID_KEDUA}
```

> ⚠️ Hapus anak KEDUA (Siti) saja. Anak pertama (Aldi) tetap dipakai untuk test lanjutan.

**🟢 Expected (200):** Pesan sukses.

---

### 2.7 — Akses Anak Orang Lain 🔴

Coba `GET /api/children/99999` (ID yang bukan milik user ini).

**🔴 Expected (403/404):** Error, bukan data anak orang lain!

> ✅ **PASS jika**: Tidak bisa akses data anak user lain (keamanan!).

---

## ═══════════════════════════════════════════

## 🧪 TEST 3: Timer Flow (5 menit)

> 🔒 Semua butuh token. Gunakan `{CHILD_ID}` yang pertama (Aldi).

### 3.1 — Start Timer 🔒

```
POST /api/children/{CHILD_ID}/timer/start
```

**Body (opsional):**

```json
{
    "durasi_menit": 30
}
```

**🟢 Expected:** Timer started, sisa waktu ditampilkan.

---

### 3.2 — Cek Status Timer 🔒

```
GET /api/children/{CHILD_ID}/timer
```

**🟢 Expected:**

```json
{
    "status": "success",
    "data": {
        "is_active": true,
        "sisa_detik": 1800,
        "should_lock": false
    }
}
```

---

### 3.3 — Stop Timer 🔒

```
POST /api/children/{CHILD_ID}/timer/stop
```

**🟢 Expected:** Timer stopped.

---

### 3.4 — Verify PIN 🔒

```
POST /api/children/{CHILD_ID}/timer/verify-pin
```

**Body:**

```json
{
    "pin": "1234"
}
```

**🟢 Expected:** PIN benar → unlock berhasil.

---

### 3.5 — Verify PIN Salah 🔴

```json
{
    "pin": "9999"
}
```

**🔴 Expected:** Error — PIN salah.

---

## ═══════════════════════════════════════════

## 🧪 TEST 4: Modules & Levels (5 menit)

### 4.1 — List Semua Modul ⚡

```
GET /api/modules
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "data": [
        { "id": 1, "nama": "Membaca", "icon": "📖" },
        { "id": 2, "nama": "Menulis", "icon": "✏️" },
        { "id": 3, "nama": "Berhitung", "icon": "🔢" }
    ]
}
```

> 📝 **CATAT** `id` modul untuk test 4.2.

---

### 4.2 — Detail Modul ⚡

```
GET /api/modules/{MODULE_ID}
```

**🟢 Expected**: Detail modul dengan deskripsi.

---

### 4.3 — Levels per Modul ⚡

```
GET /api/modules/{MODULE_ID}/levels
```

**🟢 Expected:**

```json
{
    "status": "success",
    "data": [
        { "id": 1, "nama": "Level 1", "urutan": 1 },
        { "id": 2, "nama": "Level 2", "urutan": 2 }
    ]
}
```

> 📝 **CATAT** `id` level untuk test Progress.

---

## ═══════════════════════════════════════════

## 🧪 TEST 5: Progress (5 menit)

### 5.1 — Simpan Hasil Belajar 🔒

```
POST /api/progress
```

**Body:**

```json
{
    "anak_id": 1,
    "level_id": 1,
    "score": 85,
    "bintang": 3,
    "selesai": true
}
```

**🟢 Expected (201):**

```json
{
    "status": "success",
    "message": "Progres berhasil disimpan",
    "data": {
        "score": 85,
        "bintang": 3
    }
}
```

> 💡 Kirim beberapa kali dengan level_id berbeda untuk data report yang lebih kaya.

---

### 5.2 — Simpan Beberapa Progress Lagi 🔒

Ulangi 5.1 dengan data berbeda untuk bikin report lebih lengkap:

```json
// Request 2
{ "anak_id": 1, "level_id": 2, "score": 70, "bintang": 2, "selesai": true }

// Request 3
{ "anak_id": 1, "level_id": 3, "score": 95, "bintang": 3, "selesai": true }
```

---

### 5.3 — Laporan Progres Anak 🔒

```
GET /api/progress/{CHILD_ID}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "data": {
        "child_name": "Aldi",
        "total_sessions": 3,
        "avg_score": 83.3,
        "strongest_module": "Berhitung",
        "weakest_module": "Menulis",
        "learning_style": {
            "visual": 40,
            "auditory": 30,
            "kinesthetic": 30
        },
        "recommendations": ["Aldi perlu latihan lebih di modul Menulis."]
    }
}
```

> ✅ **PASS jika**: Ada learning_style V-A-K, recommendations, strongest/weakest module.

---

## ═══════════════════════════════════════════

## 🧪 TEST 6: Shop & Customization (10 menit)

### 6.1 — List Semua Baju 🔒

```
GET /api/shop?anak_id={CHILD_ID}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Baju Merah",
            "type": "baju",
            "unlock_type": "free",
            "status": "available",
            "image_url": "/images/nusa/baju_merah.png"
        },
        {
            "id": 2,
            "name": "Baju Batik",
            "unlock_type": "reward",
            "status": "locked_reward"
        },
        {
            "id": 5,
            "name": "Baju Adat Minang",
            "unlock_type": "premium",
            "status": "locked_premium"
        }
    ]
}
```

> 📝 **CATAT** `id` item yang `unlock_type: "free"` — dipakai di test 6.2.

---

### 6.2 — Claim Baju Gratis 🔒

```
POST /api/shop/claim
```

**Body:**

```json
{
    "anak_id": 1,
    "item_id": 1
}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "message": "Baju berhasil di-claim!"
}
```

---

### 6.3 — Claim Baju Reward yang Terkunci 🔴

```json
{
    "anak_id": 1,
    "item_id": 2
}
```

**🔴 Expected (403):**

```json
{
    "status": "error",
    "message": "Baju ini belum ter-unlock. Selesaikan milestone dulu!"
}
```

> ✅ **PASS jika**: Tidak bisa claim baju yang belum ter-unlock.

---

### 6.4 — Lihat Lemari (Inventory) 🔒

```
GET /api/shop/inventory?anak_id={CHILD_ID}
```

**🟢 Expected**: Daftar baju yang sudah dimiliki anak.

---

### 6.5 — Equip Baju 🔒

```
POST /api/shop/equip
```

**Body:**

```json
{
    "anak_id": 1,
    "item_id": 1
}
```

**🟢 Expected:** Baju berhasil dipasang ke Nusa.

---

### 6.6 — Beli Baju Premium Tanpa Subscription 🔴

```
POST /api/shop/buy-premium
```

**Body:**

```json
{
    "anak_id": 1,
    "item_id": 5
}
```

**🔴 Expected (403):**

```json
{
    "status": "error",
    "message": "Kamu belum berlangganan CALISTA Premium",
    "requires_subscription": true
}
```

> ✅ **PASS jika**: Tidak bisa beli tanpa subscription aktif (keamanan monetisasi!).

---

## ═══════════════════════════════════════════

## 🧪 TEST 7: Subscription (3 menit)

### 7.1 — Cek Status Premium 🔒

```
GET /api/subscription/status
```

**🟢 Expected (200) — User BELUM premium:**

```json
{
    "status": "success",
    "is_premium": false,
    "data": null
}
```

> 💡 Untuk test premium flow, perlu insert manual ke database:
>
> ```sql
> INSERT INTO subscriptions (user_id, plan_id, status, tanggal_mulai, tanggal_berakhir)
> VALUES (1, 1, 'aktif', '2026-02-14', '2026-03-14');
> ```
>
> Setelah insert, hit endpoint ini lagi → `is_premium` harus jadi `true`.

---

## ═══════════════════════════════════════════

## 🧪 TEST 8: Parental Gate Flow (5 menit)

### 8.1 — Generate Challenge 🔒

```
POST /api/parental-gate/challenge
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "data": {
        "challenge_text": "tiga ratus empat puluh tujuh",
        "instruction": "Tulis angka dari kata di atas",
        "token": "eyJ0eXAi...",
        "expires_in": 120
    }
}
```

> 📝 **CATAT**: `challenge_text` dan `token` — dipakai di step berikutnya!
>
> 🧠 **Cara baca**: "tiga ratus empat puluh tujuh" = **347**

---

### 8.2 — Verify Challenge (Jawaban Benar) 🔒

```
POST /api/parental-gate/verify
```

**Body:**

```json
{
    "answer": 347,
    "token": "eyJ0eXAi... (token dari step 8.1)"
}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "message": "Jawaban benar!",
    "gate_token": "token_untuk_layer_2..."
}
```

> 📝 **CATAT** `gate_token` — dipakai di step 8.4!

---

### 8.3 — Verify Challenge (Jawaban Salah) 🔴

```json
{
    "answer": 999,
    "token": "eyJ0eXAi... (token dari step 8.1)"
}
```

**🔴 Expected:** Error — jawaban salah.

---

### 8.4 — Verify Password Orang Tua 🔒

```
POST /api/parental-gate/verify-password
```

**Body:**

```json
{
    "password": "password123",
    "gate_token": "token_dari_step_8.2"
}
```

**🟢 Expected (200):**

```json
{
    "status": "success",
    "message": "Verifikasi berhasil",
    "access_token": "token_akses_premium..."
}
```

> ✅ **PASS jika**: 2 layer berjalan: angka → password → akses.

---

### 8.5 — Password Salah 🔴

```json
{
    "password": "salahpassword",
    "gate_token": "token_dari_step_8.2"
}
```

**🔴 Expected:** Error 401 — password salah.

---

## ═══════════════════════════════════════════

## 📊 Test Summary Checklist

Setelah semua test selesai, centang di bawah:

### Auth (7 test)

- [ ] 1.1 Register ✅ → dapat token
- [ ] 1.2 Register duplikat → error 422
- [ ] 1.3 Login → dapat token baru
- [ ] 1.4 Login salah password → error 401
- [ ] 1.5 Get user (me) → data user
- [ ] 1.6 Akses tanpa token → error 401
- [ ] 1.7 Check auth → true/false

### Children (7 test)

- [ ] 2.1 Tambah anak → id anak tersimpan
- [ ] 2.2 Tambah anak kedua → multi-child
- [ ] 2.3 List anak → semua anak muncul
- [ ] 2.4 Detail anak → data lengkap
- [ ] 2.5 Edit anak → nama berubah
- [ ] 2.6 Hapus anak kedua → anak terhapus
- [ ] 2.7 Akses anak orang lain → DITOLAK

### Timer (5 test)

- [ ] 3.1 Start timer → timer mulai
- [ ] 3.2 Cek status → sisa waktu muncul
- [ ] 3.3 Stop timer → timer berhenti
- [ ] 3.4 PIN benar → unlock
- [ ] 3.5 PIN salah → ditolak

### Modules (3 test)

- [ ] 4.1 List modul → minimal 3 modul
- [ ] 4.2 Detail modul → deskripsi ada
- [ ] 4.3 Levels → urutan benar

### Progress (3 test)

- [ ] 5.1 Simpan progres → skor tersimpan
- [ ] 5.2 Simpan beberapa lagi → data bertambah
- [ ] 5.3 Laporan → V-A-K + recommendations

### Shop (6 test)

- [ ] 6.1 List baju → status per item
- [ ] 6.2 Claim gratis → berhasil
- [ ] 6.3 Claim terkunci → DITOLAK
- [ ] 6.4 Inventory → baju owned muncul
- [ ] 6.5 Equip → baju dipasang
- [ ] 6.6 Beli premium tanpa subs → DITOLAK

### Subscription (1 test)

- [ ] 7.1 Cek premium → false (belum subscribe)

### Parental Gate (5 test)

- [ ] 8.1 Challenge → teks angka muncul
- [ ] 8.2 Verify benar → gate_token
- [ ] 8.3 Verify salah → ditolak
- [ ] 8.4 Password benar → akses granted
- [ ] 8.5 Password salah → ditolak

---

### 🏆 Total: 37 Test Cases

| Area          | Happy Path 🟢 | Error Case 🔴 | Total  |
| :------------ | :------------ | :------------ | :----- |
| Auth          | 4             | 3             | 7      |
| Children      | 5             | 2             | 7      |
| Timer         | 4             | 1             | 5      |
| Modules       | 3             | 0             | 3      |
| Progress      | 3             | 0             | 3      |
| Shop          | 4             | 2             | 6      |
| Subscription  | 1             | 0             | 1      |
| Parental Gate | 3             | 2             | 5      |
| **Total**     | **27**        | **10**        | **37** |

---

> 💡 **Tips dari Rich:**
>
> - Test secara **sequential** (urut dari 1-8) — beberapa test butuh data dari test sebelumnya
> - Kalau ketemu error, **jangan panik** — screenshot dan lanjut ke test berikutnya
> - Error di test 🔴 itu **hal bagus** — artinya error handling kita jalan!

_Last updated: 14 Februari 2026, 15:50 WIB_
