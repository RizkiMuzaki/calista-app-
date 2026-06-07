<?php

namespace App\Jobs;

use App\Models\Anak;
use App\Services\ElevenLabsTtsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GenerateChildTtsPack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tentukan batas waktu eksekusi job (10 menit)
     */
    public $timeout = 600;

    public function __construct(
        protected Anak $anak,
        protected array $texts
    ) {
    }

    public function handle(ElevenLabsTtsService $tts)
    {
        $childId = $this->anak->id;
        $childName = $this->anak->nama_anak;
        $cacheKey = "audio_pack_status_{$childId}";

        try {
            Cache::put($cacheKey, [
                'status' => 'processing',
                'progress' => 0,
                'total' => count($this->texts),
                'processed' => 0,
                'updated_at' => now()->timestamp,
            ], now()->addHours(2));

            $folderPath = "audio_packs/child_{$childId}";
            Storage::disk('public')->makeDirectory($folderPath);

            $processed = 0;
            $total = count($this->texts);
            $lastWrittenProgress = -1;
            $consecutiveFailures = 0;

            foreach ($this->texts as $item) {
                $key = $item['key'] ?? null;
                $textTemplate = $item['text'] ?? null;

                if (!$key || !$textTemplate) {
                    continue;
                }

                $text = str_replace('[Nama]', $childName, $textTemplate);
                $fileName = "{$key}.mp3";
                $filePath = "{$folderPath}/{$fileName}";

                // Cek jika file sudah ada untuk menghemat kuota API ElevenLabs
                if (!Storage::disk('public')->exists($filePath)) {
                    try {
                        $result = $tts->synthesize($text, $this->anak->user, 'audio_pack_generation');
                        if ($result['success']) {
                            Storage::disk('public')->put($filePath, $result['audio']);
                            $consecutiveFailures = 0; // reset counter
                        } else {
                            Log::warning("Pre-generation failed for text: {$text}", [
                                'reason' => $result['reason'] ?? 'unknown'
                            ]);
                            $consecutiveFailures++;

                            if (($result['reason'] ?? '') === 'credit_exhausted') {
                                throw new \RuntimeException("Pembuatan audio pack dibatalkan: kuota ElevenLabs habis.");
                            }
                        }
                    } catch (\Throwable $e) {
                        $consecutiveFailures++;
                        Log::error("TTS synthesis exception during pack generation", [
                            'text' => $text,
                            'error' => $e->getMessage()
                        ]);

                        if ($e instanceof \RuntimeException) {
                            throw $e;
                        }
                    }
                }

                if ($consecutiveFailures >= 5) {
                    throw new \RuntimeException("Pembuatan audio pack dihentikan: 5 kegagalan beruntun terjadi pada API.");
                }

                $processed++;
                $newProgress = (int) round(($processed / $total) * 100);
                if ($newProgress !== $lastWrittenProgress || $processed === $total) {
                    $lastWrittenProgress = $newProgress;
                    Cache::put($cacheKey, [
                        'status' => 'processing',
                        'progress' => $newProgress,
                        'total' => $total,
                        'processed' => $processed,
                        'updated_at' => now()->timestamp,
                    ], now()->addHours(2));
                }
            }

            // Verifikasi jumlah file audio lengkap sebelum membuat ZIP
            $files = Storage::disk('public')->files($folderPath);
            if (count($files) < $total) {
                throw new \RuntimeException("Gagal membuat paket: hanya " . count($files) . " dari {$total} file audio yang berhasil dibuat.");
            }

            // Setelah selesai, kompresi folder menjadi ZIP
            $zipPath = "audio_packs/child_{$childId}.zip";
            $absoluteZipPath = Storage::disk('public')->path($zipPath);
            $absoluteFolderPath = Storage::disk('public')->path($folderPath);

            if (class_exists('ZipArchive')) {
                $zip = new ZipArchive();
                if ($zip->open($absoluteZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                    $files = Storage::disk('public')->files($folderPath);
                    foreach ($files as $file) {
                        $zip->addFile(Storage::disk('public')->path($file), basename($file));
                    }
                    $zip->close();
                } else {
                    throw new \RuntimeException("Gagal membuat file ZIP menggunakan ZipArchive untuk anak {$childId}");
                }
            } else {
                Log::info("ZipArchive tidak ditemukan. Menggunakan fallback kompresi CLI untuk anak {$childId}...");
                
                if (file_exists($absoluteZipPath)) {
                    unlink($absoluteZipPath);
                }

                // Coba tar (bawaan Windows 10/11)
                $escapedFolderDir = escapeshellarg($absoluteFolderPath);
                $escapedZip = escapeshellarg($absoluteZipPath);
                
                $cmd = "tar -a -c -f $escapedZip -C $escapedFolderDir .";
                $output = [];
                $returnVar = 0;
                exec($cmd, $output, $returnVar);

                if (!file_exists($absoluteZipPath) || filesize($absoluteZipPath) === 0) {
                    // Coba PowerShell Compress-Archive jika tar gagal
                    $escapedFolderWildcard = escapeshellarg($absoluteFolderPath . '/*');
                    $cmd = "powershell -Command \"Compress-Archive -Path $escapedFolderWildcard -DestinationPath $escapedZip -Force\"";
                    exec($cmd, $output, $returnVar);
                }

                if (!file_exists($absoluteZipPath) || filesize($absoluteZipPath) === 0) {
                    throw new \RuntimeException("Gagal membuat file ZIP menggunakan fallback (tar/PowerShell) untuk anak {$childId}");
                }
            }

            // Hapus folder temporary setelah dikompresi
            Storage::disk('public')->deleteDirectory($folderPath);

            // Update status sukses
            Cache::put($cacheKey, [
                'status' => 'completed',
                'progress' => 100,
                'url' => asset("storage/audio_packs/child_{$childId}.zip"),
                'updated_at' => now()->toIso8601String(),
            ], now()->addDays(7));

            Log::info("Audio pack ZIP created successfully for child {$childId}");

        } catch (\Throwable $e) {
            Log::error("Failed to generate child TTS pack", [
                'child_id' => $childId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Cache::put($cacheKey, [
                'status' => 'failed',
                'progress' => 0,
                'error' => $e->getMessage(),
            ], now()->addHours(2));
        }
    }
}
