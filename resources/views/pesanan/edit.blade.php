@extends('layouts.app')

@section('title', 'Edit Pesanan')

@section('content')

    <nav>

        <strong>Website Pegawai - Percetakan</strong>

        <a href="{{ route('pesanan.index') }}">
            Kembali
        </a>

    </nav>

    <div class="container">

        <div class="card">

            <h2>Edit Data Pesanan</h2>

            <form method="POST" action="{{ route('pesanan.update', $pesanan->id_pesanan) }}">

                @csrf
                @method('PUT')

                <label>Pelanggan</label>

                <select name="id_pelanggan">

                    <option value="">
                        -- Tidak ada --
                    </option>

                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id_pelanggan }}"
                            {{ $pesanan->id_pelanggan == $p->id_pelanggan ? 'selected' : '' }}>

                            {{ $p->nama }}

                        </option>
                    @endforeach

                </select>


                <label>Nama Pesanan</label>

                <input type="text" name="nama_pesanan" value="{{ $pesanan->nama_pesanan }}">


                <label>Jenis Pesanan</label>

                <input type="text" value="{{ ucfirst($pesanan->jenis_pesanan) }}" disabled>


                <label>Detail Pesanan</label>

                <textarea name="detail_pesanan" rows="4">{{ $pesanan->detail_pesanan }}</textarea>


                <label>Total Harga</label>

                <input type="number" name="total_harga" value="{{ $pesanan->total_harga }}" min="0">


                <button type="submit">
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

@endsection
