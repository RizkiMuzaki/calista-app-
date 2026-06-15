<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\EmailService;

class AuthController extends Controller
{
    /**
     * Register API
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
            ], [
                'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'name.required' => 'Nama wajib diisi.',
                'password.required' => 'Kata sandi wajib diisi.',
                'password.min' => 'Kata sandi minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            // Generate 6-digit OTP
            $otp = mt_rand(100000, 999999);
            Cache::put('email_otp_' . $user->email, $otp, 600); // 10 minutes

            // Send OTP email
            EmailService::sendOtpEmail($user->email, $otp);

            return response()->json([
                'status' => 'success',
                'message' => 'Akun berhasil dibuat. Silakan cek email Anda untuk verifikasi.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'is_verified' => false
                ]
            ], 201);

        } catch (ValidationException $e) {
            Log::warning('Registration validation failed', ['email' => $request->email, 'errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat registrasi'
            ], 500);
        }
    }

    /**
     * Login API
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Cek apakah email terdaftar
            $user = User::where('email', $validated['email'])->first();
            
            if (!$user) {
                Log::warning('Login attempt with non-existing email', [
                    'email' => $validated['email'],
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email tidak terdaftar'
                ], 401);
            }

            // Cek kecocokan password
            if (!Hash::check($validated['password'], $user->password)) {
                Log::warning('Invalid password attempt', [
                    'email' => $validated['email'],
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password salah'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('User logged in via API', [
                'email' => $validated['email'],
                'ip' => $request->ip()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'authenticated' => true,
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'is_verified' => $user->hasVerifiedEmail(),
                    'usage' => 'Kirim header: Authorization: Bearer ' . $token,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat login'
            ], 500);
        }
    }

    /**
     * Login/Register API via Google ID token from Flutter.
     */
    public function googleLogin(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_token' => 'required|string',
            ]);

            $googleResponse = Http::timeout(10)->get(
                'https://oauth2.googleapis.com/tokeninfo',
                ['id_token' => $validated['id_token']]
            );

            if (!$googleResponse->successful()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token Google tidak valid',
                ], 401);
            }

            $payload = $googleResponse->json();
            $expectedClientId = config('services.google.client_id');

            if ($expectedClientId && (($payload['aud'] ?? null) !== $expectedClientId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token Google bukan untuk aplikasi Calista',
                ], 401);
            }

            $emailVerified = filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if (!$emailVerified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email Google belum terverifikasi',
                ], 401);
            }

            $email = $payload['email'] ?? null;
            if (!$email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email Google tidak tersedia',
                ], 422);
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $payload['name'] ?? Str::before($email, '@'),
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(), // Auto-verify Google user on registration
                ]
            );

            $isNewUser = $user->wasRecentlyCreated;

            // If user existed but wasn't verified, mark as verified now
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
                $isNewUser = true;
            }

            if ($isNewUser) {
                try {
                    EmailService::sendWelcomeEmail($user->email, $user->name);
                } catch (\Exception $e) {
                    Log::error("Failed to send welcome email to {$user->email} via Google: " . $e->getMessage());
                }
            }

            $token = $user->createToken('google_auth_token')->plainTextToken;

            Log::info('User logged in via Google API', [
                'email' => $email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login Google berhasil',
                'authenticated' => true,
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'is_verified' => true,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Google login error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat login Google'
            ], 500);
        }
    }

    /**
     * Verify Email OTP
     */
    public function verifyEmailOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6',
            ]);

            $cachedOtp = Cache::get('email_otp_' . $validated['email']);

            if (!$cachedOtp || $cachedOtp != $validated['otp']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode OTP tidak valid atau sudah kedaluwarsa'
                ], 422);
            }

            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $user->markEmailAsVerified();
            Cache::forget('email_otp_' . $validated['email']);

            Log::info('User email verified via OTP', ['email' => $user->email]);

            // Send Welcome Email after successful email verification
            try {
                EmailService::sendWelcomeEmail($user->email, $user->name);
            } catch (\Exception $e) {
                Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Email berhasil diverifikasi',
                'data' => [
                    'user' => $user,
                    'is_verified' => true
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('OTP verification error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memverifikasi OTP'
            ], 500);
        }
    }

    /**
     * Resend Email OTP
     */
    public function resendOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email sudah terverifikasi'
                ], 400);
            }

            // Rate limit: check if recent OTP request exists (less than 2 minutes ago)
            $rateLimitKey = 'resend_otp_cooldown_' . $validated['email'];
            if (Cache::has($rateLimitKey)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan tunggu 2 menit sebelum meminta kode OTP kembali'
                ], 429);
            }

            $otp = mt_rand(100000, 999999);
            Cache::put('email_otp_' . $validated['email'], $otp, 600); // 10 mins
            Cache::put($rateLimitKey, true, 120); // 2 mins cooldown

            EmailService::sendOtpEmail($validated['email'], $otp);

            return response()->json([
                'status' => 'success',
                'message' => 'Kode OTP baru telah dikirim ke email Anda'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Resend OTP error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengirim ulang OTP'
            ], 500);
        }
    }

    /**
     * Request Forgot Password OTP
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email tidak terdaftar'
                ], 404);
            }

            // Rate limit: check if recent reset request exists (less than 2 minutes ago)
            $rateLimitKey = 'forgot_password_cooldown_' . $validated['email'];
            if (Cache::has($rateLimitKey)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan tunggu 2 menit sebelum meminta kode pemulihan kembali'
                ], 429);
            }

            $otp = mt_rand(100000, 999999);
            Cache::put('password_reset_otp_' . $validated['email'], $otp, 600); // 10 mins
            Cache::put($rateLimitKey, true, 120); // 2 mins cooldown

            EmailService::sendForgotPasswordOtpEmail($validated['email'], $otp);

            return response()->json([
                'status' => 'success',
                'message' => 'Kode OTP atur ulang kata sandi telah dikirim'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Forgot password error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses permintaan'
            ], 500);
        }
    }

    /**
     * Reset Password using OTP
     */
    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6',
                'password' => 'required|min:8|confirmed',
            ]);

            $cachedOtp = Cache::get('password_reset_otp_' . $validated['email']);

            if (!$cachedOtp || $cachedOtp != $validated['otp']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode OTP tidak valid atau sudah kedaluwarsa'
                ], 422);
            }

            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $user->password = Hash::make($validated['password']);
            $user->save();

            Cache::forget('password_reset_otp_' . $validated['email']);

            Log::info('User password successfully reset via OTP', ['email' => $user->email]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kata sandi berhasil diperbarui'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reset password error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengatur ulang kata sandi'
            ], 500);
        }
    }

    /**
     * Logout API
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ], 200);
    }

    /**
     * Get current user & check if authenticated
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'authenticated' => true,
            'data' => $request->user()
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $user = $request->user();
            $user->name = $validated['name'];
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check authentication status
     */
    public function checkAuth(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'authenticated' => true,
                'user' => $user
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'authenticated' => false,
            'message' => 'Belum login'
        ], 200);
    }
}
