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
            {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> --}}
            {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-5"><a class="hover-underline nav-link" href="{{ url('/home') }}">Home</a></li>
                    <li class="nav-item mx-5"><a class="nav-link hover-underline" href="#about">About</a></li>
                    <li class="nav-item mx-5"><a class="nav-link hover-underline" href="{{ url('/event') }}">Event</a></li>
                    @auth    
                        <li class="nav-item mx-lg-5 mx-2"><a class="nav-link hover-underline" href="{{ url('/datapeserta') }}">Data Peserta</a></li>
                    @endauth
                </ul>
                <form class="d-flex">
                    <a class="nav-link" href="{{ url('/') }}" data-bs-toggle="modal" data-bs-target="#staticBackdrop" ><img src="{{ asset('assets') }}/img/icon/logo-profile.png" alt="lah" style="width: 25px"></a>
                </form>
            </div> --}}
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">🔍 Filter Event</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Event</label>
                    <select id="statusFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Status</option>
                        <option value="1">Pendaftaran Dibuka</option>
                        <option value="0">Belum Dibuka</option>
                        <option value="2">Selesai</option>
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
                    <button id="resetFilter" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition duration-200">Reset Filter</button>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div id="eventsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>

    <!-- Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">×</button>
                </div>
                <div id="modalContent"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    @php
        $transformedEvents = [];
        $bulanIndonesia = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

        foreach ($events as $event) {
            $tglMulai = \Carbon\Carbon::parse($event->tgl_mulai_tanding);
            $tglSelesai = \Carbon\Carbon::parse($event->tgl_selesai_tanding);
            $formattedDate = $tglMulai->format('j') . ' - ' . $tglSelesai->format('j F Y');
            $formattedDate = str_replace(array_keys($bulanIndonesia), array_values($bulanIndonesia), $formattedDate);

            $registrationStatus = 'Ditutup';
            if ($event->status == 1) { $registrationStatus = 'Dibuka'; } 
            elseif ($event->status == 2) { $registrationStatus = 'Selesai'; }
            
            // [PERBAIKAN] Logika kalkulasi rentang harga diletakkan di sini
            $priceRangeText = 'N/A';
            $prices = $event->kelasPertandingan->pluck('harga')->filter();
            if ($prices->isNotEmpty()) {
                $minPrice = $prices->min();
                $maxPrice = $prices->max();
                if ($minPrice === $maxPrice) {
                    $priceRangeText = 'Rp ' . number_format($minPrice, 0, ',', '.');
                } else {
                    $priceRangeText = 'Rp ' . number_format($minPrice, 0, ',', '.') . ' - ' . number_format($maxPrice, 0, ',', '.');
                }
            }

            $transformedEvents[] = [
                'id' => $event->id,
                'title' => $event->name,
                'status' => $event->status,
                'month' => $event->month,
                'date' => $formattedDate,
                'tgl_batas_pendaftaran' => \Carbon\Carbon::parse($event->tgl_batas_pendaftaran)->format('d F Y'),
                'location' => $event->lokasi,
                'kotaOrKabupaten' => $event->kotaOrKabupaten,
                'price_range_peserta' => $priceRangeText, // Menyimpan string yang sudah diformat
                'harga_contingent' => 'Rp ' . number_format($event->harga_contingent, 0, ',', '.'),
                'description' => $event->desc,
                'registrationStatus' => $registrationStatus,
                'poster' => asset('storage/' . $event->image),
                'cp' => $event->cp,
                'juknis' => $event->juknis
            ];
        }
    @endphp

    <script>
        const events = @json($transformedEvents);
        const statusMapping = {
            1: { text: 'Pendaftaran Dibuka', className: 'bg-green-100 text-green-800' },
            0: { text: 'Belum Dibuka', className: 'bg-yellow-100 text-yellow-800' },
            2: { text: 'Selesai', className: 'bg-gray-100 text-gray-800' }
        };
        let filteredEvents = [...events];

        function renderEvents(eventsToRender) {
            const container = document.getElementById('eventsContainer');
            container.innerHTML = '';
            if (eventsToRender.length === 0) {
                container.innerHTML = `<div class="col-span-full text-center py-12"><div class="text-6xl mb-4">🔍</div><h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada event ditemukan</h3><p class="text-gray-500">Coba ubah filter pencarian Anda</p></div>`;
                return;
            }
            eventsToRender.forEach(event => {
                const eventStatus = statusMapping[event.status] || { text: 'Tidak Diketahui', className: 'bg-gray-100 text-gray-800' };
                const card = document.createElement('div');
                card.className = 'bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1';
                card.onclick = () => openModal(event);
                card.innerHTML = `
                    <div class="p-6">
                        <div class="mb-4 text-center"><img src="${event.poster}" alt="Poster ${event.title}" class="w-full h-48 mx-auto rounded-lg shadow-md object-cover object-center"></div>
                        <div class="flex justify-between items-start mb-3"><span class="px-3 py-1 rounded-full text-xs font-medium ${eventStatus.className}">${eventStatus.text}</span></div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate" title="${event.title}">${event.title}</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center"><span class="mr-2">📅</span><span>${event.date}</span></div>
                            <div class="flex items-center"><span class="mr-2">📅</span><span>${event.tgl_batas_pendaftaran} (Batas Daftar)</span></div>
                            <div class="flex items-center"><span class="mr-2">📍</span><span>${event.location}</span></div>
                            <div class="flex items-center"><span class="mr-2">💰</span><span class="font-medium text-red-600">${event.harga_contingent}</span></div>
                        </div>
                        <button class="w-full mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">Lihat Detail</button>
                    </div>`;
                container.appendChild(card);
            });
        }

        function openModal(event) {
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            modalTitle.textContent = event.title;
            const eventStatus = statusMapping[event.status] || { text: 'Tidak Diketahui' };
            const registrationButton = event.registrationStatus === 'Dibuka' 
                ? `<button onclick="registerEvent(${event.id})" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">Daftar Sekarang</button>`
                : `<button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed">Pendaftaran ${event.registrationStatus}</button>`;

            modalContent.innerHTML = `
                <div class="space-y-6">
                    <div class="text-center"><div class="mb-4"><img src="${event.poster}" alt="Poster ${event.title}" class="w-48 h-64 mx-auto rounded-lg shadow-lg object-cover"></div><h3 class="text-xl font-semibold text-gray-800">${event.title}</h3></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg"><h4 class="font-semibold text-gray-800 mb-2">📅 Informasi Event</h4><div class="space-y-1 text-sm text-gray-600"><p><strong>Tanggal:</strong> ${event.date}</p><p><strong>Lokasi:</strong> ${event.location}</p><p><strong>Kota/Kab:</strong> ${event.kotaOrKabupaten}</p><p><strong>Juknis:</strong><a href="${event.juknis}" style="color: blue; text-decoration: underline;"> Link Drive</a></p><p><strong>Status:</strong> ${eventStatus.text}</p></div></div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">💰 Biaya Pendaftaran</h4>
                            <p class="text-2xl font-bold text-red-600">${event.harga_contingent}</p>
                            <p class="text-sm text-gray-600 mt-1">Per kontingen</p><br>
                            {{-- [PERBAIKAN] Menggunakan variabel price_range_peserta --}}
                            <p class="text-2xl font-bold text-red-600">${event.price_range_peserta}</p>
                            <p class="text-sm text-gray-600 mt-1">Per-kelas</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg"><h4 class="font-semibold text-gray-800 mb-2">📝 Deskripsi</h4><p class="text-gray-600">${event.description}</p></div>
                    <div class="bg-gray-50 p-4 rounded-lg"><h4 class="font-semibold text-gray-800 mb-2">Narahubung & No.rek:</h4><p class="text-gray-600">${event.cp}</p></div>
                    <div class="bg-gray-50 p-4 rounded-lg"><h4 class="font-semibold text-gray-800 mb-2">📋 Status Pendaftaran</h4><p class="text-gray-600">Status: <strong>${event.registrationStatus}</strong></p></div>
                    <div class="pt-4">${registrationButton}</div>
                </div>`;
            
            document.getElementById('eventModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
                        
        function closeModal() { document.getElementById('eventModal').classList.add('hidden'); document.body.style.overflow = 'auto'; }
        function registerEvent(eventId) { window.location.href = `/kontingen/${eventId}`; }
        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const monthFilter = document.getElementById('monthFilter').value;
            filteredEvents = events.filter(event => (!statusFilter || String(event.status) === statusFilter) && (!monthFilter || event.month === monthFilter));
            renderEvents(filteredEvents);
        }
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('monthFilter').value = '';
            filteredEvents = [...events];
            renderEvents(filteredEvents);
        }
        
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('monthFilter').addEventListener('change', applyFilters);
        document.getElementById('resetFilter').addEventListener('click', resetFilters);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('eventModal').addEventListener('click', (e) => { if (e.target === e.currentTarget) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
        renderEvents(events);
    </script>
</body>
</html>