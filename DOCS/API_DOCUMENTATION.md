# 📚 CALISTA API Documentation

> **Base URL**: `http://localhost/CALISTA/public/api` (local)
> **Production**: `https://[ngrok-url]/api`
> **Auth**: Laravel Sanctum (Bearer Token)
> **Content-Type**: `application/json`
> **Last Updated**: 1 April 2026

---

## 🔑 Authentication

Semua endpoint bertanda 🔒 membutuhkan header:

```
Authorization: Bearer {token}
```

Token didapat dari response login/register.

---

## 1. Auth API

### 1.1 Register

`POST /api/auth/register`

**Request:**

```json
{
    "name": "Bunda Rani",
    "email": "rani@email.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**

```json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Bunda Rani",
            "email": "rani@email.com"
        },
        "token": "1|abc123xyz..."
    }
}
```

---

### 1.2 Login

`POST /api/auth/login`

**Request:**

```json
{
    "email": "rani@email.com",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Bunda Rani",
            "email": "rani@email.com"
        },
        "token": "2|xyz789abc..."
    }
}
```

**Error (401):**

```json
{
    "status": "error",
    "message": "Email atau password salah"
}
```

---

### 1.3 Logout 🔒

`POST /api/auth/logout`

**Response (200):**

```json
{
    "status": "success",
    "message": "Logout berhasil"
}
```

---

### 1.4 Get Current User 🔒

`GET /api/auth/me`

**Response (200):**

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Bunda Rani",
        "email": "rani@email.com"
    }
}
```

---

### 1.5 Check Auth Status

`GET /api/auth/check`

**Response (200):**

```json
{
    "authenticated": true,
    "user": { "id": 1, "name": "Bunda Rani" }
}
```

---

## 2. Children API 🔒

### 2.1 List Children

`GET /api/children`

**Response (200):**

```json
{
    "status": "success",
    "message": "Daftar anak berhasil diambil",
    "data": [
        {
            "id": 1,
            "nama_anak": "Aisyah",
            "tanggal_lahir": "2021-03-15",
            "umur": 4,
            "is_active": true,
            "timer": {
                "limit_detik": 3600,
                "sisa_detik": 2400,
                "formatted": "40:00",
                "has_time": true
            },
            "created_at": "2026-02-10T12:00:00.000000Z"
        }
    ],
    "total": 1
}
```

---

### 2.2 Create Child

`POST /api/children`

**Request:**

```json
{
    "nama_anak": "Aisyah",
    "tanggal_lahir": "2021-03-15",
    "limit_detik": 3600
}
```

| Field           | Type    | Required | Notes                                             |
| :-------------- | :------ | :------- | :------------------------------------------------ |
| `nama_anak`     | string  | ✅       | Max 255 chars                                     |
| `tanggal_lahir` | date    | ✅       | Format: YYYY-MM-DD, harus sebelum hari ini        |
| `limit_detik`   | integer | ❌       | Min 300 (5 min), Max 14400 (4 jam). Default: 3600 |

**Response (201):**

```json
{
    "status": "success",
    "message": "Profil anak berhasil ditambahkan",
    "data": {
        "id": 1,
        "nama_anak": "Aisyah",
        "tanggal_lahir": "2021-03-15",
        "umur": 4,
        "limit_detik": 3600
    }
}
```

---

### 2.3 Show Child Detail

`GET /api/children/{id}`

**Response (200):**

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "nama_anak": "Aisyah",
        "tanggal_lahir": "2021-03-15",
        "umur": 4,
        "is_active": true,
        "timer": {
            "limit_detik": 3600,
            "sisa_detik": 2400,
            "formatted": "40:00",
            "has_time": true,
            "is_running": false
        },
        "progress": { "reading": 60, "writing": 40, "counting": 20 },
        "recent_activity": []
    }
}
```

---

### 2.4 Update Child

`PUT /api/children/{id}`

**Request:** (semua field optional)

```json
{
    "nama_anak": "Aisyah Putri",
    "tanggal_lahir": "2021-03-15",
    "limit_detik": 7200
}
```

---

### 2.5 Delete Child

`DELETE /api/children/{id}`

**Response (200):**

```json
{
    "status": "success",
    "message": "Profil anak 'Aisyah' berhasil dihapus"
}
```

---

## 3. Timer API 🔒

### 3.1 Start Timer

`POST /api/children/{id}/timer/start`

**Response (200):**

```json
{
    "status": "success",
    "message": "Timer dimulai!",
    "data": {
        "sisa_detik": 3600,
        "formatted": "60:00",
        "timer_started_at": "2026-02-13T12:00:00.000000Z"
    }
}
```

**Error (403) — waktu habis:**

```json
{
    "status": "error",
    "message": "Waktu belajar hari ini sudah habis!",
    "data": { "sisa_detik": 0, "formatted": "00:00" }
}
```

---

### 3.2 Stop Timer

`POST /api/children/{id}/timer/stop`

**Response (200):**

```json
{
    "status": "success",
    "message": "Timer dihentikan",
    "data": { "sisa_detik": 2400, "formatted": "40:00" }
}
```

---

### 3.3 Timer Status

`GET /api/children/{id}/timer`

**Response (200):**

```json
{
    "status": "success",
    "data": {
        "child_id": 1,
        "nama_anak": "Aisyah",
        "limit_detik": 3600,
        "sisa_detik": 2400,
        "formatted": "40:00",
        "has_time": true,
        "is_running": true,
        "should_lock": false
    }
}
```

> ⚠️ **Flutter**: Polling endpoint ini setiap 30 detik. Jika `should_lock = true`, tampilkan lock screen.

---

### 3.4 Verify PIN (Unlock Screen)

`POST /api/children/{id}/timer/verify-pin`

**Request:**

```json
{
    "pin": "password_orang_tua"
}
```

**Response (200) — PIN benar:**

```json
{
    "status": "success",
    "message": "PIN benar! Layar terbuka.",
    "unlocked": true,
    "data": {
        "child_id": 1,
        "nama_anak": "Aisyah",
        "sisa_detik": 0
    }
}
```

**Error (401) — PIN salah:**

```json
{
    "status": "error",
    "message": "PIN salah. Hubungi orang tua.",
    "unlocked": false
}
```

> ⚠️ **Security**: PIN = password orang tua (hashed). Invalid attempts di-log.

---

## 4. Modules API

### 4.1 List All Modules (Public)

`GET /api/modules`

**Response (200):**

```json
{
    "success": true,
    "message": "Modules berhasil diambil",
    "data": [
        { "id": 1, "name": "Menulis", "type": "writing", "foto": "..." },
        { "id": 2, "name": "Membaca", "type": "reading", "foto": "..." },
        { "id": 3, "name": "Berhitung", "type": "counting", "foto": "..." }
    ]
}
```

---

### 4.2 Module Detail (Public)

`GET /api/modules/{id}`

**Response (200):**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Menulis",
        "type": "writing",
        "levels": [
            { "id": 1, "title": "Level 1", "order_number": 1 },
            { "id": 2, "title": "Level 2", "order_number": 2 }
        ],
        "books": []
    }
}
```

---

### 4.3 Levels per Module (Public)

`GET /api/modules/{id}/levels`

**Query Params (optional):**
| Param | Type | Description |
|:---|:---|:---|
| `anak_id` | integer | Jika dikirim, response menyertakan status progres per level |

**Response (200) — tanpa anak_id:**

```json
{
    "success": true,
    "message": "Levels untuk modul Menulis",
    "data": {
        "module": { "id": 1, "name": "Menulis", "type": "writing" },
        "levels": [
            {
                "id": 1,
                "title": "Level 1",
                "order_number": 1,
                "progress": null
            },
            { "id": 2, "title": "Level 2", "order_number": 2, "progress": null }
        ]
    }
}
```

**Response (200) — dengan `?anak_id=1`:**

```json
{
    "success": true,
    "data": {
        "module": { "id": 1, "name": "Menulis", "type": "writing" },
        "levels": [
            {
                "id": 1,
                "title": "Level 1",
                "order_number": 1,
                "progress": { "score": 85, "bintang": 4, "selesai": true }
            },
            {
                "id": 2,
                "title": "Level 2",
                "order_number": 2,
                "progress": null
            }
        ]
    }
}
```

---

## 5. Progress API 🔒

### 5.1 Save Progress

`POST /api/progress`

**Request:**

```json
{
    "anak_id": 1,
    "level_id": 3,
    "score": 85,
    "bintang": 4,
    "selesai": true
}
```

| Field      | Type    | Required | Validation                |
| :--------- | :------ | :------- | :------------------------ |
| `anak_id`  | integer | ✅       | Harus milik user login    |
| `level_id` | integer | ✅       | Harus ada di tabel levels |
| `score`    | integer | ✅       | 0–100                     |
| `bintang`  | integer | ✅       | 0–5                       |
| `selesai`  | boolean | ✅       | true/false                |

**Response (200):**

```json
{
    "status": "success",
    "message": "Progres berhasil disimpan",
    "data": {
        "progress": {
            "id": 1,
            "anak_id": 1,
            "level_id": 3,
            "score": 85,
            "bintang": 4,
            "selesai": true,
            "level": {
                "id": 3,
                "title": "Level 3",
                "module": { "id": 1, "name": "Menulis" }
            }
        },
        "anak": { "id": 1, "nama": "Aisyah" },
        "unlocked_rewards": []
    }
}
```

**Response (200) — dengan reward unlock:**

```json
{
    "status": "success",
    "message": "Progres disimpan & baju baru ter-unlock! 🎉",
    "data": {
        "progress": { "..." },
        "anak": { "id": 1, "nama": "Aisyah" },
        "unlocked_rewards": [
            {
                "id": 2,
                "name": "Baju Batik",
                "image": "/images/nusa-batik.png",
                "type": "reward"
            }
        ]
    }
}
```

> 🎓 **Reward Milestones:**
>
> - Selesaikan Level 5+ → unlock 1 baju reward
> - Selesaikan SEMUA level dalam 1 modul → unlock 1 baju reward tambahan

---

### 5.2 Progress Report (Parent Center)

`GET /api/progress/{child_id}`

**Response (200):**

```json
{
    "status": "success",
    "message": "Laporan progres anak",
    "data": {
        "child": { "id": 1, "nama": "Aisyah", "umur": 4 },
        "summary": {
            "total_sessions": 12,
            "total_completed": 8,
            "total_levels": 21,
            "overall_percentage": 38.1,
            "avg_score": 76.5
        },
        "strongest_module": "Menulis",
        "weakest_module": "Berhitung",
        "modules": [
            {
                "module_id": 1,
                "module_name": "Menulis",
                "module_type": "writing",
                "completed": 4,
                "total_levels": 5,
                "percentage": 80.0,
                "avg_score": 82.3,
                "avg_stars": 3.8
            }
        ],
        "learning_style": {
            "visual": 45,
            "auditory": 30,
            "kinesthetic": 25
        },
        "recommendations": [
            "Latihan Berhitung perlu ditingkatkan. Coba ulangi level yang skornya rendah.",
            "Anak cenderung visual learner. Bacakan cerita bergambar untuk perkuat literasi."
        ],
        "recent_activity": [
            {
                "level": "Level 3",
                "module": "Menulis",
                "score": 85,
                "bintang": 4,
                "selesai": true,
                "updated_at": "2026-02-13 12:30:00"
            }
        ]
    }
}
```

---

## 6. Shop API 🔒

### 6.1 List Shop Items

`GET /api/shop`

**Query Params:**
| Param | Type | Required | Description |
|:---|:---|:---|:---|
| `anak_id` | integer | ✅ | ID anak untuk cek status kepemilikan |

**Response (200):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Baju Default",
            "image_url": "/images/nusa-default.png",
            "unlock_type": "free",
            "status": "equipped"
        },
        {
            "id": 2,
            "name": "Baju Batik",
            "image_url": "/images/nusa-batik.png",
            "unlock_type": "reward",
            "reward_condition": "complete_5_levels",
            "status": "locked_reward"
        },
        {
            "id": 5,
            "name": "Baju Adat Minang",
            "image_url": "/images/nusa-minang.png",
            "unlock_type": "premium",
            "status": "locked_premium"
        }
    ]
}
```

**Status values:**
| Status | Meaning |
|:---|:---|
| `equipped` | Sedang dipakai |
| `owned` | Sudah dimiliki, bisa di-equip |
| `available` | Bisa diambil gratis |
| `locked_reward` | Belum unlock (perlu belajar) |
| `locked_premium` | Perlu langganan premium |

---

### 6.2 Claim Reward Item

`POST /api/shop/claim`

**Request:**

```json
{
    "anak_id": 1,
    "character_item_id": 2
}
```

**Response (200):**

```json
{
    "status": "success",
    "message": "Baju Batik berhasil di-claim!"
}
```

---

### 6.3 Inventory (Lemari Baju)

`GET /api/shop/inventory`

**Query Params:** `anak_id` (required)

**Response (200):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "Baju Default",
            "image_url": "/images/nusa-default.png",
            "is_equipped": true
        },
        {
            "id": 3,
            "name": "Baju Kebaya",
            "image_url": "/images/nusa-kebaya.png",
            "is_equipped": false
        }
    ]
}
```

---

### 6.4 Equip Item

`POST /api/shop/equip`

**Request:**

```json
{
    "anak_id": 1,
    "character_item_id": 3
}
```

**Response (200):**

```json
{
    "status": "success",
    "message": "Baju Kebaya berhasil dipasang!"
}
```

---

## 7. Subscription API 🔒

### 7.1 Check Premium Status
`GET /api/subscription/status`

**Response (200) — tidak premium:**
```json
{
    "status": "success",
    "is_premium": false,
    "data": null
}
```

**Response (200) — premium aktif:**
```json
{
    "status": "success",
    "is_premium": true,
    "data": {
        "plan_name": "CALISTA Premium",
        "starts_at": "2026-04-01",
        "ends_at": "2026-05-01"
    }
}
```

---

### 7.2 Activate Subscription 🔒 (🆕)
`POST /api/subscription/subscribe`

**Request:**
```json
{
    "plan_id": 1,
    "child_id": 1
}
```

**Response (200) — berhasil:**
```json
{
    "status": "success",
    "message": "🎉 Paket CALISTA Premium berhasil diaktifkan! Hadiah 'Baju Adat Minang' telah ditambahkan ke inventory anak.",
    "is_premium": true,
    "gift": {
        "id": 4,
        "name": "Baju Adat Minang",
        "image_url": "/images/nusa/baju_adat_minang.png"
    }
}
```

---

## 📋 Quick Reference — All Endpoints

| #   | Auth | Method | Endpoint                              | Description               |
| :-- | :--- | :----- | :------------------------------------ | :------------------------ |
| 1   | ❌   | POST   | `/api/auth/register`                  | Daftar akun               |
| 2   | ❌   | POST   | `/api/auth/login`                     | Login, dapat token        |
| 3   | ❌   | GET    | `/api/auth/check`                     | Cek status auth           |
| 4   | 🔒   | POST   | `/api/auth/logout`                    | Logout, hapus token       |
| 5   | 🔒   | GET    | `/api/auth/me`                        | Data user login           |
| 6   | ❌   | GET    | `/api/modules`                        | Daftar modul              |
| 7   | ❌   | GET    | `/api/modules/{id}`                   | Detail modul              |
| 8   | ❌   | GET    | `/api/modules/{id}/levels`            | Levels per modul          |
| 9   | 🔒   | GET    | `/api/children`                       | Daftar anak               |
| 10  | 🔒   | POST   | `/api/children`                       | Tambah anak               |
| 11  | 🔒   | GET    | `/api/children/{id}`                  | Detail anak               |
| 12  | 🔒   | PUT    | `/api/children/{id}`                  | Edit anak                 |
| 13  | 🔒   | DELETE | `/api/children/{id}`                  | Hapus anak                |
| 14  | 🔒   | POST   | `/api/children/{id}/timer/start`      | Mulai timer               |
| 15  | 🔒   | POST   | `/api/children/{id}/timer/stop`       | Stop timer                |
| 16  | 🔒   | GET    | `/api/children/{id}/timer`            | Status timer              |
| 17  | 🔒   | POST   | `/api/children/{id}/timer/verify-pin` | PIN unlock                |
| 18  | 🔒   | POST   | `/api/progress`                       | Simpan progres            |
| 19  | 🔒   | GET    | `/api/progress/{child_id}`            | Laporan progres           |
| 20  | 🔒   | GET    | `/api/shop`                           | Daftar baju toko          |
| 21  | 🔒   | POST   | `/api/shop/claim`                     | Claim baju reward         |
| 22  | 🔒   | GET    | `/api/shop/inventory`                 | Lemari baju               |
| 23  | 🔒   | POST   | `/api/shop/equip`                     | Pasang baju               |
| 24  | 🔒   | POST   | `/api/shop/buy-premium`               | 🆕 Beli baju premium      |
| 25  | 🔒   | GET    | `/api/subscription/status`            | Cek premium status        |
| 26  | 🔒   | POST   | `/api/subscription/subscribe`         | 🆕 Aktivasi Subscription  |
| 27  | 🔒   | POST   | `/api/parental-gate/challenge`        | Layer 1: Generate soal    |
| 28  | 🔒   | POST   | `/api/parental-gate/verify`           | Layer 1: Cek jawaban      |
| 29  | 🔒   | POST   | `/api/parental-gate/verify-password`  | Layer 2: Cek password     |

---

## 8. Shop — Buy Premium 🔒 (🆕)

### 8.1 Buy Premium Item

`POST /api/shop/buy-premium`

**Request:**

```json
{
    "child_id": 1,
    "item_id": 4
}
```

**Response (201) — berhasil:**

```json
{
    "status": "success",
    "message": "🎉 Baju 'Baju Adat Minang' berhasil dibeli! Cek di Lemari Baju ya!",
    "data": {
        "item": {
            "id": 4,
            "name": "Baju Adat Minang",
            "description": "Baju adat Minangkabau yang indah.",
            "image_url": "/images/nusa/baju_adat_minang.png"
        },
        "unlocked_at": "2026-02-14T00:30:00.000000Z"
    }
}
```

**Error (403) — tidak punya subscription:**

```json
{
    "status": "error",
    "message": "Kamu belum berlangganan CALISTA Premium. Berlangganan dulu ya!",
    "requires_subscription": true
}
```

---

## 9. Parental Gate API 🔒 (🆕)

### 9.1 Generate Challenge (Layer 1)

`POST /api/parental-gate/challenge`

**Response (200):**

```json
{
    "status": "success",
    "data": {
        "challenge_text": "tiga ratus empat puluh tujuh",
        "challenge_token": "encrypted_token...",
        "instruction": "Tulis angka dari kata di atas",
        "expires_in_seconds": 300
    }
}
```

---

### 9.2 Verify Challenge (Layer 1)

`POST /api/parental-gate/verify`

**Request:**

```json
{
    "answer": 347,
    "challenge_token": "encrypted_token..."
}
```

**Response (200) — benar:**

```json
{
    "status": "success",
    "verified": true,
    "message": "Benar! Silakan lanjut ke verifikasi orang tua.",
    "gate_token": "encrypted_gate_token...",
    "gate_expires_in_seconds": 600
}
```

**Response (200) — salah:**

```json
{
    "status": "error",
    "verified": false,
    "message": "Jawaban salah. Coba lagi ya!"
}
```

---

### 9.3 Verify Password (Layer 2)

`POST /api/parental-gate/verify-password`

**Request:**

```json
{
    "password": "password_orang_tua",
    "gate_token": "encrypted_gate_token...",
    "require_layer1": true
}
```

| Field            | Type    | Required | Notes                                            |
| :--------------- | :------ | :------- | :----------------------------------------------- |
| `password`       | string  | ✅       | Password akun CALISTA orang tua                  |
| `gate_token`     | string  | ❌       | Wajib jika `require_layer1 = true`               |
| `require_layer1` | boolean | ❌       | Set `true` jika pertama kali buka Toko Misterius |

**Response (200) — berhasil:**

```json
{
    "status": "success",
    "verified": true,
    "message": "Verifikasi berhasil! Akses premium terbuka.",
    "access_token": "encrypted_access_token...",
    "access_expires_in_seconds": 1800
}
```

> ⚠️ **Flow Parental Gate di Flutter:**
>
> 1. `POST /parental-gate/challenge` → tampilkan soal
> 2. User jawab → `POST /parental-gate/verify` → dapat `gate_token`
> 3. Minta password → `POST /parental-gate/verify-password` (kirim `gate_token`)
> 4. Berhasil → simpan `access_token` (valid 30 menit)

---

## ⚠️ Error Responses (Global)

**422 — Validation Error:**

```json
{
    "status": "error",
    "message": "Validasi gagal",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

**401 — Unauthenticated:**

```json
{
    "message": "Unauthenticated."
}
```

**403 — Forbidden:**

```json
{
    "status": "error",
    "message": "Anak tidak ditemukan atau bukan milik Anda"
}
```

**404 — Not Found:**

```json
{
    "status": "error",
    "message": "Data tidak ditemukan"
}
```

**500 — Server Error:**

```json
{
    "status": "error",
    "message": "Terjadi kesalahan pada server"
}
```

---

## 🔧 Flutter Integration Tips

1. **Base URL**: Simpan di `ApiConfig`, ganti saat switch dev/prod
2. **Token Storage**: Simpan token di `SharedPreferences` / `flutter_secure_storage`
3. **Timer Polling**: Poll `GET /api/children/{id}/timer` setiap 30 detik
4. **Reward Check**: Setelah `POST /api/progress`, cek `unlocked_rewards` array
5. **Premium Gate**: Cek `GET /api/subscription/status` saat buka Shop
6. **Parental Gate**: Gunakan flow 3-step (challenge → verify → password) untuk subscribe
7. **Buy Premium**: Untuk beli baju premium, cukup `POST /api/shop/buy-premium` (Layer 2 saja)

---

_Last Updated: 1 April 2026 — Simplified Monetization (No Coins), Added Subscription Activation, total 29 endpoints_
