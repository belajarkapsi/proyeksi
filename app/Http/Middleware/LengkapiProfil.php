<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LengkapiProfil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek jika diantara data ini belum ada
        if(is_null($user->alamat) || is_null($user->asal) || is_null($user->tanggal_lahir)) {
            
            return redirect()->route('profile.edit') // Arahkan ke halaman edit profil
                ->with('error', 'Mohon lengkapi dahulu data diri anda sebelum pesan!');
        }

        return $next($request);
    }
}
