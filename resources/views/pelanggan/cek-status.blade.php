@extends('layouts.app')

@section('title', 'Cek Status Pesanan')

@section('content')

    <div class="container">

        <div class="card" style="max-width: 600px; margin: 70px auto;">

            <h2>Cek Status Pesanan</h2>

            <p>
                Masukkan kode pesanan yang diberikan oleh pegawai.
            </p>

            @if (session('error'))
                <div class="alert error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())

                <div class="alert error">

                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach

                </div>

            @endif

            <form method="POST" action="{{ route('pelanggan.cek-status.proses') }}">

                @csrf

                <label>Kode Pesanan</label>

                <input type="text" name="kode_pesanan" value="{{ old('kode_pesanan') }}" placeholder="Contoh: ORD-AB12CD34"
                    required>

                <button type="submit">
                    Cek Status
                </button>

            </form>

        </div>


        @isset($pesanan)

            <div class="card" style="max-width: 600px; margin: 20px auto;">

                <h3>Informasi Pesanan</h3>

                <p>
                    <strong>Kode Pesanan:</strong>
                    {{ $pesanan->kode_pesanan }}
                </p>

                <p>
                    <strong>Nama Pesanan:</strong>
                    {{ $pesanan->nama_pesanan }}
                </p>

                <p>
                    <strong>Nama Pelanggan:</strong>
                    {{ $pesanan->pelanggan->nama ?? '-' }}
                </p>

                <p>
                    <strong>Status:</strong>

                    @if ($pesanan->status === 'menunggu_dikerjakan')
                        Menunggu Dikerjakan
                    @elseif($pesanan->status === 'sedang_dikerjakan')
                        Sedang Dikerjakan
                    @elseif($pesanan->status === 'selesai')
                        Selesai
                    @endif

                </p>

            </div>

        @endisset

    </div>

@endsection
