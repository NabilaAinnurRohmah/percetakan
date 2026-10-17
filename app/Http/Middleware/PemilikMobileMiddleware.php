<?php

namespace App\Http\Middleware;

use App\Models\Pegawai;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PemilikMobileMiddleware
{
    public function handle(Request $request, Closure $next):
    Response {
        $username = $request->header('X-Pemilik-Username');
        $password = $request->header('X-Pemilik-Password');

        if (!$username || !$password) {
            return response()->json(['message' => 'Silahkan Login Sebagai Pemilik'], 401);
        }

        $pemilik = Pegawai::where('username', $username)
        ->where('password', $password)
        ->where('peran', 'pemilik')
        ->first();

        if (!$pemilik) {
            return response()->json(['message' => 'Akses Ditolak'], 401);
        }

        $request->attributes->set('pemilik_mobile', $pemilik);

        return $next($request);
    }
}
