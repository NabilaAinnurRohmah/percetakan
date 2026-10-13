@extends('layouts.app')

@section('title', 'Data Pesanan')

@section('content')
    <nav class="order-nav">
        <strong>Website Pegawai - Percetakan</strong>
    </nav>

    <div class="order-container">
        <div class="order-card">

            <div class="order-header">
                <div class="order-heading">
                    <h2>Data Pesanan</h2>
                    <p>Kelola pesanan dan pantau proses pengerjaan.</p>
                </div>

                <a href="{{ route('pesanan.create') }}" class="btn btn-add">
                    + Tambah Pesanan
                </a>
            </div>

            @if (session('success'))
                <div class="order-alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="order-alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="table-wrapper">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesanan as $p)
                            <tr>
                                <td class="order-code">
                                    {{ $p->kode_pesanan ?? '-' }}
                                </td>

                                <td>{{ $p->nama_pelanggan ?? '-' }}</td>

                                <td>{{ $p->nama_pesanan }}</td>

                                <td class="order-price">
                                    Rp{{ number_format($p->total_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($p->status === 'menunggu_dikerjakan')
                                        <span class="status-badge status-waiting">
                                            Menunggu Dikerjakan
                                        </span>
                                    @elseif ($p->status === 'sedang_dikerjakan')
                                        <span class="status-badge status-progress">
                                            Sedang Dikerjakan
                                        </span>
                                    @else
                                        <span class="status-badge status-done">
                                            Selesai
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('pesanan.show', $p->id_pesanan) }}" class="btn btn-detail">
                                            Detail
                                        </a>

                                        @if ($p->jenis_pesanan === 'reguler')
                                            @if ($p->status === 'menunggu_dikerjakan')
                                                <form method="POST" action="{{ route('pesanan.status', $p->id_pesanan) }}"
                                                    onsubmit="return confirm('Mulai mengerjakan pesanan ini?')">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="hidden" name="status" value="sedang_dikerjakan">

                                                    <button type="submit" class="btn btn-process">
                                                        Mulai
                                                    </button>
                                                </form>
                                            @elseif ($p->status === 'sedang_dikerjakan')
                                                <form method="POST" action="{{ route('pesanan.status', $p->id_pesanan) }}"
                                                    onsubmit="return confirm('Apakah pesanan ini sudah selesai?')">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="hidden" name="status" value="selesai">

                                                    <button type="submit" class="btn btn-finish">
                                                        Selesai
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        <a href="{{ route('pesanan.edit', $p->id_pesanan) }}" class="btn btn-edit">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('pesanan.destroy', $p->id_pesanan) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <strong>Belum ada data pesanan</strong>
                                    Pesanan yang ditambahkan akan muncul di sini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="order-footer">
                Menampilkan data pesanan yang tersimpan di sistem.
            </div>

        </div>
    </div>
@endsection
