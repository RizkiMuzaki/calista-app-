<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        if ($request->user()) {
            return response()->json([
                'status' => 'success',
                'authenticated' => true,
                'user' => $request->user()
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'authenticated' => false,
            'message' => 'Belum login'
        ], 401);
    }
}
