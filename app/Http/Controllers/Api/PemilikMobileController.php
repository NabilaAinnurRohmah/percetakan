<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PemilikMobileController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $pemilik = Pegawai::where('username', $data['username'])
            ->where('password', $data['password'])
            ->where('peran', 'pemilik')
            ->first();

        if (!$pemilik) {
            return response()->json(['message' => 'Username atau Password salah'], 401);
        }

        return response()->json(['message' => 'Login berhasil',
        'pemilik' => [
            'id_pegawai' => $pemilik->id_pegawai,
            'username' => $pemilik->username,
        ]]);
    }

    public function dashboard()
    {
        $hariIni = now()->toDateString();

        return response()->json([
            'tanggal' => $hariIni,
            'total_hari_ini' => Pesanan::whereDate('tanggal_pesanan', $hariIni)->count(),
            'menunggu' => Pesanan::where('status', 'menunggu_dikerjakan')->count(),
            'sedang_dikerjakan' => Pesanan::where('status', 'sedang_dikerjakan')->count(),
            'selesai' => Pesanan::where('status', 'selesai')->count(),
        ]);
    }

    public function daftarPesanan()
    {
        $pesanan = Pesanan::with('detailLayanan')
        ->orderBy('id_pesanan', 'desc')
        ->get();

        return response()->json([$pesanan]);
    }

    public function detailPesanan($id)
    {
        $pesanan = Pesanan::with('detailLayanan')
        ->where('id_pesanan', $id)
        ->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        return response()->json([$pesanan]);
    }

}
