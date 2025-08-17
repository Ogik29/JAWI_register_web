<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Mengatur font utama */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
        }

        /* Styling untuk sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background-color: #2c3e50; /* Warna biru tua */
            padding: 1.5rem;
            transition: all 0.3s;
            z-index: 100;
        }

        .sidebar .sidebar-brand {
            font-size: 1.8rem;
            font-weight: 600;
            color: #ecf0f1; /* Warna putih keabuan */
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
        }

        .sidebar .sidebar-brand .bi {
            font-size: 2rem;
            margin-right: 0.8rem;
        }

        /* Styling untuk link navigasi di sidebar */
        .sidebar-nav .nav-link {
            color: #bdc3c7; /* Warna abu-abu terang */
            font-size: 1.05rem;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-nav .nav-link:hover {
            background-color: #34495e; /* Warna biru sedikit lebih terang */
            color: #ffffff;
        }

        /* Style untuk link yang sedang aktif */
        .sidebar-nav .nav-link.active {
            background-color: #3498db; /* Warna biru cerah */
            color: #ffffff;
            font-weight: 500;
        }

        .sidebar-nav .nav-link .bi {
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        /* Styling untuk area konten utama */
        .main-content {
            margin-left: 260px; /* Sesuaikan dengan lebar sidebar */
            padding: 2rem;
            transition: margin-left 0.3s;
        }

        /* Untuk tampilan mobile, sidebar disembunyikan */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px; /* Sembunyikan sidebar */
            }
            .main-content {
                margin-left: 0;
            }
            /* Style untuk tombol toggle sidebar di mobile */
            .sidebar-toggle {
                display: block !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <a class="sidebar-brand text-decoration-none" href="{{ route('superadmin.dashboard') }}">
            <i class="bi bi-shield-lock-fill"></i>
            <span>SuperAdmin</span>
        </a>

        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                {{-- Request::is() untuk mendeteksi URL yang sedang aktif --}}
                <a class="nav-link {{ Request::is('superadmin/dashboard*') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('superadmin/tambah-event*') ? 'active' : '' }}" href="{{ route('superadmin.tambah_event') }}">
                    <i class="bi bi-plus-square-fill"></i>
                    <span>Tambah Event</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('superadmin/kelola-event*') ? 'active' : '' }}" href="{{ route('superadmin.kelola_event') }}">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Kelola Event</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('superadmin') && !Request::is('superadmin/*') ? 'active' : '' }}" href="{{ route('superadmin.kelola_admin') }}">
                    <i class="bi bi-person-fill-gear"></i>
                    <span>Admin Index</span>
                </a>
            </li>
        </ul>
    </aside>


    

    <!-- Main Content -->
    <main class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 rounded">
            <div class="container-fluid">
                {{-- Tombol ini bisa untuk toggle sidebar di mobile --}}
                <button class="btn btn-primary d-lg-none sidebar-toggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                Nama Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        {{-- ====================================================== --}}
        {{-- TAMBAHKAN KODE FLASH MESSAGE DI SINI --}}
        {{-- ====================================================== --}}
        <div class="container-fluid px-0"> {{-- Bungkus agar alert tidak terlalu lebar --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Gagal!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Opsional: Jika Anda butuh validasi error dari Validator --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terjadi Kesalahan!</strong> Harap periksa kembali isian Anda.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        {{-- ====================================================== --}}
        {{-- AKHIR KODE FLASH MESSAGE --}}
        {{-- ====================================================== --}}

        
        {{-- Area konten dinamis --}}
        <div class="content-body">
            @yield('content')
        </div>
    </main>
</div>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>