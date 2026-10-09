@extends('layouts.app')

@section('title', 'Edit Pesanan')

@section('content')
    <nav>
        <strong>Website Pegawai - Percetakan</strong>
        <a href="{{ route('pesanan.index') }}">Kembali</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Edit Data Pesanan</h2>

            @if ($errors->any())
                <div class="alert error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pesanan.update', $pesanan->id_pesanan) }}">
                @csrf
                @method('PUT')

                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pesanan->nama_pelanggan) }}"
                    maxlength="100" required>

                <label>Nama Pesanan</label>
                <input type="text" name="nama_pesanan" value="{{ old('nama_pesanan', $pesanan->nama_pesanan) }}"
                    maxlength="500" required>

                <label>Jenis Pesanan</label>
                <input type="text"
                    value="{{ $pesanan->jenis_pesanan == 'langsung' ? 'Pesanan Langsung' : 'Pesanan Reguler' }}" readonly>

                <h3>Rincian Layanan</h3>
                <div id="daftar-rincian"></div>

                <button type="button" id="tambah-layanan">
                    + Tambah Layanan
                </button>

                <h3>Total: <span id="total-harga">Rp0</span></h3>

                <label>Catatan Pesanan</label>
                <textarea name="detail_pesanan" rows="3">{{ old('detail_pesanan', $pesanan->detail_pesanan) }}</textarea>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        const daftarHarga = @json($layanan);
        const daftarRincian = document.getElementById('daftar-rincian');
        let nomorRincian = 0;

        function rupiah(nilai) {
            return 'Rp' + Number(nilai).toLocaleString('id-ID');
        }

        function buatRincian(jenis = '', jumlah = 1) {
            const id = nomorRincian++;
            const kotak = document.createElement('div');

            kotak.className = 'rincian-item';
            kotak.style.cssText =
                'border:1px solid #ddd;padding:15px;margin:12px 0;border-radius:8px';

            const label = document.createElement('label');
            label.textContent = 'Jenis Layanan';

            const select = document.createElement('select');
            select.name = `rincian[${id}][jenis_layanan]`;
            select.required = true;

            const kosong = document.createElement('option');
            kosong.value = '';
            kosong.textContent = '-- Pilih Layanan --';
            select.appendChild(kosong);

            Object.entries(daftarHarga).forEach(([key, item]) => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = item.nama + ' - ' + rupiah(item.harga);
                option.selected = key === jenis;
                select.appendChild(option);
            });

            const labelJumlah = document.createElement('label');
            labelJumlah.textContent = 'Jumlah (lembar/unit)';

            const inputJumlah = document.createElement('input');
            inputJumlah.type = 'number';
            inputJumlah.name = `rincian[${id}][jumlah]`;
            inputJumlah.min = '1';
            inputJumlah.step = '1';
            inputJumlah.value = jumlah;
            inputJumlah.required = true;

            const harga = document.createElement('p');
            const subtotal = document.createElement('p');

            const tombolHapus = document.createElement('button');
            tombolHapus.type = 'button';
            tombolHapus.textContent = 'Hapus Layanan';

            function hitung() {
                const item = daftarHarga[select.value];
                const banyak = Math.max(0, parseInt(inputJumlah.value) || 0);
                const hargaSatuan = item ? item.harga : 0;
                const nilaiSubtotal = hargaSatuan * banyak;

                harga.textContent = 'Harga satuan: ' + rupiah(hargaSatuan);
                subtotal.textContent = 'Subtotal: ' + rupiah(nilaiSubtotal);
                kotak.dataset.subtotal = nilaiSubtotal;

                hitungTotal();
            }

            select.addEventListener('change', hitung);
            inputJumlah.addEventListener('input', hitung);

            tombolHapus.addEventListener('click', () => {
                kotak.remove();
                hitungTotal();
            });

            kotak.append(
                label, select,
                labelJumlah, inputJumlah,
                harga, subtotal, tombolHapus
            );

            daftarRincian.appendChild(kotak);
            hitung();
        }

        function hitungTotal() {
            let total = 0;

            daftarRincian.querySelectorAll('.rincian-item').forEach(item => {
                total += Number(item.dataset.subtotal || 0);
            });

            document.getElementById('total-harga').textContent = rupiah(total);
        }

        document.getElementById('tambah-layanan')
            .addEventListener('click', () => buatRincian());

        @php
            $rincianLama = old('rincian');

            if ($rincianLama === null) {
                $rincianLama = $pesanan->detailLayanan
                    ->map(function ($item) {
                        return [
                            'jenis_layanan' => $item->jenis_layanan,
                            'jumlah' => $item->jumlah,
                        ];
                    })
                    ->toArray();
            }

            if (empty($rincianLama)) {
                $rincianLama = [['jenis_layanan' => '', 'jumlah' => 1]];
            }
        @endphp

        const rincianLama = @json($rincianLama);

        rincianLama.forEach(item => {
            buatRincian(item.jenis_layanan || '', item.jumlah || 1);
        });
    </script>
@endsection
