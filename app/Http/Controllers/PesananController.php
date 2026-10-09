<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Daftar layanan dan harga
    private function daftarHarga()
    {
        return [
            'fotokopi_bw' => [
                'nama' => 'Fotokopi Hitam Putih',
                'harga' => 500,
            ],
            'print_bw' => [
                'nama' => 'Print Hitam Putih',
                'harga' => 1000,
            ],
            'print_warna' => [
                'nama' => 'Print Berwarna',
                'harga' => 2000,
            ],
            'jilid_lakban' => [
                'nama' => 'Jilid Lakban',
                'harga' => 3000,
            ],
            'jilid_spiral' => [
                'nama' => 'Jilid Spiral',
                'harga' => 7000,
            ],
            'laminating' => [
                'nama' => 'Laminating',
                'harga' => 3000,
            ],
            'scan' => [
                'nama' => 'Scan Dokumen',
                'harga' => 1000,
            ],
        ];
    }

    // Memeriksa login pegawai
    private function cekLogin()
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        return null;
    }

    // Menampilkan semua pesanan
    public function index()
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $pesanan = Pesanan::with('detailLayanan')
            ->orderBy('id_pesanan', 'desc')
            ->get();

        return view('pesanan.index', compact('pesanan'));
    }

    // Menampilkan form tambah pesanan
    public function create()
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $layanan = $this->daftarHarga();

        return view('pesanan.create', compact('layanan'));
    }

    // Menyimpan pesanan baru
    public function store(Request $request)
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $layanan = $this->daftarHarga();

        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'nama_pesanan' => 'required|string|max:500',
            'jenis_pesanan' => 'required|in:langsung,reguler',
            'detail_pesanan' => 'nullable|string',

            'rincian' => 'required|array|min:1',
            'rincian.*.jenis_layanan' => [
                'required',
                'in:' . implode(',', array_keys($layanan)),
            ],
            'rincian.*.jumlah' => 'required|integer|min:1',
        ]);

        // Menentukan jenis pesanan
        $langsung = $request->jenis_pesanan === 'langsung';

        // Menyimpan data utama pesanan
        $pesanan = Pesanan::create([
            'id_pelanggan' => null,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_pesanan' => $request->nama_pesanan,
            'jenis_pesanan' => $request->jenis_pesanan,
            'detail_pesanan' => $request->detail_pesanan,
            'status' => $langsung
                ? 'selesai'
                : 'menunggu_dikerjakan',
            'tanggal_selesai' => $langsung ? now() : null,
            'total_harga' => 0,
        ]);

        // Menyimpan setiap layanan dan menghitung total
        $total = 0;

        foreach ($request->rincian as $item) {
            $pilihan = $layanan[$item['jenis_layanan']];
            $jumlah = (int) $item['jumlah'];
            $harga = $pilihan['harga'];
            $subtotal = $harga * $jumlah;

            $pesanan->detailLayanan()->create([
                'jenis_layanan' => $item['jenis_layanan'],
                'jumlah' => $jumlah,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        // Memperbarui total harga pesanan
        $pesanan->total_harga = $total;

        // Membuat kode otomatis untuk pesanan reguler
        if (!$langsung) {
            $pesanan->kode_pesanan = 'ORD-' . str_pad(
                $pesanan->id_pesanan,
                4,
                '0',
                STR_PAD_LEFT
            );
        }

        $pesanan->save();

        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Pesanan berhasil disimpan.');
    }

    // Menampilkan detail pesanan
    public function show($id)
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $pesanan = Pesanan::with('detailLayanan')
            ->findOrFail($id);

        $layanan = $this->daftarHarga();

        return view(
            'pesanan.show',
            compact('pesanan', 'layanan')
        );
    }

    // Menampilkan form edit pesanan
    public function edit($id)
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $pesanan = Pesanan::with('detailLayanan')
            ->findOrFail($id);

        $layanan = $this->daftarHarga();

        return view(
            'pesanan.edit',
            compact('pesanan', 'layanan')
        );
    }

    // Memperbarui pesanan dan rincian layanan
    public function update(Request $request, $id)
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $layanan = $this->daftarHarga();

        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'nama_pesanan' => 'required|string|max:500',
            'detail_pesanan' => 'nullable|string',

            'rincian' => 'required|array|min:1',
            'rincian.*.jenis_layanan' => [
                'required',
                'in:' . implode(',', array_keys($layanan)),
            ],
            'rincian.*.jumlah' => 'required|integer|min:1',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // Memperbarui informasi utama
        $pesanan->nama_pelanggan = $request->nama_pelanggan;
        $pesanan->nama_pesanan = $request->nama_pesanan;
        $pesanan->detail_pesanan = $request->detail_pesanan;

        $pesanan->save();

        // Menghapus rincian lama
        $pesanan->detailLayanan()->delete();

        // Menyimpan rincian terbaru
        $total = 0;

        foreach ($request->rincian as $item) {
            $pilihan = $layanan[$item['jenis_layanan']];
            $jumlah = (int) $item['jumlah'];
            $harga = $pilihan['harga'];
            $subtotal = $harga * $jumlah;

            $pesanan->detailLayanan()->create([
                'jenis_layanan' => $item['jenis_layanan'],
                'jumlah' => $jumlah,
                'harga_satuan' => $harga,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        // Memperbarui total
        $pesanan->total_harga = $total;
        $pesanan->save();

        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    // Menghapus pesanan
    public function destroy($id)
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    // Memperbarui status pesanan
    public function updateStatus(Request $request, $id)
    {
        if ($redirect = $this->cekLogin()) {
            return $redirect;
        }

        $request->validate([
            'status' => 'required|in:menunggu_dikerjakan,sedang_dikerjakan,selesai',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        $pesanan->status = $request->status;

        if ($request->status === 'selesai') {
            $pesanan->tanggal_selesai = now();
        } else {
            $pesanan->tanggal_selesai = null;
        }

        $pesanan->save();

        return redirect()
            ->route('pesanan.show', $pesanan->id_pesanan)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
