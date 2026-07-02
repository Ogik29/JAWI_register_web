<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bracket: {{ $kelas->kelas->nama_kelas }}</title>
    
    {{-- ASET CSS UNTUK DATATABLES & BUTTONS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- STYLE TIDAK DIUBAH SAMA SEKALI UNTUK MEMPERTAHANKAN TAMPILAN ASLI --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&family=Inter:wght@400;500;600;700&display=swap');
        
        :root {
            --color-merah-gelap: #8B0000;
            --color-merah-terang: #DC143C;
            --color-merah-teks: #991b1b;
            --color-putih: #ffffff;
            --color-abu-terang: #f4f4f7;
            --color-border: #e0e0e0;
            --color-teks-utama: #2c2c2c;
            --color-teks-sekunder: #6c757d;
            --match-width: 250px;
            --round-gap: 80px;
            --match-vert-gap: 54px;
        }

        body { margin: 0; font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: var(--color-teks-utama); }
        .header { background: linear-gradient(90deg, var(--color-merah-gelap) 0%, var(--color-merah-terang) 100%); color: var(--color-putih); padding: 25px 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; justify-content: space-between; align-items: center; }
        .header .title h1 { margin: 0; font-family: 'Roboto Condensed', sans-serif; font-size: 1.8em; text-transform: uppercase; letter-spacing: 1px; }
        .header .title p { margin: 4px 0 0; color: rgba(255, 255, 255, 0.9); opacity: 0.9; }
        .actions-container { display: flex; gap: 10px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 22px; border: 2px solid transparent; border-radius: 8px; font-weight: 700; text-decoration: none; cursor: pointer; transition: all 0.25s ease; text-transform: uppercase; font-family: 'Inter', sans-serif; }
        .btn-home { background-color: rgba(255,255,255,0.2); color: var(--color-putih); }
        .btn-home:hover { background-color: rgba(255,255,255,0.3); }
        .btn-draw { background-color: var(--color-putih); color: var(--color-merah-gelap); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-draw:hover { background-color: #f0f2f5; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .notification { margin: 20px 40px; padding: 15px 25px; border-radius: 8px; font-weight: 500; border-left: 5px solid; }
        .notification.success { background-color: #e6f7ec; color: #006421; border-color: #4caf50; }
        .notification.error { background-color: #fdecea; color: #a30000; border-color: var(--color-merah-gelap); }
        .main-content { padding: 30px 40px; }
        .player-pool-container { background-color: var(--color-putih); border-radius: 12px; padding: 25px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .player-pool-container h3 { margin-top: 0; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4em; color: var(--color-teks-utama); text-transform: uppercase; }
        .player-pool-container p { margin-top:-15px; margin-bottom:20px; color: var(--color-teks-sekunder); }
        .player-pool { display: flex; flex-wrap: wrap; gap: 12px; min-height: 80px; padding: 10px; background-color: #f8f9fa; border-radius: 8px; border: 2px dashed #e0e0e0;}
        
        .player-unit-wrapper.sortable-ghost { opacity: 0.4; background-color: #c7d2fe; border-style: solid; }
        .player-unit-wrapper.sortable-chosen { box-shadow: 0 8px 16px rgba(0,0,0,0.2); }

        .player-item { display: flex; flex-direction: column; padding: 8px 14px; background-color: #f4f4f7; border-radius: 6px; }
        .player-item:not(:last-child) { margin-bottom: 5px; }
        .player-item .player-name { font-weight: 600; font-size: 0.9em; color: var(--color-teks-utama); }
        .player-item .player-contingent { font-size: 0.8em; color: var(--color-teks-sekunder); }
        .bracket-wrapper { overflow-x: auto; padding: 40px 30px; background-color: var(--color-abu-terang); border-radius: 12px; box-shadow: inset 0 2px 8px rgba(0,0,0,0.06); }
        .bracket { display: flex; }
        .round { display: flex; flex-direction: column; flex-shrink: 0; min-width: var(--match-width); margin-right: var(--round-gap); justify-content: space-around; }
        .round-title { width: 100%; text-align: center; font-family: 'Roboto Condensed', sans-serif; font-size: 1.3em; color: var(--color-merah-teks); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 30px; }
        .match-wrapper { background-color: var(--color-putih); border-radius: 8px; overflow: hidden; border: 1px solid var(--color-border); border-left: 4px solid var(--color-merah-gelap); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.07); transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .match-wrapper:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1); }
        .round > .match-wrapper:not(:last-child) { margin-bottom: var(--match-vert-gap); }
        .player-slot { padding: 8px; min-height: 80px; box-sizing: border-box; display: flex; flex-direction: column; align-items: stretch; justify-content: center; position: relative; transition: background-color 0.2s; }
        .player-slot:empty::after { content: 'Slot Tersedia'; display: flex; align-items: center; justify-content: center; width: calc(100% - 16px); height: calc(100% - 16px); color: var(--color-teks-sekunder); border: 2px dashed #e0e0e0; border-radius: 7px; font-size: 0.9em; position: absolute; top: 8px; left: 8px;}
        .player-slot + .player-slot { border-top: 1px solid var(--color-border); }

        .player-unit-wrapper { border: 2px solid #e0e0e0; border-radius: 8px; padding: 5px; background-color: white; }
        .player-unit-wrapper.is-draggable { cursor: grab; }
        .player-unit-wrapper.is-draggable:hover { border-color: #4f46e5; }
        .player-placeholder { width: 100%; text-align: center; color: #7f8c8d; font-style: italic; }

        .participants-table-container { background-color: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px; }
        .participants-table-container h3 { margin-top: 0; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4em; color: var(--color-teks-utama); text-transform: uppercase; }
        .dataTables_wrapper .dataTables_filter input { border-radius: 8px; border: 1px solid #ccc; padding: 5px 10px; margin-left: 5px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: var(--color-merah-gelap) !important; color: white !important; border-radius: 50% !important; border: none !important; }
        .dt-buttons .dt-button { background-color: #333 !important; color: white !important; border:none; border-radius: 5px; }
        #participantsTable thead { background-color: #f9fafb; }
    </style>
</head>
<body>
    <header class="header">
        <div class="title">
            <h1>Pengaturan Pertandingan: {{ $kelas->kelas->nama_kelas }} ({{ $kelas->gender }})</h1>
            <p>{{ $kelas->kategoriPertandingan->nama_kategori }} - {{ $kelas->jenisPertandingan->nama_jenis }} - {{ $kelas->kelas->rentangUsia->rentang_usia }}</p>
        </div>
        <div class="actions-container">
             <a href="{{ route('bracket.exportExcel', $kelas) }}" class="btn" style="background-color: #1D6F42; color: white;">
                <i class="bi bi-file-earmark-excel-fill" style="margin-right: 8px;"></i> Export Excel
            </a>
            <a href="{{ route('bracket.exportAll', $kelas) }}" class="btn" style="background-color: #0B5ED7; color: white;">
                <i class="bi bi-file-earmark-spreadsheet-fill" style="margin-right: 8px;"></i> Export All
            </a>
            <a href="{{ route('adminIndex') }}#bracket" class="btn btn-home">Kembali</a>
            <form action="{{ route('bracket.generate', $kelas) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-draw" onclick="return confirm('Yakin ingin mengundi ulang? Pengaturan manual akan hilang.')">
                    DRAW / UNDI ULANG
                </button>
            </form>
        </div>
    </header>

    @if(session('success')) <div class="notification success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notification error">{{ session('error') }}</div> @endif

    <div class="main-content">
        @if ($allApprovedPlayers->isNotEmpty())
            <div class="participants-table-container">
                <h3>Daftar Peserta Terverifikasi ({{ $allApprovedPlayers->count() }} Orang)</h3>
                <table id="participantsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Kontingen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allApprovedPlayers as $player)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $player->name }}</td>
                            <td>{{ $player->contingent->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- [DIUBAH] Menggunakan logika dari file kedua yang menggunakan $unassignedUnits --}}
        @if ($unassignedUnits->isNotEmpty())
            <div class="player-pool-container">
                <h3>Daftar Unit Belum Ditempatkan</h3>
                <p>Geser unit dari daftar ini ke slot kosong di babak manapun.</p>
                <div id="player-pool" class="player-pool">
                    @foreach ($unassignedUnits as $unit_id => $playersInUnit)
                        <div class="player-unit-wrapper is-draggable" data-unit-id="{{ $unit_id }}">
                            @foreach ($playersInUnit as $peserta)
                                <div class="player-item">
                                    <span class="player-name">{{ $peserta->player->name }}</span>
                                    <span class="player-contingent">{{ $peserta->player->contingent->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- [DIUBAH] Menggunakan struktur HTML bracket dari file kedua yang lebih sederhana dan fungsional --}}
        @if($totalRounds > 0)
        <div class="bracket-wrapper">
            <div class="bracket">
                @for ($r = 1; $r <= $totalRounds; $r++)
                    <div class="round">
                        <div class="round-title">
                            @if ($r == $totalRounds) Final @elseif ($r == $totalRounds - 1) Semi Final @elseif ($r == $totalRounds - 2 && $totalRounds > 2) Perempat Final @elseif ($r == $totalRounds - 3 && $totalRounds > 3) Perdelapan Final @else Babak Penyisihan {{ $r }} @endif
                        </div>
                        @if(isset($rounds[$r]))
                            @foreach ($rounds[$r] as $match)
                                <div class="match-wrapper" data-match-id="{{ $match->id }}">
                                    {{-- Slot menjadi container yang selalu bisa menerima item --}}
                                    {{ $match->id }}
                                    <div class="player-slot" data-slot="1">
                                        @if($match->pemain_unit_1->isNotEmpty())
                                            <div class="player-unit-wrapper is-draggable" data-unit-id="{{ $match->unit1_id }}">
                                                @foreach($match->pemain_unit_1 as $peserta)
                                                    <div class="player-item">
                                                        <span class="player-name">{{ $peserta->player->name }}</span>
                                                        <span class="player-contingent">{{ $peserta->player->contingent->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="player-slot" data-slot="2">
                                        @if($match->pemain_unit_2->isNotEmpty())
                                            <div class="player-unit-wrapper is-draggable" data-unit-id="{{ $match->unit2_id }}">
                                                @foreach($match->pemain_unit_2 as $peserta)
                                                    <div class="player-item">
                                                        <span class="player-name">{{ $peserta->player->name }}</span>
                                                        <span class="player-contingent">{{ $peserta->player->contingent->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            {{-- Logika placeholder yang disederhanakan --}}
                                            @if($r == 1 && $match->unit1_id && !$match->unit2_id)
                                                <div class="player-placeholder">BYE</div>
                                            @elseif ($r > 1)
                                                 <div class="player-placeholder">Menunggu Pemenang</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endfor
            </div>
        </div>
        @else
        <div class="player-pool-container text-center">
            <h3>Bracket Belum Dibuat</h3>
            <p>Silakan tekan tombol "DRAW / UNDI ULANG" untuk membuat bagan pertandingan.</p>
        </div>
        @endif

    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi DataTables dipertahankan dari file asli
            $('#participantsTable').DataTable({
                dom: '<"flex justify-between items-center mb-4"fB>rt<"flex justify-between items-center mt-4"ip>',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> Print',
                        titleAttr: 'Print',
                        title: 'Daftar Peserta - {{ $kelas->kelas->nama_kelas }}'
                    }
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan MENU entri",
                    info: "Menampilkan START - END dari TOTAL peserta",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari MAX)",
                    paginate: { first: "<<", last: ">>", next: ">", previous: "<" },
                    searchPlaceholder: "Masukkan nama atau kontingen..."
                }
            });

            // [DIUBAH] Menggunakan script dari file kedua yang lebih canggih
            const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json', 'Accept': 'application/json' };
            
            function saveUnitPosition(unitId, matchId, slotNumber) {
                 fetch('{{ route("bracket.updatePosition") }}', { 
                     method: 'POST', headers: headers, 
                     body: JSON.stringify({ unit_id: unitId, match_id: matchId, slot: slotNumber }) 
                 })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status >= 400) { alert('Error: ' + body.message); } 
                    else { console.log('Success:', body.message); }
                    location.reload(); 
                }).catch((error) => { 
                    console.error('Fatal Error:', error); 
                    alert('Gagal menyimpan posisi pemain.'); 
                    location.reload(); 
                });
            };
            
            // Ambil SEMUA container yang bisa menerima item (pool dan semua slot)
            const containers = document.querySelectorAll('.player-pool, .player-slot');

            containers.forEach(container => {
                new Sortable(container, {
                    group: 'units', // Nama grup yang sama memungkinkan perpindahan antar container
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    draggable: '.is-draggable', // Menentukan elemen apa yang bisa di-drag

                    onEnd: function (evt) {
                        const draggedUnitEl = evt.item;
                        const targetContainer = evt.to;

                        // Hanya panggil save jika item dipindahkan ke dalam slot pertandingan
                        if (targetContainer.classList.contains('player-slot')) {
                            const unitId = draggedUnitEl.dataset.unitId;
                            const matchId = targetContainer.closest('.match-wrapper').dataset.matchId;
                            const slotNumber = targetContainer.dataset.slot;
                            
                            saveUnitPosition(unitId, matchId, slotNumber);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>