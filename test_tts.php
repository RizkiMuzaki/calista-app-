<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$key = env('ELEVENLABS_API_KEY');
$voiceId = env('ELEVENLABS_NUSA_VOICE_ID');

echo "=== ElevenLabs TTS Test ===" . PHP_EOL;
echo "API Key: " . (empty($key) ? 'MISSING' : substr($key, 0, 8) . '...') . PHP_EOL;
echo "Voice ID: " . ($voiceId ?: 'MISSING') . PHP_EOL;

if (!$key || !$voiceId) {
    echo "ERROR: API Key atau Voice ID kosong di .env!" . PHP_EOL;
    exit(1);
}

$url = "https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}/stream?output_format=mp3_44100_128&optimize_streaming_latency=3";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_HTTPHEADER => [
        'xi-api-key: ' . $key,
        'Accept: audio/mpeg',
        'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'text' => 'Halo, aku Nusa! Aku senang belajar bersamamu.',
        'model_id' => 'eleven_flash_v2_5',
        'language_code' => 'id',
        'voice_settings' => [
            'stability' => 0.45,
            'similarity_boost' => 0.90,
            'style' => 0.15,
            'speed' => 0.97,
            'use_speaker_boost' => true,
        ],
    ]),
]);

$body = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$curlError = curl_error($ch);
unset($ch); // curl_close() deprecated in PHP 8.5+

echo "HTTP Status: {$httpCode}" . PHP_EOL;
echo "Content-Type: {$contentType}" . PHP_EOL;
echo "Body length: " . strlen($body) . " bytes" . PHP_EOL;

if ($curlError) {
    echo "CURL Error: {$curlError}" . PHP_EOL;
}

if ($httpCode === 200 && str_contains($contentType, 'audio')) {
    echo "✅ SUCCESS! ElevenLabs TTS berfungsi." . PHP_EOL;
    file_put_contents('/tmp/nusa_test.mp3', $body);
    echo "Audio saved to /tmp/nusa_test.mp3" . PHP_EOL;
} else {
    echo "❌ FAILED!" . PHP_EOL;
    echo "Response body (first 500 chars): " . substr($body, 0, 500) . PHP_EOL;
}

// Test Groq STT availability
echo PHP_EOL . "=== Groq API Test ===" . PHP_EOL;
$groqKey = env('GROQ_API_KEY');
echo "Groq API Key: " . (empty($groqKey) ? 'MISSING' : substr($groqKey, 0, 8) . '...') . PHP_EOL;

$ch2 = curl_init('https://api.groq.com/openai/v1/models');
curl_setopt_array($ch2, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $groqKey],
]);
$groqBody = curl_exec($ch2);
$groqCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
unset($ch2); // curl_close() deprecated in PHP 8.5+

echo "Groq Status: {$groqCode}" . PHP_EOL;
if ($groqCode === 200) {
    echo "✅ Groq API berfungsi." . PHP_EOL;
} else {
    echo "❌ Groq API gagal: " . substr($groqBody, 0, 200) . PHP_EOL;
}
