<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>@yield('title', 'Sistem Percetakan')</title>
</head>

<body>

    @if (isset($gunakanSidebar) && $gunakanSidebar === true && session('pegawai_login'))
        <div class="app-layout">

            <aside class="app-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-logo">P</div>
                    <div>
                        <h2>Percetakan</h2>
                        <p>Sistem Pengelolaan</p>
                    </div>
                </div>

                <div class="sidebar-menu-title">MENU UTAMA</div>

                <nav class="sidebar-menu">
                    <a href="{{ route('pesanan.index') }}"
                        class="{{ request()->routeIs('pesanan.index', 'pesanan.create', 'pesanan.show', 'pesanan.edit') ? 'active' : '' }}">
                        <span class="sidebar-icon">▤</span>
                        <span>Data Pesanan</span>
                    </a>

                    <a href="{{ route('pesanan.riwayat') }}"
                        class="{{ request()->routeIs('pesanan.riwayat') ? 'active' : '' }}">
                        <span class="sidebar-icon">◷</span>
                        <span>Riwayat Pesanan</span>
                    </a>
                </nav>

                <div class="sidebar-bottom">
                    <div class="sidebar-user">
                        <div class="sidebar-user-avatar">P</div>
                        <div>
                            <strong>Pegawai</strong>
                            <span>Sudah login</span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('pegawai.logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-logout">
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <main class="app-main">
                @yield('content')
            </main>

        </div>
    @else
        <main class="public-main">
            @yield('content')
        </main>
    @endif

</body>

</html>
