<?php

use App\Http\Controllers\PastikanKepemilikanUser;
use App\Http\Middleware\GuestOnly;
use App\Http\Middleware\HarusLoginUntukPesan;
use App\Http\Middleware\LengkapiProfil;
use App\Http\Middleware\NoCacheMiddleware;
use App\Http\Middleware\ValidasiCabang;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
            'lengkapi.profil' => LengkapiProfil::class,
            'user.sebenarnya' => PastikanKepemilikanUser::class,
            'no.cache' => NoCacheMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response) {
            // Kalau error-nya 419 (Page Expired / CSRF mismatch)
            if ($response->getStatusCode() === 419) {
                Alert::error('Error', 'Sesi Anda sudah berakhir, silakan ulangi pemesanan.');

                return redirect()->route('dashboard');
            }

            return $response;
        });
    })->create();
