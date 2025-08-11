@extends('main')

@section('content')
    {{-- navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/home"><img src="{{ asset('assets') }}/img/icon/logo-jawi2.png" alt="kocak"
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
            <p class="playfair-font text-light" style="font-size: 100px;margin-left:100px ">Kejuaraan Pencak Silat</p>
         </div>
    </div>  
    </div>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="row mt-5">
                <div class="img col-5">
                <img class="w-75" src="{{ asset('assets') }}/img/poster/poster-kejurcab-pagarnusa.jpg" alt="lah" style="margin-left: 100px">
            </div>
            <div class="col-6 mt-5">
                <h3>Deskripsi</h3>
                <p class="playfair-font">Pencak silat merupakan seni beladiri tradisional yang memiliki
                                        berbagai ketentuan, keselarasan, keseimbangan, keserasian
                                        antara wirama, wirasa, dan wiraga. Juga menanamkan sikap
                                        berbudi pekerti yang luhur serta pengamalan falsafah Silat.
                                        Pagar Nusa ialah sebuah organisasi yang mempunyai tujuan
                                        untuk membentuk suatu wadah dibawah naungan Nahdlatul
                                        Ulama yang khusus mengembangkan seni bela diri pencak
                                        silat. Di sisi lain tumbuh berbagai perguruan pencak silat
                                        dengan segala keanekaragamannya berdasarkan segi agama,
                                        aqidah, kepercayaan dan jurus – jurus lainnya. Dalam organisasi
                                        ini diharuskan mengajarkan materi jurus baku dari PSNU Pagar
                                        Nusa sebagai bukti bahwa perguruan tradisional tersebut
                                        tergabung dalam PSNU Pagar Nusa.

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
                            <h2 class="h4 fw-bold mb-3">Kategori :</h2>
                            <p class="text-dark mb-2">Ketentuan Kategori Tanding: <a href="{{ url('/tanding-pdf') }}">Kelas Tanding PDF</a></p>
                            <p class="text-dark mb-2">Ketentuan Kategori Seni dan Jurus Paket: <a href="{{ url('/seni-juruspaket-pdf') }}">Kelas Seni dan Jurus Paket PDF</a></p>
                            <p class="text-dark mb-2">Ketentuan Kelas: <a href="{{ url('/ketentuankelas-pdf') }}">Ketentuan Kelas</a></p>
                        </div>

                        <!-- Berkas Content -->
                        <div id="berkas-content" class="tab-content d-none">
                            <h2 class="h4 fw-bold mb-3">Berkas yang harus disiapkan</h2>
                            <ul>
                                <li>Fotokopi Kartu Keluarga</li>
                                <li>Kartu Tanda Anggota Pagar Nusa</li>
                                <li>Biodata Atlet</li>
                                <li>Formulir Kontingen</li>
                            </ul>
                            {{-- <p class="text-muted">Ini adalah konten untuk berkas. Di sini Anda dapat mengelola file dan dokumen.</p> --}}
                        </div>

                        <!-- Kegiatan Content -->
                        <div id="kegiatan-content" class="tab-content d-none">
                            <h2 class="h4 fw-bold mb-3">Kegiatan : </h2>
                            <p class="text-dark m-0">Pendaftaran</p>
                            <p class="text-muted m-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Online</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>7 Agustus 2025 - 23 September 2025</p>
                            <p class="text-dark m-0">Technical Meeting (Gedung PCNU Sidoarjo)</p>
                            <p class="text-muted m-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Jl. Erlangga, Kapasan, Sidokare, Kec. Sidoarjo,
Kabupaten Sidoarjo, Jawa Timur 61214.</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>23 September 2025</p>
                            <p class="text-dark m-0">Pelaksaan (Gedung Olahraga Sidoarjo)</p>
                            <p class="text-muted mb-0"><i class="bi bi-geo-alt text-danger pe-2"></i>Jl. Pahlawan, Wismasarinadi, Magersari, Kec. Sidoarjo,
Kabupaten Sidoarjo, Jawa Timur 61213.
</p>
                            <p class="text-muted "><i class="bi bi-calendar3 text-danger pe-2"></i>3 - 5 Oktober 2025</p>
                        </div>
                    </div>
                </div>
                <div class="containers">
                    <div class="d-flex justify-content-center mt-3">
                        <div class="card border-light mt-5 w-75 shadow p-3 mb-5 bg-body ">
                            <h1 class="playfair-font text-center">Pendaftaran Peserta</h1>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="bi bi-person text-danger me-1"></i>CP : Mas Hoiri <a href="tel:0877-0242-6911">
                                    (0877-0242-6911)</a></li>
                                <li class="list-group-item"><i class="bi bi-person text-danger me-1"></i>CP : Mas Feris <a href="tel:0897-6464-461">
                                    (0897-6464-461)</a></li>
                
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