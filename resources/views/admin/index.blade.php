<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Silat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .modal-content { max-height: 90vh; }
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .sub-nav-btn:not(.active) {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .sub-nav-btn:not(.active):hover {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm border-b">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center"><span class="text-white font-bold text-lg">🥋</span></div>
                <div><h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1><p class="text-sm text-gray-500">Event Management System</p></div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right"><p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_lengkap }}</p><p class="text-xs text-gray-500">{{ Auth::user()->role->name }}</p></div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center"><span class="text-gray-600 text-sm">👤</span></div>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b">
        <div class="px-6">
            <div class="flex space-x-8">
                <button onclick="showSection('dashboard')" class="nav-btn py-4 px-2 border-b-2 border-red-500 text-red-600 font-medium">📊 Dashboard</button>
                <button onclick="showSection('events')" class="nav-btn py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">🏆 Kelola Event</button>
            </div>
        </div>
    </nav>

    <main class="p-6">
        <div id="dashboard" class="section">
            <div class="mb-6"><h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2><p class="text-gray-600">Ringkasan aktivitas event silat Anda</p></div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-600">Total Kontingen</p><p class="text-2xl font-bold text-gray-900">{{ $totalContingents }}</p></div><div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><span class="text-blue-600 text-xl">🏢</span></div></div></div>
                <div class="bg-white p-6 rounded-xl shadow-sm border"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-600">Total Atlet</p><p class="text-2xl font-bold text-gray-900">{{ $totalPlayers }}</p></div><div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><span class="text-green-600 text-xl">👥</span></div></div></div>
                <div class="bg-white p-6 rounded-xl shadow-sm border"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-600">Kontingen Pending</p><p class="text-2xl font-bold text-orange-600">{{ $pendingContingentsCount }}</p></div><div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center"><span class="text-orange-600 text-xl">⏳</span></div></div></div>
                <div class="bg-white p-6 rounded-xl shadow-sm border"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-600">Atlet Pending</p><p class="text-2xl font-bold text-yellow-600">{{ $pendingPlayersCount }}</p></div><div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"><span class="text-yellow-600 text-xl">🏃</span></div></div></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-6"><h3 class="text-lg font-semibold text-gray-900 mb-4">Event Aktif Terbaru Anda</h3><div class="space-y-4">@forelse ($activeEvents as $event)<div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"><div><h4 class="font-medium text-gray-900">{{ $event->name }}</h4><p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->format('d M') }} - {{ \Carbon\Carbon::parse($event->tgl_selesai_tanding)->format('d M Y') }} • {{ $event->lokasi }}</p></div><span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Aktif</span></div>@empty<p class="text-sm text-gray-500">Tidak ada event yang sedang aktif.</p>@endforelse</div></div>
        </div>

        <div id="events" class="section hidden">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Daftar Event Anda</h3></div>
                <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead><tbody class="divide-y divide-gray-200">@forelse ($events as $event)<tr><td class="p-3"><div class="text-sm font-medium text-gray-900">{{ $event->name }}</div></td><td class="p-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->format('d M Y') }}</td><td class="p-3 text-sm text-gray-900">{{ $event->lokasi }}</td><td class="p-3">@if($event->status == 1) <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span> @elseif ($event->status == 0) <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Segera Dibuka</span> @else <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">Tutup</span> @endif</td><td class="p-3 text-sm text-gray-900">{{ $event->players_count }} atlet</td><td class="p-3"><button onclick='viewEventDetail(@json($event))' class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button></td></tr>@empty<tr><td colspan="6" class="p-3 text-center text-sm text-gray-500">Tidak ada event ditemukan.</td></tr>@endforelse</tbody></table></div>
            </div>
            
            <div class="mb-6 flex space-x-2 border-b pb-2">
                <button onclick="showSubSection('pending')" class="sub-nav-btn active">Menunggu Verifikasi</button>
                <button onclick="showSubSection('approved')" class="sub-nav-btn">Disetujui</button>
                <button onclick="showSubSection('rejected')" class="sub-nav-btn">Ditolak</button>
            </div>
            
            <div id="pending" class="sub-section">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Verifikasi Kontingen</h3></div>
                    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead><tbody class="divide-y divide-gray-200">@forelse ($contingentsForVerification as $contingent)<tr><td class="p-3"><div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div><div class="text-sm text-gray-500">{{ $contingent->event->name }}</div></td><td class="p-3"><div class="text-sm font-medium text-gray-900">{{ $contingent->manajer_name }}</div><div class="text-sm text-gray-500">{{ $contingent->no_telp }}</div></td><td class="p-3 text-sm text-blue-600"><a href="{{ Storage::url($contingent->surat_rekomendasi) }}" target="_blank" class="hover:underline">Surat Rekomendasi</a><br>@if($contingent->transactions->first() && $contingent->transactions->first()->foto_invoice)<a href="{{ Storage::url($contingent->transactions->first()->foto_invoice) }}" target="_blank" class="hover:underline">Bukti Bayar</a>@else<span class="text-gray-500">N/A</span>@endif</td><td class="p-3"><button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi</button><button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-xs font-medium ml-2">Detail</button></td></tr>@empty<tr><td colspan="4" class="p-3 text-center text-sm text-gray-500">Tidak ada kontingen perlu diverifikasi.</td></tr>@endforelse</tbody></table></div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Verifikasi Atlet</h3></div>
                    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen & Dokumen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead>
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
                                @foreach($registration['player_instances'] as $player)
                                <div class="text-xs text-blue-600">
                                    {{ $player->name }}: 
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
                                    <span class="text-gray-600 text-xs truncate" title="{{ $player->name }}">{{ \Illuminate\Support\Str::limit($player->name, 15) }}</span>
                                </div>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-3 text-center text-sm text-gray-500">Tidak ada atlet perlu diverifikasi.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
            </div>

            <div id="approved" class="sub-section hidden">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Daftar Kontingen Disetujui</h3></div>
                    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead><tbody class="divide-y divide-gray-200">@forelse($approvedContingents as $contingent)<tr><td class="p-3"><div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div><div class="text-sm text-gray-500">{{ $contingent->event->name }}</div></td><td class="p-3 text-sm text-gray-900">{{ $contingent->manajer_name }}</td><td class="p-3 text-sm text-gray-900">{{ $contingent->players->count() }} atlet</td><td class="p-3"><button onclick='viewContingentDetail(@json($contingent))' class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button></td></tr>@empty<tr><td colspan="4" class="p-3 text-center text-sm text-gray-500">Belum ada kontingen yang disetujui.</td></tr>@endforelse</tbody></table></div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Daftar Atlet Terverifikasi</h3></div>
                    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($groupedApprovedPlayers as $registration)
                        @php $firstPlayer = $registration['player_instances']->first(); @endphp
                        <tr>
                            <td class="p-3 text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</td>
                            <td class="p-3 text-sm text-gray-900">{{ $registration['nama_kelas'] }}</td>
                            <td class="p-3 text-sm text-gray-900">{{ $firstPlayer->contingent->name }}</td>
                            <td class="p-3">
                                @foreach($registration['player_instances'] as $player)
                                <button onclick='viewPlayerDetail(@json($player))' class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-2">Detail {{ \Illuminate\Support\Str::limit($player->name, 10) }}</button>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-3 text-center text-sm text-gray-500">Belum ada atlet yang terverifikasi.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
            </div>

            <div id="rejected" class="sub-section hidden">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Daftar Kontingen Ditolak</h3></div>
                    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Manajer</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead><tbody class="divide-y divide-gray-200">@forelse($rejectedContingents as $contingent)<tr><td class="p-3"><div class="text-sm font-medium text-gray-900">{{ $contingent->name }}</div><div class="text-sm text-gray-500">{{ $contingent->event->name }}</div></td><td class="p-3 text-sm text-gray-900">{{ $contingent->manajer_name }}</td><td class="p-3 text-sm text-gray-700 italic">"{{ $contingent->catatan ?: 'Tidak ada catatan' }}"</td><td class="p-3"><button onclick="openVerificationModal('contingent', '{{ $contingent->id }}', '{{ $contingent->name }}', '{{ route('admin.verify.contingent', $contingent->id) }}')" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600">Verifikasi Ulang</button></td></tr>@empty<tr><td colspan="4" class="p-3 text-center text-sm text-gray-500">Tidak ada kontingen yang ditolak.</td></tr>@endforelse</tbody></table></div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-900">Daftar Atlet Ditolak</h3></div>
                    <div class="overflow-x-auto"><table class="w-full"><thead class="bg-gray-50"><tr><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th><th class="p-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($groupedRejectedPlayers as $registration)
                        @php $firstPlayer = $registration['player_instances']->first(); @endphp
                        <tr>
                            <td class="p-3">
                                <div class="text-sm font-medium text-gray-900">{{ $registration['player_names'] }}</div>
                                <div class="text-sm text-gray-500">{{ $registration['nama_kelas'] }}</div>
                            </td>
                            <td class="p-3 text-sm text-gray-900">{{ $firstPlayer->contingent->name }}</td>
                            <td class="p-3 text-sm text-gray-700 italic">
                                "{{ $firstPlayer->catatan ?: 'Tidak ada catatan spesifik.' }}"
                            </td>
                            <td class="p-3">
                                @foreach($registration['player_instances'] as $player)
                                <div class="mb-1">
                                    <button onclick="openVerificationModal('player', '{{ $player->id }}', '{{ $player->name }}', '{{ route('admin.verify.player', $player->id) }}')" class="bg-yellow-500 text-white px-2 py-0.5 rounded text-xs hover:bg-yellow-600">Verifikasi Ulang {{ \Illuminate\Support\Str::limit($player->name, 10) }}</button>
                                </div>
                                @endforeach
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-3 text-center text-sm text-gray-500">Tidak ada atlet yang ditolak.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl modal-content overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4"><h3 id="detailModalTitle" class="text-xl font-semibold text-gray-900">Detail</h3><button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">&times;</button></div><div id="detailModalContent" class="text-sm"></div>
            </div>
            <div class="flex justify-end p-4 bg-gray-50 rounded-b-xl"><button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">Tutup</button></div>
        </div>
    </div>
    
    <div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-lg modal-content overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4"><h3 id="verificationModalTitle" class="text-lg font-semibold text-gray-900">Verifikasi</h3><button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600">&times;</button></div>
                <form id="verificationForm" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <p>Anda akan memverifikasi <strong id="verificationItemName"></strong>. Silakan periksa semua dokumen dan pembayaran yang terlampir.</p>
                        <div><label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (jika ditolak)</label><textarea name="catatan" id="catatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1" rows="3" placeholder="Tambahkan alasan penolakan..."></textarea></div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4"><button type="button" onclick="closeVerificationModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button><button type="submit" name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button><button type="submit" name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Setujui</button></div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function closeVerificationModal() {
            document.getElementById('verificationModal').classList.add('hidden');
        }

        const detailModal = document.getElementById('detailModal');
        const detailModalTitle = document.getElementById('detailModalTitle');
        const detailModalContent = document.getElementById('detailModalContent');
        
        function closeDetailModal() {
            detailModal.classList.add('hidden');
        }

        function viewEventDetail(event) {
            detailModalTitle.textContent = 'Detail Event: ' + event.name;
            let statusText = event.status == 1 ? 'Aktif' : (event.status == 0 ? 'Segera Dibuka' : 'Tutup');
            detailModalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong class="block text-gray-500">Status</strong> <p>${statusText}</p></div>
                    <div><strong class="block text-gray-500">Lokasi</strong> <p>${event.lokasi}</p></div>
                    <div><strong class="block text-gray-500">Tanggal Mulai</strong> <p>${new Date(event.tgl_mulai_tanding).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div>
                    <div><strong class="block text-gray-500">Tanggal Selesai</strong> <p>${new Date(event.tgl_selesai_tanding).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div>
                    <div><strong class="block text-gray-500">Batas Pendaftaran</strong> <p>${new Date(event.tgl_batas_pendaftaran).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div>
                    <div><strong class="block text-gray-500">Biaya Kontingen</strong> <p>Rp ${Number(event.harga_contingent).toLocaleString('id-ID')}</p></div>
                    <div><strong class="block text-gray-500">Total Peserta</strong> <p>${event.players_count} atlet</p></div>
                    <div><strong class="block text-gray-500">Contact Person</strong> <p>${event.cp}</p></div>
                </div>
                <div class="mt-4"><strong class="block text-gray-500">Deskripsi</strong> <p class="whitespace-pre-wrap">${event.desc || '-'}</p></div>
            `;
            detailModal.classList.remove('hidden');
        }

        function viewContingentDetail(contingent) {
            detailModalTitle.textContent = 'Detail Kontingen: ' + contingent.name;
            let playersList = contingent.players.length > 0 ? contingent.players.map(player => `<li>${player.name}</li>`).join('') : '<li>Belum ada peserta terdaftar.</li>';
            detailModalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong class="block text-gray-500">Event</strong> <p>${contingent.event.name}</p></div>
                    <div><strong class="block text-gray-500">Nama Manajer</strong> <p>${contingent.manajer_name}</p></div>
                    <div><strong class="block text-gray-500">Email</strong> <p>${contingent.email}</p></div>
                    <div><strong class="block text-gray-500">No. Telepon</strong> <p>${contingent.no_telp}</p></div>
                    <div><strong class="block text-gray-500">Pemilik Akun</strong> <p>${contingent.user.nama_lengkap}</p></div>
                    <div><strong class="block text-gray-500">Jumlah Atlet</strong> <p>${contingent.players.length} orang</p></div>
                </div>
                <div class="mt-4"><strong class="block text-gray-500">Catatan Admin</strong> <p class="whitespace-pre-wrap">${contingent.catatan || 'Tidak ada catatan.'}</p></div>
                <div class="mt-4"><strong class="block text-gray-500">Daftar Atlet</strong> <ul class="list-disc list-inside mt-1">${playersList}</ul></div>
            `;
            detailModal.classList.remove('hidden');
        }

        function viewPlayerDetail(player) {
            detailModalTitle.textContent = 'Detail Atlet: ' + player.name;
            
            const formatCurrency = (number) => {
                if (number === null || typeof number === 'undefined') return 'N/A';
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            };

            const invoiceLinkHtml = player.player_invoice?.foto_invoice 
                ? `<a href="/storage/${player.player_invoice.foto_invoice}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Bayar</a>`
                : `<span class="text-gray-500 italic">Belum ada invoice</span>`;

            const hargaKelasHtml = player.kelas_pertandingan 
                ? `<span class="font-bold text-green-600">${formatCurrency(player.kelas_pertandingan.harga)}</span>`
                : `<span class="text-gray-500 italic">N/A</span>`;

            const totalInvoiceHtml = player.player_invoice
                ? `<span class="font-bold text-blue-600">${formatCurrency(player.player_invoice.total_price)}</span>`
                : `<span class="text-gray-500 italic">N/A</span>`;

            detailModalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong class="block text-gray-500">Event</strong> <p>${player.contingent.event.name}</p></div>
                    <div><strong class="block text-gray-500">Kontingen</strong> <p>${player.contingent.name}</p></div>
                    <div><strong class="block text-gray-500">NIK</strong> <p>${player.nik}</p></div>
                    <div><strong class="block text-gray-500">Tanggal Lahir</strong> <p>${new Date(player.tgl_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p></div>
                    <div><strong class="block text-gray-500">Email</strong> <p>${player.email || '-'}</p></div>
                    <div><strong class="block text-gray-500">No. Telepon</strong> <p>${player.no_telp || '-'}</p></div>
                    <div><strong class="block text-gray-500">Gender</strong> <p>${player.gender}</p></div>
                    <div><strong class="block text-gray-500">Kelas Tanding</strong> <p>${player.kelas_pertandingan.kelas.nama_kelas || 'N/A'}</p></div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <strong class="block text-gray-600 text-base mb-2">Informasi Pembayaran</strong>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><strong class="block text-gray-500">Harga Kelas Peserta</strong><p>${hargaKelasHtml}</p></div>
                        <div><strong class="block text-gray-500">Total Tagihan Invoice</strong><p>${totalInvoiceHtml}</p></div>
                        <div><strong class="block text-gray-500">Bukti Bayar</strong><p>${invoiceLinkHtml}</p></div>
                    </div>
                </div>
                <div class="mt-4"><strong class="block text-gray-500">Catatan Admin</strong> <p class="whitespace-pre-wrap">${player.catatan || 'Tidak ada catatan.'}</p></div>
            `;
            detailModal.classList.remove('hidden');
        }
    </script>
</body>
</html>```