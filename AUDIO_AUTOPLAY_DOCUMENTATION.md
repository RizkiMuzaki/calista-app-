# Fitur Autoplay Audio Hadiah - Dokumentasi

## Deskripsi
Fitur ini memungkinkan audio dari hadiah yang menang untuk diputar secara otomatis ketika pemain menyelesaikan permainan glass game.

## File yang Dimodifikasi

### 1. Controller: `app/Http/Controllers/GameController.php`
**Perubahan:**
- Menambahkan field `audio` ke dalam array hadiah yang dikirim ke view
- Menambahkan field `audio` ke dalam response JSON endpoint `/game/{id}/save-selected-prizes`
- Menambahkan field `audio` ke dalam response JSON endpoint `/game/{id}/get-prizes`

**Endpoint yang Diupdate:**
- `saveSelectedPrizes()` - Sekarang mengirim data audio untuk hadiah yang ditampilkan di gelas
- `getSelectedPrizes()` - Sekarang mengirim data audio untuk hadiah yang dimenangkan
- `showGlassGame()` - Sekarang mengirim data audio untuk semua hadiah yang tersedia

### 2. View: `resources/views/pages/gamegelas.blade.php`
**Perubahan HTML:**
- Menambahkan attribute `data-prize-audio` ke element `.prize-card`
  ```html
  data-prize-audio="{{ $prize['audio'] ? asset('storage/' . $prize['audio']) : '' }}"
  ```

**Perubahan JavaScript:**

#### Deklarasi Audio Variable
```javascript
let prizeAudio = null; // Variabel global untuk menyimpan instance audio hadiah
```

#### Update Prize Selection
- Method `initializePrizeSelection()` sekarang capture audio dari `data-prize-audio`
- Audio disimpan dalam object `selectedPrizeData` dengan key `audio`

#### Helper Function Audio Path
```javascript
function getAudioPath(audioPath) {
    if (!audioPath) return '';
    
    if (audioPath.startsWith('http')) return audioPath;
    if (audioPath.includes('/storage/')) return audioPath;
    
    return '/storage/' + (audioPath.startsWith('/') ? audioPath.substring(1) : audioPath);
}
```

#### Autoplay Audio di Modal Hasil
- Setelah hadiah ditampilkan di modal, audio diputar otomatis dengan delay 500ms
- Volume diatur ke 0.8
- Audio sebelumnya akan dihentikan jika ada
- Jika browser memblokir autoplay, error akan log di console tanpa mengganggu UX

```javascript
// Play prize audio for first prize if available
if (data.prizes[0].audio) {
    const audioPath = getAudioPath(data.prizes[0].audio);
    if (audioPath) {
        // Stop any previously playing audio
        if (prizeAudio) {
            prizeAudio.pause();
            prizeAudio.currentTime = 0;
        }
        
        // Create and play new audio with delay
        setTimeout(() => {
            prizeAudio = new Audio(audioPath);
            prizeAudio.volume = 0.8;
            prizeAudio.play().catch(err => console.log('Audio autoplay blocked:', err));
        }, 500);
    }
}
```

#### Pause Audio saat Modal Ditutup
```javascript
// Stop audio when modal closes
if (prizeAudio) {
    prizeAudio.pause();
    prizeAudio.currentTime = 0;
}
```

## Model: `app/Models/Hadiah.php`
**Status:** Sudah memiliki field `audio` di `$fillable` array
```php
protected $fillable = [
    'game_id',
    'nama_hadiah',
    'jenis_hadiah',
    'foto',
    'audio',  // ← Sudah ada
];
```

## Fitur dalam Filament Resource
Jika menggunakan Filament untuk management hadiah, pastikan ada form field untuk audio:

```php
FileUpload::make('audio')
    ->label('Audio')
    ->disk('public')
    ->directory('hadiahs/audio')
    ->acceptedFileTypes(['audio/*'])
    ->maxSize(10240)
    ->required()
```

## Flow Penggunaan

1. **Admin Upload Hadiah**
   - Menggunakan Filament untuk upload hadiah dengan foto dan audio
   - Audio disimpan di `/storage/hadiahs/audio/`

2. **User Memilih Hadiah**
   - User memilih minimal 2 hadiah dari list
   - Data audio dari hadiah tersimpan di memory (selectedPrizeData)

3. **User Bermain Game**
   - Lihat hadiah dimasukkan ke gelas (animation)
   - Acak gelas
   - Pilih gelas untuk menang

4. **Hasil - Autoplay Audio**
   - Modal hasil ditampilkan dengan hadiah yang dimenangkan
   - Audio dari hadiah tersebut diputar otomatis
   - Audio berhenti saat modal ditutup atau user keluar

## Konfigurasi Audio

### Volume
Default: 0.8 (80%)
Ubah di: `prizeAudio.volume = 0.8;`

### Delay Autoplay
Default: 500ms (setengah detik setelah modal muncul)
Ubah di: `setTimeout(() => { ... }, 500);`

### Format Audio yang Didukung
- MP3 (.mp3)
- WAV (.wav)
- OGG (.ogg)
- M4A (.m4a)

### Ukuran File Max
- Di Filament: 10240 KB (10 MB)
- Rekomendasi: 1-3 MB untuk performance optimal

## Error Handling

- **Audio tidak ditemukan:** Tidak akan error, fungsi akan skip jika path kosong
- **Autoplay diblokir browser:** Error akan log di console, UX tetap lancar
- **Format audio tidak didukung:** Berbeda per browser, konsol akan tampilkan error

## Browser Compatibility

| Browser | Support | Autoplay |
|---------|---------|----------|
| Chrome  | ✅      | Perlu user interaction |
| Firefox | ✅      | Perlu user interaction |
| Safari  | ✅      | Perlu user interaction |
| Edge    | ✅      | Perlu user interaction |

**Note:** Autoplay audio umumnya diblokir browser jika user belum melakukan interaksi. Dalam konteks game ini, klik tombol bermain dianggap sebagai user interaction yang cukup.

## Testing

1. Upload hadiah dengan audio melalui Filament
2. Buka game gelas
3. Pilih hadiah dengan audio
4. Main game dan menang
5. Verifikasi audio diputar di modal hasil
6. Cek browser console untuk error (jika ada)

## Troubleshooting

### Audio tidak diputar
- Pastikan file audio sudah ter-upload di `/storage/hadiahs/audio/`
- Cek permissions folder storage
- Buka browser console untuk error messages
- Pastikan browser tidak memblokir autoplay

### Audio lag atau tidak jelas
- Kompres audio file hingga 1-2 MB
- Gunakan format MP3 untuk compatibility maksimal
- Cek bitrate audio (128-192 kbps cukup)

### Path audio tidak benar
- Verifikasi path di database dengan `php artisan tinker`
- Pastikan URL path dimulai dengan `/storage/`
- Gunakan `asset()` helper untuk generate URL yang benar
