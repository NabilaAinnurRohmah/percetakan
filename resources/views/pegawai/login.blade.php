@extends('layouts.app')

@section('title', 'Login Pegawai')

@section('content')

    <div class="container">

        <div class="card" style="max-width: 450px; margin: 80px auto;">

            <h2>Login Pegawai</h2>

            @if (session('error'))
                <div class="alert error">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('pegawai.login.proses') }}">

                @csrf

                <label>Username</label>

                <input type="text" name="username" value="{{ old('username') }}">

                @error('username')
                    <small>{{ $message }}</small>
                @enderror

                <label>Password</label>

                <input type="password" name="password">

                @error('password')
                    <small>{{ $message }}</small>
                @enderror

                <button type="submit">
                    Login
                </button>

            </form>

        </div>

    </div>

@endsection
