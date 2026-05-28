<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
'auth.guru_mapel' => \App\Http\Middleware\IsGuruMapel::class,
            'auth.wali_kelas' => \App\Http\Middleware\IsWaliKelas::class,
            'auth.student' => \App\Http\Middleware\IsStudent::class,
            'auth.superadmin' => \App\Http\Middleware\IsSuperAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
