<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $pesanan = Pesanan::with('pelanggan')
        ->orderBy('id_pesanan', 'desc')
        ->get();

        return view('pesanan.index', compact('pesanan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $pelanggan = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('pesanan.create', compact('pelanggan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $request->validate([
            'id_pelanggan' => 'nullable|exists:pelanggan,id_pelanggan',
            'nama_pesanan' => 'required|max:500',
            'jenis_pesanan' => 'required|in:langsung,reguler',
            'detail_pesanan' => 'nullable',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $status = 'menunggu_dikerjakan';

        if ($request->jenis_pesanan === 'langsung') {
            $status = 'selesai';
        }

        $pesanan = Pesanan::create([
            'id_pelanggan' => $request->id_pelanggan,
            'nama_pesanan' => $request->nama_pesanan,
            'jenis_pesanan' => $request->jenis_pesanan,
            'detail_pesanan' => $request->detail_pesanan,
            'status' => $status,
            'tanggal_selesai' =>
                $request->jenis_pesanan === 'langsung'
                ? now() : null,
            'total_harga' => $request->total_harga,
        ]);

        if ($request->jenis_pesanan === 'reguler') {
            $pesanan->kode_pesanan = 'ORD-' . str_pad
                ($pesanan->id_pesanan, 4, '0', STR_PAD_LEFT);
            $pesanan->save();
        }

        return redirect()->route('pesanan.index')
        ->with('success', 'Data berhasil disimpan.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $pesanan = Pesanan::with('pelanggan')->findOrFail($id);

        return view('pesanan.show', compact('pesanan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $pesanan = Pesanan::findOrFail($id);
        $pelanggan = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('pesanan.edit', compact('pesanan', 'pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $request->validate([
            'id_pelanggan' => 'nullable|exists:pelanggan,id_pelanggan',
            'nama_pesanan' => 'required|max:150',
            'detail_pesanan' => 'nullable',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        $pesanan->update([
            'id_pelanggan' => $request->id_pelanggan,
            'nama_pesanan' => $request->nama_pesanan,
            'detail_pesanan' => $request->detail_pesanan,
            'total_harga' => $request->total_harga,
        ]);

        return redirect()->route('pesanan.index')
            ->with('success', 'Data berhasil diperbarui.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
        }

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()->route('pesanan.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        if (!session('pegawai_login')) {
            return redirect()->route('pegawai.login');
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

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
