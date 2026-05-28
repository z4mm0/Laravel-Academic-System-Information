<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGuruMapel
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'guru_mapel') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}

