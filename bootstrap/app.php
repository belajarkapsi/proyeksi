<?php

use App\Http\Middleware\GuestOnly;
use App\Http\Middleware\HarusLoginUntukPesan;
use App\Http\Middleware\ValidasiCabang;
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
            'guest.only' => GuestOnly::class,
            'booking.only' => HarusLoginUntukPesan::class,
            'validasi.cabang' => ValidasiCabang::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
