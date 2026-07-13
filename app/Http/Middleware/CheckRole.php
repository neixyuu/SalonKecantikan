<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            if (auth()->check()) {
                // Sudah login tapi role salah
                $redirect = auth()->user()->role === 'admin' ? '/admin/dashboard' : '/dashboard';
                return redirect($redirect)->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
