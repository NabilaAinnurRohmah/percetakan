@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="history-container">
        <div class="history-card">

            <div class="history-header">
                <div>
                    <h2>Riwayat Pesanan</h2>
                    <p>
                        Daftar pesanan yang telah selesai dikerjakan.
                    </p>
                </div>

                <span class="history-count">
                    {{ $pesanan->count() }} Pesanan Selesai
                </span>
            </div>

            @if (session('success'))
                <div class="order-alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="history-table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Pesanan</th>
                            <th>Jenis Pesanan</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesanan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="history-code">
                                    {{ $item->kode_pesanan ?? '-' }}
                                </td>

                                <td class="history-customer">
                                    {{ $item->nama_pelanggan ?? '-' }}
                                </td>

                                <td>{{ $item->nama_pesanan }}</td>

                                <td>
                                    {{ $item->jenis_pesanan === 'langsung' ? 'Langsung' : 'Reguler' }}
                                </td>

                                <td>
                                    {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y H:i') : '-' }}
                                </td>

                                <td class="history-price">
                                    Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="history-status">
                                        Selesai
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('pesanan.show', $item->id_pesanan) }}" class="history-detail-btn">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="history-empty">
                                    Belum ada pesanan yang selesai.<br>
                                    Pesanan yang telah selesai akan muncul di halaman ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
