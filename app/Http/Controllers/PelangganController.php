<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pelanggan.cek-status');
    }


    public function cek(Request $request)
    {
        $request->validate([
            'kode_pesanan' => 'required'
        ]);

        $pesanan = Pesanan::where(
            'kode_pesanan', strtoupper(trim($request->kode_pesanan))
        )->first();

        if (!$pesanan) {
            return back()
            ->withInput()
            ->with('error', 'Kode pesanan tidak ditemukan.');
        }

        return view('pelanggan.cek-status', compact('pesanan'));
    }

}
