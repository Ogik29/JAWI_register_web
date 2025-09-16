<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bracket: {{ $kelas->kelas->nama_kelas }}</title>
    
    <style>
        /* Impor Font Google */
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&family=Inter:wght@400;500;600;700&display=swap');

        /* [DESAIN TOTAL BARU] Variabel Desain Sistem - TEMA CHAMPIONSHIP JELAS */
        :root {
            /* Palet Warna */
            --color-merah-gelap: #8B0000;      /* Maroon / Dark Red */
            --color-merah-terang: #DC143C;     /* Crimson Red untuk Aksen */
            --color-merah-teks: #991b1b;       /* Darkest Red untuk Teks Judul */
            --color-putih: #ffffff;
            --color-abu-terang: #f4f4f7;       /* Latar Belakang Bracket (sangat penting untuk kontras) */
            --color-border: #e0e0e0;
            --color-teks-utama: #2c2c2c;
            --color-teks-sekunder: #6c757d;

            /* Ukuran & Jarak */
            --match-width: 250px;
            --round-gap: 80px;
            --match-vert-gap: 54px; /* Dibuat lebih besar untuk ruang nafas antar kartu */
        }

        /* Konfigurasi Dasar */
        body { margin: 0; font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: var(--color-teks-utama); }

        /* Header Utama */
        .header {
            background: linear-gradient(90deg, var(--color-merah-gelap) 0%, var(--color-merah-terang) 100%);
            color: var(--color-putih);
            padding: 25px 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex; justify-content: space-between; align-items: center;
        }
        .header .title h1 { margin: 0; font-family: 'Roboto Condensed', sans-serif; font-size: 1.8em; text-transform: uppercase; letter-spacing: 1px; }
        .header .title p { margin: 4px 0 0; color: rgba(255, 255, 255, 0.9); opacity: 0.9; }

        /* Tombol di Header */
        .actions-container { display: flex; gap: 10px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 22px; border: 2px solid transparent; border-radius: 8px; font-weight: 700; text-decoration: none; cursor: pointer; transition: all 0.25s ease; text-transform: uppercase; font-family: 'Inter', sans-serif; }
        .btn-home { background-color: rgba(255,255,255,0.2); color: var(--color-putih); }
        .btn-home:hover { background-color: rgba(255,255,255,0.3); }
        .btn-draw { background-color: var(--color-putih); color: var(--color-merah-gelap); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-draw:hover { background-color: #f0f2f5; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        
        /* Notifikasi */
        .notification { margin: 20px 40px; padding: 15px 25px; border-radius: 8px; font-weight: 500; border-left: 5px solid; }
        .notification.success { background-color: #e6f7ec; color: #006421; border-color: #4caf50; }
        .notification.error { background-color: #fdecea; color: #a30000; border-color: var(--color-merah-gelap); }
        
        /* Konten Utama & Daftar Pemain */
        .main-content { padding: 30px 40px; }
        .player-pool-container { background-color: var(--color-putih); border-radius: 12px; padding: 25px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .player-pool-container h3 { margin-top: 0; font-family: 'Roboto Condensed', sans-serif; font-size: 1.4em; color: var(--color-teks-utama); text-transform: uppercase; }
        .player-pool-container p { margin-top:-15px; margin-bottom:20px; color: var(--color-teks-sekunder); }
        .player-pool { display: flex; flex-wrap: wrap; gap: 12px; }
        
        /* Item Pemain */
        .player-item { display: flex; flex-direction: column; padding: 8px 14px; background-color: #f4f4f7; border-radius: 6px; }
        .player-item:not(:last-child) { margin-bottom: 5px; } /* Memberi jarak jika ada >1 pemain di satu unit */
        .player-item.is-draggable { cursor: grab; transition: background-color 0.2s; }
        .player-item.is-draggable:hover { background-color: #e9e9e9; }
        .player-item .player-name { font-weight: 600; font-size: 0.9em; color: var(--color-teks-utama); }
        .player-item .player-contingent { font-size: 0.8em; color: var(--color-teks-sekunder); }
        
        /* [PERBAIKAN] Area Bracket & Layouting */
        .bracket-wrapper {
            overflow-x: auto;
            padding: 40px 30px; /* Memberi ruang nafas di dalam bracket */
            background-color: var(--color-abu-terang); /* Latar belakang kontras */
            border-radius: 12px;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.06);
        }
        .bracket { display: flex; align-items: center; }
        .round { display: flex; flex-direction: column; flex-shrink: 0; min-width: var(--match-width); margin-right: var(--round-gap); justify-content: space-around; }
        .round-title { width: 100%; text-align: center; font-family: 'Roboto Condensed', sans-serif; font-size: 1.3em; color: var(--color-merah-teks); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 30px; }
        
        .match-wrapper {
            background-color: var(--color-putih);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--color-border);
            border-left: 4px solid var(--color-merah-gelap);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.07);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .match-wrapper:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
        }

        .round > .match-wrapper:not(:last-child) { margin-bottom: var(--match-vert-gap); }
        
        /* Slot Pemain */
        .player-slot { padding: 12px 14px; min-height: 58px; box-sizing: border-box; display: flex; flex-direction: column; align-items: stretch; justify-content: center; position: relative; }
        .player-slot.draggable:empty::after { content: 'Slot Tersedia'; display: flex; align-items: center; justify-content: center; width: calc(100% - 2px); height: calc(100% - 2px); color: var(--color-teks-sekunder); border: 2px dashed #e0e0e0; border-radius: 7px; font-size: 0.9em; font-weight: 500; position: absolute; top: 1px; left: 1px;}
        .player-slot.locked .player-placeholder { width: 100%; text-align: center; color: var(--color-teks-sekunder); font-size: 0.9em; font-style: italic; }
        .player-slot.bye-locked { background-color: #fdfdfd; }
        .player-slot + .player-slot { border-top: 1px solid var(--color-border); }

    </style>
</head>
<body>
    <header class="header">
        <div class="title">
            <h1>Pengaturan Pertandingan: {{ $kelas->kelas->nama_kelas }} ({{ $kelas->gender }})</h1>
            <p>{{ $kelas->kategoriPertandingan->nama_kategori }} - {{ $kelas->jenisPertandingan->nama_jenis }} - {{ $kelas->kelas->rentangUsia->rentang_usia }}</p>
        </div>
        <div class="actions-container">
            <a href="{{ route('adminIndex') }}#bracket" class="btn btn-home">Kembali</a>
            <form action="{{ route('bracket.generate', $kelas) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-draw" onclick="return confirm('Yakin ingin mengundi ulang? Pengaturan manual di babak 1 akan hilang.')">
                    DRAW / UNDI ULANG
                </button>
            </form>
        </div>
    </header>

    @if(session('success')) <div class="notification success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="notification error">{{ session('error') }}</div> @endif

    <div class="main-content">
        @if ($totalRounds > 0 && $unassignedPlayers->isNotEmpty())
            <div class="player-pool-container">
                <h3>Daftar Pemain Belum Ditempatkan</h3>
                <p>Geser pemain dari daftar ini ke slot kosong di Babak 1 untuk mengatur atau menukar posisi pertandingan.</p>
                <div id="player-pool" class="player-pool">
                    @foreach ($unassignedPlayers as $player)
                        <div class="player-item is-draggable" data-player-id="{{ $player->id }}">
                            <span class="player-name">{{ $player->name }}</span>
                            <span class="player-contingent">{{ $player->contingent->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bracket-wrapper">
            <div class="bracket">
                @for ($r = 1; $r <= $totalRounds; $r++)
                    <div class="round">
                        <div class="round-title">
                             @if ($r == $totalRounds) Final
                            @elseif ($r == $totalRounds - 1) Semi Final
                            @elseif ($r == $totalRounds - 2 && $totalRounds > 2) Perempat Final
                            @else Babak {{ $r }}
                            @endif
                        </div>
                        @if(isset($rounds[$r]))
                            @foreach ($rounds[$r] as $match)
                                <div class="match-wrapper" data-match-id="{{ $match->id }}">
                                    
                                    {{-- ===================== SLOT 1 (PERBAIKAN) ===================== --}}
                                    <div class="player-slot @if($r == 1) draggable @else locked @endif" data-slot="1">
                                        @if($match->pemain_unit_1->isNotEmpty())
                                            @foreach($match->pemain_unit_1 as $peserta)
                                                <div class="player-item @if($r == 1) is-draggable @endif" data-player-id="{{ $peserta->player->id }}">
                                                    <span class="player-name">{{ $peserta->player->name }}</span>
                                                    <span class="player-contingent">{{ $peserta->player->contingent->name }}</span>
                                                </div>
                                            @endforeach
                                        @elseif ($r > 1)
                                            <div class="player-placeholder">Menunggu Pemenang</div>
                                        @endif
                                    </div>

                                    {{-- Logika untuk mengunci slot BYE (PERBAIKAN) --}}
                                    @php
                                        $isByeSlotLocked = $r == 1 && $match->unit1_id && !$match->unit2_id;
                                    @endphp

                                    {{-- ===================== SLOT 2 (PERBAIKAN) ===================== --}}
                                    <div class="player-slot @if($r == 1 && !$isByeSlotLocked) draggable @else locked @endif @if($isByeSlotLocked) bye-locked @endif" data-slot="2">
                                        @if($match->pemain_unit_2->isNotEmpty())
                                            @foreach($match->pemain_unit_2 as $peserta)
                                                <div class="player-item @if($r == 1) is-draggable @endif" data-player-id="{{ $peserta->player->id }}">
                                                    <span class="player-name">{{ $peserta->player->name }}</span>
                                                    <span class="player-contingent">{{ $peserta->player->contingent->name }}</span>
                                                </div>
                                            @endforeach
                                        @elseif ($r > 1 || $isByeSlotLocked)
                                            <div class="player-placeholder">{{ $r > 1 ? 'Menunggu Pemenang' : '' }}</div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json', 'Accept': 'application/json' };
            const savePlayerPosition = (playerId, matchId, slotNumber) => {
                fetch('{{ route("bracket.updatePosition") }}', { method: 'POST', headers: headers, body: JSON.stringify({ player_id: playerId, match_id: matchId, slot: slotNumber }) })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status >= 400) { 
                        alert('Error: ' + body.message); 
                        location.reload(); 
                    } else { 
                        console.log('Success:', body.message); 
                        // Muat ulang halaman agar perubahan (misalnya swap) terlihat
                        location.reload(); 
                    }
                }).catch((error) => { 
                    console.error('Fatal Error:', error); 
                    alert('Gagal menyimpan posisi pemain.'); 
                    location.reload(); 
                });
            };
            
            const playerPool = document.getElementById('player-pool');
            const draggableSlots = document.querySelectorAll('.player-slot.draggable');
            
            const sortableOptions = {
                group: 'players', 
                animation: 150,
                // Fungsi ini dipanggil saat item ditambahkan ke list/slot
                onAdd: function (evt) {
                    // Jangan lakukan apa-apa jika pemain dikembalikan ke pool
                    if (evt.to.id === 'player-pool') {
                        // Di masa depan, Anda bisa menambahkan logika untuk MENGHAPUS pemain dari slot
                        return;
                    }
                    const playerId = evt.item.dataset.playerId;
                    const matchId = evt.to.closest('.match-wrapper').dataset.matchId;
                    const slotNumber = evt.to.dataset.slot;
                    savePlayerPosition(playerId, matchId, slotNumber);
                }
            };
            
            if (playerPool) new Sortable(playerPool, sortableOptions);
            
            draggableSlots.forEach(slot => { 
                new Sortable(slot, sortableOptions); 
            });
        });
    </script>
</body>
</html>