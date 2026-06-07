    # 🏪 CALISTA — Sistem Toko, Premium & Monetisasi

    > **Dokumen ini** menjelaskan seluruh fitur toko baju, langganan premium, dan mekanisme monetisasi aplikasi CALISTA.
    > Keputusan di dokumen ini adalah hasil brainstorming **Rizki × Rich (13 Feb 2026)**.
    >
    > **Status**: ✅ Konsep Final — Harga TBD (menunggu diskusi tim)

    ---

    ## 📖 Daftar Isi

    1. [Konsep Utama](#1--konsep-utama)
    2. [Toko Baju Nusa — 3 Tier](#2--toko-baju-nusa--3-tier)
    3. [Toko Misterius (Premium Area)](#3--toko-misterius-premium-area)
    4. [Parental Gate — Double Layer](#4--parental-gate--double-layer)
    5. [Langganan Premium (Subscription)](#5--langganan-premium-subscription)
    6. [Reward System — "Mengejar Baju"](#6--reward-system--mengejar-baju)
    7. [Timer & Lock Screen](#7--timer--lock-screen)
    8. [Revenue Model](#8--revenue-model)
    9. [Alur Lengkap (User Flow)](#9--alur-lengkap-user-flow)
    10. [Status Implementasi](#10--status-implementasi)
    11. [Keputusan yang Masih Pending](#11--keputusan-yang-masih-pending)

    ---

    ## 1. 🎯 Konsep Utama

    CALISTA menggunakan **model monetisasi hybrid** yang menggabungkan tiga pendekatan:

    | Pendekatan     | Mekanisme                                       | Target          |
    | :------------- | :---------------------------------------------- | :-------------- |
    | **Gratis**     | Baju default + konten belajar dasar (Level 1-5) | Semua user      |
    | **Gamifikasi** | Baju reward dari progress belajar               | Engagement anak |
    | **Berbayar**   | Langganan premium + beli baju satuan            | Revenue         |

    ### Prinsip Penting:

    - ✅ **Konten edukasi dasar GRATIS** — membaca, menulis, berhitung Level 1-5 bisa diakses semua anak tanpa bayar
    - ✅ **Tanpa koin/virtual currency** — semua transaksi langsung dalam Rupiah, via payment gateway (Dana, Google Play, dll)
    - ✅ **Anak tidak bisa beli sendiri** — semua pembelian melalui Parental Gate
    - ✅ **Reward dari belajar, bukan dari uang** — baju reward didapat dari usaha anak, bukan dompet orang tua

    ---

    ## 2. 👗 Toko Baju Nusa — 3 Tier

    Karakter "Nusa" bisa dipakaikan baju berbeda. Ada 3 kategori baju:

    ### Tier 1: FREE (Gratis)

    | Item               | Cara Dapat                 | Jumlah |
    | :----------------- | :------------------------- | :----- |
    | Baju Default | Langsung punya saat daftar | 1 baju |

    - Otomatis di-assign ke semua anak baru
    - Tidak bisa dihapus atau dijual

    ### Tier 2: REWARD (Dari Belajar)

    | Item        | Cara Dapat                           | Jumlah |
    | :---------- | :----------------------------------- | :----- |
    | Baju Batik  | Selesaikan semua level Modul Menulis | 1 baju |
    | Baju Kebaya | Selesaikan semua level Modul Membaca | 1 baju |

    - **Terlihat dari awal** di toko (bukan surprise)
    - Ada **progress bar** yang menunjukkan berapa persen sudah tercapai
    - Anak bisa lihat tujuan dan "mengejar" bajunya
    - Berlaku untuk semua user (free maupun premium)

    ### Tier 3: PREMIUM (Beli Satuan)

    | Item                        | Cara Dapat             | Jumlah     |
    | :-------------------------- | :--------------------- | :--------- |
    | Baju Adat Minang            | Beli di Toko Misterius | 1 baju     |
    | Baju Jas Kecil              | Beli di Toko Misterius | 1 baju     |
    | Baju Adat Bali              | Beli di Toko Misterius | 1 baju     |
    | _(+baju baru setiap bulan)_ | Beli di Toko Misterius | Berkembang |

    - **HANYA bisa diakses** oleh subscriber premium
    - **Dibeli satu per satu** (BUKAN langsung dapat semua)
    - Harga per baju: **TBD** (menunggu diskusi tim)
    - Koleksi baru bisa ditambahkan setiap bulan sebagai insentif tetap berlangganan

    ---

    ## 3. ✨ Toko Misterius (Premium Area)

    ### Konsep

    Toko Misterius adalah area khusus di dalam Toko Baju Nusa yang **tertutup awan/kabut** dan menimbulkan rasa penasaran anak. Ini bukan popup iklan — ini adalah **bagian dari pengalaman bermain**.

    ### Tampilan (Sebelum Subscribe)

    ```
    ╔══════════════════════════════════════╗
    ║         ☁️✨ TOKO MISTERIUS ✨☁️      ║
    ║                                      ║
    ║     ?       ?       ?       ?        ║
    ║    ☁️      ☁️      ☁️      ☁️       ║
    ║                                      ║
    ║    "Ada baju spesial tersembunyi     ║
    ║     di balik awan... Penasaran?"     ║
    ║                                      ║
    ║         ✨ [ BUKA TOKO ] ✨          ║
    ╚══════════════════════════════════════╝
    ```

    - Baju premium terlihat sebagai **siluet bayangan** atau **tanda tanya** di balik awan
    - Animasi awan bergerak pelan, sesekali terlihat detail baju
    - Tombol "Buka Toko" memicu **Parental Gate** (lihat bagian 4)

    ### Tampilan (Setelah Subscribe)

    ```
    ╔══════════════════════════════════════╗
    ║       🌟 TOKO MISTERIUS TERBUKA 🌟   ║
    ║                                      ║
    ║  ┌──────┐  ┌──────┐  ┌──────┐       ║
    ║  │ Baju │  │ Baju │  │ Baju │       ║
    ║  │Minang│  │Jas   │  │Adat  │       ║
    ║  │      │  │Kecil │  │Bali  │       ║
    ║  │Rp__K │  │Rp__K │  │Rp__K │       ║
    ║  │[BELI]│  │[BELI]│  │[BELI]│       ║
    ║  └──────┘  └──────┘  └──────┘       ║
    ║                                      ║
    ║  💎 Koleksi baru setiap bulan!       ║
    ╚══════════════════════════════════════╝
    ```

    - Awan terbuka dengan animasi
    - Baju premium ditampilkan dengan harga masing-masing
    - Tombol "Beli" juga melalui **Parental Gate** (Layer 2 saja)

    ### Kenapa Desain Ini Efektif?

    1. **Curiosity** — Anak penasaran isi toko → tanya orang tua secara natural
    2. **Bukan iklan paksa** — Anak yang tertarik sendiri, bukan dipaksa popup
    3. **Natural upsell** — Orang tua tahu anaknya mau apa
    4. **Bagian dari gameplay** — Toko Misterius terasa seperti fitur game, bukan halaman jualan

    ---

    ## 4. 🔐 Parental Gate — Double Layer

    ### Mengapa Double Layer?

    Karena CALISTA ditargetkan untuk anak 5-10 tahun, **aturan Google Play dan App Store mengharuskan** semua pembelian diarahkan ke orang tua. Anak TIDAK BOLEH bisa membeli sendiri.

    Satu layer (soal matematika saja) tidak cukup karena anak usia 9-10 tahun sudah bisa menjawab soal penjumlahan 2 digit. Oleh karena itu kita menggunakan **double layer** security:

    ### Layer 1: Challenge — "Tulis Angka dari Kata"

    ```
    ┌──────────────────────────────────┐
    │  🔒 AREA ORANG TUA               │
    │                                  │
    │  "Halaman ini untuk              │
    │   Ayah/Bunda saja ya! 🤗"       │
    │                                  │
    │  Tulis angka dari kata ini:      │
    │                                  │
    │  "tiga ratus empat puluh tujuh"  │
    │                                  │
    │  ┌──────────────────────────┐    │
    │  │  [___________]           │    │
    │  └──────────────────────────┘    │
    │              ← Jawaban: 347      │
    │                                  │
    │  [ LANJUT ]     [ KEMBALI ]      │
    └──────────────────────────────────┘
    ```

    **Kenapa "tulis angka dari kata" lebih aman dari soal matematika:**

    - Harus **baca kalimat panjang** → anak 5-6 tahun belum lancar baca
    - Harus **konversi kata ke angka** → skill yang belum berkembang di usia 5-7
    - Angka **berubah random** setiap kali → tidak bisa dihafal
    - Buat orang dewasa? Selesai dalam **3 detik**

    ### Layer 2: Password Orang Tua

    ```
    ┌──────────────────────────────────┐
    │  🔐 VERIFIKASI ORANG TUA         │
    │                                  │
    │  "Masukkan password akun         │
    │   CALISTA Anda"                  │
    │                                  │
    │  ┌──────────────────────────┐    │
    │  │  ●●●●●●●●               │    │
    │  └──────────────────────────┘    │
    │                                  │
    │  [ MASUK ]      [ BATAL ]        │
    └──────────────────────────────────┘
    ```

    **Kenapa Layer 2 tidak bisa ditembus anak:**

    - Password orang tua = password login CALISTA
    - Anak tidak tahu password ini
    - Divalidasi di server (`Hash::check`)
    - Failed attempt di-log untuk keamanan

    ### Kapan Parental Gate Muncul?

    | Aksi                                         | Layer 1 | Layer 2  |
    | :------------------------------------------- | :------ | :------- |
    | Klik "Buka Toko Misterius" (belum subscribe) | ✅      | ✅       |
    | Klik "Beli" baju premium (sudah subscribe)   | ❌      | ✅       |
    | Klik "Berlangganan" dari popup               | ✅      | ✅       |
    | Unlock layar setelah timer habis             | ❌      | ✅ (PIN) |

    > **Catatan**: Setelah subscribe, beli baju hanya perlu Layer 2 (password) karena orang tua sudah terverifikasi saat subscribe.

    ---

    ## 5. 💎 Langganan Premium (Subscription)

    ### Apa yang Didapat Subscriber?

    | Fitur                                | Free User              | Premium User              |
    | :----------------------------------- | :--------------------- | :------------------------ |
    | Belajar Level 1-5 (semua modul)      | ✅                     | ✅                        |
    | 1 Baju Default                       | ✅                     | ✅                        |
    | 2 Baju Reward (dari belajar)         | ✅                     | ✅                        |
    | AI Voice                             | 🟡 Terbatas (10x/hari) | ✅ Unlimited              |
    | Laporan V-A-K (gaya belajar)         | 🟡 Ringkasan           | ✅ Lengkap + Rekomendasi  |
    | **Belajar Level 6-10 (semua modul)** | 🔒 Locked              | ✅ Unlocked               |
    | **Toko Misterius**                   | 🔒 Locked              | ✅ Bisa beli baju premium |
    | **Mini-games eksklusif**             | 🔒 Locked              | ✅ Unlocked               |

    ### Detail Berlangganan:

    - **Nama**: CALISTA Premium
    - **Durasi**: Bulanan
    - **Harga**: **TBD** (menunggu diskusi tim — opsi: Rp9.900 / Rp14.900 / Rp19.900)
    - **Pembayaran**: Via payment gateway (Dana, Google Play Billing, dll)
    - **Auto-renew**: Opsional

    ### Popup Subscribe (dari Toko Misterius)

    ```
    ┌──────────────────────────────────────┐
    │   💎 CALISTA Premium                  │
    │                                      │
    │   Buka semua fitur spesial!          │
    │                                      │
    │   ✅ Buka Toko Misterius (baju baru) │
    │   ✅ Game bonus (Level 6-10)         │
    │   ✅ AI Voice tanpa batas            │
    │   ✅ Laporan belajar lengkap         │
    │   ✅ Mini-games seru                 │
    │                                      │
    │   Rp__.___ / bulan                   │
    │                                      │
    │   [ BERLANGGANAN ]  [ NANTI DULU ]   │
    └──────────────────────────────────────┘
    ```

    ---

    ## 6. 🎯 Reward System — "Mengejar Baju"

    ### Konsep: Goal-Driven, Bukan Surprise

    Baju reward TIDAK diberikan secara kejutan. Sebaliknya, baju **sudah terlihat dari awal** di toko, lengkap dengan:

    1. **Gambar baju** yang terlihat jelas (bukan siluet)
    2. **Syarat mendapatkan** (misal: "Selesaikan semua level Menulis")
    3. **Progress bar** yang menunjukkan kemajuan real-time

    ### Contoh Tampilan di Toko

    ```
    ┌────────────────────────┐
    │      👗 Baju Batik      │
    │                        │
    │     [gambar baju]      │
    │                        │
    │  ████████░░░░ 66%      │
    │  4 dari 6 level        │
    │                        │
    │  "Selesaikan Modul     │
    │   Menulis untuk        │
    │   mendapatkannya!"     │
    │                        │
    │  🔒 Belum terbuka      │
    └────────────────────────┘
    ```

    ### Kenapa Model "Mengejar" Lebih Baik dari "Milestone Surprise"?

    | Aspek          | Surprise (Lama)      | Mengejar (Baru)                                       |
    | :------------- | :------------------- | :---------------------------------------------------- |
    | Motivasi       | Muncul setelah dapat | Ada dari awal                                         |
    | Engagement     | "Kapan ya dapet?"    | "Tinggal 2 level lagi!"                               |
    | Tujuan belajar | Nggak jelas          | Jelas: "Aku mau baju itu!"                            |
    | Psikologi      | Random reward        | **Goal Gradient Effect** — makin dekat makin semangat |

    ### Mapping Reward ke Modul

    | Baju Reward | Syarat                                   | Jumlah Level |
    | :---------- | :--------------------------------------- | :----------- |
    | Baju Batik  | Selesaikan **semua level** Modul Menulis | ~5-6 level   |
    | Baju Kebaya | Selesaikan **semua level** Modul Membaca | ~5-6 level   |

    > Mapping ini bisa diubah atau ditambah sesuai kebutuhan.

    ---

    ## 7. ⏰ Timer & Lock Screen

    ### Timer — Flexible

    | Setting | Nilai                |
    | :------ | :------------------- |
    | Minimum | 5 menit (300 detik)  |
    | Maximum | 4 jam (14.400 detik) |
    | Default | 1 jam (3.600 detik)  |

    - Orang tua set timer saat buat profil anak atau edit kapan saja
    - Timer berjalan **di server** (anak tidak bisa hack dari HP)
    - Reset otomatis setiap hari (pukul 00:00)

    ### Lock Screen Flow

    ```
    Timer berjalan
        ↓
    sisa_detik = 0
        ↓
    API return: should_lock = true
        ↓
    - Flutter tampilkan Lock Screen (overlay filter)
        ↓
    Anak tidak bisa menggunakan app
        ↓
    Orang tua masukkan password (Layer 2)
        ↓
    POST /api/children/{id}/timer/verify-pin
        ↓
    Password benar → unlock layar
    Password salah → tetap terkunci + log attempt
    ```

    ### Fitur Keamanan Timer:

    - ✅ Polling setiap 30 detik (Flutter cek status)
    - ✅ Server-side timer (anti manipulasi)
    - ✅ Daily reset otomatis
    - ✅ Invalid PIN attempt di-log
    - 🟡 Android `startLockTask()` untuk screen pinning (riset)

    ---

    ## 8. 💰 Revenue Model

    ### Dua Sumber Revenue

    ```
    ┌─────────────────────────────────────────┐
    │           💰 REVENUE CALISTA            │
    │                                         │
    │  ┌─────────────────┐ ┌───────────────┐  │
    │  │   STREAM 1      │ │  STREAM 2     │  │
    │  │                 │ │               │  │
    │  │   Subscription  │ │  Baju Satuan  │  │
    │  │   Premium       │ │               │  │
    │  │                 │ │  Rp__K/baju   │  │
    │  │   Rp__K/bulan   │ │  di Toko      │  │
    │  │   (recurring)   │ │  Misterius    │  │
    │  │                 │ │  (one-time)   │  │
    │  └─────────────────┘ └───────────────┘  │
    └─────────────────────────────────────────┘
    ```

    | Stream           | Tipe                | Frekuensi      | Estimasi                               |
    | :--------------- | :------------------ | :------------- | :------------------------------------- |
    | **Subscription** | Recurring (bulanan) | Setiap bulan   | Rp\_\_K × jumlah subscriber            |
    | **Baju Premium** | One-time purchase   | Saat beli baju | Rp\_\_K × jumlah baju × jumlah pembeli |

    ### Kenapa Model Ini Bagus:

    1. **Double revenue** — subscribe + beli baju
    2. **Recurring income** — subscription bulanan
    3. **Low barrier** — harga terjangkau untuk orang tua Indonesia
    4. **Ethical** — konten edukasi dasar tetap gratis
    5. **Scalable** — bisa tambahin baju baru setiap bulan tanpa effort besar

    ---

    ## 9. 🔄 Alur Lengkap (User Flow)

    ### Flow 1: Anak Baru Mendaftar

    ```
    Register → Buat profil anak → Anak dapat baju default (FREE)
    → Mulai belajar Level 1 → Progress bar baju reward muncul di toko
    → Belajar terus → Progress naik → Selesai semua level 1 modul
    → 🎉 Baju Reward ter-unlock!
    ```

    ### Flow 2: Anak Penasaran Toko Misterius

    ```
    Anak lihat Toko Misterius (tertutup awan) → Penasaran
    → Klik "Buka Toko" → Parental Gate Layer 1 (tulis angka)
    → Anak: "Bunda, bantuin dong!" (natural upsell)
    → Orang tua lihat popup subscribe → Tertarik
    → Parental Gate Layer 2 (password) → Bayar → Subscribe ✅
    → Toko Misterius terbuka → Anak lihat baju premium
    → Anak: "Bunda, aku mau baju pilot!"
    → Orang tua: Password → Beli → Baju terbeli ✅
    ```

    ### Flow 3: Timer Habis

    ```
    Anak belajar → Timer countdown → sisa_detik = 0
    → Lock screen muncul → Anak tidak bisa pakai app
    → "Minta Ayah/Bunda untuk membuka ya!"
    → Orang tua masukkan password → Unlock ✅
    → (Timer reset besok pagi)
    ```

    ---

    ## 10. 📊 Status Implementasi

    | Komponen              | Backend                           | Flutter  | Status       |
    | :-------------------- | :-------------------------------- | :------- | :----------- |
    | Toko Baju (3 tier)    | ✅ API Ready                      | 🔴 Belum | Backend done |
    | Toko Misterius UI     | —                                 | 🔴 Belum | Desain only  |
    | Parental Gate Layer 1 | 🔴 Perlu API                      | 🔴 Belum | Belum        |
    | Parental Gate Layer 2 | ✅ API Ready (`verify-pin`)       | 🔴 Belum | Backend done |
    | Subscription check    | ✅ API Ready                      | 🔴 Belum | Backend done |
    | Reward progress bar   | ✅ Data ready (`progress/report`) | 🔴 Belum | Backend done |
    | Timer + Lock          | ✅ API Ready                      | 🔴 Belum | Backend done |
    | Payment Gateway       | ✅ PaymentController ready        | 🔴 Belum | Backend done |
    | Baju Premium purchase | 🟡 Perlu adjust ShopController    | 🔴 Belum | Partial      |

    ---

    ## 11. ❓ Keputusan yang Masih Pending

    | #   | Keputusan                                           | Siapa Putuskan      | Deadline |
    | :-- | :-------------------------------------------------- | :------------------ | :------- |
    | 1   | Harga subscription premium per bulan                | Tim (diskusi)       | TBD      |
    | 2   | Harga per baju premium                              | Tim (diskusi)       | TBD      |
    | 3   | Payment gateway yang dipakai (Dana/Google Play/dll) | Tim (diskusi)       | TBD      |
    | 4   | Jumlah baju premium awal (3? 5?)                    | Tim (diskusi)       | TBD      |
    | 5   | Batas AI Voice untuk free user (10x/hari?)          | Tim (diskusi)       | TBD      |
    | 6   | Android screen pinning (`startLockTask`)            | Rizki + Tasya riset | TBD      |

    ---

    _Dokumen ini dibuat: 13 Februari 2026, 13:54 WIB_
    _Updated: 14 Februari 2026, 00:20 WIB — Sync baju names dengan seeder_
    _Oleh: Rizki Muzaki × Rich (AI Mentor)_
    _Status: Konsep Final — Implementasi dimulai setelah harga diputuskan tim_
