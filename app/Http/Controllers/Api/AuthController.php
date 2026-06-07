<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Akun berhasil dibuat',
                'data' => [
                    'user' => $user,
                    'token' => $token,
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
                ]
            );

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

    /**
     * Check authentication status
     */
    public function checkAuth(Request $request)
    {
        // Cek user pakai guard sanctum secara eksplisit karena route ini public
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
        ], 200); // Return 200 biar tidak dianggap error oleh frontend
    }
}
