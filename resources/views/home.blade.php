@extends('main')

@section('content')



@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif


    {{-- navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark p-0">
        <div class="container-fluid bg-dark">
            <a class="navbar-brand" href="/">
                <div class="d-flex flex-column container">
                    <h1 class="text-danger m-0"><b>JAWI</b></h1>
                    <span><b>Jawara Indonesia</b></span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {{-- PERUBAHAN: Margin diubah menjadi responsif (mx-lg-5 untuk layar besar, mx-2 untuk lebih kecil) --}}
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-lg-5 mx-2">
                        <a class=" hover-underline nav-link " aria-current="page" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item mx-lg-5 mx-2">
                        <a class="nav-link hover-underline" href="#about">About</a>
                    </li>
                    <li class="nav-item mx-lg-5 mx-2">
                        <a class="nav-link hover-underline" href="{{ url('/event') }}">Event</a>
                    </li>
                </ul>

                @guest
                    <form class="d-flex">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" ><img src="{{ asset('assets') }}/img/icon/logo-profile.png"
                                alt="Login" style="width: 25px"></a>
                    </form>
                @endguest

                @auth
                    {{-- PERUBAHAN: Sebaiknya ini bukan form, tapi dropdown menu untuk profil dan logout --}}
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets') }}/img/icon/logo-profile.png" alt="{{ Auth::user()->name }}" style="width: 25px">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            {{-- <li><a class="dropdown-item" href="#">Profil Saya</a></li> --}}
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/logout"> Logout</a>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    {{-- end of navbar --}}

    {{-- content --}}
    <div class="container-fluid pb-5" style="background: linear-gradient(135deg, #000000 0%, #1f1f1f 50%, #dc2626 100%);">
        {{-- PERUBAHAN: Menghapus class 'layout' yang tidak standar dan menggunakan 'container' untuk padding --}}
        <div class="container pt-5">
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-lg" role="alert">
                    <span>{{ session('status') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-lg" role="alert">
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- PERUBAHAN: Menggunakan align-items-center untuk menyejajarkan vertikal --}}
            <div class="row align-items-center mb-5" style="min-height: 80vh;">
                {{-- PERUBAHAN: Mengatur kolom agar full width di mobile (col-12), dan terbagi di layar besar (col-lg-7).
                             Teks diatur center di mobile (text-center) dan rata kiri di layar besar (text-lg-start). --}}
                <div class="col-lg-7 col-12 text-center text-lg-start">
                    {{-- PERUBAHAN: Menggunakan kelas display-1/display-2 yang responsif, bukan font-size 100px --}}
                    <h1 class="display-2 fw-bold text-light m-0">JAWARA</h1>
                    <h1 class="display-2 fw-bold m-0" style="color: #dc2626;">INDONESIA</h1>
                    
                    <p class="playfair-font desk-1 text-light fs-5 mt-4">
                        Jawara Indonesia atau di Sebut JAWI adalah platform digital yang dirancang khusus untuk mendukung penyelenggaraan turnamen Pencak Silat secara modern, efisien, dan profesional.
                    </p>
                    
                    {{-- PERUBAHAN: Lebar tombol dibuat lebih fleksibel, misal w-50 di mobile dan w-auto/w-25 di desktop --}}
                    <button type="button" class="text-white align-middle text-decoration-none btn btn-danger w-50 w-lg-25 btn-start mb-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="height: 50px;">Get Started</button>
                    
                    {{-- Modal Login --}}
                    @guest
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-center">
                                    <h5 class="modal-title" id="staticBackdropLabel">LOGIN</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('login') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="email" class="form-control" name="email" placeholder="EMAIL" style="height: 50px" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" class="form-control" name="password" placeholder="PASSWORD" style="height: 50px" required>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-danger w-100" style="height: 50px;">MASUK</button>
                                        </div>
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-secondary w-100" style="height: 50px;" data-bs-dismiss="modal">CLOSE</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="text-center">Belum memiliki akun? <a href="{{ url('/registMain') }}" class="text-danger">Daftar</a></div>
                                <div class="text-center mb-3">Lupa password? <a href="{{ route('password.request') }}" class="text-danger">Lupa password</a></div>
                            </div>
                        </div>
                    </div>
                    @endguest

                    {{-- PERBAIKAN: Modal untuk logout seharusnya tidak diperlukan, tombol logout langsung bekerja. Tapi jika ingin konfirmasi, bisa pakai ini --}}
                    @auth
                        <!-- Jika Anda ingin modal konfirmasi logout, bisa ditaruh di sini.
                             Namun, praktik yang lebih umum adalah langsung logout menggunakan form seperti di navbar. -->
                    @endauth
                    
                    <section id="about"></section>
                </div>
                {{-- PERUBAHAN: Kolom gambar dibuat tersembunyi di layar kecil (d-none d-lg-block) atau bisa juga dibuat di tengah (col-12) --}}
                <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center">
                     <img style="width: 300px" src="{{ asset('assets') }}/img/icon/logo-jawi2.png" alt="Logo Jawi">
                </div>
            </div>
        </div>
    </div>
        
    <!-- about -->
    <div class="container" style="margin-top: 60px">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="mb-5"><b>Tentang</b><b style="color: #dc2626"> JAWI</b></h1>
                <p class="fs-5 mb-5">Jawara Indonesia atau disebut JAWI adalah platform digital yang dirancang khusus untuk mendukung penyelenggaraan turnamen Pencak Silat secara modern, efisien, dan profesional.</p>
                <div class="container overflow-hidden">
                {{-- PERUBAHAN: Menambahkan col-md-6 agar di layar tablet tampil 2 kolom, bukan langsung 1 kolom --}}
                <div class="row gx-4">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="p-3 border bg-light hover-shadow hover-mencungul h-100"> {{-- Menambahkan h-100 agar tinggi card sama --}}
                            <div class="row p-2">
                                <div class="col-12 text-start mb-3">
                                    <svg class="btn text-light mb-3" style="background-color: #dc2626" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                                    </svg>
                                </div>
                                <div class="col-12 text-start mb-2">
                                    <h4><b>Manajemen Peserta</b></h4>
                                </div>
                                <div class="col-12 text-start">
                                    <p>Sistem pendaftaran dan pengelolaan peserta yang terintegrasi dan mudah digunakan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="p-3 border bg-light hover-shadow hover-mencungul h-100">
                             <div class="row p-2">
                                <div class="col-12 text-start mb-3">
                                    <svg class="btn text-light mb-3" style="background-color: #000000" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                                        <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z"/>
                                    </svg>
                                </div>
                                <div class="col-12 text-start mb-2">
                                    <h4><b>Digital Scoring</b></h4>
                                </div>
                                <div class="col-12 text-start">
                                    <p>Sistem penilaian digital real-time yang akurat dan transparan untuk setiap pertandingan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="p-3 border bg-light hover-shadow hover-mencungul h-100">
                             <div class="row p-2">
                                <div class="col-12 text-start mb-3">
                                    <svg class="btn text-light mb-3" style="background-color: #dc2626" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                                        <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                                    </svg>
                                </div>
                                <div class="col-12 text-start mb-2">
                                    <h4><b>Bracket Otomatis</b></h4>
                                </div>
                                <div class="col-12 text-start">
                                    <p>Pembuatan bracket pertandingan otomatis dengan algoritma yang fair dan efisien.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-5 text-light">
                        <div class="card p-3" style="background: linear-gradient(135deg, #000000 0%, #1f1f1f 50%, #dc2626 100%);">
                            <div class="card-body">
                                <div class="card-title mb-4">
                                    <h1><b>Visi Kami</b></h1>
                                </div>
                                <div class="card-text">
                                    JAWI bukan hanya software. Kami adalah partner digital Anda dalam menyukseskan setiap pertandingan Pencak Silat. Mari bangun masa depan olahraga Indonesia — dimulai dari sistem yang lebih cerdas.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end of about -->
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row justify-content-between g-4">
                {{-- PERUBAHAN: Bagian footer Anda sudah responsif, tidak ada perubahan signifikan yang diperlukan --}}
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <div class="h4 fw-bold text-danger mb-3">Jawara Indonesia</div>
                    <p class="text-muted">We look forward to working with you.</p>
                </div>
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <h4 class="h6 fw-semibold mb-3">Menu Utama</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#about" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#team" class="text-muted text-decoration-none">Our Team</a></li>
                        <li class="mb-2"><a href="#contact" class="text-muted text-decoration-none">Event</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <h4 class="h6 fw-semibold mb-3">Hubungi Kami</h4>
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                        <a href="https://www.instagram.com/jawaraindonesia.co.id?igsh=cDVqZTJkNGcxeDRv" class="social-icon text-white text-decoration-none fs-4">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="mailto:jawaraindonesiam@gmail.com" class="social-icon text-white text-decoration-none fs-4">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <a href="https://maps.app.goo.gl/yNrmtc3NSemCFCBs9" class="social-icon text-white text-decoration-none fs-4" target="_blank">
                            <i class="bi bi-house"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; 2025 Jawara Indonesia. All rights reserved.</p>
            </div>
        </div>
    </footer>
    {{-- end of content --}}

@endsection