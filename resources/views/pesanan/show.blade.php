@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <nav class="order-nav">
        <strong>Website Pegawai - Percetakan</strong>

        <a href="{{ route('pesanan.index') }}">Kembali ke Data Pesanan</a>
    </nav>

    <div class="detail-container">
        <div class="detail-card">

            <div class="detail-header">
                <div>
                    <h2>Detail Pesanan</h2>
                    <p>Informasi lengkap pesanan dan rincian biaya layanan.</p>
                </div>

                <a href="{{ route('pesanan.index') }}" class="btn btn-detail">
                    Kembali
                </a>
            </div>

            @if (session('success'))
                <div class="order-alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="detail-info">
                <div class="detail-info-item">
                    <strong>Kode Pesanan</strong>
                    <span>{{ $pesanan->kode_pesanan ?? '-' }}</span>
                </div>

                <div class="detail-info-item">
                    <strong>Nama Pelanggan</strong>
                    <span>{{ $pesanan->nama_pelanggan ?? '-' }}</span>
                </div>

                <div class="detail-info-item">
                    <strong>Nama Pesanan</strong>
                    <span>{{ $pesanan->nama_pesanan }}</span>
                </div>

                <div class="detail-info-item">
                    <strong>Jenis Pesanan</strong>
                    <span>{{ ucfirst($pesanan->jenis_pesanan) }}</span>
                </div>

                <div class="detail-info-item">
                    <strong>Status Pesanan</strong>
                    <span>
                        {{ ucwords(str_replace('_', ' ', $pesanan->status)) }}
                    </span>
                </div>

                <div class="detail-info-item">
                    <strong>Tanggal Pesanan</strong>
                    <span>
                        {{ $pesanan->tanggal_pesanan ? \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d-m-Y H:i') : '-' }}
                    </span>
                </div>
            </div>

            <h3 class="detail-section-title">Rincian Layanan</h3>

            <div class="detail-table-wrapper">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Harga Satuan</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesanan->detailLayanan as $item)
                            <tr>
                                <td>
                                    {{ $layanan[$item->jenis_layanan]['nama'] ?? $item->jenis_layanan }}
                                </td>

                                <td class="text-center">
                                    {{ $item->jumlah }}
                                </td>

                                <td class="text-right">
                                    Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="text-right">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="detail-empty">
                                    Belum ada rincian layanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="detail-total">
                <span>Total Pembayaran</span>
                <strong>
                    Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                </strong>
            </div>

            @if ($pesanan->detail_pesanan)
                <div class="detail-note">
                    <strong>Catatan Pesanan</strong>
                    <div>{{ $pesanan->detail_pesanan }}</div>
                </div>
            @endif

        </div>
    </div>
@endsection
