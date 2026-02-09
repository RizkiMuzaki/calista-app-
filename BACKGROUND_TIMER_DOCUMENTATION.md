# Background Timer Implementation

## Overview
Timer di halaman profil anak sekarang tetap berjalan bahkan ketika halaman tidak aktif (tab tertutup/background).

## Cara Kerja

### 1. Service Worker (service-worker.js)
- Berjalan di background secara independen dari halaman
- Menerima pesan dari halaman utama untuk start/stop/reset timer
- Melacak waktu berlalu setiap 1 detik
- Mengirim update ke halaman utama
- Menampilkan browser notification ketika timer habis

### 2. Timing Accuracy
- **Server Timestamp**: Menggunakan `server_time` dari API untuk akurasi
- **Local Countdown**: Menampilkan countdown setiap 1 detik secara smooth (client-side)
- **Sync Berkala**: Mensinkronisasi dengan server setiap 10 detik untuk memastikan akurasi

### 3. Flow Eksekusi

#### Saat Timer Dimulai:
1. User klik tombol "Mulai Timer"
2. Request POST ke `/anak/{id}/start-timer`
3. Server update `timer_started_at` di database
4. Response berisi `timer_started_at` dan `server_time`
5. JavaScript kirim pesan ke Service Worker dengan timing data
6. Service Worker mulai track waktu berlalu di background
7. Browser meminta notification permission

#### Saat User Pindah Tab:
1. Service Worker tetap berjalan di background
2. Setiap 1 detik, Service Worker:
   - Hitung elapsed time dari start time
   - Hitung remaining time
   - Kirim update ke tab (jika tab aktif)
   - Cek jika waktu habis → stop dan tampilkan notification
3. Halaman utama tetap menampilkan countdown terbaru dari Service Worker

#### Saat Tab Kembali Aktif:
1. Event `visibilitychange` dipicu
2. JavaScript request data terbaru dari server
3. Server hitung remaining time berdasarkan elapsed time
4. Update display dengan data terbaru dari server

#### Saat Timer Berakhir:
1. Service Worker mendeteksi remaining time ≤ 0
2. Menghentikan interval
3. Menampilkan browser notification (walaupun tab tertutup)
4. Mengirim message ke halaman aktif jika ada

### 4. API Response (getRemainingTime)

```json
{
  "success": true,
  "remaining_seconds": 1800,           // Sisa detik
  "formatted_time": "00:30:00",        // Format HH:MM:SS
  "has_time": true,                    // Ada sisa waktu?
  "timer_started_at": "2026-01-23...", // Kapan timer dimulai
  "limit_detik": 3600,                 // Total limit detik
  "is_running": true,                  // Apakah timer sedang berjalan
  "timestamp": 1674456000,             // Server timestamp (detik)
  "server_time": 1674456000            // Server time untuk background tracking
}
```

### 5. Browser Notification
Ketika timer habis dan halaman tidak aktif:
- Menampilkan notification dengan title "Waktu Belajar Habis!"
- User bisa klik untuk fokus ke halaman
- Requires permission dari user sebelumnya

## Fitur-Fitur

✅ **Background Tracking** - Timer berjalan meskipun tab tertutup
✅ **Smooth Display** - Countdown update setiap 1 detik
✅ **Server Sync** - Sinkronisasi setiap 10 detik untuk akurasi
✅ **Notification** - Alert browser ketika waktu habis
✅ **Timestamp-based** - Menggunakan server timestamp, tidak bergantung client clock
✅ **Fallback** - Tetap work bahkan jika Service Worker tidak support

## Browser Support

- ✅ Chrome/Chromium 40+
- ✅ Firefox 44+
- ✅ Edge 17+
- ✅ Opera 27+
- ⚠️ Safari (partial - notification tidak support)

## Testing

### Test 1: Background Timer
1. Mulai timer
2. Pindah ke tab lain
3. Seharusnya timer tetap berkurang di background
4. Kembali ke tab → display updated dengan benar

### Test 2: Notification
1. Mulai timer dengan sisa < 30 detik
2. Pindah ke tab lain
3. Tunggu hingga timer habis
4. Seharusnya notification muncul (notification permission harus diijinkan)

### Test 3: Accuracy
1. Mulai timer
2. Catat waktu di browser
3. Pindah ke background untuk 10 detik
4. Kembali ke tab
5. Waktu display seharusnya akurat (±1 detik)

## Important Notes

⚠️ **Notification Permission**: User harus allow notification permission untuk browser notification bekerja
⚠️ **Service Worker**: Perlu HTTPS di production (kecuali localhost)
⚠️ **Server Time**: Akurasi bergantung pada server dan client clock

## Maintenance

Jika ada update pada timer logic:
1. Update `/anak/{id}/start-timer` endpoint
2. Update `/anak/{id}/stop-timer` endpoint
3. Update `/anak/{id}/remaining-time` response
4. Update `service-worker.js` logic
5. Update JavaScript di `profilprogreanak.blade.php`

## Files Modified

- `/public/service-worker.js` - Baru
- `/resources/views/pages/profilprogreanak.blade.php` - Updated JavaScript
- `/app/Http/Controllers/AnakController.php` - Added `server_time` to response
