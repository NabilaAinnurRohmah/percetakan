@extends('layouts.app')

@section('title', 'Tambah Pesanan')

@section('content')

    <nav>

        <strong>Website Pegawai - Percetakan</strong>

        <a href="{{ route('pesanan.index') }}">
            Kembali
        </a>

    </nav>

    <div class="container">

        <div class="card">

            <h2>Tambah Data Pesanan</h2>

            @if ($errors->any())

                <div class="alert error">

                    <ul>

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <form method="POST" action="{{ route('pesanan.store') }}">

                @csrf

                <label>Pelanggan</label>

                <select name="id_pelanggan">

                    <option value="">
                        -- Pilih Pelanggan --
                    </option>

                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id_pelanggan }}">

                            {{ $p->nama }}

                        </option>
                    @endforeach

                </select>


                <label>Nama Pesanan</label>

                <input type="text" name="nama_pesanan" placeholder="Contoh: Cetak Makalah">


                <label>Jenis Pesanan</label>

                <select name="jenis_pesanan" required>

                    <option value="">
                        -- Pilih Jenis --
                    </option>

                    <option value="langsung">
                        Pesanan Langsung
                    </option>

                    <option value="reguler">
                        Pesanan Reguler
                    </option>

                </select>


                <label>Detail Pesanan</label>

                <textarea name="detail_pesanan" rows="4" placeholder="Contoh: Cetak 20 halaman, hitam putih">
            </textarea>


                <label>Total Harga</label>

                <input type="number" name="total_harga" min="0" step="500" placeholder="15000">


                <button type="submit">
                    Simpan Pesanan
                </button>

            </form>

        </div>

    </div>

@endsection
