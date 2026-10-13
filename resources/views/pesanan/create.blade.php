@extends('layouts.app')

@section('title', 'Tambah Pesanan')

@section('content')
    <nav class="order-nav">
        <strong>Website Pegawai - Percetakan</strong>
        <a href="{{ route('pesanan.index') }}">Kembali ke Data Pesanan</a>
    </nav>

    <div class="create-container">
        <div class="create-card">

            <div class="create-header">
                <div>
                    <h2>Tambah Data Pesanan</h2>
                    <p>Masukkan informasi pelanggan dan rincian layanan pesanan.</p>
                </div>

                <a href="{{ route('pesanan.index') }}" class="btn btn-detail">
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="order-alert alert-error">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul class="create-error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="create-form" method="POST" action="{{ route('pesanan.store') }}">
                @csrf

                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                    maxlength="100" placeholder="Masukkan nama pelanggan" required>

                <label for="nama_pesanan">Nama Pesanan</label>
                <input type="text" id="nama_pesanan" name="nama_pesanan" value="{{ old('nama_pesanan') }}"
                    placeholder="Contoh: Dokumen kuliah" maxlength="500" required>

                <label for="jenis_pesanan">Jenis Pesanan</label>
                <select id="jenis_pesanan" name="jenis_pesanan" required>
                    <option value="">-- Pilih Jenis Pesanan --</option>

                    <option value="langsung" {{ old('jenis_pesanan') == 'langsung' ? 'selected' : '' }}>
                        Pesanan Langsung
                    </option>

                    <option value="reguler" {{ old('jenis_pesanan') == 'reguler' ? 'selected' : '' }}>
                        Pesanan Reguler
                    </option>
                </select>

                <h3 class="create-section-title">Rincian Layanan</h3>
                <p class="create-section-description">
                    Pilih jenis layanan dan masukkan jumlahnya. Harga satuan,
                    subtotal, dan total akan dihitung secara otomatis.
                </p>

                <div id="daftar-rincian"></div>

                <div class="create-actions">
                    <button type="button" id="tambah-layanan" class="btn btn-add-service">
                        + Tambah Layanan
                    </button>
                </div>

                <div class="create-total">
                    <span>Total Pembayaran</span>
                    <strong id="total-harga">Rp0</strong>
                </div>

                <label for="detail_pesanan">Catatan Pesanan (Opsional)</label>
                <textarea id="detail_pesanan" name="detail_pesanan" rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('detail_pesanan') }}</textarea>

                <div class="create-actions">
                    <button type="submit" class="btn btn-save-create">
                        Simpan Pesanan
                    </button>

                    <a href="{{ route('pesanan.index') }}" class="btn btn-cancel-create">
                        Batal
                    </a>
                </div>
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
            harga.className = 'rincian-harga';

            const subtotal = document.createElement('p');
            subtotal.className = 'rincian-subtotal';

            const tombolHapus = document.createElement('button');
            tombolHapus.type = 'button';
            tombolHapus.textContent = 'Hapus Layanan';
            tombolHapus.className = 'btn btn-remove-service';

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
                label,
                select,
                labelJumlah,
                inputJumlah,
                harga,
                subtotal,
                tombolHapus
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

        document.getElementById('tambah-layanan').addEventListener('click', () => {
            buatRincian();
        });

        @php
            $rincianLama = old('rincian', [['jenis_layanan' => '', 'jumlah' => 1]]);

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
