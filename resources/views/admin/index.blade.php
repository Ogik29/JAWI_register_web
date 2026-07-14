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

    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon/logo-jawi2.png') }}">


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

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 640px) {
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                text-align: left !important;
                margin-bottom: 0.5rem;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
                margin-top: 0.25rem;
            }
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: center !important;
                margin-top: 0.5rem;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 4px 8px !important;
                font-size: 11px !important;
                margin: 2px !important;
            }
            .modal-content {
                max-height: 92vh;
                margin: 0 8px;
            }
        }

        /* Cegah tabel tergepengkan di layar smartphone */
        .overflow-x-auto table {
            min-width: 650px !important;
        }

        .overflow-x-auto th, .overflow-x-auto td {
            vertical-align: top;
        }
    </style>
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm border-b">
        <div class="px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="{{ url('/') }}" class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg hover:bg-gray-200 text-xs sm:text-sm font-medium flex items-center space-x-1.5" title="Kembali ke Halaman Utama">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Home</span>
                </a>
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-red-600 rounded-lg flex items-center justify-center shrink-0">
                    <span class="text-white font-bold text-base sm:text-lg">🥋</span>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900 leading-tight">Admin Dashboard</h1>
                    <p class="text-xs sm:text-sm text-gray-500">Event Management System</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 self-end sm:self-auto">
                <div class="text-right">
                    <p class="text-xs sm:text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->role->name }}</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center shrink-0">
                    <span class="text-gray-600 text-sm">👤</span>
                </div>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b">
        <div class="px-4 sm:px-6 overflow-x-auto whitespace-nowrap scrollbar-none">
            <div class="flex space-x-4 sm:space-x-8">
                <button onclick="showSection('events')" class="nav-btn py-3 sm:py-4 px-2 border-b-2 border-red-500 text-red-600 font-medium text-sm sm:text-base">🏆 Kelola Event</button>
                <button onclick="showSection('bracket')" class="nav-btn py-3 sm:py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-sm sm:text-base">⚔️ Bracket Prestasi</button>
                <button onclick="showSection('dashboard')" class="nav-btn py-3 sm:py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-sm sm:text-base">📊 Dashboard</button>
            </div>
        </div>
    </nav>

    <main class="p-3 sm:p-6">
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

            <div class="mb-6 flex space-x-2 border-b pb-2 overflow-x-auto whitespace-nowrap scrollbar-none">
                <button onclick="showSubSection('pending')" class="sub-nav-btn active text-xs sm:text-sm">Menunggu Verifikasi</button>
                <button onclick="showSubSection('approved')" class="sub-nav-btn text-xs sm:text-sm">Disetujui</button>
                <button onclick="showSubSection('rejected')" class="sub-nav-btn text-xs sm:text-sm">Ditolak</button>
            </div>

            <div id="pending" class="sub-section">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Verifikasi Data Kontingen (Tahap 1)</h3>
                        <div class="w-full sm:w-72">
                            <input type="text" id="pendingContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
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
                                        <div class="text-sm font-medium text-gray-900">{{ $contingent->user?->nama_lengkap }}</div>
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
                                        @php
                                            $isUnpaidContingent = ($contingent->event->harga_contingent > 0 && (!$contingent->transactions->first() || !$contingent->transactions->first()->foto_invoice));
                                        @endphp
                                        <button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}', {{ $isUnpaidContingent ? 'true' : 'false' }})" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi</button>
                                        <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium ml-2">Detail</button>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-4 sm:p-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Verifikasi Pembayaran Kontingen (Tahap 2)</h3>
                <div class="w-full sm:w-72">
                    <input type="text" id="dataVerificationContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                </div>
            </div>

            <!-- Tambahkan wrapper scroll -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-max" id="dataVerificationContingentsTable">
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
                                <div class="text-sm font-medium text-gray-900">{{ $contingent->user?->nama_lengkap }}</div>
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
                                @php
                                    $isUnpaidContingent = ($contingent->event->harga_contingent > 0 && (!$contingent->transactions->first() || !$contingent->transactions->first()->foto_invoice));
                                @endphp
                                <button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}', {{ $isUnpaidContingent ? 'true' : 'false' }})" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi</button>
                                <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium ml-2">Detail</button>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Verifikasi Atlet (Sudah Bayar)</h3>
                        <div class="w-full sm:w-72">
                            <input type="text" id="pendingPlayerSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari nama atlet, kelas, atau kontingen...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="pendingPlayersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($groupedPlayersForVerification as $registration)
                                @php 
                                    $firstPlayer = $registration['player_instances']->first(); 
                                    $playerIds = $registration['player_instances']->pluck('id')->implode(',');
                                    $isTeam = $registration['player_instances']->count() > 1;
                                @endphp
                                <tr>
                                    <td class="p-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration['nama_kelas'] }} ({{ $registration['gender'] }})</div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $firstPlayer->contingent->name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <span class="font-medium">Manajer:</span> {{ $firstPlayer->contingent->manajer_name }}
                                        </div>
                                        @if ($firstPlayer->contingent->no_telp)
                                        <div class="text-xs text-gray-400">
                                            <span>📞 {{ $firstPlayer->contingent->no_telp }}</span>
                                        </div>
                                        @endif
                                        @if ($firstPlayer->contingent->email)
                                        <div class="text-xs text-gray-400 truncate" style="max-width: 160px;">
                                            <span>✉ {{ $firstPlayer->contingent->email }}</span>
                                        </div>
                                        @endif
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
                                    <td class="p-3 text-sm text-gray-900">
                                        @if ($firstPlayer->playerInvoice)
                                            <div class="text-gray-900 font-mono text-xs">
                                                Invoice{{ $firstPlayer->playerInvoice->id }}_{{ $firstPlayer->contingent->name }}_{{ number_format( $firstPlayer->playerInvoice->total_price) }}
                                            </div>
                                        @else
                                            <span class="text-gray-500 italic text-xs">Belum ada invoice</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-blue-600">
                                        @if($firstPlayer->playerInvoice && $firstPlayer->playerInvoice->foto_invoice)
                                        <a href="{{ Storage::url($firstPlayer->playerInvoice->foto_invoice) }}" target="_blank" class="hover:underline font-semibold">Lihat Bukti Bayar</a>
                                        @else
                                        <span class="text-gray-500 italic">Belum Dibayar</span>
                                        @endif
                                    </td>
                                    <td class="p-3 align-top">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <button onclick="openVerificationModal('{{ $isTeam ? 'Tim' : 'Atlet' }}', '{{ $playerIds }}', '{{ $registration['player_names'] }}', '{{ route('admin.verify.player', $firstPlayer->id) }}')" class="bg-blue-600 text-white px-2 py-0.5 rounded text-xs hover:bg-blue-700 w-20 text-center">Verifikasi</button>
                                            <button onclick='viewTeamDetail(@json($registration['player_instances']), {{ $isTeam ? "true" : "false" }})' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                        {{-- Judul dan Search di kiri --}}
                        <div class="w-full sm:w-auto">
                            <h3 class="text-lg font-semibold text-gray-900">Verifikasi Atlet (Belum Bayar, namun Kontingen Disetujui)</h3>
                            <div class="mt-2 w-full sm:w-80">
                                <input type="text" id="pendingPlayerDataSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari atlet, kelas, kontingen...">
                            </div>
                        </div>
                        {{-- Tombol Export Baru di kanan --}}
                        <div>
                             <a href="{{ route('admin.events.export-pending-data', $event->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition-colors shadow-sm whitespace-nowrap">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9A2.25 2.25 0 0019.5 19.5V10.5a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                Export ke Excel
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="pendingPlayersDataTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Status Pembayaran</th>
                                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($groupedPlayersForDataVerification as $registration)
                                @php 
                                    $firstPlayer = $registration['player_instances']->first(); 
                                    $playerIds = $registration['player_instances']->pluck('id')->implode(',');
                                    $isTeam = $registration['player_instances']->count() > 1;
                                @endphp
                                <tr>
                                    <td class="p-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration['nama_kelas'] }} ({{ $registration['gender'] }})</div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $firstPlayer->contingent->name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <span class="font-medium">Manajer:</span> {{ $firstPlayer->contingent->manajer_name }}
                                        </div>
                                        @if ($firstPlayer->contingent->no_telp)
                                        <div class="text-xs text-gray-400">
                                            <span>📞 {{ $firstPlayer->contingent->no_telp }}</span>
                                        </div>
                                        @endif
                                        @if ($firstPlayer->contingent->email)
                                        <div class="text-xs text-gray-400 truncate" style="max-width: 160px;">
                                            <span>✉ {{ $firstPlayer->contingent->email }}</span>
                                        </div>
                                        @endif
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
                                    <td class="p-3 text-sm">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Belum Buat Invoice</span>
                                    </td>
                                    <td class="p-3 align-top">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <button onclick="openVerificationModal('{{ $isTeam ? 'Tim' : 'Atlet' }}', '{{ $playerIds }}', '{{ $registration['player_names'] }}', '{{ route('admin.verify.player', $firstPlayer->id) }}')" class="bg-blue-600 text-white px-2 py-0.5 rounded text-xs hover:bg-blue-700 w-20 text-center">Verifikasi</button>
                                            <button onclick='viewTeamDetail(@json($registration['player_instances']), {{ $isTeam ? "true" : "false" }})' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                        </div>
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
                {{-- BAGIAN KONTINGEN DISETUJUI --}}
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kontingen Disetujui</h3>

                        <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="w-full sm:w-80">
                                <input type="text" id="approvedContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari nama kontingen atau event...">
                            </div>
                            <div>
                                <a href="{{ route('admin.export.approved-contingents') }}" 
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition-colors shadow-sm whitespace-nowrap">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9A2.25 2.25 0 0019.5 19.5V10.5a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Export ke Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
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
                                    <td class="p-3 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $contingent->manajer_name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Akun: {{ $contingent->user->nama_lengkap }}</div>
                                        @if($contingent->no_telp)
                                        <div class="text-xs text-gray-400">📞 {{ $contingent->no_telp }}</div>
                                        @endif
                                        @if($contingent->email)
                                        <div class="text-xs text-gray-400 truncate" style="max-width:160px;">✉ {{ $contingent->email }}</div>
                                        @endif
                                    </td>
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
                </div>
                {{-- GANTI SELURUH BLOK INI DENGAN YANG DI BAWAH --}}

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    {{-- ======================================================================= --}}
    {{-- BAGIAN HEADER YANG DIDESAIGN ULANG --}}
    {{-- ======================================================================= --}}
    <div class="p-4 sm:p-6 border-b bg-gray-50">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            {{-- Bagian Kiri: Judul --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar Atlet Terverifikasi</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar semua peserta yang telah disetujui untuk bertanding.</p>
            </div>
            {{-- Bagian Kanan: Search & Export Button --}}
            <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="approvedPlayerSearch" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari nama, kelas, atau kontingen...">
                </div>
                
                {{-- TOMBOL EXPORT BARU YANG LEBIH MENARIK --}}
                <a href="{{ route('admin.events.export-approved', $event->id) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700 transition-colors shadow-sm whitespace-nowrap">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9A2.25 2.25 0 0019.5 19.5V10.5a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Export ke Excel
                </a>

                <a href="{{ route('admin.events.print-all-cards', $event->id) }}" target="_blank"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-colors shadow-sm whitespace-nowrap">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 2.5A2.5 2.5 0 002.5 5v5A2.5 2.5 0 005 12.5h10A2.5 2.5 0 0017.5 10V5A2.5 2.5 0 0015 2.5H5zM4 5a1 1 0 011-1h10a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" clip-rule="evenodd" />
                        <path d="M2.5 13.5A.5.5 0 002 14v1.5a.5.5 0 00.5.5h15a.5.5 0 00.5-.5V14a.5.5 0 00-.5-.5h-15zM4 14.5a.5.5 0 00-.5.5v1h13v-1a.5.5 0 00-.5-.5H4z" />
                    </svg>
                    Cetak Semua Kartu
                </a>
            </div>
        </div>
    </div>
    
    {{-- ======================================================================= --}}
    {{-- BAGIAN TABEL (KONTEN TIDAK BERUBAH) --}}
    {{-- ======================================================================= --}}
    <div class="overflow-x-auto">
        <table class="w-full" id="approvedPlayersTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- Logika Blade Anda untuk loop tidak perlu diubah, karena sudah benar --}}
                @foreach($groupedApprovedPlayers as $registration)
                @php 
                    $firstPlayer = $registration['player_instances']->first(); 
                    $playerIds = $registration['player_instances']->pluck('id')->implode(',');
                    $isTeam = $registration['player_instances']->count() > 1;
                @endphp
                <tr>
                    <td class="p-3 text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</td>
                    <td class="p-3 text-sm text-gray-900">{{ $registration['nama_kelas'] }}</td>
                    <td class="p-3 text-sm text-gray-900">
                        <div class="font-medium text-gray-900">{{ $firstPlayer->contingent->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5"><span class="font-medium">Manajer:</span> {{ $firstPlayer->contingent->manajer_name }}</div>
                        @if ($firstPlayer->contingent->no_telp)
                        <div class="text-xs text-gray-400">📞 {{ $firstPlayer->contingent->no_telp }}</div>
                        @endif
                        @if ($firstPlayer->contingent->email)
                        <div class="text-xs text-gray-400 truncate" style="max-width:160px;">✉ {{ $firstPlayer->contingent->email }}</div>
                        @endif
                    </td>
                    <td class="p-3 text-sm text-gray-900">
                        <div class="text-gray-900 font-mono text-xs">
<<<<<<< HEAD
                           @if ($firstPlayer->playerInvoice)    
                                Invoice{{ $firstPlayer->playerInvoice->id }}{{ $firstPlayer->contingent->name }}{{ number_format( $firstPlayer->playerInvoice->total_price) }}
                            @else
                                Invoice-kosong
                            @endif
=======
                            @if ($firstPlayer->playerInvoice)    
                                Invoice{{ $firstPlayer->playerInvoice->id }}_{{ $firstPlayer->contingent->name }}_{{ number_format( $firstPlayer->playerInvoice->total_price) }}
                            @else
                                Belum-Bayar
                            @endif
>>>>>>> 4e685253772b778335ae76487a07cdc811f1e59b
                        </div>
                    </td>
                    <td class="p-3">
                        <div class="flex items-center space-x-2 my-1">
                            <button onclick='viewTeamDetail(@json($registration['player_instances']), {{ $isTeam ? "true" : "false" }})' class="text-blue-600 hover:text-blue-800 text-xs font-medium whitespace-nowrap">Detail</button>
                            <button onclick="openRejectModal('{{ $isTeam ? 'Tim' : 'Atlet' }}', '{{ $playerIds }}', '{{ $registration['player_names'] }}', '{{ route('admin.verify.player', $firstPlayer->id) }}')" class="text-red-600 hover:text-red-800 text-xs font-medium">Tolak</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
            </div>

            <div id="rejected" class="sub-section hidden">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Kontingen Ditolak</h3>
                        <div class="w-full sm:w-72">
                            <input type="text" id="rejectedContingentSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
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
<<<<<<< HEAD
                                    <td class="p-3 text-sm text-gray-900">{{ $contingent->user?->nama_lengkap }}</td>
=======
                                    <td class="p-3 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $contingent->manajer_name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Akun: {{ $contingent->user->nama_lengkap }}</div>
                                        @if($contingent->no_telp)
                                        <div class="text-xs text-gray-400">📞 {{ $contingent->no_telp }}</div>
                                        @endif
                                        @if($contingent->email)
                                        <div class="text-xs text-gray-400 truncate" style="max-width:160px;">✉ {{ $contingent->email }}</div>
                                        @endif
                                    </td>
>>>>>>> 4e685253772b778335ae76487a07cdc811f1e59b
                                    <td class="p-3 text-sm text-gray-700 italic">"{{ $contingent->catatan ?: 'Tidak ada catatan' }}"</td>
                                    <td class="p-3">
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $isUnpaidContingent = ($contingent->event->harga_contingent > 0 && (!$contingent->transactions->first() || !$contingent->transactions->first()->foto_invoice));
                                            @endphp
                                            <button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}', {{ $isUnpaidContingent ? 'true' : 'false' }})" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600">Verifikasi Ulang</button>
                                            <button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border overflow-hidden p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Atlet Ditolak</h3>
                        <div class="w-full sm:w-72">
                             <input type="text" id="rejectedPlayerSearch" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
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
                                @php 
                                    $firstPlayer = $registration['player_instances']->first(); 
                                    $playerIds = $registration['player_instances']->pluck('id')->implode(',');
                                    $isTeam = $registration['player_instances']->count() > 1;
                                @endphp
                                <tr>
                                    <td class="p-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration['nama_kelas'] }}</div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-900">
                                        <div class="font-medium text-gray-900">{{ $firstPlayer->contingent->name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5"><span class="font-medium">Manajer:</span> {{ $firstPlayer->contingent->manajer_name }}</div>
                                        @if ($firstPlayer->contingent->no_telp)
                                        <div class="text-xs text-gray-400">📞 {{ $firstPlayer->contingent->no_telp }}</div>
                                        @endif
                                        @if ($firstPlayer->contingent->email)
                                        <div class="text-xs text-gray-400 truncate" style="max-width:160px;">✉ {{ $firstPlayer->contingent->email }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-gray-700 italic">"{{ $firstPlayer->catatan ?: 'Tidak ada catatan spesifik.' }}"</td>
                                    <td class="p-3">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <button onclick="openVerificationModal('{{ $isTeam ? 'Tim' : 'Atlet' }}', '{{ $playerIds }}', '{{ $registration['player_names'] }}', '{{ route('admin.verify.player', $firstPlayer->id) }}')" class="bg-yellow-500 text-white px-2 py-0.5 rounded text-xs hover:bg-yellow-600 truncate" title="Verifikasi Ulang {{ $registration['player_names'] }}">Verifikasi Ulang</button>
                                            <button onclick='viewTeamDetail(@json($registration['player_instances']), {{ $isTeam ? "true" : "false" }})' class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="bracket" class="section hidden">
        <div class="max-w-7xl mx-auto">
            
            {{-- BAGIAN HEADER --}}
            <div class="mb-8 pb-4 border-b-2 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Bracket Pertandingan Prestasi</h2>
                <p class="mt-1 text-gray-600">Pilih event dan kelas untuk melihat atau membuat bagan pertandingan.</p>
            </div>

            {{-- BAGIAN KONTEN --}}
            @forelse ($kelasUntukBracket->groupBy('event_id') as $event_id => $kelasGrup)
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                    {{-- Header untuk setiap Event Card --}}
                    <div class="px-6 py-4 border-b bg-gray-50/75">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex justify-center items-center w-8 h-8 rounded-lg bg-red-100 text-red-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-6.75c-.621 0-1.125.504-1.125 1.125V18.75m9 0h-9" /></svg>
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900">Event: {{ $kelasGrup->first()->event->name }}</h3>
                        </div>
                    </div>
                    
                    {{-- Grid untuk menampilkan kartu Kelas Pertandingan --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                            @foreach ($kelasGrup as $kelas)
                                <a href="{{ route('bracket.show', $kelas->id) }}" 
                                class="relative group flex flex-col bg-white rounded-lg border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-lg hover:border-red-500 hover:-translate-y-1">
                                {{-- TAMBAHAN 2: Penanda "Sudah Drawing" (Blok @if baru) --}}
                                @if($kelas->has_drawing)
                                    <div class="absolute top-2 right-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center gap-1" title="Bracket sudah dibuat">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        <span>Drawn</span>
                                    </div>
                                @endif
                                    
                                    {{-- Konten Utama Kartu --}}
                                     <div class="p-4 flex-grow flex flex-col justify-between">
                                        {{-- Bagian Atas: Judul Utama --}}
                                        <div>
                                            <p class="font-bold text-gray-800 group-hover:text-red-700 transition-colors pr-16">
                                                {{ $kelas->kelas->nama_kelas ?? 'Nama Kelas' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $kelas->kategoriPertandingan->nama_kategori ?? 'Kategori Pertandingan' }}
                                            </p>
                                        </div>
                                        
                                        {{-- Bagian Bawah: Detail Tambahan --}}
                                        <div class="mt-4 space-y-2 text-sm text-gray-600">
                                            
                                            {{-- Detail 1: Jenis Pertandingan --}}
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>{{ $kelas->jenisPertandingan->nama_jenis ?? 'Jenis' }}</span>
                                            </div>
                                            
                                            {{-- Detail 2: Rentang Usia --}}
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span>{{ $kelas->kelas->rentangUsia->rentang_usia ?? 'Rentang Usia' }}</span>
                                            </div>
                                            
                                            {{-- Detail 3: Gender --}}
                                            <div class="flex items-center">
                                                {{-- Ikon Gender --}}
                                                @if($kelas->gender == 'Laki-laki')
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                                @elseif($kelas->gender == 'Perempuan')
                                                    <svg class="w-4 h-4 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                                @else
                                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.964A3 3 0 006 12v-1.5a3 3 0 013-3h.008v.008h-.008V12z" /></svg>
                                                @endif
                                                <span>{{ $kelas->gender }}</span>
                                            </div>
                                
                                        </div>
                                    </div>
                                    
                                    {{-- Footer Kartu untuk Jumlah Peserta --}}
                                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                                        <span class="inline-flex items-center text-xs font-semibold text-red-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                            {{ $kelas->players_count }} Peserta Terverifikasi
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                {{-- Tampilan Kartu Kosong yang Lebih Menarik --}}
                <div class="bg-white text-center rounded-xl shadow-sm border-2 border-dashed border-gray-300 p-8">
                    <div class="mx-auto w-12 h-12 flex items-center justify-center bg-gray-100 rounded-full">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 010-5.304m5.304 0a3.75 3.75 0 010 5.304m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.008H12V12z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-800">Belum Ada Data Bracket</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Tidak ada kelas kategori "Prestasi" yang memiliki peserta terverifikasi untuk ditampilkan saat ini.
                    </p>
                </div>
            @endforelse
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
                    <input type="hidden" name="player_ids" id="verificationPlayerIds" value="">
                    <div class="space-y-4">
                        <p>Anda akan memverifikasi <strong id="verificationItemName"></strong>.</p>
                        
                        {{-- Warning untuk Kontingen Belum Bayar --}}
                        <div id="unpaidContingentWarning" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start space-x-2.5">
                            <span class="text-yellow-500 font-bold text-base mt-0.5">⚠️</span>
                            <div>
                                <h4 class="text-xs font-bold text-yellow-800">Perhatian: Kontingen Belum Dibayar!</h4>
                                <p class="text-[11px] text-yellow-700 mt-0.5 leading-relaxed">Kontingen ini belum mengunggah bukti pembayaran pendaftaran kontingen untuk event ini.</p>
                            </div>
                        </div>

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
                    <input type="hidden" name="player_ids" id="rejectPlayerIds" value="">
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

            $('#pendingPlayersDataTable').DataTable(dtConfig);
            // Menghubungkan search input baru ke tabelnya
            $('#pendingPlayerDataSearch').on('keyup', function() { 
                $('#pendingPlayersDataTable').DataTable().search(this.value).draw(); 
            });

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
            // Sembunyikan semua section
            document.querySelectorAll('.section').forEach(section => section.classList.add('hidden'));
            
            // Tampilkan section yang dipilih
            const targetSection = document.getElementById(sectionId);
            if(targetSection) {
                targetSection.classList.remove('hidden');
            }

            // Atur style tombol navigasi
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('border-red-500', 'text-red-600');
                btn.classList.add('border-transparent', 'text-gray-500');

                if(btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(`'${sectionId}'`)) {
                    btn.classList.add('border-red-500', 'text-red-600');
                    btn.classList.remove('border-transparent', 'text-gray-500');
                }
            });
        }
        
        // Atur agar section "events" ditampilkan secara default saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            showSection('events');
        });

        function showSubSection(subSectionId) {
            document.querySelectorAll('.sub-section').forEach(section => section.classList.add('hidden'));
            const target = document.getElementById(subSectionId);
            if (target) target.classList.remove('hidden');
            
            document.querySelectorAll('.sub-nav-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(`'${subSectionId}'`)) {
                    btn.classList.add('active');
                }
            });
        }

        function openVerificationModal(type, id, name, actionUrl, isUnpaid = false) {
            document.getElementById('verificationModalTitle').textContent = `Verifikasi ${type}`;
            document.getElementById('verificationItemName').textContent = name;
            document.getElementById('verificationForm').action = actionUrl;
            document.getElementById('verificationPlayerIds').value = id || '';
            
            const warningEl = document.getElementById('unpaidContingentWarning');
            if (isUnpaid) {
                warningEl.classList.remove('hidden');
            } else {
                warningEl.classList.add('hidden');
            }

            const catatan = document.getElementById('catatan');
            catatan.value = '';
            catatan.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function openRejectModal(type, id, name, actionUrl) {
            document.getElementById('rejectModalTitle').textContent = `Tolak ${type}`;
            document.getElementById('rejectItemName').textContent = name;
            document.getElementById('rejectForm').action = actionUrl;
            document.getElementById('rejectPlayerIds').value = id || '';
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
            const players = Array.isArray(contingent.players) ? contingent.players : [];
            const transactions = Array.isArray(contingent.transactions) ? contingent.transactions : [];
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
            if (transactions.length > 0 && transactions[0].foto_invoice) {
                buktiBayarHtml = `<a href="${storageUrlPrefix}${transactions[0].foto_invoice}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Bayar</a>`;
            } else {
                buktiBayarHtml = `<span class="text-gray-500 italic">N/A</span>`;
            }

            // PERUBAHAN: Menambahkan biaya kontingen dan format angkanya
            const hargaKontingen = contingent.event ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(contingent.event.harga_contingent) : 'N/A';

            if (players.length > 0) {
                const playersByClass = players.reduce((acc, player) => {
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
                    <div><strong class="block text-gray-500">Biaya Kontingen</strong> <p>${hargaKontingen}</p></div>
                    <div class="md:col-span-2 bg-blue-50 rounded-lg p-3 border border-blue-100">
                        <strong class="block text-gray-600 text-xs uppercase tracking-wide mb-2">Informasi Manajer Kontingen</strong>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <div><span class="text-gray-500 text-xs">Nama Manajer:</span><p class="font-semibold text-gray-800">${contingent.manajer_name || '-'}</p></div>
                            <div><span class="text-gray-500 text-xs">No. Telepon:</span><p class="font-semibold text-gray-800">${contingent.no_telp || '-'}</p></div>
                            <div><span class="text-gray-500 text-xs">Email Kontingen:</span><p class="font-semibold text-gray-800">${contingent.email || '-'}</p></div>
                            <div><span class="text-gray-500 text-xs">Akun Pengguna:</span><p class="font-semibold text-gray-800">${contingent.user.nama_lengkap} (${contingent.user.email})</p></div>
                        </div>
                    </div>
                    
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

                    <div class="md:col-span-2"><strong class="block text-gray-500">Jumlah Atlet</strong> <p>${players.length} orang</p></div>
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

        function viewTeamDetail(players, isTeam = false) {
            if (!Array.isArray(players) || players.length === 0) return;
            const firstPlayer = players[0];
            const playerNames = players.map(p => p.name).join(', ');
            detailModalTitle.textContent = isTeam ? ('Detail Tim / Invoice: ' + playerNames) : ('Detail Atlet: ' + firstPlayer.name);
            
            const storageUrlPrefix = "/storage/";
            const formatCurrency = (number) => { if (number === null || typeof number === 'undefined') return 'N/A'; return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); };
            const formatCurrencyNoRP = (number) => {
                if (number === null || typeof number === 'undefined') return 'N/A';
                return new Intl.NumberFormat('id-ID', { style: 'decimal', minimumFractionDigits: 0 }).format(number);
            };

            const invoiceLinkHtml = firstPlayer.player_invoice?.foto_invoice ? `<a href="${storageUrlPrefix}${firstPlayer.player_invoice.foto_invoice}" target="_blank" class="text-blue-600 hover:underline font-semibold">Lihat Bukti Bayar</a>` : `<span class="text-gray-500 italic">Belum ada invoice/pembayaran</span>`;
            const totalInvoiceHtml = firstPlayer.player_invoice ? `<span class="font-bold text-blue-600">${formatCurrency(firstPlayer.player_invoice.total_price)}</span>` : `<span class="text-gray-500 italic">N/A</span>`;

            let lastClassId = null;
            let membersHtml = players.map((player, index) => {
                let docLinks = [];
                if (player.foto_ktp) docLinks.push(`<a href="${storageUrlPrefix}${player.foto_ktp}" target="_blank" class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium hover:bg-blue-100">KTP</a>`);
                if (player.foto_diri) docLinks.push(`<a href="${storageUrlPrefix}${player.foto_diri}" target="_blank" class="px-2.5 py-1 bg-green-50 text-green-700 rounded text-xs font-medium hover:bg-green-100">Foto Diri</a>`);
                if (player.foto_persetujuan_ortu) docLinks.push(`<a href="${storageUrlPrefix}${player.foto_persetujuan_ortu}" target="_blank" class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded text-xs font-medium hover:bg-purple-100">Izin Ortu</a>`);
                if (docLinks.length === 0) docLinks.push(`<span class="text-gray-400 italic text-xs">Tidak ada dokumen</span>`);

                const tglLahirFormatted = player.tgl_lahir ? new Date(player.tgl_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';

                const pKategori = player.kelas_pertandingan?.kategori_pertandingan?.nama_kategori || 'N/A';
                const pJenis = player.kelas_pertandingan?.jenis_pertandingan?.nama_jenis || 'N/A';
                const pKelas = player.kelas_pertandingan?.kelas?.nama_kelas || 'N/A';
                const pRentangUsia = player.kelas_pertandingan?.kelas?.rentang_usia?.rentang_usia || 'N/A';
                const pHarga = player.kelas_pertandingan ? formatCurrency(player.kelas_pertandingan.harga) : 'N/A';

                // Tampilkan pembatas jika kelas tanding berbeda dengan atlet sebelumnya
                let dividerHtml = '';
                const currentClassId = player.kelas_pertandingan_id;
                if (index > 0 && lastClassId !== currentClassId) {
                    dividerHtml = `
                        <div class="my-4 flex items-center">
                            <div class="flex-grow border-t-2 border-dashed border-red-300"></div>
                            <span class="mx-3 text-xs text-red-500 font-semibold uppercase tracking-wider bg-white px-2">Kelas Tanding</span>
                            <div class="flex-grow border-t-2 border-dashed border-red-300"></div>
                        </div>
                    `;
                }
                lastClassId = currentClassId;

                return `
                    ${dividerHtml}
                    <div class="border rounded-lg p-3 bg-gray-50/50 mb-3 border-gray-200">
                        <div class="flex items-center justify-between border-b pb-2 mb-2">
                            <span class="font-semibold text-gray-800 text-sm">${isTeam ? `Anggota ${index + 1}: ${player.name}` : player.name}</span>
                            <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-700 rounded">${player.gender || '-'}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs mb-2">
                            <div><span class="text-gray-500">NIK:</span> <span class="font-mono">${player.nik || '-'}</span></div>
                            <div><span class="text-gray-500">Tgl Lahir:</span> <span>${tglLahirFormatted}</span></div>
                            <div><span class="text-gray-500">Email:</span> <span>${player.email || '-'}</span></div>
                            <div><span class="text-gray-500">No. Telp:</span> <span>${player.no_telp || '-'}</span></div>
                        </div>
                        
                        <!-- Rincian Kelas & Kategori Pemain -->
                        <div class="bg-yellow-50 p-2.5 rounded border border-yellow-100 my-2 text-xs">
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                <div><span class="text-gray-500 block">Kategori:</span> <span class="font-semibold text-gray-800">${pKategori}</span></div>
                                <div><span class="text-gray-500 block">Jenis/Tipe:</span> <span class="font-semibold text-gray-800">${pJenis}</span></div>
                                <div><span class="text-gray-500 block">Kelas:</span> <span class="font-semibold text-gray-800">${pKelas}</span></div>
                                <div><span class="text-gray-500 block">Rentang Usia:</span> <span class="font-semibold text-gray-800">${pRentangUsia}</span></div>
                                <div><span class="text-gray-500 block">Biaya Kelas:</span> <span class="font-semibold text-green-600">${pHarga}</span></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-200">
                            <span class="text-xs font-medium text-gray-500">Dokumen:</span>
                            ${docLinks.join(' ')}
                        </div>
                        ${player.catatan ? `<div class="mt-2 text-xs text-red-600 bg-red-50 p-1.5 rounded">Catatan: ${player.catatan}</div>` : ''}
                    </div>
                `;
            }).join('');

            detailModalContent.innerHTML = `
                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                        <div><strong class="text-gray-600">Event:</strong> <p class="text-gray-900 font-medium">${firstPlayer.contingent?.event?.name || '-'}</p></div>
                        <div><strong class="text-gray-600">Kontingen:</strong> <p class="text-gray-900 font-medium">${firstPlayer.contingent?.name || '-'}</p></div>
                        <div><strong class="text-gray-600">Manajer Kontingen:</strong> <p class="text-gray-900 font-medium">${firstPlayer.contingent?.manajer_name || '-'}</p></div>
                        <div><strong class="text-gray-600">No. Telp Manajer:</strong> <p class="text-gray-900 font-medium">${firstPlayer.contingent?.no_telp || '-'}</p></div>
                        <div class="md:col-span-2"><strong class="text-gray-600">Email Kontingen:</strong> <p class="text-gray-900 font-medium">${firstPlayer.contingent?.email || '-'}</p></div>
                    </div>

                    <div>
                        <strong class="block text-gray-700 font-semibold mb-2 border-b pb-1">Daftar Atlet &amp; Detail Kelas (${players.length} Orang)</strong>
                        <div class="max-h-[28rem] overflow-y-auto pr-1">
                            ${membersHtml}
                        </div>
                    </div>

                    <div>
                        <strong class="block text-gray-700 font-semibold mb-2 border-b pb-1">Informasi Pembayaran</strong>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs bg-gray-50 p-3 rounded-lg">
                            <div><span class="text-gray-500 block">Total Invoice:</span> ${totalInvoiceHtml}</div>
                            <div><span class="text-gray-500 block">Bukti Bayar:</span> ${invoiceLinkHtml}</div>
                        </div>
                    </div>
                </div>
            `;
            detailModal.classList.remove('hidden');
        }

        function viewPlayerDetail(player) {
            viewTeamDetail([player], false);
        }
    </script>
</body>

</html>