# Kode Perubahan - Referensi Teknis

## 1. GameController.php - saveSelectedPrizes()

### BEFORE:
```php
$displayedPrizes[] = [
    'id' => $prize->id,
    'nama_hadiah' => $prize->nama_hadiah,
    'jenis_hadiah' => $prize->jenis_hadiah,
    'foto' => $prize->foto ? asset('storage/' . $prize->foto) : null,
];
```

### AFTER:
```php
$displayedPrizes[] = [
    'id' => $prize->id,
    'nama_hadiah' => $prize->nama_hadiah,
    'jenis_hadiah' => $prize->jenis_hadiah,
    'foto' => $prize->foto ? asset('storage/' . $prize->foto) : null,
    'audio' => $prize->audio ? asset('storage/' . $prize->audio) : null,  // ← NEW
];
```

---

## 2. GameController.php - getSelectedPrizes()

### BEFORE:
```php
return response()->json([
    'success' => true,
    'prizes' => [
        [
            'id' => $randomPrize->id,
            'nama_hadiah' => $randomPrize->nama_hadiah,
            'jenis_hadiah' => $randomPrize->jenis_hadiah,
            'foto' => $randomPrize->foto ? asset('storage/' . $randomPrize->foto) : null,
        ]
    ],
    'substituted_prizes' => [],
    'message' => 'Selamat! Anda mendapatkan hadiah'
]);
```

### AFTER:
```php
return response()->json([
    'success' => true,
    'prizes' => [
        [
            'id' => $randomPrize->id,
            'nama_hadiah' => $randomPrize->nama_hadiah,
            'jenis_hadiah' => $randomPrize->jenis_hadiah,
            'foto' => $randomPrize->foto ? asset('storage/' . $randomPrize->foto) : null,
            'audio' => $randomPrize->audio ? asset('storage/' . $randomPrize->audio) : null,  // ← NEW
        ]
    ],
    'substituted_prizes' => [],
    'message' => 'Selamat! Anda mendapatkan hadiah'
]);
```

---

## 3. GameController.php - showGlassGame()

### BEFORE:
```php
'hadiahs' => $availablePrizes->map(function ($hadiah) {
    return [
        'id' => $hadiah->id,
        'game_id' => $hadiah->game_id,
        'nama_hadiah' => $hadiah->nama_hadiah,
        'jenis_hadiah' => $hadiah->jenis_hadiah,
        'foto' => $hadiah->foto,
        'stok' => $hadiah->stok ?? 1,
    ];
})->toArray(),
```

### AFTER:
```php
'hadiahs' => $availablePrizes->map(function ($hadiah) {
    return [
        'id' => $hadiah->id,
        'game_id' => $hadiah->game_id,
        'nama_hadiah' => $hadiah->nama_hadiah,
        'jenis_hadiah' => $hadiah->jenis_hadiah,
        'foto' => $hadiah->foto,
        'audio' => $hadiah->audio,  // ← NEW
        'stok' => $hadiah->stok ?? 1,
    ];
})->toArray(),
```

---

## 4. gamegelas.blade.php - Audio Variable Declaration

### ADDED:
```javascript
// Prize audio element
let prizeAudio = null;
```

---

## 5. gamegelas.blade.php - HTML Prize Card

### BEFORE:
```blade
<div class="prize-card" 
     data-prize-id="{{ $prize['id'] }}" 
     data-prize-name="{{ $prize['nama_hadiah'] }}"
     data-prize-type="{{ $prize['jenis_hadiah'] }}"
     data-prize-photo="{{ $prize['foto'] ? asset('storage/' . $prize['foto']) : '' }}">
```

### AFTER:
```blade
<div class="prize-card" 
     data-prize-id="{{ $prize['id'] }}" 
     data-prize-name="{{ $prize['nama_hadiah'] }}"
     data-prize-type="{{ $prize['jenis_hadiah'] }}"
     data-prize-photo="{{ $prize['foto'] ? asset('storage/' . $prize['foto']) : '' }}"
     data-prize-audio="{{ $prize['audio'] ? asset('storage/' . $prize['audio']) : '' }}">  <!-- ← NEW -->
```

---

## 6. gamegelas.blade.php - initializePrizeSelection()

### BEFORE:
```javascript
selectedPrizeData.push({
    id: prizeId,
    name: this.dataset.prizeName,
    type: this.dataset.prizeType,
    photo: this.dataset.prizePhoto
});
```

### AFTER:
```javascript
selectedPrizeData.push({
    id: prizeId,
    name: this.dataset.prizeName,
    type: this.dataset.prizeType,
    photo: this.dataset.prizePhoto,
    audio: this.dataset.prizeAudio  // ← NEW
});
```

---

## 7. gamegelas.blade.php - getAudioPath() Helper

### ADDED:
```javascript
// Helper function untuk normalize audio path
function getAudioPath(audioPath) {
    if (!audioPath) return '';
    
    if (audioPath.startsWith('http')) return audioPath;
    if (audioPath.includes('/storage/')) return audioPath;
    
    return '/storage/' + (audioPath.startsWith('/') ? audioPath.substring(1) : audioPath);
}
```

---

## 8. gamegelas.blade.php - showResults() Audio Handling

### ADDED (dalam fungsi showResults):
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
        
        // Create and play new audio with delay untuk memastikan modal sudah terbuka
        setTimeout(() => {
            prizeAudio = new Audio(audioPath);
            prizeAudio.volume = 0.8;
            prizeAudio.play().catch(err => console.log('Audio autoplay blocked:', err));
        }, 500);
    }
}
```

---

## 9. gamegelas.blade.php - Modal Close Handler

### BEFORE:
```javascript
// Reset game after modal closes
document.getElementById('resultsModal').addEventListener('hidden.bs.modal', function() {
    resetGame();
    prizeSelectionSection.style.display = 'block';
    gameSection.classList.remove('active');
});
```

### AFTER:
```javascript
// Reset game after modal closes
document.getElementById('resultsModal').addEventListener('hidden.bs.modal', function() {
    // Stop audio when modal closes
    if (prizeAudio) {
        prizeAudio.pause();
        prizeAudio.currentTime = 0;
    }
    
    resetGame();
    prizeSelectionSection.style.display = 'block';
    gameSection.classList.remove('active');
});
```

---

## Ringkasan Perubahan

| File | Tipe | Detail |
|------|------|--------|
| GameController.php | Backend | +4 baris (3 endpoint) |
| gamegelas.blade.php | Frontend | +30 baris (HTML + JS) |
| Hadiah.php | Model | Tidak ada perubahan (audio sudah ada) |

## Total Modifikasi
- **2 file PHP diubah** (controller + view)
- **~35 baris kode ditambahkan**
- **0 breaking changes**
- **Backward compatible** (jika hadiah tanpa audio, feature tidak error)
