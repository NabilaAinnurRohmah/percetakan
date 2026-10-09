@extends('layouts.app')

@section('title', 'Data Pesanan')

@section('content')
    <nav>
        <strong>Website Pegawai - Percetakan</strong>
        <a href="{{ route('pesanan.create') }}">+ Tambah Pesanan</a>
        <a href="{{ route('pegawai.logout') }}">Logout</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Data Pesanan</h2>

            @if (session('success'))
                <p class="alert">{{ session('success') }}</p>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Nama Pesanan</th>
                        <th>Jumlah Jenis Layanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $p)
                        <tr>
                            <td>{{ $p->kode_pesanan ?? '-' }}</td>
                            <td>{{ $p->nama_pelanggan }}</td>
                            <td>{{ $p->nama_pesanan }}</td>
                            <td>{{ $p->detailLayanan->count() }} jenis</td>
                            <td>
                                Rp{{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                {{ ucwords(str_replace('_', ' ', $p->status)) }}
                            </td>
                            <td>
                                <a href="{{ route('pesanan.show', $p->id_pesanan) }}">
                                    Detail
                                </a>

                                <a href="{{ route('pesanan.edit', $p->id_pesanan) }}">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('pesanan.destroy', $p->id_pesanan) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada data pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
