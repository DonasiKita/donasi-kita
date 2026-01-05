<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Cek jika user sudah login dan memang admin, langsung lempar ke dashboard
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            // Jika login tapi bukan admin, logout dulu agar tidak stuck
            Auth::logout();
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 2. Ambil Credentials
        $credentials = $request->only('email', 'password');

        // 3. Cek apakah user mencentang "Ingat Saya" (name="remember" di form HTML)
        $remember = $request->has('remember');

        // 4. Proses Login
        if (Auth::attempt($credentials, $remember)) {

            // 5. Cek Role (Keamanan Tambahan)
            // Jika password benar, tapi role BUKAN admin
            if (Auth::user()->role !== 'admin') {
                Auth::logout(); // Tendang keluar
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses Admin.',
                ])->onlyInput('email');
            }

            // 6. Jika Password Benar DAN Role Admin
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang kembali, Admin!');
        }

        // 7. Jika Email/Password Salah
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
