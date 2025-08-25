<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Silat</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ASET UNTUK DATATABLES -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .modal-content {
            max-height: 90vh;
        }

        .sub-nav-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .sub-nav-btn.active {
            background-color: #c50000;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sub-nav-btn:not(.active) {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .sub-nav-btn:not(.active):hover {
            background-color: #e5e7eb;
        }

        /* Style agar input search dan pagination DataTables sesuai tema */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 6px 12px;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #c50000;
            box-shadow: 0 0 0 2px rgba(197, 0, 0, 0.2);
            outline: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #c50000 !important;
            color: white !important;
            border-color: #c50000 !important;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_info,
        .dataTables_paginate {
            padding-top: 1rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm border-b">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg hover:bg-gray-200 text-sm font-medium flex items-center space-x-2" title="Kembali ke Halaman Utama">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">🥋</span>
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                    <p class="text-sm text-gray-500">Event Management System</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->role->name }}</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-gray-600 text-sm">👤</span>
                </div>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b">
        <div class="px-6">
            <div class="flex space-x-8">
                <button onclick="showSection('events')" class="nav-btn py-4 px-2 border-b-2 border-red-500 text-red-600 font-medium ">🏆 Kelola Event</button>
                <button onclick="showSection('dashboard')" class="nav-btn py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">📊 Dashboard</button>
            </div>
        </div>
    </nav>

    <main class="p-6">
        <div id="dashboard" class="section hidden">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
                <p class="text-gray-600">Ringkasan aktivitas event silat Anda</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Kontingen</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalContingents }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><span class="text-blue-600 text-xl">🏢</span></div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Atlet</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalPlayers }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><span class="text-green-600 text-xl">👥</span></div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Kontingen Pending</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $pendingContingentsCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center"><span class="text-orange-600 text-xl">⏳</span></div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Atlet Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $pendingPlayersCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"><span class="text-yellow-600 text-xl">🏃</span></div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Aktif Terbaru Anda</h3>
                <div class="space-y-4">
                    @forelse ($activeEvents as $event)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $event->name }}</h4>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->format('d M') }} - {{ \Carbon\Carbon::parse($event->tgl_selesai_tanding)->format('d M Y') }} • {{ $event->lokasi }}</p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Aktif</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Tidak ada event yang sedang aktif.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div id="events" class="section">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Event Anda</h3>
                </div>
                <div class="overflow-x-auto p-6">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($events as $event)
                            <tr>
                                <td class="p-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                </td>
                                <td class="p-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->format('d M Y') }}</td>
                                <td class="p-3 text-sm text-gray-900">{{ $event->lokasi }}</td>
                                <td class="p-3">
                                    @if($event->status == 1) <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                                    @elseif ($event->status == 0) <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Segera Dibuka</span>
                                    @else <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">Tutup</span>
                                    @endif
                                </td>
                                <td class="p-3 text-sm text-gray-900">{{ $event->players_count }} atlet</td>
                                <td class="p-3">
                                    <button onclick='viewEventDetail(@json($event))' class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-6 flex space-x-2 border-b pb-2">
                <button onclick="showSubSection('pending')" class="sub-nav-btn active">Menunggu Verifikasi</button>
                <button onclick="showSubSection('approved')" class="sub-nav-btn">Disetujui</button>
                <button onclick="showSubSection('rejected')" class="sub-nav-btn">Ditolak</button>
            </div>

            <div id="pending" class="sub-section">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Data Kontingen (Tahap 1)</h3>
                        <div class="w-1/3">
                            <input type="text" id="pendingContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>
                    <table class="w-full" id="pendingContingentsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Surat Rekomendasi</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Biaya Kontingen</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contingentsForVerification as $contingent)
                        <tr>
                            <td class="p-3">
                                <div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div>
                                <div class="text-sm text-gray-500">{{ $contingent->event->name }}</div>
                            </td>
                            <td class="p-3">
                                <div class="text-sm font-medium text-gray-900">{{ $contingent->manajer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $contingent->no_telp }}</div>
                            </td>
                            <td class="p-3 text-sm text-blue-600">
                                @if ($contingent->surat_rekomendasi)
                                <a href="{{ Storage::url($contingent->surat_rekomendasi) }}" target="_blank" class="hover:underline">Surat Rekomendasi</a><br>
                                @else
                                <span class="text-gray-500">N/A</span> <br>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($contingent->event->harga_contingent) }}</div>
                            </td>
                            <td class="p-3">
                                <button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi</button>
                                <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium ml-2">Detail</button>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Pembayaran Kontingen (Tahap 2)</h3>
                        <div class="w-1/3">
                            <input type="text" id="dataVerificationContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>
                <table class="w-full" id="dataVerificationContingentsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti Pembayaran</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Biaya Kontingen</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contingentsForDataVerification as $contingent)
                        <tr>
                            <td class="p-3">
                                <div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div>
                                <div class="text-sm text-gray-500">{{ $contingent->event->name }}</div>
                            </td>
                            <td class="p-3">
                                <div class="text-sm font-medium text-gray-900">{{ $contingent->manajer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $contingent->no_telp }}</div>
                            </td>
                            <td class="p-3 text-sm text-blue-900">
                                @if($contingent->transactions->first() && $contingent->transactions->first()->foto_invoice)
                                    <a href="{{ Storage::url($contingent->transactions->first()->foto_invoice) }}" target="_blank" class="hover:underline">Bukti Bayar</a>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($contingent->event->harga_contingent) }}</div>
                            </td>
                            <td class="p-3">
                                <button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi</button>
                                <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium ml-2">Detail</button>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Atlet</h3>
                        <div class="w-1/3">
                            <input type="text" id="pendingPlayerSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari nama atlet, kelas, atau kontingen...">
                        </div>
                    </div>

                    <table class="w-full" id="pendingPlayersTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($groupedPlayersForVerification as $registration)
                            @php $firstPlayer = $registration['player_instances']->first(); @endphp
                            <tr>
                                <td class="p-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration['nama_kelas'] }} ({{ $registration['gender'] }})</div>
                                </td>
                                <td class="p-3 text-sm text-gray-900">
                                    <div>{{ $firstPlayer->contingent->name }}</div>
                                </td>
                                <td class="p-3 text-sm text-gray-900">
                                    @foreach($registration['player_instances'] as $player)
                                    <div class="text-xs text-blue-600">
                                        {{ \Illuminate\Support\Str::limit($player->name, 15) }}:
                                        @if($player->foto_ktp) <a href="{{ Storage::url($player->foto_ktp) }}" target="_blank" class="hover:underline">KTP</a> | @endif
                                        @if($player->foto_diri) <a href="{{ Storage::url($player->foto_diri) }}" target="_blank" class="hover:underline">Foto</a> | @endif
                                        @if($player->foto_persetujuan_ortu) <a href="{{ Storage::url($player->foto_persetujuan_ortu) }}" target="_blank" class="hover:underline">Izin</a> @endif
                                    </div>
                                    @endforeach
                                </td>
                                <td class="p-3 text-sm text-blue-600">
                                    @if($firstPlayer->playerInvoice && $firstPlayer->playerInvoice->foto_invoice)
                                    <a href="{{ Storage::url($firstPlayer->playerInvoice->foto_invoice) }}" target="_blank" class="hover:underline font-semibold">Lihat Bukti Bayar</a>
                                    @else
                                    <span class="text-gray-500 italic">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td class="p-3 align-top">
                                    @foreach($registration['player_instances'] as $player)
                                    <div class="flex items-center space-x-2 mb-1">
                                        <button onclick="openVerificationModal('player', '{{ $player->id }}', '{{ $player->name }}', '{{ route('admin.verify.player', $player->id) }}')" class="bg-blue-600 text-white px-2 py-0.5 rounded text-xs hover:bg-blue-700 w-20 text-center">Verifikasi</button>
                                        <button onclick='viewPlayerDetail(@json($player))' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB APPROVED --}}
            <div id="approved" class="sub-section hidden">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kontingen Disetujui</h3>
                        <div class="w-1/3">
                            <input type="text" id="approvedContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari nama kontingen atau event...">
                        </div>
                    </div>
                    <table class="w-full" id="approvedContingentsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedContingents as $contingent)
                            <tr>
                                <td class="p-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $contingent->event->name }}</div>
                                </td>
                                <td class="p-3 text-sm text-gray-900">{{ $contingent->manajer_name }}</td>
                                <td class="p-3 text-sm text-gray-900">{{ $contingent->players->count() }} atlet</td>
                                <td class="p-3 space-x-2">
                                    <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
                                    <button onclick="openRejectModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}')" class="text-red-600 hover:text-red-800 text-sm font-medium">Tolak</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Atlet Terverifikasi</h3>
                        <div class="w-1/3">
                            <input type="text" id="approvedPlayerSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari nama atlet, kelas, atau kontingen...">
                        </div>
                    </div>
                    <table class="w-full" id="approvedPlayersTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedApprovedPlayers as $registration)
                            @php $firstPlayer = $registration['player_instances']->first(); @endphp
                            <tr>
                                <td class="p-3 text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</td>
                                <td class="p-3 text-sm text-gray-900">{{ $registration['nama_kelas'] }}</td>
                                <td class="p-3 text-sm text-gray-900">{{ $firstPlayer->contingent->name }}</td>
                                <td class="p-3 text-sm text-gray-900">
                                    <div class="text-gray-900 font-mono text-xs">
                                        Invoice{{ $firstPlayer->playerInvoice->id }}_{{ $firstPlayer->contingent->name }}_{{ number_format( $firstPlayer->playerInvoice->total_price) }}
                                    </div>
                                </td>
                                <td class="p-3">
                                    @foreach($registration['player_instances'] as $player)
                                    <div class="flex items-center space-x-2 my-1">
                                        <button onclick='viewPlayerDetail(@json($player))' class="text-blue-600 hover:text-blue-800 text-xs font-medium whitespace-nowrap">Detail</button>
                                        <button onclick="openRejectModal('player', '{{ $player->id }}', '{{ $player->name }}', '{{ route('admin.verify.player', $player->id) }}')" class="text-red-600 hover:text-red-800 text-xs font-medium">Tolak</button>
                                        <span class="text-gray-600 text-xs truncate" title="{{$player->name}}">{{ \Illuminate\Support\Str::limit($player->name, 15) }}</span>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="rejected" class="sub-section hidden">
                <<div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kontingen Ditolak</h3>
                        <div class="w-1/3">
                            <input type="text" id="rejectedContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>
                    <table class="w-full" id="rejectedContingentsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedContingents as $contingent)
                            <tr>
                                <td class="p-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $contingent->event->name }}</div>
                                </td>
                                <td class="p-3 text-sm text-gray-900">{{ $contingent->manajer_name }}</td>
                                <td class="p-3 text-sm text-gray-700 italic">"{{ $contingent->catatan ?: 'Tidak ada catatan' }}"</td>
                                {{-- PERUBAHAN DI SINI: Tombol detail ditambahkan --}}
                                <td class="p-3">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}')" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600">Verifikasi Ulang</button>
                                        {{-- TOMBOL BARU DITAMBAHKAN DI SINI --}}
                                        <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-white rounded-xl shadow-sm border overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Atlet Ditolak</h3>
                        <div class="w-1/3">
                             <input type="text" id="rejectedPlayerSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>
                    <table class="w-full" id="rejectedPlayersTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedRejectedPlayers as $registration)
                            @php $firstPlayer = $registration['player_instances']->first(); @endphp
                            <tr>
                                <td class="p-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration['nama_kelas'] }}</div>
                                </td>
                                <td class="p-3 text-sm text-gray-900">{{ $firstPlayer->contingent->name }}</td>
                                <td class="p-3 text-sm text-gray-700 italic">"{{ $firstPlayer->catatan ?: 'Tidak ada catatan spesifik.' }}"</td>
                                {{-- PERUBAHAN DI SINI: Tombol detail ditambahkan untuk setiap atlet --}}
                                <td class="p-3">
                                    @foreach($registration['player_instances'] as $player)
                                    <div class="flex items-center space-x-2 mb-1">
                                        <button onclick="openVerificationModal('player', '{{ $player->id }}', '{{ $player->name }}', '{{ route('admin.verify.player', $player->id) }}')" class="bg-yellow-500 text-white px-2 py-0.5 rounded text-xs hover:bg-yellow-600 truncate" title="Verifikasi Ulang {{ $player->name }}">Verifikasi Ulang</button>
                                        {{-- TOMBOL BARU DITAMBAHKAN DI SINI --}}
                                        <button onclick='viewPlayerDetail(@json($player))' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                        <span class="text-gray-600 text-xs truncate" title="{{$player->name}}">{{ \Illuminate\Support\Str::limit($player->name, 10) }}</span>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl modal-content overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="detailModalTitle" class="text-xl font-semibold text-gray-900">Detail</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div id="detailModalContent" class="text-sm"></div>
            </div>
            <div class="flex justify-end p-4 bg-gray-50 rounded-b-xl">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">Tutup</button>
            </div>
        </div>
    </div>

    {{-- MODAL VERIFIKASI (Untuk Menunggu Verifikasi & Ditolak) --}}
    <div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-lg modal-content overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="verificationModalTitle" class="text-lg font-semibold text-gray-900">Verifikasi</h3>
                    <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form id="verificationForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <p>Anda akan memverifikasi <strong id="verificationItemName"></strong>.</p>
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (opsional jika setuju, wajib jika tolak)</label>
                            <textarea name="catatan" id="catatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:border-red-500 focus:ring-red-500" rows="3" placeholder="Tambahkan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeVerificationModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button>
                        <button type="submit" name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- MODAL TOLAK (Khusus untuk item yang sudah disetujui) --}}
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-lg modal-content overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="rejectModalTitle" class="text-lg font-semibold text-gray-900">Tolak Verifikasi</h3>
                    <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <p>Anda akan menolak <strong id="rejectItemName"></strong>. Status akan diubah menjadi "Ditolak".</p>
                        <div>
                            <label for="rejectCatatan" class="block text-sm font-medium text-gray-700">Catatan Penolakan (Wajib Diisi)</label>
                            <textarea name="catatan" id="rejectCatatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1 focus:border-red-500 focus:ring-red-500" rows="3" placeholder="Tambahkan alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // INISIALISASI DATATABLES
        $(document).ready(function() {
            const dtConfig = {
                "dom": 'rt<"bottom"lip><"clear">',
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
            };

            $('#pendingContingentsTable').DataTable(dtConfig);
            $('#dataVerificationContingentsTable').DataTable(dtConfig); // BARU
            $('#approvedContingentsTable').DataTable(dtConfig);
            $('#rejectedContingentsTable').DataTable(dtConfig);
            
            $('#pendingPlayersTable').DataTable(dtConfig);
            $('#approvedPlayersTable').DataTable(dtConfig);
            $('#rejectedPlayersTable').DataTable(dtConfig);

            $('#pendingContingentSearch').on('keyup', function() { $('#pendingContingentsTable').DataTable().search(this.value).draw(); });
            $('#dataVerificationContingentSearch').on('keyup', function() { $('#dataVerificationContingentsTable').DataTable().search(this.value).draw(); }); // BARU
            $('#approvedContingentSearch').on('keyup', function() { $('#approvedContingentsTable').DataTable().search(this.value).draw(); });
            $('#rejectedContingentSearch').on('keyup', function() { $('#rejectedContingentsTable').DataTable().search(this.value).draw(); });
            
            $('#pendingPlayerSearch').on('keyup', function() { $('#pendingPlayersTable').DataTable().search(this.value).draw(); });
            $('#approvedPlayerSearch').on('keyup', function() { $('#approvedPlayersTable').DataTable().search(this.value).draw(); });
            $('#rejectedPlayerSearch').on('keyup', function() { $('#rejectedPlayersTable').DataTable().search(this.value).draw(); });
        });
        // VALIDASI FORM SAAT SUBMIT
        document.getElementById('verificationForm').addEventListener('submit', function(event) {
            const submitter = event.submitter || document.activeElement;
            if (submitter && submitter.value === 'reject') {
                const catatan = document.getElementById('catatan');
                if (catatan.value.trim() === '') {
                    event.preventDefault();
                    alert('Harap isi kolom catatan untuk menolak.');
                    catatan.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                    catatan.focus();
                }
            }
        });

        document.getElementById('rejectForm').addEventListener('submit', function(event) {
            const catatan = document.getElementById('rejectCatatan');
            if (catatan.value.trim() === '') {
                event.preventDefault();
                alert('Harap isi kolom catatan untuk menolak.');
                catatan.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                catatan.focus();
            }
        });

        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => section.classList.add('hidden'));
            document.getElementById(sectionId).classList.remove('hidden');
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('border-red-500', 'text-red-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            event.currentTarget.classList.add('border-red-500', 'text-red-600');
            event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
        }

        function showSubSection(subSectionId) {
            document.querySelectorAll('.sub-section').forEach(section => section.classList.add('hidden'));
            document.getElementById(subSectionId).classList.remove('hidden');
            document.querySelectorAll('.sub-nav-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
        }

        function openVerificationModal(type, id, name, actionUrl) {
            document.getElementById('verificationModalTitle').textContent = `Verifikasi ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            document.getElementById('verificationItemName').textContent = name;
            document.getElementById('verificationForm').action = actionUrl;
            const catatan = document.getElementById('catatan');
            catatan.value = '';
            catatan.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function openRejectModal(type, id, name, actionUrl) {
            document.getElementById('rejectModalTitle').textContent = `Tolak ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            document.getElementById('rejectItemName').textContent = name;
            document.getElementById('rejectForm').action = actionUrl;
            const catatan = document.getElementById('rejectCatatan');
            catatan.value = '';
            catatan.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeVerificationModal() { document.getElementById('verificationModal').classList.add('hidden'); }
        function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }

        const detailModal = document.getElementById('detailModal');
        const detailModalTitle = document.getElementById('detailModalTitle');
        const detailModalContent = document.getElementById('detailModalContent');

        function closeDetailModal() { detailModal.classList.add('hidden'); }

        function viewEventDetail(event) {
            detailModalTitle.textContent = 'Detail Event: ' + event.name;
            let statusText = event.status == 1 ? 'Aktif' : (event.status == 0 ? 'Segera Dibuka' : 'Tutup');
            detailModalContent.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><strong class="block text-gray-500">Status</strong> <p>${statusText}</p></div><div><strong class="block text-gray-500">Lokasi</strong> <p>${event.lokasi}</p></div><div><strong class="block text-gray-500">Tanggal Mulai</strong> <p>${new Date(event.tgl_mulai_tanding).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div><div><strong class="block text-gray-500">Tanggal Selesai</strong> <p>${new Date(event.tgl_selesai_tanding).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div><div><strong class="block text-gray-500">Batas Pendaftaran</strong> <p>${new Date(event.tgl_batas_pendaftaran).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div><div><strong class="block text-gray-500">Biaya Kontingen</strong> <p>Rp ${Number(event.harga_contingent).toLocaleString('id-ID')}</p></div><div><strong class="block text-gray-500">Total Peserta</strong> <p>${event.players_count} atlet</p></div><div><strong class="block text-gray-500">Contact Person</strong> <div>${event.cp}</div></div></div><div class="mt-4"><strong class="block text-gray-500">Deskripsi</strong> <p class="whitespace-pre-wrap">${event.desc || '-'}</p></div>`;
            detailModal.classList.remove('hidden');
        }
        
        function viewContingentDetail(contingent) {
            detailModalTitle.textContent = 'Detail Kontingen: ' + contingent.name;
            let playersListHtml = '<li>Belum ada peserta terdaftar.</li>';

            // Mendapatkan URL storage dari variabel global (jika ada) atau hardcode
            // Ini untuk memastikan path file benar
            const storageUrlPrefix = "/storage/";

            // LOGIKA BARU: Buat HTML untuk link Surat Rekomendasi
            let suratHtml;
            if (contingent.surat_rekomendasi) {
                suratHtml = `<a href="${storageUrlPrefix}${contingent.surat_rekomendasi}" target="_blank" class="text-blue-600 hover:underline">Lihat Surat Rekomendasi</a>`;
            } else {
                suratHtml = `<span class="text-gray-500 italic">N/A</span>`;
            }

            // LOGIKA BARU: Buat HTML untuk link Bukti Bayar
            let buktiBayarHtml;
            // Cek jika relasi transactions ada, tidak kosong, dan punya foto_invoice
            if (contingent.transactions && contingent.transactions.length > 0 && contingent.transactions[0].foto_invoice) {
                buktiBayarHtml = `<a href="${storageUrlPrefix}${contingent.transactions[0].foto_invoice}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Bayar</a>`;
            } else {
                buktiBayarHtml = `<span class="text-gray-500 italic">N/A</span>`;
            }

            // PERUBAHAN: Menambahkan biaya kontingen dan format angkanya
            const hargaKontingen = contingent.event ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(contingent.event.harga_contingent) : 'N/A';

            if (contingent.players && contingent.players.length > 0) {
                const playersByClass = contingent.players.reduce((acc, player) => {
                    const classId = player.kelas_pertandingan_id || 'no-class';
                    if (!acc[classId]) { acc[classId] = []; }
                    acc[classId].push(player);
                    return acc;
                }, {});

                playersListHtml = Object.values(playersByClass).map(playerGroup => {
                    const firstPlayer = playerGroup[0];
                    const playerNames = playerGroup.map(p => p.name).join(', ');
                    const className = firstPlayer.kelas_pertandingan?.kelas?.nama_kelas || 'Kelas tidak tersedia';
                    return `<li><strong>${playerNames}</strong> - <em>${className}</em></li>`;
                }).join('');
            }
            
            // PERUBAHAN UTAMA: Sisipkan HTML baru ke dalam modal
            detailModalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong class="block text-gray-500">Event</strong> <p>${contingent.event.name}</p></div>
                    <div><strong class="block text-gray-500">Nama Manajer</strong> <p>${contingent.manajer_name}</p></div>
                    <div><strong class="block text-gray-500">Email</strong> <p>${contingent.email}</p></div>
                    <div><strong class="block text-gray-500">No. Telepon</strong> <p>${contingent.no_telp}</p></div>
                    <div><strong class="block text-gray-500">Pemilik Akun</strong> <p>${contingent.user.nama_lengkap}</p></div>
                    <div><strong class="block text-gray-500">Biaya Kontingen</strong> <p>${hargaKontingen}</p></div>
                    
                    <!-- KONTEN BARU DITAMBAHKAN DI SINI -->
                    <div>
                        <strong class="block text-gray-500">Surat Rekomendasi</strong>
                        <p>${suratHtml}</p>
                    </div>
                    <div>
                        <strong class="block text-gray-500">Bukti Bayar Kontingen</strong>
                        <p>${buktiBayarHtml}</p>
                    </div>
                    <!-- AKHIR DARI KONTEN BARU -->

                    <div class="md:col-span-2"><strong class="block text-gray-500">Jumlah Atlet</strong> <p>${contingent.players.length} orang</p></div>
                </div>
                <div class="mt-4"><strong class="block text-gray-500">Catatan Admin</strong> 
                    <p class="whitespace-pre-wrap">${contingent.catatan || 'Tidak ada catatan.'}</p>
                </div>
                <div class="mt-4">
                    <strong class="block text-gray-500">Daftar Atlet</strong> <ul class="list-disc list-inside mt-1 space-y-1">${playersListHtml}</ul>
                </div>
            `;
            detailModal.classList.remove('hidden');
        }

        function viewPlayerDetail(player) {
            detailModalTitle.textContent = 'Detail Atlet: ' + player.name;
            const formatCurrency = (number) => { if (number === null || typeof number === 'undefined') return 'N/A'; return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); };
            const formatCurrencyNoRP = (number) => {
                if (number === null || typeof number === 'undefined') return 'N/A';
                // Ubah style ke 'decimal' dan hapus properti 'currency'
                return new Intl.NumberFormat('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 0
                }).format(number);
            };

            const invoiceName = player.player_invoice ? `Invoice${player.player_invoice.id}_${player.contingent.name}_${formatCurrencyNoRP(player.player_invoice.total_price)}` : 'Tidak Terikat Invoice';
            const invoiceLinkHtml = player.player_invoice ?.foto_invoice ? `<a href="/storage/${player.player_invoice.foto_invoice}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Bayar</a>` : `<span class="text-gray-500 italic">Belum ada invoice</span>`;
            const kategori = player.kelas_pertandingan?.kategori_pertandingan?.nama_kategori || 'N/A';
            const jenis = player.kelas_pertandingan?.jenis_pertandingan?.nama_jenis || 'N/A';
            const kelas = player.kelas_pertandingan?.kelas?.nama_kelas || 'N/A';
            const rentangUsia = player.kelas_pertandingan?.kelas?.rentang_usia?.rentang_usia || 'N/A';
            const hargaKelasHtml = player.kelas_pertandingan ? `<span class="font-bold text-green-600">${formatCurrency(player.kelas_pertandingan.harga)}</span>` : `<span class="text-gray-500 italic">N/A</span>`;
            const totalInvoiceHtml = player.player_invoice ? `<span class="font-bold text-blue-600">${formatCurrency(player.player_invoice.total_price)}</span>` : `<span class="text-gray-500 italic">N/A</span>`;

            detailModalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <strong class="block text-gray-500 mb-2 border-b pb-1">Data Diri</strong>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <span class="text-gray-600">Nama</span> <span>: ${player.name}</span>
                            <span class="text-gray-600">NIK</span> <span>: ${player.nik}</span>
                            <span class="text-gray-600">Gender</span> <span>: ${player.gender}</span>
                            <span class="text-gray-600">Tgl Lahir</span> <span>: ${new Date(player.tgl_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</span>
                        </div>
                    </div>
                    <div>
                        <strong class="block text-gray-500 mb-2 border-b pb-1">Informasi Tim</strong>
                         <div class="grid grid-cols-2 gap-2 text-sm">
                            <span class="text-gray-600">Event</span> <span class="truncate">: ${player.contingent.event.name}</span>
                            <span class="text-gray-600">Kontingen</span> <span class="truncate">: ${player.contingent.name}</span>
                            <span class="text-gray-600">Email</span> <span>: ${player.email || '-'}</span>
                            <span class="text-gray-600">No. Telepon</span> <span>: ${player.no_telp || '-'}</span>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 mt-2">
                         <strong class="block text-gray-500 mb-2 border-b pb-1">Detail Pertandingan</strong>
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                            <div><span class="text-gray-600 font-semibold">Kategori:</span> <p class="text-gray-800">${kategori}</p></div>
                            <div><span class="text-gray-600 font-semibold">Jenis:</span> <p class="text-gray-800">${jenis}</p></div>
                            <div><span class="text-gray-600 font-semibold">Kelas:</span> <p class="text-gray-800">${kelas}</p></div>
                            <div><span class="text-gray-600 font-semibold">Rentang Usia:</span> <p class="text-gray-800">${rentangUsia}</p></div>
                         </div>
                    </div>
                    
                    <div class="md:col-span-2 mt-2">
                        <strong class="block text-gray-500 mb-2 border-b pb-1">Informasi Pembayaran</strong>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="md:col-span-3"><strong class="block text-gray-600">Harga Kelas</strong><p>${hargaKelasHtml}</p></div>
                            <div><strong class="block text-gray-600">Nama Invoice</strong><p>${invoiceName}</p></div>
                            <div><strong class="block text-gray-600">Total Invoice</strong><p>${totalInvoiceHtml}</p></div>
                            <div><strong class="block text-gray-600">Bukti Bayar</strong><p>${invoiceLinkHtml}</p></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4"><strong class="block text-gray-500">Catatan Admin</strong> <p class="whitespace-pre-wrap text-sm">${player.catatan || 'Tidak ada catatan.'}</p></div>
            `;
            detailModal.classList.remove('hidden');
        }
    </script>
</body>

</html>