<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Menampilkan halaman cek status
    public function index()
    {
        return view('pelanggan.cek-status');
    }

    // Memeriksa kode pesanan
    public function cek(Request $request)
    {
        $request->validate([
            'kode_pesanan' => 'required|string',
        ]);

        $kode = strtoupper(trim($request->kode_pesanan));

        $pesanan = Pesanan::with('detailLayanan')
            ->where('kode_pesanan', $kode)
            ->where('jenis_pesanan', 'reguler')
            ->first();

        if (!$pesanan) {
            return back()
                ->withInput()
                ->with('error', 'Kode pesanan tidak ditemukan.');
        }

        $layanan = [
            'fotokopi_bw' => ['nama' => 'Fotokopi Hitam Putih'],
            'print_bw' => ['nama' => 'Print Hitam Putih'],
            'print_warna' => ['nama' => 'Print Berwarna'],
            'jilid_lakban' => ['nama' => 'Jilid Lakban'],
            'jilid_spiral' => ['nama' => 'Jilid Spiral'],
            'laminating' => ['nama' => 'Laminating'],
            'scan' => ['nama' => 'Scan Dokumen'],
        ];

        return view(
            'pelanggan.cek-status',
            compact('pesanan', 'layanan')
        );
    }
}
