@extends('main')

@section('content')
    {{-- navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img src="{{ asset('assets') }}/img/icon/logo-jawi2.png" alt="kocak"
                    style="width: 100px; height: 80px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" href="#">About</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" href="#">Contact</a>
                    </li>
                    <li class="nav-item mx-5 dropdown">
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
                    </li>
                </ul>
                <form class="d-flex">
                    <a class="nav-link" href="/operator"><img src="{{ asset('assets') }}/img/icon/logo-profile.png"
                            alt="lah" style="width: 25px"></a>
                    {{-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button> --}}
                </form>
            </div>
        </div>
    </nav>
    {{-- end of navbar --}}
    
    <div class="container-fluid mt-5">
        <div class="row bg-dark">
        <div class="col-6 mt-5">
            <p class="playfair-font text-light" style="font-size: 100px;margin-left:100px ">{{ $event->name }}</p>
         </div>
    </div>  
    </div>


    <div class="container-fluid">

    {{-- {{ $event }} --}}


        <div class="row mb-5">
            <div class="row mt-5">
                <div class="img col-5">
                <img class="w-75" src="{{ asset('assets') }}/img/poster/{{ $event->image }}" alt="lah" style="margin-left: 100px">
            </div>
            <div class="col-6 mt-5">
                <h3>Deskripsi</h3>
                <p class="playfair-font">{!! $event->desc !!}

                </p>
                
                <!-- Tab Buttons -->
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <button 
                        id="kategori-btn" 
                        class="btn btn-outline-danger tab-button w-100"
                        onclick="showTab('kategori')"
                    >
                        Kategori
                    </button>
                    <button 
                        id="berkas-btn" 
                        class="btn btn-outline-danger tab-button w-100"
                        onclick="showTab('berkas')"
                    >
                        Berkas
                    </button>
                    <button 
                        id="kegiatan-btn" 
                        class="btn btn-outline-danger tab-button w-100"
                        onclick="showTab('kegiatan')"
                    >
                        Kegiatan
                    </button>
                </div>

                <!-- Content Area -->
                <div class="card shadow-sm">
                    <div class="card-body text-start p-4">
                        <!-- Kategori Content -->
                        <div id="kategori-content" class="tab-content">
                            <h2 class="h4 fw-bold mb-3">Kategori : </h2>

                            {!! $event->kategori !!}
                        </div>

                        <!-- Berkas Content -->
                        <div id="berkas-content" class="tab-content d-none">
                            <h2 class="h4 fw-bold mb-3">Berkas : </h2>

                            {!! $event->berkas !!}
                            {{-- <p class="text-muted">Ini adalah konten untuk berkas. Di sini Anda dapat mengelola file dan dokumen.</p> --}}
                        </div>

                        <!-- Kegiatan Content -->
                        <div id="kegiatan-content" class="tab-content d-none">
                            <h2 class="h4 fw-bold mb-3">Kegiatan : </h2>

                            {!! $event->kegiatan !!}

                        </div>
                    </div>
                </div>
                <div class="containers">
                    <div class="d-flex justify-content-center mt-3">
                        <div class="card border-light mt-5 w-75 shadow p-3 mb-5 bg-body ">
                            <h1 class="playfair-font text-center">Pendaftaran Peserta</h1>
                            <ul class="list-group list-group-flush">

                                @foreach($event->eventRoles->where('type', 'CP') as $userEvent)

                                <li class="list-group-item"><i class="bi bi-person text-danger me-1"></i>CP : {{ $userEvent->user->nama_lengkap }} <a href="tel:0877-0242-6911">
                                    ({{ $userEvent->user->no_telp }})</a></li>

                                @endforeach
                
                                {{-- <li class="list-group-item"><i class="bi bi-person text-danger me-1"></i>CP : Mas Feris(0897-6464-461)</li> --}}
                                {{-- <li class="list-group-item"><i class="bi bi-person text-danger"></i>CP</li> --}}
                            </ul>
                            {{-- <a href="" class="btn btn-danger mt-3">Masuk</a> --}}
                            <a href="{{ url('/kontingen') }}" class="btn btn-danger mt-3">Masuk</a>
                            <a href="{{ url('/datapeserta') }}" class="btn btn-outline-danger my-3">Data Peserta</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
                
            
       
    </div>
        <div class="half-circle">
            <div class="logo-container">
                <img class="rounded fixed-bottom" src="{{ asset('assets') }}/img/icon/logo-jawi2.png" alt="lah" style="width: 100px; height: 100px; margin-left: 1350px;margin-bottom: 25px;">
            </div>
        </div>   
        </div>
    </div>

    <!-- Footer -->
    <div class="footer text-light bg-dark  pt-3 mt-5">
        <div class="d-flex justify-content-center">
            <h4 class="text-center">Jawara Indonesia</h4>
            
        </div>
        <div class="d-flex justify-content-center">
            <p class="text-center">Temukan pengalaman lebih di setiap event kejuaraan!</p>
            
        </div>
        <div class="d-flex justify-content-center py-4 px-5">
            <div class="col-md-6 ">
                <h5>About Us</h5>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat officia suscipit deleniti cum
                    fugit sequi culpa, fugiat quis magnam amet quos, officiis ad repellat vero fuga laudantium
                    voluptatum ullam rem iusto dicta?</p>
            </div>
            <div class="col-md-6">
                <h5>Contact Us</h5>
                <p>Email: <a href="mailto:info@example.com" class="text-light">info@example.com</a></p>
            </div>
        </div>
    </div>
    <!-- end of footer -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showTab(tabName) {
            // Sembunyikan semua konten
            const allContent = document.querySelectorAll('.tab-content');
            allContent.forEach(content => {
                content.classList.add('d-none');
            });

            // Hapus kelas aktif dari semua tombol
            const allButtons = document.querySelectorAll('.tab-button');
            allButtons.forEach(button => {
                button.classList.remove('btn-danger');
                button.classList.add('btn-outline-danger');
            });

            // Tampilkan konten yang dipilih
            document.getElementById(tabName + '-content').classList.remove('d-none');

            // Aktifkan tombol yang dipilih
            const activeButton = document.getElementById(tabName + '-btn');
            activeButton.classList.remove('btn-outline-danger');
            activeButton.classList.add('btn-danger');
        }

        // Inisialisasi tab pertama
        document.addEventListener('DOMContentLoaded', function() {
            showTab('kategori');
        });
    </script>
    
    <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96b01fed259df87e',t:'MTc1NDQ5OTk4Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>  
@endsection