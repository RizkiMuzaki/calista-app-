<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElevenLabsTtsService;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GenerateSharedAudioPack extends Command
{
    protected $signature = 'audio:generate-shared-pack {--force : Force regenerate existing audios}';
    protected $description = 'Generate the shared static audio pack and pre/post segments from game_texts.json';

    public function handle(ElevenLabsTtsService $tts)
    {
        $this->info("Starting shared audio pack generation...");

        $jsonPath = storage_path('app/game_texts.json');
        if (!file_exists($jsonPath)) {
            $this->error("game_texts.json not found in storage/app/.");
            return 1;
        }

        $texts = json_decode(file_get_contents($jsonPath), true);
        if (!$texts) {
            $this->error("Invalid or empty game_texts.json.");
            return 1;
        }

        $force = $this->option('force');
        $sharedFolder = "audio_packs/shared";
        Storage::disk('public')->makeDirectory($sharedFolder);

        $manifest = [];
        $total = count($texts);
        $processed = 0;
        $consecutiveFailures = 0;

        foreach ($texts as $item) {
            $key = $item['key'] ?? null;
            $text = $item['text'] ?? null;

            if (!$key || !$text) {
                continue;
            }

            $processed++;
            $this->info("Processing [{$processed}/{$total}]: {$key}");

            if (strpos($text, '[Nama]') !== false) {
                // Stitch type
                $parts = explode('[Nama]', $text, 2);
                $preText = trim($parts[0] ?? '');
                $postText = trim($parts[1] ?? '');

                // Filter out punctuation and symbols for TTS normalization
                $preTextClean = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', '', $preText);
                $postTextClean = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', '', $postText);

                $preKey = "{$key}_pre";
                $postKey = "{$key}_post";

                $preFile = "{$sharedFolder}/{$preKey}.mp3";
                $postFile = "{$sharedFolder}/{$postKey}.mp3";

                $manifest[$key] = [
                    'type' => 'stitch',
                    'pre' => trim($preTextClean) !== '' ? "{$preKey}.mp3" : null,
                    'post' => trim($postTextClean) !== '' ? "{$postKey}.mp3" : null,
                ];

                // Process pre
                if (trim($preTextClean) !== '') {
                    if ($force || !Storage::disk('public')->exists($preFile)) {
                        try {
                            $result = $tts->synthesize($preText, null, 'audio_pack_generation');
                            if ($result['success']) {
                                Storage::disk('public')->put($preFile, $result['audio']);
                                $consecutiveFailures = 0;
                            } else {
                                $this->warn("Failed pre-tts for {$key}: " . ($result['reason'] ?? 'unknown'));
                                $consecutiveFailures++;
                            }
                        } catch (\Throwable $e) {
                            $consecutiveFailures++;
                            $this->error("Exception pre-tts for {$key}: " . $e->getMessage());
                        }
                    }
                }

                // Process post
                if (trim($postTextClean) !== '') {
                    if ($force || !Storage::disk('public')->exists($postFile)) {
                        try {
                            $result = $tts->synthesize($postText, null, 'audio_pack_generation');
                            if ($result['success']) {
                                Storage::disk('public')->put($postFile, $result['audio']);
                                $consecutiveFailures = 0;
                            } else {
                                $this->warn("Failed post-tts for {$key}: " . ($result['reason'] ?? 'unknown'));
                                $consecutiveFailures++;
                            }
                        } catch (\Throwable $e) {
                            $consecutiveFailures++;
                            $this->error("Exception post-tts for {$key}: " . $e->getMessage());
                        }
                    }
                }
            } else {
                // Static type
                $staticFile = "{$sharedFolder}/{$key}.mp3";
                $manifest[$key] = [
                    'type' => 'static',
                    'file' => "{$key}.mp3",
                ];

                if ($force || !Storage::disk('public')->exists($staticFile)) {
                    try {
                        $result = $tts->synthesize($text, null, 'audio_pack_generation');
                        if ($result['success']) {
                            Storage::disk('public')->put($staticFile, $result['audio']);
                            $consecutiveFailures = 0;
                        } else {
                            $this->warn("Failed static tts for {$key}: " . ($result['reason'] ?? 'unknown'));
                            $consecutiveFailures++;
                        }
                    } catch (\Throwable $e) {
                        $consecutiveFailures++;
                        $this->error("Exception static tts for {$key}: " . $e->getMessage());
                    }
                }
            }

            if ($consecutiveFailures >= 5) {
                $this->error("Exiting: 5 consecutive failures.");
                return 1;
            }
        }

        // Save manifest to shared directory so it's included in ZIP
        $manifestJson = json_encode([
            'version' => '1.0.0',
            'updated_at' => now()->toIso8601String(),
            'files' => $manifest
        ], JSON_PRETTY_PRINT);

        Storage::disk('public')->put("{$sharedFolder}/manifest.json", $manifestJson);
        $this->info("Manifest generated.");

        // Zip the folder
        $zipPath = "audio_packs/shared_pack.zip";
        $absoluteZipPath = Storage::disk('public')->path($zipPath);
        $absoluteFolderPath = Storage::disk('public')->path($sharedFolder);

        // Ensure directories exist
        Storage::disk('public')->makeDirectory("audio_packs");

        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($absoluteZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $files = Storage::disk('public')->files($sharedFolder);
                foreach ($files as $file) {
                    $zip->addFile(Storage::disk('public')->path($file), basename($file));
                }
                $zip->close();
                $this->info("ZIP archive created successfully at {$absoluteZipPath}");
            } else {
                $this->error("Failed to open ZipArchive.");
                return 1;
            }
        } else {
            $this->warn("ZipArchive class not found. Trying CLI fallback.");
            $escapedFolderDir = escapeshellarg($absoluteFolderPath);
            $escapedZip = escapeshellarg($absoluteZipPath);
            $cmd = "tar -a -c -f $escapedZip -C $escapedFolderDir .";
            exec($cmd);
        }

        // Save metadata/manifest publicly for version checking
        Storage::disk('public')->put("audio_packs/shared_pack_manifest.json", json_encode([
            'version' => '1.0.0',
            'hash' => file_exists($absoluteZipPath) ? hash_file('sha256', $absoluteZipPath) : '',
            'url' => asset("storage/audio_packs/shared_pack.zip"),
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT));

        $this->info("Shared audio pack creation completed successfully!");
        return 0;
    }
}
