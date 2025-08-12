@extends('main')

@section('content')
    {{-- navbar --}}

    {{-- {{ $data }} --}}

    @if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
    @elseif(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
    @endif

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid bg-light">
            <a class="navbar-brand" href="/"><img src="{{ asset('assets') }}/img/icon/logo-jawi2.png" alt="kocak"
                    style="width: 100px; height: 80px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-5">
                        <a class=" hover-underline nav-link " aria-current="page" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" href="#about">About</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" href="#event">Event</a>
                    </li>
                    {{-- <li class="nav-item mx-5 dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Register
                        </a>
                        <ul class="dropdown-menu " aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Peserta</a></li>
                            <li><a class="dropdown-item" href="#">Juri</a></li>
                            <li><a class="dropdown-item" href="#">Dewan</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li> --}}
                </ul>
                <form class="d-flex">
                    <a class="nav-link" href="/" data-bs-toggle="modal" data-bs-target="#staticBackdrop" ><img src="{{ asset('assets') }}/img/icon/logo-profile.png"
                            alt="lah" style="width: 25px"></a>
                    {{-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button> --}}
                </form>
            </div>
        </div>
    </nav>
    {{-- end of navbar --}}
    {{-- content --}}
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-7" style="margin-top: 100px">
                <p class="playfair-font" style="font-size: 100px;margin-left:200px "><b>JAWARA INDONESIA</b></p>
                <p class="playfair-font" style="margin-left:200px">Jawara Indonesia atau di Sebut JAWI adalah platform digital
 yang dirancang khusus untuk mendukung penyelenggaraan
 turnamen Pencak Silat secara modern, efisien, dan
 profesional. 
                </p>
                    <button type="button" class="text-white align-middle text-decoration-none btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="margin-left:200px; height: 50px;">Get Started</button>
                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-center">
                            <h5 class="modal-title" id="staticBackdropLabel">LOGIN</h5>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('login') }}" method="post">
                                @csrf
                                <div class="mb-3 d-flex justify-content-center">
                                    <input type="email" class="form-control mt-3" id="exampleFormControlInput1" name="email" placeholder="EMAIL" style="height: 50px">
                                </div>
                                <div class="mb-3 d-flex justify-content-center">
                                    <input type="password" class="form-control" id="exampleFormControlInput1" name="password" placeholder="PASSWORD" style="height: 50px">
                            </div>
                            <div class="mb-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger w-100" style="height: 50px;">MASUK</button>
                            </div>
                            <div class="mb-3 d-flex justify-content-center">
                                <button type="button" class="btn btn-secondary w-100" style="height: 50px;" data-bs-dismiss="modal">CLOSE</button>
                            </div>
                            </form>
                        </div>
                        <div class="text-center">Belum memiliki akun? <a href="{{ url('/registMain') }}" class="text-danger">Daftar</a>Atau</div>
                        <div class="text-center mb-3">Lupa password? <a href="{{ route('password.request') }}" class="text-danger">Lupa password</a></div>
                    </div>
                </div>
                </div>
                <section id="about">
                    </section>
                
            </div>
            <div class="col-5 text-center mt-5">
                <div class="mx-auto" style="margin-top: 50px;width: 90px; height: 100px"><img style="width: 200px"
                        src="{{ asset('assets') }}/img/icon/logo-jawi2.png" alt="lah"></div>
            </div>
        </div>
        <!-- about -->
        
        <div class="container" style="margin-top: 300px;">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>About Us</h1>
                    
                    <p style="font-size:20px">Kami hadir sebagai solusi menyeluruh — mulai dari
 manajemen peserta, penjadwalan pertandingan, hingga
 sistem digital scoring real-time yang akurat dan transparan.
 Kami percaya bahwa teknologi dapat membawa semangat
 kompetisi ke level yang lebih tinggi. Dengan fitur seperti
 bracket otomatis, pengelolaan juri dan arena, serta rekap
 nilai otomatis, JAWI membantu panitia fokus pada kualitas
 acara, bukan kerumitan teknis.</p>
 <p style="font-size:20px">Digunakan untuk event skala lokal hingga nasional, sistem
 kami mendukung multi-peran (operator, juri, dewan,
 penonton) dan bisa diakses secara fleksibel. Kami
 berkomitmen menghadirkan pengalaman turnamen yang
 adil, transparan, dan berkelas.
 JAWI bukan hanya software. Kami adalah partner digital
 Anda dalam menyukseskan setiap pertandingan Pencak
 Silat. Mari bangun masa depan olahraga Indonesia — dimulai
 dari sistem yang lebih cerdas.</p>
                </div>
            </div>
        </div>
        <!-- end of about -->
        <!-- Event -->
        
        <div class="container" style="margin-top: 300px;">
            <section id="event">
                    </section>
            <div class="row mt-5">
            <div class="col-12 text-center">
                
                <h2>Daftar Event</h2>
                <div class="row mb-5">
                    @foreach ($data as $d)
                    <div class="col-4 mt-5">
                        <div class="img">
                            <a href="{{ url('/event/'. $d['slug']) }}"><img class="w-75 hover-shadow hover-mencungul" src="{{ asset('assets') }}/img/poster/{{ $d['image'] }}" alt=""></a>
                            <p class="mb-0 mt-4" >By: {{ $d['penyelenggara'] }}</p>
                            <h3 class="mt-1 text-center">{{ $d['name'] }}</h3>
                            <a href="{{ url('/event/1') }}" class="text-decoration-none">Pendaftaran</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        </div>
        <!-- end of Event -->
    </div>
    {{-- <!-- footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container">
            <div class="row py-4">
                <div class="col-md-6">
                    <h5>We look forward to working with you</h5>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Contact Us</h5>
                    <p>Email: <a href="mailto:info@example.com" class="text-white">info@example.com</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- end of footer --> --}}
    <!-- Footer -->
    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row justify-content-between g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="h4 fw-bold text-brand-red mb-3">Jawara Indonesia</div>
                    <p class="text-muted">We look forward to working
with you.</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h4 class="h6 fw-semibold mb-3">Menu Utama</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#about" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#team" class="text-muted text-decoration-none">Our Team</a></li>
                        <li class="mb-2"><a href="#contact" class="text-muted text-decoration-none">Event</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h4 class="h6 fw-semibold mb-3">Hubungi Kami</h4>
                    <div class="d-flex gap-2">
                        <a href="https://www.instagram.com/jawaraindonesia.co.id?igsh=cDVqZTJkNGcxeDRv" class="social-icon text-white text-decoration-none">
                            <i class="bi bi-instagram text-light"></i>
                        </a>
                        {{-- <a href="#" class="social-icon text-white text-decoration-none">
                            <i class="bi bi-facebook text-light"></i>
                        </a> --}}
                        <a href="mailto:jawaraindonesiam@gmail.com" class="social-icon text-white text-decoration-none">
                            <i class="bi bi-envelope text-light"></i>
                        </a>
                        <a href="https://maps.app.goo.gl/yNrmtc3NSemCFCBs9" class="social-icon text-white text-decoration-none" target="_blank">
                            <i class="bi bi-house text-light"></i>
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
