<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $nama_anak
 * @property \Carbon\Carbon $tanggal_lahir
 * @property bool $is_active
 * @property int $limit_detik
 * @property int $sisa_detik
 * @property \Carbon\Carbon $tanggal_reset
 * @property \Carbon\Carbon|null $timer_started_at
 * @property \Carbon\Carbon|null $timer_last_updated
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Anak extends Model
{
    protected $table = 'anaks';

    protected $fillable = [
        'user_id',
        'nama_anak',
        'rename_count',
        'tanggal_lahir',
        'jenis_kelamin',
        'avatar_path',
        'background_path',
        'is_active',
        'limit_detik',
        'sisa_detik',
        'tanggal_reset',
        'timer_started_at',
        'timer_last_updated',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_reset' => 'date',
        'is_active' => 'boolean',
        'timer_started_at' => 'datetime',
        'timer_last_updated' => 'datetime',
        'rename_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progresAnaks()
    {
        return $this->hasMany(ProgresAnak::class);
    }

    public function playSessions()
    {
        return $this->hasMany(PlaySession::class);
    }

    /**
     * Baju yang dimiliki anak (wardrobe/lemari baju).
     */
    public function childItems()
    {
        return $this->hasMany(ChildItem::class, 'anak_id');
    }

    /**
     * Cek dan reset timer harian
     */
    public function checkAndResetDaily()
    {
        $today = Carbon::today();
        
        // Jika tanggal reset bukan hari ini, reset sisa detik
        if (!$this->tanggal_reset || $this->tanggal_reset->lt($today)) {
            $this->sisa_detik = $this->limit_detik;
            $this->tanggal_reset = $today;
            $this->timer_started_at = null;
            $this->timer_last_updated = null;
            $this->save();
            
            return true;
        }
        
        return false;
    }

    /**
     * Update timer yang sedang berjalan - PENTING: hitung elapsed time dari server
     * FIXED: Only deduct 1 second at a time, track fractional seconds
     */
    public function updateRunningTimer()
    {
        // Jika timer tidak berjalan, kembalikan sisa_detik saja
        if (!$this->timer_started_at) {
            return $this->sisa_detik;
        }

        $now = Carbon::now();
        
        // Hitung berapa lama sejak timer terakhir di-update dengan presisi
        $referenceTime = $this->timer_last_updated ?? $this->timer_started_at;
        $elapsedSeconds = $referenceTime->diffInRealSeconds($now, false); // Get float value
        
        // Only update if at least 1 full second has passed
        if ($elapsedSeconds >= 1.0) {
            $secondsToDeduct = floor($elapsedSeconds);
            
            // Kurangi sisa_detik dengan waktu yang telah berlalu
            $newRemaining = max(0, $this->sisa_detik - $secondsToDeduct);
            
            $this->sisa_detik = $newRemaining;
            $this->timer_last_updated = $now;
            if ($newRemaining <= 0) {
                $this->timer_started_at = null;
                $this->timer_last_updated = null;
            }
            $this->save();
        }
        
        return $this->sisa_detik;
    }

    /**
     * Mulai timer
     */
    public function startTimer()
    {
        if (!$this->timer_started_at) {
            $now = Carbon::now();
            $this->timer_started_at = $now;
            $this->timer_last_updated = $now;
            $this->save();
        }
        
        return $this;
    }

    /**
     * Hentikan timer
     */
    public function stopTimer()
    {
        // Update sisa waktu terlebih dahulu sebelum stop
        if ($this->timer_started_at) {
            $this->updateRunningTimer();
        }
        
        $this->timer_started_at = null;
        $this->timer_last_updated = null;
        $this->save();
        
        return $this;
    }

    /**
     * Reset timer ke limit awal
     */
    public function resetTimer()
    {
        $now = Carbon::now();
        $this->sisa_detik = $this->limit_detik;
        $this->tanggal_reset = $now->toDateString();
        $this->timer_started_at = null;
        $this->timer_last_updated = null;
        $this->save();
        
        return $this;
    }

    /**
     * Format sisa waktu untuk display
     */
    public function getFormattedRemainingTime()
    {
        $seconds = $this->sisa_detik;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Cek apakah timer masih tersedia
     */
    public function hasTimeRemaining()
    {
        return $this->sisa_detik > 0;
    }

    /**
     * Mengurangi waktu dengan jumlah detik tertentu
     */
    public function deductTime($seconds)
    {
        $this->sisa_detik = max(0, $this->sisa_detik - $seconds);
        $this->save();
        
        return $this->sisa_detik;
    }

    /**
     * Hitung sisa waktu berdasarkan waktu berjalan
     */
    public function calculateElapsedTime()
    {
        if (!$this->timer_started_at) {
            return 0;
        }

        $now = Carbon::now();
        $reference = $this->timer_last_updated ?? $this->timer_started_at;
        return $now->diffInSeconds($reference);
    }

    /**
     * Hitung progress untuk tipe tertentu (reading, counting, writing)
     */
    public function getProgressByType($type)
    {
        $slugMap = [
            'reading' => 'membaca',
            'writing' => 'menulis',
            'counting' => 'berhitung',
        ];
        $slug = $slugMap[$type] ?? $type;

        $module = Module::where('slug', $slug)->withCount('levels')->first();
        $total = $module?->levels_count ?? 0;

        $completed = $this->progresAnaks()
            ->whereHas('level.module', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('selesai', true)
            ->count();

        $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
        $avgScore = $this->progresAnaks()
            ->whereHas('level.module', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('selesai', true)
            ->avg('score') ?? 0;

        return [
            'type' => $type,
            'label' => ucfirst($type),
            'completed' => $completed,
            'total' => $total,
            'percentage' => round($percentage, 1),
            'avgScore' => round($avgScore, 1)
        ];
    }

    /**
     * Hitung semua progress (reading, counting, writing)
     */
    public function getAllProgress()
    {
        return [
            'reading' => $this->getProgressByType('reading'),
            'counting' => $this->getProgressByType('counting'),
            'writing' => $this->getProgressByType('writing'),
            'puzzle' => $this->getProgressByType('puzzle'),
        ];
    }

    /**
     * Hitung progress per module
     */
    public function getProgressByModule()
    {
        $modules = Module::all();
        $moduleProgress = [];

        foreach ($modules as $module) {
            $total = $module->levels()->count();
            
            $completed = $this->progresAnaks()
                ->whereHas('level', function ($q) use ($module) {
                    $q->where('module_id', $module->id);
                })
                ->where('selesai', true)
                ->count();

            $moduleProgress[] = [
                'module' => $module->name,
                'completed' => $completed,
                'total' => $total,
                'percentage' => $total > 0 ? round(($completed / $total) * 100, 1) : 0
            ];
        }

        return $moduleProgress;
    }

    /**
     * Ambil recent progress (10 terbaru)
     */
    public function getRecentProgress()
    {
        return $this->progresAnaks()
            ->with('level.module')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
    }
}
