<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class AuthPegawaiController extends Controller
{
    public function showLogin()
    {
        return view('pegawai.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $pegawai = Pegawai::where('username', $request->username)
        ->where('password', $request->password)
        ->first();

        if (!$pegawai) {
            return back()
            ->withInput()
            ->with('error', 'Username atau password salah.');
        }

        session([
            'pegawai_login' => true,
            'id_pegawai' => $pegawai->id_pegawai,
            'username' => $pegawai->username,
            ]);

        return redirect()->route('pesanan.index');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('pegawai.login');
    }
}
