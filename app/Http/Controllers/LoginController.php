<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        if (Auth::check()) {
            return $this->homeRedirect();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'nama_karyawan' => 'nullable|string|max:255',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Hubungi admin.',
                ])->onlyInput('email');
            }

            return $this->homeRedirect($request);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function homeRedirect(?Request $request = null)
    {
        $user = Auth::user();

        if (strtolower($user->role) === 'kasir') {
            if ($request) {
                session([
                    'nama_karyawan' => $request->nama_karyawan ?: $user->name,
                ]);
            }

            return redirect()->route('admin.pos.index');
        }

        return redirect()->route('admin.cash-sessions.index');
    }
}
