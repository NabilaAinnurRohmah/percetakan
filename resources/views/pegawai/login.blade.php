@extends('layouts.app')

@section('title', 'Login Pegawai dan Pemilik')

@section('content')
    <div class="login-page">
        <div class="login-card">

            <div class="login-header">
                <div class="login-logo">P</div>
                <h2>Login Pegawai</h2>
                <p>
                    Silakan masuk menggunakan akun pegawai
                    untuk mengelola pesanan percetakan.
                </p>
            </div>

            @if (session('error'))
                <div class="login-alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="login-alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form class="login-form" method="POST" action="{{ route('pegawai.login.proses') }}">
                @csrf

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                    placeholder="Masukkan username" autocomplete="username" required autofocus>

                @error('username')
                    <div class="login-error">{{ $message }}</div>
                @enderror

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password"
                    autocomplete="current-password" required>

                @error('password')
                    <div class="login-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="login-button">
                    Login
                </button>
            </form>

            <div class="login-footer">
                Sistem Informasi Pesanan Percetakan
            </div>

        </div>
    </div>
@endsection
