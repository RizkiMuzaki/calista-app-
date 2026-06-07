<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * 🎓 LEARNING: ParentalGateController
 * 
 * Controller untuk Parental Gate — Double Layer Security.
 * 
 * Layer 1: Challenge "Tulis Angka dari Kata" 
 *   → Filter anak kecil (5-7 tahun belum bisa konversi kata→angka)
 *   → Angka random setiap request (anti-hafal)
 *   → Orang dewasa selesai dalam 3 detik
 * 
 * Layer 2: Password Orang Tua
 *   → Sudah ada di ChildController::verifyPin()
 *   → Validasi password akun CALISTA via Hash::check
 * 
 * Kapan Layer 1 muncul:
 *   - Klik "Buka Toko Misterius" (belum subscribe)
 *   - Klik "Berlangganan" dari popup
 * 
 * Kapan TIDAK perlu Layer 1:
 *   - Beli baju premium (sudah subscribe) → Layer 2 saja
 *   - Unlock timer → Layer 2 saja (verify-pin)
 */
class ParentalGateController extends Controller
{
    /**
     * 🎓 LEARNING: Daftar angka dalam bahasa Indonesia
     * 
     * Penting: Kita generate angka 100-999 saja.
     * - Terlalu kecil (1-99): Anak usia 8+ sudah bisa
     * - Terlalu besar (1000+): Bikin orang tua ribet
     * - Sweet spot: 100-999 (tiga digit, cukup sulit untuk anak)
     */
    private array $satuan = [
        0 => '', 1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat',
        5 => 'lima', 6 => 'enam', 7 => 'tujuh', 8 => 'delapan', 9 => 'sembilan',
    ];

    private array $belasan = [
        10 => 'sepuluh', 11 => 'sebelas', 12 => 'dua belas', 13 => 'tiga belas',
        14 => 'empat belas', 15 => 'lima belas', 16 => 'enam belas', 
        17 => 'tujuh belas', 18 => 'delapan belas', 19 => 'sembilan belas',
    ];

    // ========== API ENDPOINTS ==========

    public function pinStatus(Request $request)
    {
        $hasParentPin = !empty($request->user()->parent_pin);

        return response()->json([
            'status' => 'success',
            'has_parent_pin' => $hasParentPin,
            'needs_pin_setup' => !$hasParentPin,
        ]);
    }

    public function setupPin(Request $request)
    {
        $validated = $request->validate([
            'pin' => ['required', 'digits_between:4,6'],
            'pin_confirmation' => ['required', 'same:pin'],
        ]);

        $user = $request->user();
        if (!empty($user->parent_pin)) {
            return response()->json([
                'status' => 'error',
                'message' => 'PIN Orang Tua sudah dibuat.',
                'has_parent_pin' => true,
                'needs_pin_setup' => false,
            ], 409);
        }

        $user->parent_pin = Hash::make($validated['pin']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'PIN Orang Tua berhasil dibuat.',
            'has_parent_pin' => true,
            'needs_pin_setup' => false,
        ]);
    }

    /**
     * Generate challenge baru.
     * 
     * POST /api/parental-gate/challenge
     * 
     * Response: {
     *   "status": "success",
     *   "data": {
     *     "challenge_text": "tiga ratus empat puluh tujuh",
     *     "challenge_token": "encrypted_token"
     *   }
     * }
     * 
     * 🎓 LEARNING: Kenapa pakai token?
     * Supaya jawaban tidak bisa di-guess/brute-force. 
     * Token berisi angka yang di-encrypt, dan hanya valid 5 menit.
     */
    public function generateChallenge(Request $request)
    {
        try {
            // Generate angka random 100-999
            $number = rand(100, 999);
            
            // Konversi ke kata Indonesia
            $text = $this->numberToWords($number);
            
            // Encrypt angka + timestamp sebagai token
            // 🎓 LEARNING: Token berisi angka + waktu, di-encrypt supaya aman
            $tokenData = json_encode([
                'number' => $number,
                'expires_at' => now()->addMinutes(5)->timestamp,
            ]);
            $token = encrypt($tokenData);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'challenge_text' => $text,
                    'challenge_token' => $token,
                    'instruction' => 'Tulis angka dari kata di atas',
                    'expires_in_seconds' => 300, // 5 menit
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Parental Gate: Gagal generate challenge', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat challenge',
            ], 500);
        }
    }

    /**
     * Verifikasi jawaban challenge Layer 1.
     * 
     * POST /api/parental-gate/verify
     * 
     * Request: {
     *   "answer": 347,
     *   "challenge_token": "encrypted_token"
     * }
     * 
     * Response (benar): {
     *   "status": "success",
     *   "verified": true,
     *   "gate_token": "token_untuk_layer2"
     * }
     * 
     * Response (salah): {
     *   "status": "error",
     *   "verified": false,
     *   "message": "Jawaban salah. Coba lagi ya!"
     * }
     */
    public function verifyChallenge(Request $request)
    {
        try {
            $validated = $request->validate([
                'answer' => 'required|integer',
                'challenge_token' => 'required|string',
            ]);

            // Decrypt token
            $tokenData = json_decode(decrypt($validated['challenge_token']), true);

            // Cek apakah token expired
            if (now()->timestamp > $tokenData['expires_at']) {
                return response()->json([
                    'status' => 'error',
                    'verified' => false,
                    'message' => 'Challenge sudah expired. Silakan minta challenge baru.',
                    'expired' => true,
                ], 400);
            }

            // Bandingkan jawaban
            $correctNumber = $tokenData['number'];

            if ((int) $validated['answer'] === $correctNumber) {
                // 🎓 LEARNING: Generate gate_token yang valid 10 menit
                // Token ini dipakai Flutter untuk proceed ke Layer 2
                $gateToken = encrypt(json_encode([
                    'layer1_passed' => true,
                    'user_id' => $request->user()->id,
                    'expires_at' => now()->addMinutes(10)->timestamp,
                ]));

                Log::info('Parental Gate Layer 1: PASSED', [
                    'user_id' => $request->user()->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'verified' => true,
                    'message' => 'Benar! Silakan lanjut ke verifikasi orang tua.',
                    'gate_token' => $gateToken,
                    'gate_expires_in_seconds' => 600, // 10 menit
                ], 200);
            }

            // Jawaban salah
            Log::warning('Parental Gate Layer 1: FAILED', [
                'user_id' => $request->user()->id,
                'expected' => $correctNumber,
                'got' => $validated['answer'],
            ]);

            return response()->json([
                'status' => 'error',
                'verified' => false,
                'message' => 'Jawaban salah. Coba lagi ya!',
            ], 200); // 200 bukan 401 — biar Flutter handle UI-nya

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json([
                'status' => 'error',
                'verified' => false,
                'message' => 'Token tidak valid. Silakan minta challenge baru.',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Parental Gate: Gagal verifikasi', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memverifikasi jawaban',
            ], 500);
        }
    }

    /**
     * Verifikasi password orang tua (Layer 2).
     * 
     * POST /api/parental-gate/verify-password
     * 
     * Request: {
     *   "password": "password_orang_tua",
     *   "gate_token": "token_dari_layer1" (opsional, wajib jika butuh Layer 1)
     * }
     * 
     * 🎓 LEARNING: Layer 2 bisa dipanggil TANPA gate_token jika:
     *   - User sudah subscriber (beli baju premium → Layer 2 saja)
     *   - Unlock timer (verify-pin yang sudah ada)
     * 
     * gate_token WAJIB jika:
     *   - Pertama kali subscribe (Layer 1 + Layer 2)
     */
    public function verifyPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'password' => 'nullable|string',
                'pin' => 'nullable|string',
                'gate_token' => 'nullable|string',
                'require_layer1' => 'nullable|boolean',
            ]);

            $user = $request->user();
            if (empty($user->parent_pin)) {
                return response()->json([
                    'status' => 'error',
                    'verified' => false,
                    'message' => 'Buat PIN Orang Tua terlebih dahulu.',
                    'needs_pin_setup' => true,
                ], 409);
            }

            // Jika require_layer1 = true, cek gate_token
            if ($request->input('require_layer1', false)) {
                if (empty($validated['gate_token'])) {
                    return response()->json([
                        'status' => 'error',
                        'verified' => false,
                        'message' => 'Harus melewati challenge Layer 1 terlebih dahulu.',
                    ], 400);
                }

                // Verify gate_token
                try {
                    $gateData = json_decode(decrypt($validated['gate_token']), true);
                    
                    if (!$gateData['layer1_passed'] || $gateData['user_id'] !== $user->id) {
                        return response()->json([
                            'status' => 'error',
                            'verified' => false,
                            'message' => 'Token Layer 1 tidak valid.',
                        ], 400);
                    }

                    if (now()->timestamp > $gateData['expires_at']) {
                        return response()->json([
                            'status' => 'error',
                            'verified' => false,
                            'message' => 'Token Layer 1 sudah expired. Ulangi dari awal.',
                            'expired' => true,
                        ], 400);
                    }
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    return response()->json([
                        'status' => 'error',
                        'verified' => false,
                        'message' => 'Token Layer 1 corrupt. Ulangi dari awal.',
                    ], 400);
                }
            }

            $parentVerified = false;
            if (!empty($validated['pin'])) {
                $parentVerified = Hash::check($validated['pin'], $user->parent_pin);
            }

            if (!$parentVerified && !empty($validated['password'])) {
                $parentVerified = Hash::check($validated['password'], $user->password);
            }

            if ($parentVerified) {
                Log::info('Parental Gate Layer 2: PASSED', [
                    'user_id' => $user->id,
                ]);

                // Generate final access token (valid 30 menit)
                $accessToken = encrypt(json_encode([
                    'full_access' => true,
                    'user_id' => $user->id,
                    'expires_at' => now()->addMinutes(30)->timestamp,
                ]));

                return response()->json([
                    'status' => 'success',
                    'verified' => true,
                    'message' => 'Verifikasi berhasil! Akses premium terbuka.',
                    'access_token' => $accessToken,
                    'access_expires_in_seconds' => 1800, // 30 menit
                ], 200);
            }

            // Password salah
            Log::warning('Parental Gate Layer 2: WRONG PASSWORD', [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'status' => 'error',
                'verified' => false,
                'message' => 'PIN Orang Tua salah.',
                'needs_pin_setup' => false,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Parental Gate Layer 2 error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memverifikasi password',
            ], 500);
        }
    }

    // ========== HELPER METHODS ==========

    /**
     * 🎓 LEARNING: Konversi angka ke kata bahasa Indonesia
     * 
     * Contoh:
     *   347 → "tiga ratus empat puluh tujuh"
     *   511 → "lima ratus sebelas"
     *   100 → "seratus"
     *   205 → "dua ratus lima"
     */
    private function numberToWords(int $number): string
    {
        if ($number < 0 || $number > 999) {
            return (string) $number;
        }

        if ($number === 0) {
            return 'nol';
        }

        $result = '';

        // Ratusan
        $ratusan = intdiv($number, 100);
        if ($ratusan > 0) {
            if ($ratusan === 1) {
                $result .= 'seratus';
            } else {
                $result .= $this->satuan[$ratusan] . ' ratus';
            }
        }

        // Sisa setelah ratusan
        $sisa = $number % 100;

        if ($sisa > 0) {
            if ($ratusan > 0) {
                $result .= ' ';
            }

            if ($sisa >= 10 && $sisa <= 19) {
                // Belasan (10-19)
                $result .= $this->belasan[$sisa];
            } else {
                // Puluhan (20-99) atau satuan (1-9)
                $puluhan = intdiv($sisa, 10);
                $satuanSisa = $sisa % 10;

                if ($puluhan > 0) {
                    $result .= $this->satuan[$puluhan] . ' puluh';
                    if ($satuanSisa > 0) {
                        $result .= ' ' . $this->satuan[$satuanSisa];
                    }
                } else {
                    $result .= $this->satuan[$satuanSisa];
                }
            }
        }

        return $result;
    }
}
