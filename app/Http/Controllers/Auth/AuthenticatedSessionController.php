<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Tangani permintaan autentikasi masuk.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'Active') {
                Auth::logout();

                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Akun Anda tidak aktif!',
                ]);
            }

            return match ($user->role) {
                'admin', 'chef', 'cashier' => to_route('adminDashboard'),
                'user' => to_route('userDashboard'),
                default => back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Akses peran tidak diizinkan!',
                ]),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak cocok dengan data kami.',
        ]);
    }

    /**
     * Hapus sesi autentikasi.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
