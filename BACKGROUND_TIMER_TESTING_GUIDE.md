# Background Timer - Testing Manual Guide

## 🧪 Manual Testing Scenarios

### Scenario 1: Basic Background Timer (Recommended Start)

**Setup:**
1. Buka browser DevTools (F12)
2. Buka tab Application → Service Workers
3. Buka halaman profil anak

**Steps:**
```
1. Klik tombol "Mulai Timer"
   Expected: Notifikasi "Timer dimulai! (berjalan di background)"

2. Perhatikan countdown di layar
   Expected: Berkurang dari limit detik

3. Switch ke tab browser lain
   Expected: Timer tidak terlihat tapi tetap berjalan

4. Wait 15 detik di tab lain

5. Kembali ke tab timer
   Expected: Waktu berkurang ~15 detik lebih dari terakhir dilihat

6. Klik "Stop Timer"
   Expected: Timer berhenti, notifikasi "Timer dihentikan"

7. Verifikasi di DevTools:
   - Application → Service Workers → Refresh → Service Worker masih "activated"
   - Console → Tidak ada error
```

### Scenario 2: Timer Countdown Accuracy

**Setup:**
1. Pastikan Service Worker registered (lihat DevTools)
2. Set limit timer ke 60 detik (1 menit)

**Steps:**
```
1. Catat waktu system (hh:mm:ss) 
   Example: 10:30:45

2. Mulai timer
   Expected: Display shows "00:01:00" (60 seconds)

3. Wait 10 detik sambil lihat countdown
   Expected: Countdown smooth, berkurang 1 setiap 1 detik

4. Di detik ke-10, switch ke tab lain

5. Wait 5 detik lagi (total 15 detik elapsed)

6. Kembali ke tab
   Expected: Display shows ~00:00:45 (60-15=45)
   Tolerance: ±1 detik

7. Let countdown continue until 00:00:10

8. Pindah ke tab lain lagi

9. Monitor system time, tunggu 10 detik lebih

10. Kembali ke tab
    Expected: Timer habis atau mendekati 0
    Should: Notification muncul atau timer stopped
```

### Scenario 3: Notification Test

**Prerequisites:**
- Browser notification permission ALLOWED
- Device notifications enabled

**Setup:**
1. Di halaman, izinkan notification (browser prompt akan muncul)
2. Set limit timer ke 30 detik

**Steps:**
```
1. Mulai timer

2. Switch ke tab/window lain

3. Minimize browser window atau switch aplikasi

4. Wait 30 detik untuk timer habis

5. Expected Results:
   ✓ Notification pop-up muncul
   ✓ Title: "Waktu Belajar Habis!"
   ✓ Body: "Sesi belajar anak Anda telah berakhir"
   ✓ Icon: Aplikasi logo
   ✓ Notification persist sampai user close

6. Klik notification
   Expected: Browser/tab fokus ke halaman timer

7. Verifikasi di halaman:
   - Timer status: "Timer terhenti" atau "00:00:00"
   - Notification tetap bisa di-click bahkan setelah tab fokus
```

### Scenario 4: Stop/Reset Functionality with Background

**Setup:**
1. Buka halaman timer
2. Set limit ke 120 detik (2 menit)

**Steps for STOP:**
```
1. Mulai timer

2. Switch ke tab lain setelah 5 detik (sisa ~115 detik)

3. Kembali ke tab

4. Klik "Hentikan Timer" button
   Expected: 
   ✓ Notifikasi "Timer dihentikan"
   ✓ Display tetap menunjukkan sisa waktu
   ✓ Countdown berhenti (tidak berkurang lagi)

5. Switch ke tab lain

6. Wait 10 detik

7. Kembali ke tab
   Expected: Waktu masih sama (tidak berkurang)
```

**Steps for RESET:**
```
1. Timer dalam state "stopped" dengan sisa ~100 detik

2. Klik "Reset Timer" button
   Expected: Confirmation dialog "Reset timer ke limit awal?"

3. Klik "Iya"
   Expected:
   ✓ Notifikasi "Timer telah direset"
   ✓ Display menunjukkan limit penuh (120 detik)
   ✓ Status: "Timer terhenti"

4. Mulai timer lagi, dan verify countdown works

5. Klik "Reset" saat timer sedang running
   Expected: Confirmation dialog
   ✓ Setelah reset, countdown mulai dari limit penuh lagi
```

### Scenario 5: Multiple Children Timer

**Setup:**
1. Halaman sudah punya multiple anak entries
2. Anak A aktif, Anak B dan C tidak aktif

**Steps:**
```
1. Di active child section, mulai timer untuk Anak A
   Expected: "Anak Aktif Saat Ini" section menampilkan countdown

2. Scroll ke bawah ke "Daftar Anak" section
   Expected: Semua anak card terlihat
   - Anak A card: border hijau, "Aktif" badge, countdown terlihat
   - Anak B & C: border normal

3. Klik "Aktivkan" button di Anak B card
   Expected:
   ✓ Anak B status berubah jadi aktif
   ✓ Anak A status berubah jadi tidak aktif
   ✓ Page reload/update
   ✓ Active section menampilkan Anak B timer
   ✓ Anak A timer sebelumnya di-pause

4. Di background section, pastikan Anak B timer visible
   Expected: Progress section menampilkan Anak B progress

5. Mulai Anak B timer

6. Switch ke tab lain, wait 10 detik

7. Kembali ke tab
   Expected: Anak B timer ter-update dengan benar
```

### Scenario 6: Service Worker Validation

**Setup:**
1. DevTools open (F12)
2. Tab "Application" siap

**Steps:**
```
1. Check Service Worker registered:
   Navigate to: DevTools → Application → Service Workers
   Expected:
   ✓ "/service-worker.js" listed
   ✓ Status: "activated and running"
   ✓ Scope: root (/) atau root + path

2. Mulai timer di halaman

3. Open DevTools Console
   Expected:
   ✓ Pesan: "Service Worker registered successfully"
   ✓ Tidak ada error messages

4. Di Console, jalankan:
   ```javascript
   navigator.serviceWorker.controller.postMessage({
     type: 'TIMER_START',
     payload: {
       anakId: 1,
       startTime: Date.now(),
       limitDetik: 60
     }
   });
   ```
   Expected: Service Worker menerima message tanpa error

5. Check Service Worker cache:
   Application → Cache Storage
   Expected:
   ✓ Cache entries mungkin ada (tergantung config)
   ✓ Bisa inspect messages di DevTools

6. Klik "Details" di Service Worker
   Expected: Inspect panel membuka dengan info
```

### Scenario 7: Network Throttling Test

**Setup:**
1. DevTools → Network tab
2. Set throttle ke "Slow 3G"

**Steps:**
```
1. Mulai timer dengan throttling active
   Expected:
   ✓ Timer mulai dengan sedikit delay
   ✓ Countdown tetap smooth (local calculation)

2. Switch ke tab lain, wait 5 detik

3. Kembali ke tab (masih throttle)
   Expected:
   ✓ Data sync dari server mungkin lambat
   ✓ Tapi countdown tetap berjalan dari local state
   ✓ Saat data tiba, update ditampilkan

4. Stop throttling
   Expected:
   ✓ Sync normal kembali
   ✓ Akurasi waktu lebih baik

5. Monitor Network tab untuk requests:
   Expected:
   ✓ `/anak/{id}/remaining-time` requests
   ✓ Frequency: ~1 per 10 detik
   ✓ Response time: <100ms normal, <500ms slow 3G
```

### Scenario 8: Edge Cases

**Test: Timer dengan sisa waktu sangat singkat**
```
1. Mulai timer dengan limit 5 detik
2. Jangan delay, langsung lihat countdown
3. Expected: 00:00:05 → 00:00:04 → ... → 00:00:00
4. Switch ke background sebelum habis
5. Expected: Notification tetap muncul saat habis
```

**Test: Browser close saat timer berjalan**
```
1. Mulai timer dengan limit 60 detik
2. Close browser sepenuhnya
3. Reopen browser dalam 10 detik
4. Navigasi ke halaman sama
5. Expected: Timer resume dari 50 detik (tidak dari 60)
   Note: Bisa berbeda ±1-2 detik tergantung browser/OS
```

**Test: System time change**
```
1. Mulai timer dengan limit 60 detik
2. Wait 5 detik (sisa ~55)
3. Switch ke tab lain
4. Change system time forward +30 detik
5. Kembali ke tab
6. Expected: Timer update ke ~25 detik (55-30)
   Note: Ini akan terlihat "jump" di countdown
```

## 📊 Expected Performance Metrics

| Metric | Expected | Notes |
|--------|----------|-------|
| Initial load | <2s | Page + Service Worker registration |
| Countdown update | 1/sec | Smooth visual |
| Server sync | 1/10sec | Every 10 seconds |
| Notification delay | <1s | After time limit reached |
| Network request | <100ms | On normal connection |
| Memory usage | <5MB | Including Service Worker |
| CPU usage | <1% | Idle when not counting |

## 🔍 Debugging Tips

### Check Service Worker Status
```javascript
// Di browser console:
navigator.serviceWorker.getRegistrations().then(regs => {
  console.log('Service Workers:', regs);
  regs.forEach(reg => {
    console.log('Controller:', navigator.serviceWorker.controller);
    console.log('State:', reg.installing, reg.waiting, reg.active);
  });
});
```

### Send Manual Message to Service Worker
```javascript
// Start manual timer
if (navigator.serviceWorker.controller) {
  navigator.serviceWorker.controller.postMessage({
    type: 'TIMER_START',
    payload: {
      anakId: 1,
      startTime: Date.now(),
      limitDetik: 10
    }
  });
}

// Stop manual timer
if (navigator.serviceWorker.controller) {
  navigator.serviceWorker.controller.postMessage({
    type: 'TIMER_STOP'
  });
}
```

### Check Notification Permission
```javascript
// Di browser console:
console.log('Notification permission:', Notification.permission);
// Possible values: 'default', 'granted', 'denied'
```

### View All Timers Data
```javascript
// Di browser console:
console.log('Active timer:', activeTimer);
console.log('Remaining seconds:', localRemainingSeconds);
console.log('Limit seconds:', limitDetik);
console.log('Service Worker:', navigator.serviceWorker.controller);
```

## ✅ Passing Criteria

Test dianggap **PASSED** jika:
- ✅ Timer berjalan di background (berkurang setiap detik)
- ✅ Akurasi ±1 detik saat kembali dari background
- ✅ Notification muncul saat timer habis (jika permission allowed)
- ✅ No console errors
- ✅ Service Worker successfully registered
- ✅ Stop/Reset bekerja dengan benar
- ✅ Multiple tabs sync dengan proper

## ❌ Known Limitations

- 🚫 Safari: Desktop notification terbatas
- 🚫 IE 11: Tidak support Service Worker sama sekali
- 🚫 Private/Incognito mode: Beberapa browser batasi Service Worker
- 🚫 Clear cache: Service Worker akan unregister dan perlu reload

---

**Last Updated:** Jan 23, 2026
**Status:** Ready for Production Testing
