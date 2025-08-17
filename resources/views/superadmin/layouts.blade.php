<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Aurora</title>

    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Google Fonts: Inter (font yang sangat bersih untuk UI) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --aurora-bg: #f8f9fc;
            --aurora-text: #5a5c69;
            --aurora-primary: #4e73df;
            --aurora-secondary: #858796;
            --aurora-navbar-bg: rgba(255, 255, 255, 0.5); /* Kunci efek kaca */
            --aurora-card-bg: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--aurora-bg);
            color: var(--aurora-text);
        }

        /* --- Aurora Navbar --- */
        .aurora-navbar {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1030;
            padding: 0.75rem 2rem;
            background: var(--aurora-navbar-bg);
            backdrop-filter: blur(10px); /* Efek Kaca Buram */
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        /* Efek dinamis saat di-scroll */
        .aurora-navbar.scrolled {
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .aurora-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--aurora-primary);
        }
        .aurora-navbar .navbar-brand .bi {
            margin-right: 0.5rem;
        }

        /* Link Navigasi */
        .aurora-navbar .nav-link {
            color: var(--aurora-secondary);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            position: relative;
            transition: color 0.2s ease, background-color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .aurora-navbar .nav-link:hover {
            color: var(--aurora-primary);
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        /* Indikator untuk link aktif */
        .aurora-navbar .nav-link.active {
            color: var(--aurora-primary);
            font-weight: 600;
        }
        .aurora-navbar .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 1rem;
            right: 1rem;
            height: 3px;
            background: var(--aurora-primary);
            border-radius: 3px;
        }

        /* --- Konten Utama --- */
        .main-content {
            padding: 2rem;
        }

        /* Header Halaman (di bawah navbar) */
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        /* Mengganti gaya default card agar sesuai tema */
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }
    </style>
    @stack('styles')
</head>
<body>

<header class="aurora-navbar" id="mainNavbar">
    <nav class="container-fluid d-flex justify-content-between align-items-center">
        {{-- Brand/Logo --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('superadmin.dashboard') }}">
            <i class="bi bi-shield-shaded"></i>
            <span>Aurora</span>
        </a>

        {{-- Menu Navigasi Utama --}}
        <ul class="nav">
            <li class="nav-item">
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
                <a class="nav-link {{ Request::is('superadmin/kelola-admin*') ? 'active' : '' }}" href="{{ route('superadmin.kelola_admin') }}">
                    <i class="bi bi-person-fill-gear"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>
        </ul>

        {{-- Profil Pengguna --}}
        <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5 me-1"></i>
                <span class="d-none d-lg-inline">Nama Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person-vcard me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </nav>
</header>


<main class="main-content">
    <div class="container-fluid">
        {{-- Header Halaman --}}
        <div class="page-header">
            <h1>@yield('title', 'Dashboard')</h1>
            {{-- Anda bisa menambahkan breadcrumb di sini jika perlu --}}
        </div>
        
        {{-- Flash Messages --}}
        @include('superadmin.partials.flash_messages') {{-- Mengasumsikan flash message dipisah --}}

        {{-- Area Konten Dinamis --}}
        <div class="content-body">
            @yield('content')
        </div>
    </div>
</main>


{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Skrip kecil untuk efek dinamis navbar saat di-scroll
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('mainNavbar');
        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        }
    });
</script>
@stack('scripts')
</body>
</html>