<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bracket: {{ $kelas->kelas->nama_kelas }}</title>
    
    <style>
        /* Impor Font Google */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        /* [PERBAIKAN] Variabel Desain Sistem dengan Ukuran yang Lebih Kompak */
        :root {
            --bracket-gap: 60px;       /* Jarak antar ronde, dari 80px */
            --match-width: 240px;      /* Lebar kotak, dari 280px */
            --match-vert-gap: 25px;  /* Jarak vertikal antar kotak, dari 40px */
            --color-bg: #f8f9fa; 
            --color-surface: #ffffff; 
            --color-border: #dee2e6; 
            --color-connector: #ced4da;
            --text-primary: #212529; 
            --text-secondary: #6c757d; 
            --accent-primary: #4f46e5;
            --accent-primary-hover: #4338ca; 
            --accent-success: #16a34a; 
            --accent-success-hover: #15803d;
        }

        /* Konfigurasi Dasar */
        body { margin: 0; font-family: 'Inter', sans-serif; background-color: var(--color-bg); color: var(--text-primary); }

        /* Header Utama */
        .header { background-color: var(--color-surface); padding: 20px 40px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .header .title h1 { margin: 0; font-size: 1.5em; font-weight: 700; }
        .header .title p { margin: 4px 0 0; color: var(--text-secondary); }

        /* Tombol */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .btn-home { background-color: #f1f5f9; color: #475569; } .btn-home:hover { background-color: #e2e8f0; }
        .btn-draw { background-color: var(--accent-primary); color: white; } .btn-draw:hover { background-color: var(--accent-primary-hover); }
        .btn-winner { flex-grow: 1; padding: 8px 10px; font-size: 0.8em; font-weight: 600; background-color: #f1f5f9; color: var(--accent-success); border: 1px solid #e2e8f0; border-radius: 6px; }
        .btn-winner:hover { background-color: #d1fae5; border-color: var(--accent-success); }
        .btn-bye { width: 100%; background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; font-weight: 600; font-size: 0.9em; padding: 10px; }
        .btn-bye:hover { background-color: #c7d2fe; border-color: #a5b4fc; }

        /* Notifikasi */
        .notification { padding: 15px 40px; }
        .notification.success { background-color: #d1fae5; color: #065f46; }
        .notification.error { background-color: #fee2e2; color: #991b1b; }

        /* Konten Utama */
        .main-content { padding: 30px 40px; }

        /* Daftar Pemain */
        .player-pool-container { background-color: var(--color-surface); border-radius: 12px; padding: 20px; margin-bottom: 30px; border: 1px solid var(--color-border); }
        .player-pool-container h3 { margin-top: 0; font-size: 1.2em; }
        .player-pool-container p { margin-top:-10px; margin-bottom:20px; font-size:0.9em; color: var(--text-secondary); }
        .player-pool { display: flex; flex-wrap: wrap; gap: 10px; min-height: 50px; }
        
        /* Item Pemain */
        .player-item { display: flex; flex-direction: column; padding: 8px 12px; background-color: #f1f5f9; border-radius: 6px; border: 1px solid #e2e8f0; }
        .player-item.is-draggable { cursor: grab; }
        .player-item.is-draggable:hover { background-color: #e2e8f0; }
        .player-item .player-name { font-weight: 600; font-size: 0.9em; }
        .player-item .player-contingent { font-size: 0.75em; color: var(--text-secondary); }
        .player-item.is-winner { border: 2px solid var(--accent-success); background-color: #dcfce7; }
        .player-item .player-name.winner { color: var(--accent-success); font-weight: 800; }

        /* Area Bracket */
        .bracket-wrapper { overflow-x: auto; padding-bottom: 20px; }
        .bracket { display: flex; align-items: center; } /* align-items: center membantu perataan vertikal */
        .round { display: flex; flex-direction: column; justify-content: space-around; min-width: var(--match-width); margin-right: var(--bracket-gap); position: relative; }
        .round-title { text-align: center; font-size: 1em; font-weight: 700; color: var(--text-secondary); margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.05em; }
        
        .match { background-color: var(--color-surface); border-radius: 8px; margin-bottom: var(--match-vert-gap); border: 1px solid var(--color-border); position: relative; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.04); }
        
        /* [PERBAIKAN] Logika Garis Penghubung yang Lebih Presisi */
        .match::after {
            content: '';
            position: absolute;
            background-color: var(--color-connector);
            width: calc(var(--bracket-gap) / 2);
            height: 2px;
            left: 100%;
            top: 50%;
            transform: translateY(-1px);
        }
        .match:not(:last-child) { margin-bottom: var(--match-vert-gap); }
        .match:nth-child(odd)::before {
            content: '';
            position: absolute;
            background-color: var(--color-connector);
            width: 2px;
            left: calc(100% + var(--bracket-gap) / 2);
            top: 50%;
            height: calc(50% + var(--match-vert-gap) / 2);
        }
        .match:nth-child(even)::before {
            content: '';
            position: absolute;
            background-color: var(--color-connector);
            width: 2px;
            left: calc(100% + var(--bracket-gap) / 2);
            bottom: 50%;
            height: calc(50% + var(--match-vert-gap) / 2);
        }
        .round:last-of-type .match::before, .round:last-of-type .match::after { display: none; }

        /* [PERBAIKAN] Ukuran Slot dan Font di dalamnya */
        .player-slot { padding: 8px 10px; min-height: 52px; display: flex; flex-direction: column; justify-content: center; }
        .player-slot.draggable:empty::after { content: 'Letakkan Pemain'; display: flex; align-items: center; justify-content: center; width: 100%; height: 38px; color: #adb5bd; border: 2px dashed #e9ecef; border-radius: 6px; font-size: 0.85em; }
        .player-slot.locked .player-placeholder { color: #adb5bd; font-size: 0.85em; font-style: italic; text-align: center; }
        .player-slot.bye-locked { background-color: #f8f9fa; }
        
        .match-actions { padding: 6px; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items:center; gap:8px; background-color: #f8f9fa;}
        .score-inputs { display: flex; gap: 4px; }
        .score-inputs input { width: 40px; border: 1px solid var(--color-border); text-align: center; border-radius: 5px; padding: 5px; font-weight: 500; font-size: 0.8em; }
        .winner-actions { display: flex; flex-grow: 1; gap: 4px; }

    </style>
</head>
<body>
    <header class="header">
        <div class="title">
            <h1>Bracket: {{ $kelas->kelas->nama_kelas }} ({{ $kelas->gender }})</h1>
            <p>{{ $kelas->kategoriPertandingan->nama_kategori }} - {{ $kelas->jenisPertandingan->nama_jenis }}</p>
        </div>
        <div class="actions">
            <a href="{{ route('adminIndex') }}#bracket" class="btn btn-home">Kembali</a>
            <form action="{{ route('bracket.generate', $kelas) }}" method="POST" style="margin:0;">
                <button type="submit" class="btn btn-draw" onclick="return confirm('Yakin ingin mengundi ulang? Pengaturan manual di babak 1 akan hilang.')">
                    @csrf
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
                                <div class="match" data-match-id="{{ $match->id }}">
                                    <form action="{{ route('bracket.updateMatch', $match) }}" method="POST">
                                        @csrf
                                        <div class="player-slot @if($r == 1) draggable @else locked @endif" data-slot="1">
                                            @if($match->player1)
                                                <div class="player-item @if($match->status === 'selesai' && $match->winner_id == $match->player1_id) is-winner @endif @if($r == 1) is-draggable @endif" data-player-id="{{ $match->player1->id }}">
                                                    <span class="player-name @if($match->status === 'selesai' && $match->winner_id === $match->player1_id) winner @endif">{{ $match->player1->name }}</span>
                                                    <span class="player-contingent">{{ $match->player1->contingent->name }}</span>
                                                </div>
                                            @elseif ($r > 1)
                                                <div class="player-placeholder">Menunggu Pemenang</div>
                                            @endif
                                        </div>

                                        <div style="border-bottom: 1px solid var(--color-border);"></div>
                                        
                                        @php
                                            $isByeSlotLocked = $r == 1 && $match->player1_id && !$match->player2_id;
                                        @endphp

                                        <div class="player-slot 
                                            @if($r == 1 && !$isByeSlotLocked) draggable @else locked @endif 
                                            @if($isByeSlotLocked) bye-locked @endif" 
                                            data-slot="2">

                                            @if($match->player2)
                                                <div class="player-item @if($match->status === 'selesai' && $match->winner_id == $match->player2_id) is-winner @endif @if($r == 1) is-draggable @endif" data-player-id="{{ $match->player2->id }}">
                                                    <span class="player-name @if($match->status === 'selesai' && $match->winner_id === $match->player2_id) winner @endif">{{ $match->player2->name }}</span>
                                                    <span class="player-contingent">{{ $match->player2->contingent->name }}</span>
                                                </div>
                                            @elseif ($r > 1 || $isByeSlotLocked)
                                                <div class="player-placeholder">{{ $r > 1 ? 'Menunggu Pemenang' : '' }}</div>
                                            @endif
                                        </div>
                                        
                                        @if($match->status !== 'selesai')
                                            @if ($match->player1_id && $match->player2_id)
                                                <div class="match-actions">
                                                    <div class="score-inputs">
                                                        <input type="number" name="score1" value="{{ $match->score1 }}" placeholder="S1" title="Skor Pemain 1">
                                                        <input type="number" name="score2" value="{{ $match->score2 }}" placeholder="S2" title="Skor Pemain 2">
                                                    </div>
                                                    <div class="winner-actions">
                                                        <button type="submit" class="btn btn-winner" name="winner_id" value="{{ $match->player1_id }}">Pemenang ↑</button>
                                                        <button type="submit" class="btn btn-winner" name="winner_id" value="{{ $match->player2_id }}">Pemenang ↓</button>
                                                    </div>
                                                </div>
                                            @elseif ($match->player1_id && !$match->player2_id)
                                                <div class="match-actions">
                                                    <button type="submit" class="btn btn-bye" name="winner_id" value="{{ $match->player1_id }}">Loloskan (BYE) →</button>
                                                </div>
                                            @endif
                                        @endif
                                    </form>
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
                    if (status >= 400) { alert('Error: ' + body.message); location.reload(); } 
                    else { console.log('Success:', body.message); location.reload(); }
                }).catch((error) => { console.error('Fatal Error:', error); alert('Gagal menyimpan posisi pemain.'); location.reload(); });
            };
            const playerPool = document.getElementById('player-pool');
            const draggableSlots = document.querySelectorAll('.player-slot.draggable');
            const sortableOptions = {
                group: 'players', animation: 150,
                onAdd: function (evt) {
                    if (evt.to.id === 'player-pool') return;
                    const playerId = evt.item.dataset.playerId;
                    const matchId = evt.to.closest('.match').dataset.matchId;
                    const slotNumber = evt.to.dataset.slot;
                    savePlayerPosition(playerId, matchId, slotNumber);
                }
            };
            if (playerPool) new Sortable(playerPool, sortableOptions);
            draggableSlots.forEach(slot => { new Sortable(slot, sortableOptions); });
        });
    </script>
</body>
</html>