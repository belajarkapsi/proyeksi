<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
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
        if (!Auth::check()) {
            Alert::error('Eitss', 'Silakan login terlebih dahulu.');

            return redirect()->route('login');
        }

        $user = Auth::user();

        // Cek jika diantara data ini belum ada
        if(is_null($user->alamat) || is_null($user->asal) || is_null($user->tanggal_lahir)) {

            Alert::alert('Data Belum Lengkap', 'Mohon lengkapi dahulu data diri anda sebelum memesan!');

            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
