<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            if (Auth::guard('pemilik')->check()) {
                return redirect()->route('admin.dashboard');
            }

            // Jika Penyewa sudah login -> Lempar ke Dashboard Utama
            if (Auth::guard('web')->check()) {
                return redirect()->route('dashboard');
            }

        return $next($request);
    }
}
