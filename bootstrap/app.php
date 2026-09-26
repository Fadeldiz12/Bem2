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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->alias([
            'admin.auth'            => \App\Http\Middleware\AdminAuth::class,
            'admin.session-expiry'  => \App\Http\Middleware\AdminSessionExpiry::class,
            'role'                  => \App\Http\Middleware\CheckRole::class,
            'noindex'               => \App\Http\Middleware\NoIndex::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();