<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsWaliKelas
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'wali_kelas') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}

