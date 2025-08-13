<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Silat Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .modal-backdrop { backdrop-filter: blur(4px); }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen">
    <!-- Header -->
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
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-5">
                        <a class=" hover-underline nav-link " aria-current="page" href="{{ url('/home') }}">Home</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" href="#about">About</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link hover-underline" href="{{ url('/event/1') }}">Event</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <a class="nav-link" href="{{ url('/home') }}" data-bs-toggle="modal" data-bs-target="#staticBackdrop" ><img src="{{ asset('assets') }}/img/icon/logo-profile.png"
                            alt="lah" style="width: 25px"></a>
                    {{-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button> --}}
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">🔍 Filter Event</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Event</label>
                    <select id="statusFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Status</option>
                        <option value="Pendaftaran Dibuka">Pendaftaran Dibuka</option>
                        <option value="Segera Dimulai">Segera Dimulai</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="categoryFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Kategori</option>
                        <option value="Tanding">Tanding</option>
                        <option value="Tunggal">Tunggal</option>
                        <option value="Ganda">Ganda</option>
                        <option value="Regu">Regu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select id="monthFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Bulan</option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="resetFilter" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div id="eventsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Event cards will be populated by JavaScript -->
        </div>
    </div>

    <!-- Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                </div>
                
                <div id="modalContent">
                    <!-- Modal content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        // Sample event data
        const events = [
            {
                id: 1,
                title: "Kejuaraan Silat Nasional 2024",
                status: "Pendaftaran Dibuka",
                category: "Tanding",
                month: "Maret",
                date: "15-17 Maret 2024",
                location: "Jakarta",
                fee: "Rp 150.000",
                description: "Kejuaraan silat tingkat nasional dengan peserta dari seluruh Indonesia. Event ini menampilkan berbagai kategori tanding untuk semua tingkat usia.",
                classes: ["Putra Dewasa", "Putri Dewasa", "Putra Junior", "Putri Junior"],
                registrationStatus: "Dibuka",
                poster: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280' viewBox='0 0 200 280'%3E%3Cdefs%3E%3ClinearGradient id='grad1' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23dc2626;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23991b1b;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='280' fill='url(%23grad1)'/%3E%3Ctext x='100' y='40' font-family='Arial, sans-serif' font-size='16' font-weight='bold' text-anchor='middle' fill='white'%3EKEJUARAAN%3C/text%3E%3Ctext x='100' y='60' font-family='Arial, sans-serif' font-size='20' font-weight='bold' text-anchor='middle' fill='white'%3ESILAT NASIONAL%3C/text%3E%3Ctext x='100' y='80' font-family='Arial, sans-serif' font-size='24' font-weight='bold' text-anchor='middle' fill='white'%3E2024%3C/text%3E%3Ccircle cx='100' cy='140' r='40' fill='%23fbbf24' stroke='white' stroke-width='3'/%3E%3Ctext x='100' y='150' font-family='Arial, sans-serif' font-size='30' text-anchor='middle' fill='%23991b1b'%3E🏆%3C/text%3E%3Ctext x='100' y='200' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3E15-17 MARET%3C/text%3E%3Ctext x='100' y='220' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3EJAKARTA%3C/text%3E%3Ctext x='100' y='250' font-family='Arial, sans-serif' font-size='12' text-anchor='middle' fill='%23fbbf24'%3EPENDAFTARAN DIBUKA%3C/text%3E%3C/svg%3E"
            },
            {
                id: 2,
                title: "Festival Silat Tradisional",
                status: "Segera Dimulai",
                category: "Tunggal",
                month: "Februari",
                date: "28 Februari 2024",
                location: "Yogyakarta",
                fee: "Rp 100.000",
                description: "Festival yang menampilkan keindahan seni bela diri silat tradisional Indonesia dengan berbagai aliran dan perguruan.",
                classes: ["Tunggal Putra", "Tunggal Putri", "Tunggal Veteran"],
                registrationStatus: "Ditutup",
                poster: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280' viewBox='0 0 200 280'%3E%3Cdefs%3E%3ClinearGradient id='grad2' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23059669;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23064e3b;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='280' fill='url(%23grad2)'/%3E%3Ctext x='100' y='30' font-family='Arial, sans-serif' font-size='14' font-weight='bold' text-anchor='middle' fill='white'%3EFESTIVAL%3C/text%3E%3Ctext x='100' y='50' font-family='Arial, sans-serif' font-size='16' font-weight='bold' text-anchor='middle' fill='white'%3ESILAT TRADISIONAL%3C/text%3E%3Ccircle cx='100' cy='120' r='50' fill='%23fbbf24' stroke='white' stroke-width='3'/%3E%3Ctext x='100' y='135' font-family='Arial, sans-serif' font-size='35' text-anchor='middle' fill='%23064e3b'%3E🎭%3C/text%3E%3Cpath d='M60 180 Q100 160 140 180 Q100 200 60 180' fill='%23fbbf24'/%3E%3Ctext x='100' y='220' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3E28 FEBRUARI%3C/text%3E%3Ctext x='100' y='240' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3EYOGYAKARTA%3C/text%3E%3Ctext x='100' y='260' font-family='Arial, sans-serif' font-size='12' text-anchor='middle' fill='%23fbbf24'%3ESEGERA DIMULAI%3C/text%3E%3C/svg%3E"
            },
            {
                id: 3,
                title: "Turnamen Silat Pelajar",
                status: "Pendaftaran Dibuka",
                category: "Ganda",
                month: "April",
                date: "20-22 April 2024",
                location: "Bandung",
                fee: "Rp 75.000",
                description: "Turnamen khusus untuk pelajar SMP dan SMA se-Jawa Barat. Ajang untuk mengembangkan bakat muda dalam silat.",
                classes: ["Ganda SMP", "Ganda SMA", "Ganda Campuran"],
                registrationStatus: "Dibuka",
                poster: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280' viewBox='0 0 200 280'%3E%3Cdefs%3E%3ClinearGradient id='grad3' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%232563eb;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%231e40af;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='280' fill='url(%23grad3)'/%3E%3Ctext x='100' y='30' font-family='Arial, sans-serif' font-size='16' font-weight='bold' text-anchor='middle' fill='white'%3ETURNAMEN%3C/text%3E%3Ctext x='100' y='50' font-family='Arial, sans-serif' font-size='18' font-weight='bold' text-anchor='middle' fill='white'%3ESILAT PELAJAR%3C/text%3E%3Crect x='70' y='80' width='60' height='80' fill='%23fbbf24' stroke='white' stroke-width='2' rx='5'/%3E%3Ctext x='100' y='130' font-family='Arial, sans-serif' font-size='30' text-anchor='middle' fill='%231e40af'%3E🎓%3C/text%3E%3Ctext x='100' y='190' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3ESMP %26 SMA%3C/text%3E%3Ctext x='100' y='210' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3E20-22 APRIL%3C/text%3E%3Ctext x='100' y='230' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3EBANDUNG%3C/text%3E%3Ctext x='100' y='250' font-family='Arial, sans-serif' font-size='12' text-anchor='middle' fill='%23fbbf24'%3EPENDAFTARAN DIBUKA%3C/text%3E%3C/svg%3E"
            },
            {
                id: 4,
                title: "Piala Gubernur Silat",
                status: "Selesai",
                category: "Regu",
                month: "Januari",
                date: "10-12 Januari 2024",
                location: "Surabaya",
                fee: "Rp 200.000",
                description: "Kompetisi bergengsi tingkat provinsi dengan hadiah total ratusan juta rupiah. Event tahunan yang selalu dinanti.",
                classes: ["Regu Putra", "Regu Putri", "Regu Campuran"],
                registrationStatus: "Selesai",
                poster: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280' viewBox='0 0 200 280'%3E%3Cdefs%3E%3ClinearGradient id='grad4' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23b45309;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23451a03;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='280' fill='url(%23grad4)'/%3E%3Ctext x='100' y='30' font-family='Arial, sans-serif' font-size='18' font-weight='bold' text-anchor='middle' fill='white'%3EPIALA%3C/text%3E%3Ctext x='100' y='50' font-family='Arial, sans-serif' font-size='16' font-weight='bold' text-anchor='middle' fill='white'%3EGUBERNUR%3C/text%3E%3Ctext x='100' y='70' font-family='Arial, sans-serif' font-size='20' font-weight='bold' text-anchor='middle' fill='%23fbbf24'%3ESILAT%3C/text%3E%3Ccircle cx='100' cy='130' r='45' fill='%23fbbf24' stroke='white' stroke-width='3'/%3E%3Ctext x='100' y='145' font-family='Arial, sans-serif' font-size='35' text-anchor='middle' fill='%23451a03'%3E🏅%3C/text%3E%3Ctext x='100' y='200' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3E10-12 JANUARI%3C/text%3E%3Ctext x='100' y='220' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3ESURABAYA%3C/text%3E%3Ctext x='100' y='250' font-family='Arial, sans-serif' font-size='12' text-anchor='middle' fill='%23ef4444'%3ESELESAI%3C/text%3E%3C/svg%3E"
            },
            {
                id: 5,
                title: "Silat Championship 2024",
                status: "Pendaftaran Dibuka",
                category: "Tanding",
                month: "Mei",
                date: "5-7 Mei 2024",
                location: "Medan",
                fee: "Rp 125.000",
                description: "Kejuaraan silat dengan standar internasional. Kesempatan untuk bertanding dengan atlet-atlet terbaik.",
                classes: ["Kelas A", "Kelas B", "Kelas C", "Kelas Pemula"],
                registrationStatus: "Dibuka",
                poster: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280' viewBox='0 0 200 280'%3E%3Cdefs%3E%3ClinearGradient id='grad5' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23dc2626;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23450a0a;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='280' fill='url(%23grad5)'/%3E%3Ctext x='100' y='30' font-family='Arial, sans-serif' font-size='16' font-weight='bold' text-anchor='middle' fill='white'%3ESILAT%3C/text%3E%3Ctext x='100' y='50' font-family='Arial, sans-serif' font-size='18' font-weight='bold' text-anchor='middle' fill='%23fbbf24'%3ECHAMPIONSHIP%3C/text%3E%3Ctext x='100' y='70' font-family='Arial, sans-serif' font-size='24' font-weight='bold' text-anchor='middle' fill='white'%3E2024%3C/text%3E%3Ccircle cx='100' cy='130' r='40' fill='%23450a0a' stroke='%23fbbf24' stroke-width='3'/%3E%3Ctext x='100' y='145' font-family='Arial, sans-serif' font-size='30' text-anchor='middle' fill='%23fbbf24'%3E⚔️%3C/text%3E%3Ctext x='100' y='190' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3ESTANDAR INTERNASIONAL%3C/text%3E%3Ctext x='100' y='210' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3E5-7 MEI%3C/text%3E%3Ctext x='100' y='230' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3EMEDAN%3C/text%3E%3Ctext x='100' y='250' font-family='Arial, sans-serif' font-size='12' text-anchor='middle' fill='%23fbbf24'%3EPENDAFTARAN DIBUKA%3C/text%3E%3C/svg%3E"
            },
            {
                id: 6,
                title: "Open Tournament Silat",
                status: "Pendaftaran Dibuka",
                category: "Tunggal",
                month: "Juni",
                date: "15-16 Juni 2024",
                location: "Makassar",
                fee: "Rp 90.000",
                description: "Turnamen terbuka untuk semua perguruan silat. Format tunggal dengan juri internasional.",
                classes: ["Tunggal Bebas", "Tunggal Baku", "Tunggal Kreasi"],
                registrationStatus: "Dibuka",
                poster: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='280' viewBox='0 0 200 280'%3E%3Cdefs%3E%3ClinearGradient id='grad6' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%237c3aed;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%233730a3;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='280' fill='url(%23grad6)'/%3E%3Ctext x='100' y='30' font-family='Arial, sans-serif' font-size='16' font-weight='bold' text-anchor='middle' fill='white'%3EOPEN%3C/text%3E%3Ctext x='100' y='50' font-family='Arial, sans-serif' font-size='18' font-weight='bold' text-anchor='middle' fill='white'%3ETOURNAMENT%3C/text%3E%3Ctext x='100' y='70' font-family='Arial, sans-serif' font-size='20' font-weight='bold' text-anchor='middle' fill='%23fbbf24'%3ESILAT%3C/text%3E%3Ccircle cx='100' cy='130' r='45' fill='%23fbbf24' stroke='white' stroke-width='3'/%3E%3Ctext x='100' y='145' font-family='Arial, sans-serif' font-size='35' text-anchor='middle' fill='%233730a3'%3E🥇%3C/text%3E%3Ctext x='100' y='190' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3EJURI INTERNASIONAL%3C/text%3E%3Ctext x='100' y='210' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3E15-16 JUNI%3C/text%3E%3Ctext x='100' y='230' font-family='Arial, sans-serif' font-size='14' text-anchor='middle' fill='white'%3EMAKASSAR%3C/text%3E%3Ctext x='100' y='250' font-family='Arial, sans-serif' font-size='12' text-anchor='middle' fill='%23fbbf24'%3EPENDAFTARAN DIBUKA%3C/text%3E%3C/svg%3E"
            }
        ];

        let filteredEvents = [...events];

        function renderEvents(eventsToRender) {
            const container = document.getElementById('eventsContainer');
            container.innerHTML = '';

            eventsToRender.forEach(event => {
                const statusColor = {
                    'Pendaftaran Dibuka': 'bg-green-100 text-green-800',
                    'Segera Dimulai': 'bg-yellow-100 text-yellow-800',
                    'Selesai': 'bg-gray-100 text-gray-800'
                };

                const card = document.createElement('div');
                card.className = 'bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1';
                card.onclick = () => openModal(event);
                
                card.innerHTML = `
                    <div class="p-6">
                        <div class="mb-4 text-center">
                            <img src="${event.poster}" alt="Poster ${event.title}" class="w-24 h-32 mx-auto rounded-lg shadow-md object-cover">
                        </div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColor[event.status]}">${event.status}</span>
                            <span class="text-sm text-gray-500">${event.category}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">${event.title}</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <span class="mr-2">📅</span>
                                <span>${event.date}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-2">📍</span>
                                <span>${event.location}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-2">💰</span>
                                <span class="font-medium text-red-600">${event.fee}</span>
                            </div>
                        </div>
                        <button class="w-full mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            Lihat Detail
                        </button>
                    </div>
                `;
                
                container.appendChild(card);
            });

            if (eventsToRender.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada event ditemukan</h3>
                        <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
                    </div>
                `;
            }
        }

        function openModal(event) {
            const modal = document.getElementById('eventModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            modalTitle.textContent = event.title;

            const registrationButton = event.registrationStatus === 'Dibuka' 
                ? `<button onclick="registerEvent(${event.id})" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                     Daftar Sekarang
                   </button>`
                : `<button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed">
                     Pendaftaran ${event.registrationStatus}
                   </button>`;

            modalContent.innerHTML = `
                <div class="space-y-6">
                    <div class="text-center">
                        <div class="mb-4">
                            <img src="${event.poster}" alt="Poster ${event.title}" class="w-48 h-64 mx-auto rounded-lg shadow-lg object-cover">
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">${event.title}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">📅 Informasi Event</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><strong>Tanggal:</strong> ${event.date}</p>
                                <p><strong>Lokasi:</strong> ${event.location}</p>
                                <p><strong>Kategori:</strong> ${event.category}</p>
                                <p><strong>Status:</strong> ${event.status}</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">💰 Biaya Pendaftaran</h4>
                            <p class="text-2xl font-bold text-red-600">${event.fee}</p>
                            <p class="text-sm text-gray-600 mt-1">Per peserta</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">📝 Deskripsi</h4>
                        <p class="text-gray-600">${event.description}</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">🥋 Kelas Pertandingan</h4>
                        <div class="flex flex-wrap gap-2">
                            ${event.classes.map(cls => `<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">${cls}</span>`).join('')}
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">📋 Status Pendaftaran</h4>
                        <p class="text-gray-600">Status: <strong>${event.registrationStatus}</strong></p>
                    </div>
                    
                    <div class="pt-4">
                        ${registrationButton}
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function registerEvent(eventId) {
            const event = events.find(e => e.id === eventId);
            alert(`Terima kasih! Anda berhasil mendaftar untuk event "${event.title}". Tim kami akan menghubungi Anda segera untuk konfirmasi pembayaran.`);
            closeModal();
        }

        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const categoryFilter = document.getElementById('categoryFilter').value;
            const monthFilter = document.getElementById('monthFilter').value;

            filteredEvents = events.filter(event => {
                return (!statusFilter || event.status === statusFilter) &&
                       (!categoryFilter || event.category === categoryFilter) &&
                       (!monthFilter || event.month === monthFilter);
            });

            renderEvents(filteredEvents);
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('monthFilter').value = '';
            filteredEvents = [...events];
            renderEvents(filteredEvents);
        }

        // Event listeners
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('monthFilter').addEventListener('change', applyFilters);
        document.getElementById('resetFilter').addEventListener('click', resetFilters);
        document.getElementById('closeModal').addEventListener('click', closeModal);

        // Close modal when clicking outside
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Initial render
        renderEvents(events);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96e00edbe42f9fb9',t:'MTc1NTAwMjYwMy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
