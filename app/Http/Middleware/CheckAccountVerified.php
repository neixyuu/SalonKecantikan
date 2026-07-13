<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->account_status !== 'verified') {
            // Izinkan akses ke halaman status akun & logout saja
            if (!$request->is('status-akun') && !$request->is('logout')) {
                return redirect('/status-akun');
            }
        }

        return $next($request);
    }
}
