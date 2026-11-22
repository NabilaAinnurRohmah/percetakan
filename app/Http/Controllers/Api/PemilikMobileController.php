<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pegawai;
use Carbon\Carbon;
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

        $pesananHariIni = Pesanan::whereDate(
            'tanggal_pesanan',
            $hariIni
        );

        return response()->json([
            'tanggal' => $hariIni,

            'total_hari_ini' => (clone $pesananHariIni)->count(),

            'dana_hari_ini' => (clone $pesananHariIni)->sum('total_harga'),

            'menunggu' => Pesanan::where(
                'status',
                'menunggu_dikerjakan'
            )->count(),

            'sedang_dikerjakan' => Pesanan::where(
                'status',
                'sedang_dikerjakan'
            )->count(),

            'selesai' => Pesanan::where(
                'status',
                'selesai'
            )->count(),
        ]);
    }

    public function daftarPesanan()
    {
        $pesanan = Pesanan::with('detailLayanan')
        ->orderBy('id_pesanan', 'desc')
        ->get();

        return response()->json($pesanan);
    }

    public function detailPesanan($id)
    {
        $pesanan = Pesanan::with('detailLayanan')
        ->where('id_pesanan', $id)
        ->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        return response()->json($pesanan);
    }


public function laporan(Request $request)
{
    $data = $request->validate([
        'filter' => 'required|in:harian,mingguan,bulanan,tahunan',
        'tanggal' => 'nullable|date',
        'bulan' => 'nullable|integer|min:1|max:12',
        'tahun' => 'nullable|integer|min:2000|max:2100',
    ]);

    $filter = $data['filter'];
    $tanggal = Carbon::parse($data['tanggal'] ?? now());

    switch ($filter) {
        case 'harian':
            $awal = $tanggal->copy()->startOfDay();
            $akhir = $tanggal->copy()->endOfDay();
            break;

        case 'mingguan':
            $awal = $tanggal->copy()->startOfWeek(Carbon::MONDAY);
            $akhir = $tanggal->copy()->endOfWeek(Carbon::SUNDAY);
            break;

        case 'bulanan':
            $bulan = $data['bulan'] ?? now()->month;
            $tahun = $data['tahun'] ?? now()->year;

            $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $akhir = $awal->copy()->endOfMonth();
            break;

        case 'tahunan':
            $tahun = $data['tahun'] ?? now()->year;

            $awal = Carbon::create($tahun, 1, 1)->startOfYear();
            $akhir = $awal->copy()->endOfYear();
            break;
    }

    $query = Pesanan::whereBetween(
        'tanggal_pesanan',
        [$awal, $akhir]
    );

    $pesanan = (clone $query)
        ->orderBy('tanggal_pesanan', 'desc')
        ->get();

    return response()->json([
        'filter' => $filter,
        'tanggal_mulai' => $awal->toDateString(),
        'tanggal_akhir' => $akhir->toDateString(),

        'jumlah_pesanan' => (clone $query)->count(),

        'total_dana' => (clone $query)->sum('total_harga'),

        'pesanan' => $pesanan,
    ]);
}


    public function unduhLaporan(Request $request)
    {
        $data = $request->validate([
            'filter' => 'required|in:harian,mingguan,bulanan,tahunan',
            'tanggal' => 'nullable|date',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $filter = $data['filter'];
        $tanggal = Carbon::parse($data['tanggal'] ?? now());

        switch ($filter) {
            case 'harian':
                $awal = $tanggal->copy()->startOfDay();
                $akhir = $tanggal->copy()->endOfDay();
                break;

            case 'mingguan':
                $awal = $tanggal->copy()->startOfWeek(Carbon::MONDAY);
                $akhir = $tanggal->copy()->endOfWeek(Carbon::SUNDAY);
                break;

            case 'bulanan':
                $bulan = $data['bulan'] ?? now()->month;
                $tahun = $data['tahun'] ?? now()->year;

                $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $akhir = $awal->copy()->endOfMonth();
                break;

            case 'tahunan':
                $tahun = $data['tahun'] ?? now()->year;

                $awal = Carbon::create($tahun, 1, 1)->startOfYear();
                $akhir = $awal->copy()->endOfYear();
                break;
        }

        $pesanan = Pesanan::whereBetween(
            'tanggal_pesanan',
            [$awal, $akhir]
        )
            ->orderBy('tanggal_pesanan', 'asc')
            ->get();

        $namaFile = 'laporan-pesanan-' . $filter . '-' .
            $awal->format('Ymd') . '-' . $akhir->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($pesanan) {
            $file = fopen('php://output', 'w');

            // BOM agar karakter UTF-8 terbaca baik di Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Kode Pesanan',
                'Nama Pelanggan',
                'Nama Pesanan',
                'Tanggal Pesanan',
                'Status',
                'Total Harga'
            ], ';');

            foreach ($pesanan as $item) {
                fputcsv($file, [
                    $item->kode_pesanan,
                    $item->nama_pelanggan,
                    $item->nama_pesanan,
                    $item->tanggal_pesanan,
                    $item->status,
                    $item->total_harga
                ], ';');
            }

            fclose($file);
        }, $namaFile, [
            'Content-Type' => 'text/csv; charset=UTF-8'
        ]);
    }

}
