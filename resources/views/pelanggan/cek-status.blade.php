@extends('layouts.app')

@section('title', 'Cek Status Pesanan')

@section('content')
    <div class="status-page">

        {{-- Form pencarian pesanan --}}
        <div class="status-card">
            <div class="status-header">
                <h2>Cek Status Pesanan</h2>
                <p>
                    Masukkan kode pesanan yang diberikan oleh pegawai
                    untuk melihat rincian dan status pengerjaan.
                </p>
            </div>

            @if (session('error'))
                <div class="status-alert status-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="status-alert status-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form class="status-form" method="POST" action="{{ route('pelanggan.cek-status.proses') }}">
                @csrf

                <label for="kode_pesanan">Kode Pesanan</label>
                <input type="text" id="kode_pesanan" name="kode_pesanan" value="{{ old('kode_pesanan') }}"
                    placeholder="Contoh: ORD-0001" required>

                <button type="submit" class="status-submit">
                    Cek Status Pesanan
                </button>
            </form>
        </div>

        {{-- Hasil pencarian --}}
        @isset($pesanan)
            <div class="status-card">

                <div class="status-result-header">
                    <h3>Informasi Pesanan</h3>
                    <p>Berikut rincian pesanan berdasarkan kode yang dimasukkan.</p>
                </div>

                <div class="status-info-grid">
                    <div class="status-info-item">
                        <strong>Kode Pesanan</strong>
                        <span>{{ $pesanan->kode_pesanan ?? '-' }}</span>
                    </div>

                    <div class="status-info-item">
                        <strong>Nama Pelanggan</strong>
                        <span>{{ $pesanan->nama_pelanggan ?? '-' }}</span>
                    </div>

                    <div class="status-info-item">
                        <strong>Nama Pesanan</strong>
                        <span>{{ $pesanan->nama_pesanan }}</span>
                    </div>

                    <div class="status-info-item">
                        <strong>Tanggal Pesanan</strong>
                        <span>
                            {{ $pesanan->tanggal_pesanan ? \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d-m-Y H:i') : '-' }}
                        </span>
                    </div>
                </div>

                <h4 class="status-section-title">Rincian Layanan</h4>

                <div class="status-table-wrapper">
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Harga Satuan</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center;">
                                        Belum ada rincian layanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="status-total">
                    <span>Total Harga</span>
                    <strong>
                        Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="status-progress-box">
                    <strong>Status Pengerjaan</strong>

                    @if ($pesanan->status === 'menunggu_dikerjakan')
                        <span class="status-badge status-waiting">
                            Menunggu Dikerjakan
                        </span>
                        <p class="status-progress-note">
                            Pesanan telah tercatat dan menunggu untuk dikerjakan.
                        </p>
                    @elseif ($pesanan->status === 'sedang_dikerjakan')
                        <span class="status-badge status-progress">
                            Sedang Dikerjakan
                        </span>
                        <p class="status-progress-note">
                            Pesanan sedang dalam proses pengerjaan.
                        </p>
                    @elseif ($pesanan->status === 'selesai')
                        <span class="status-badge status-done">
                            Selesai
                        </span>
                        <p class="status-progress-note">
                            Pesanan telah selesai dikerjakan.
                        </p>
                    @endif
                </div>

            </div>
        @endisset

    </div>
@endsection
