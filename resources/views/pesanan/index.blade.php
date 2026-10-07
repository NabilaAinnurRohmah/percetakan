@extends('layouts.app')

@section('title', 'Data Pesanan')

@section('content')

    <nav>

        <div>
            <strong>Website Pegawai - Percetakan</strong>
        </div>

        <div>
            {{ session('nama_pegawai') }}

            <a href="{{ route('pegawai.logout') }}">
                Logout
            </a>
        </div>

    </nav>

    <div class="container">

        <div class="card">

            <h2>Mengelola Data Pesanan</h2>

            @if (session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('pesanan.create') }}" class="btn">
                + Tambah Pesanan
            </a>

            <br><br>

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pesanan as $item)
                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->kode_pesanan ?? '-' }}
                            </td>

                            <td>
                                {{ $item->nama_pesanan }}
                            </td>

                            <td>
                                {{ $item->pelanggan->nama ?? '-' }}
                            </td>

                            <td>
                                {{ ucfirst($item->jenis_pesanan) }}
                            </td>

                            <td>
                                {{ str_replace('_', ' ', ucfirst($item->status)) }}
                            </td>

                            <td>

                                <a href="{{ route('pesanan.show', $item->id_pesanan) }}" class="btn">
                                    Detail
                                </a>

                                <a href="{{ route('pesanan.edit', $item->id_pesanan) }}" class="btn btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('pesanan.destroy', $item->id_pesanan) }}" method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-danger"
                                        onclick="return confirm('Hapus pesanan ini?')">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
