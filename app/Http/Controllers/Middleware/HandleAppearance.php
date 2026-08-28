<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $peranPengguna = strtolower(trim(Auth::user()->role ?? ''));

        $daftarPeranDiizinkan = [];
        foreach ($roles as $peran) {
            $daftarPeranDiizinkan[] = strtolower(trim($peran));
        }

        if (in_array($peranPengguna, $daftarPeranDiizinkan)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
