@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <nav>
        <strong>Website Pegawai - Percetakan</strong>
        <a href="{{ route('pesanan.index') }}">Kembali</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Detail Pesanan</h2>

            @if (session('success'))
                <p class="alert">{{ session('success') }}</p>
            @endif

            <p><strong>Kode Pesanan:</strong>
                {{ $pesanan->kode_pesanan ?? '-' }}</p>

            <p><strong>Nama Pelanggan:</strong>
                {{ $pesanan->nama_pelanggan }}</p>

            <p><strong>Nama Pesanan:</strong>
                {{ $pesanan->nama_pesanan }}</p>

            <p><strong>Jenis Pesanan:</strong>
                {{ ucfirst($pesanan->jenis_pesanan) }}</p>

            <p><strong>Status:</strong>
                {{ ucwords(str_replace('_', ' ', $pesanan->status)) }}</p>

            <h3>Rincian Layanan</h3>

            <table>
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan->detailLayanan as $item)
                        <tr>
                            <td>
                                {{ $layanan[$item->jenis_layanan]['nama'] ?? $item->jenis_layanan }}
                            </td>
                            <td>{{ $item->jumlah }}</td>
                            <td>
                                Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>
                Total Pembayaran:
                Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
            </h3>

            @if ($pesanan->detail_pesanan)
                <p><strong>Catatan:</strong> {{ $pesanan->detail_pesanan }}</p>
            @endif

            <hr>

            <h3>Perbarui Status</h3>

            <form method="POST" action="{{ route('pesanan.status', $pesanan->id_pesanan) }}">
                @csrf
                @method('PUT')

                <label>Status Pesanan</label>

                <select name="status" required>
                    <option value="menunggu_dikerjakan" {{ $pesanan->status == 'menunggu_dikerjakan' ? 'selected' : '' }}>
                        Menunggu Dikerjakan
                    </option>

                    <option value="sedang_dikerjakan" {{ $pesanan->status == 'sedang_dikerjakan' ? 'selected' : '' }}>
                        Sedang Dikerjakan
                    </option>

                    <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                </select>

                <button type="submit">Simpan Status</button>
            </form>

            <p>
                <a href="{{ route('pesanan.edit', $pesanan->id_pesanan) }}">
                    Edit Pesanan
                </a>
            </p>
        </div>
    </div>
@endsection
