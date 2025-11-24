<?php

use App\Http\Middleware\GuestOnly;
use App\Http\Middleware\HarusLoginUntukPesan;
use App\Http\Middleware\LengkapiProfil;
use App\Http\Middleware\ValidasiCabang;
use Symfony\Component\HttpFoundation\Response;
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
        $exceptions->respond(function (Response $response) {
            // Kalau error-nya 419 (Page Expired / CSRF mismatch)
            if ($response->getStatusCode() === 419) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Sesi Anda sudah berakhir, silakan ulangi pemesanan.');
            }

            return $response;
        });
    })->create();
