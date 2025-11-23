<?php

use App\Http\Middleware\GuestOnly;
use App\Http\Middleware\HandleAuthRedirect;
use App\Http\Middleware\HarusLoginUntukPesan;
use App\Http\Middleware\LengkapiProfil;
use App\Http\Middleware\ValidasiCabang;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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
            'validasi.cabang' => ValidasiCabang::class,
            'lengkapi.profil' => LengkapiProfil::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {

            // Lempar ke halaman login dengan pesan Error Session
            return redirect()->guest(route('login'))
                ->with('error', 'Eitss, kamu harus login dulu untuk melanjutkan!');
        });
    })->create();
