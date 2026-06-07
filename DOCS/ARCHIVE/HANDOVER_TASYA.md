# 📋 CALISTA — Handover Document untuk Tasya (Flutter)

> **Tanggal**: 14 Februari 2026
> **Dari**: Rizki Muzaki (Backend)
> **Untuk**: Tasya (Frontend Flutter)

---

## 🌐 Koneksi ke Backend API

### Base URL

```
https://ena-uncanned-loyce.ngrok-free.dev/api
```

> ⚠️ **URL ini bisa berubah** kalau Rizki restart Ngrok. Tanya Rizki untuk URL terbaru.

### Wajib Tambah Header Ini

```dart
// Di setiap HTTP request, tambahin header ini:
headers: {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'ngrok-skip-browser-warning': 'true',  // ← WAJIB, biar skip halaman warning
}

// Kalau endpoint butuh auth (🔒), tambahin juga:
headers: {
  ...headers,
  'Authorization': 'Bearer $token',
}
```

### Contoh Setup di Flutter (Dio)

```dart
import 'package:dio/dio.dart';

class ApiService {
  static const String baseUrl = 'https://ena-uncanned-loyce.ngrok-free.dev/api';

  final Dio _dio = Dio(BaseOptions(
    baseUrl: baseUrl,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'ngrok-skip-browser-warning': 'true',
    },
  ));

  // Set token setelah login
  void setToken(String token) {
    _dio.options.headers['Authorization'] = 'Bearer $token';
  }
}
```

### Contoh Setup di Flutter (http package)

```dart
import 'package:http/http.dart' as http;

const String baseUrl = 'https://ena-uncanned-loyce.ngrok-free.dev/api';

Future<http.Response> apiGet(String endpoint, String token) {
  return http.get(
    Uri.parse('$baseUrl$endpoint'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'ngrok-skip-browser-warning': 'true',
      'Authorization': 'Bearer $token',
    },
  );
}
```

---

## 🔑 Authentication Flow

### 1. Register Orang Tua

```
POST /api/auth/register
```

```json
// Request
{
  "name": "Bunda Rani",
  "email": "rani@email.com",
  "password": "password123",
  "password_confirmation": "password123"
}

// Response (201)
{
  "status": "success",
  "message": "Registrasi berhasil",
  "data": {
    "user": { "id": 1, "name": "Bunda Rani", "email": "rani@email.com" },
    "token": "1|abc123xyz..."  // ← SIMPAN TOKEN INI!
  }
}
```

### 2. Login

```
POST /api/auth/login
```

```json
// Request
{
    "email": "rani@email.com",
    "password": "password123"
}

// Response (200) → sama format dengan register
// Error (401) → {"status": "error", "message": "Email atau password salah"}
```

### 3. Logout 🔒

```
POST /api/auth/logout
```

### 4. Get Current User 🔒

```
GET /api/auth/me
```

---

## 👶 Children API (Profil Anak) 🔒

| Method | Endpoint             | Fungsi                |
| :----- | :------------------- | :-------------------- |
| GET    | `/api/children`      | Daftar semua anak     |
| POST   | `/api/children`      | Tambah anak baru      |
| GET    | `/api/children/{id}` | Detail anak + progres |
| PUT    | `/api/children/{id}` | Edit profil anak      |
| DELETE | `/api/children/{id}` | Hapus anak            |

### Tambah Anak

```json
// POST /api/children
{
    "nama": "Aldi",
    "tanggal_lahir": "2019-05-15",
    "jenis_kelamin": "L",
    "pin": "1234"
}
```

### Timer Endpoints 🔒

| Method | Endpoint                              | Fungsi                |
| :----- | :------------------------------------ | :-------------------- |
| POST   | `/api/children/{id}/timer/start`      | Mulai timer belajar   |
| POST   | `/api/children/{id}/timer/stop`       | Stop timer            |
| GET    | `/api/children/{id}/timer`            | Cek status timer      |
| POST   | `/api/children/{id}/timer/verify-pin` | Verifikasi PIN unlock |

---

## 📚 Module API (Modul Belajar)

| Method | Endpoint                   | Auth | Fungsi             |
| :----- | :------------------------- | :--- | :----------------- |
| GET    | `/api/modules`             | ❌   | Daftar semua modul |
| GET    | `/api/modules/{id}`        | ❌   | Detail modul       |
| GET    | `/api/modules/{id}/levels` | ❌   | Levels per modul   |

---

## 📊 Progress API (Progres Belajar) 🔒

| Method | Endpoint                   | Fungsi               |
| :----- | :------------------------- | :------------------- |
| POST   | `/api/progress`            | Simpan hasil belajar |
| GET    | `/api/progress/{child_id}` | Laporan progres anak |

### Simpan Hasil Belajar

```json
// POST /api/progress
{
    "anak_id": 1,
    "module_id": 1,
    "level_id": 1,
    "skor": 85,
    "waktu_selesai": 120,
    "jawaban_benar": 8,
    "total_soal": 10
}
```

---

## 👗 Shop API (Toko Baju Nusa) 🔒

| Method | Endpoint                | Fungsi                               |
| :----- | :---------------------- | :----------------------------------- |
| GET    | `/api/shop`             | Daftar semua baju + status per anak  |
| POST   | `/api/shop/claim`       | Claim baju reward (gratis)           |
| GET    | `/api/shop/inventory`   | Lemari baju anak                     |
| POST   | `/api/shop/equip`       | Ganti baju Nusa                      |
| POST   | `/api/shop/buy-premium` | Beli baju premium (subscriber only!) |

### Item Status Values

```
"available"      → Bisa diambil/dibeli
"owned"          → Sudah dimiliki
"equipped"       → Sedang dipakai
"locked_reward"  → Terkunci, perlu milestone belajar
"locked_premium" → Terkunci, perlu subscription
```

### Claim Reward Baju

```json
// POST /api/shop/claim
{ "anak_id": 1, "item_id": 3 }
```

### Ganti Baju

```json
// POST /api/shop/equip
{ "anak_id": 1, "item_id": 3 }
```

### Beli Baju Premium

```json
// POST /api/shop/buy-premium
{ "anak_id": 1, "item_id": 5 }

// Error (403) jika belum subscribe:
{ "status": "error", "message": "Kamu belum berlangganan CALISTA Premium", "requires_subscription": true }
```

---

## 💳 Subscription API 🔒

| Method | Endpoint                   | Fungsi                  |
| :----- | :------------------------- | :---------------------- |
| GET    | `/api/subscription/status` | Cek status premium user |

### Response

```json
// User SUDAH premium:
{
  "status": "success",
  "is_premium": true,
  "data": {
    "plan_name": "Bulanan",
    "starts_at": "2026-02-01",
    "ends_at": "2026-03-01"
  }
}

// User BELUM premium:
{
  "status": "success",
  "is_premium": false,
  "data": null
}
```

---

## 🔐 Parental Gate API (Pengaman Pembelian) 🔒

Sistem pengaman 2 layer sebelum anak bisa beli/akses fitur premium.

### Layer 1: Challenge "Tulis Angka dari Kata"

```
POST /api/parental-gate/challenge
```

```json
// Response
{
    "status": "success",
    "data": {
        "challenge_text": "tiga ratus empat puluh tujuh",
        "instruction": "Tulis angka dari kata di atas",
        "token": "encrypted_token_here",
        "expires_in": 120
    }
}
```

### Layer 1: Verify Jawaban

```
POST /api/parental-gate/verify
```

```json
// Request
{
  "answer": 347,
  "token": "encrypted_token_from_challenge"
}

// Response OK
{
  "status": "success",
  "message": "Jawaban benar!",
  "gate_token": "token_untuk_layer_2"
}
```

### Layer 2: Password Orang Tua

```
POST /api/parental-gate/verify-password
```

```json
// Request
{
  "password": "password_orang_tua",
  "gate_token": "token_dari_layer_1"
}

// Response OK
{
  "status": "success",
  "message": "Verifikasi berhasil",
  "access_token": "token_akses_premium"
}
```

---

## 🎯 Flutter Integration Flow

### Flow 1: Login → Pilih Anak → Belajar

```
1. POST /api/auth/login          → Dapat token
2. GET  /api/children             → Tampilkan daftar anak
3. GET  /api/children/{id}        → Detail anak yang dipilih
4. GET  /api/modules              → Tampilkan modul belajar
5. GET  /api/modules/{id}/levels  → Tampilkan level per modul
6. POST /api/progress             → Simpan hasil belajar
```

### Flow 2: Toko Baju

```
1. GET  /api/shop                 → Tampilkan semua baju + status
2. GET  /api/subscription/status  → Cek apakah user premium
3. POST /api/shop/claim           → Claim baju reward (gratis)
4. POST /api/shop/equip           → Ganti baju Nusa
```

### Flow 3: Beli Premium Baju (Subscriber)

```
1. GET  /api/subscription/status         → Cek premium
2. POST /api/parental-gate/challenge     → Layer 1: Generate challenge
3. POST /api/parental-gate/verify        → Layer 1: Verifikasi jawaban
4. POST /api/parental-gate/verify-password → Layer 2: Password orang tua
5. POST /api/shop/buy-premium            → Beli baju premium
```

---

## ⚠️ Catatan Penting

1. **Semua response pakai Bahasa Indonesia** — sesuai target user (anak Indonesia)
2. **Token disimpan di SharedPreferences** — jangan hilang setelah restart app
3. **`anak_id`** — setiap request shop/progress butuh `anak_id`, bukan `user_id`
4. **URL Ngrok bisa berubah** — tanya Rizki kalau dapat error connection
5. **Error format** selalu:
    ```json
    { "status": "error", "message": "Pesan error..." }
    ```

---

## 🧪 Test Account

```
Email: rani@email.com
Password: password123
```

> 💡 Kalau belum ada, register dulu pakai endpoint register di atas.

---

## ❓ Kontak

Kalau ada pertanyaan soal API → **Tanya Rizki** langsung ya!
