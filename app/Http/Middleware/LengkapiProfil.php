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

        // Kumpulkan field yang kosong
        $missing = [];

        if (is_null($user->alamat) || $user->alamat === '') {
            $missing[] = 'alamat';
        }

        if (is_null($user->asal) || $user->asal === '') {
            $missing[] = 'asal';
        }

        if (is_null($user->jenis_kelamin) || $user->jenis_kelamin === '') {
            $missing[] = 'jenis_kelamin';
        }

        if (is_null($user->tanggal_lahir) || $user->tanggal_lahir === '') {
            $missing[] = 'tanggal_lahir';
        }

        // Cek jika diantara data ini belum ada
        if(!empty($missing)) {
            Alert::warning('Data Belum Lengkap', 'Mohon lengkapi dahulu data diri anda sebelum memesan!');

            return redirect()->route('profile.edit')->with('missing_profile_fields', $missing);
        }

        return $next($request);
    }
}
