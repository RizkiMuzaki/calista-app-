<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        return view('Auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
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
        
        Auth::login($user);
        
        return redirect()->route('anak.create')->with('success', 'Akun berhasil dibuat! Silakan isi data anak.');
    }

    // Show login form
    public function showLogin()
    {
        return view('Auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->filled('remember');

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            Log::warning('Login attempt with non-existing email', ['email' => $request->email, 'ip' => $request->ip()]);
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        // Cek kecocokan password (berikan pesan lebih spesifik)
        if (! Hash::check($request->password, $user->password)) {
            Log::warning('Invalid password attempt', ['email' => $request->email, 'ip' => $request->ip()]);
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Log::info('User logged in', ['email' => $request->email, 'ip' => $request->ip()]);
            
            // Redirect ke intended page / profile anak setelah login sukses
            return redirect()->intended(route('anak.profile'))->with('status', 'Selamat datang kembali!');
        }

        // Logging failed attempt untuk diagnosa
        Log::warning('Failed login attempt (unknown reason)', ['email' => $request->email, 'ip' => $request->ip()]);

        return back()
            ->withErrors(['login' => 'Gagal login, coba lagi.'])
            ->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // gunakan key 'status' agar konsisten dengan tampilan
        return redirect()->route('halamanawal')->with('status', 'Berhasil logout.');
    }
}