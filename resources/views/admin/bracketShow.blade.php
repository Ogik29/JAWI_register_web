<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Tautan Tailwind CSS agar tombol Home berfungsi --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Bracket: {{ $kelas->kelas->nama_kelas }}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --bracket-gap: 60px;
            --match-width: 250px;
            --match-vert-gap: 50px;
            --connector-color: #ced4da;
            --border-color: #dee2e6;
            --bg-color: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #e9ecef;
            color: #212529;
        }

        .header {
            background-color: #fff;
            padding: 20px 40px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { margin: 0; font-size: 1.5em; }
        .header .actions form { margin: 0; }
        
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 10px 20px; border: none; border-radius: 6px;
            font-weight: 600; text-decoration: none; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-home { background-color: #f1f5f9; color: #475569; }
        .btn-home:hover { background-color: #e2e8f0; }
        .btn-draw { background-color: #4f46e5; color: white; }
        .btn-draw:hover { background-color: #4338ca; }
        .btn-save {
            padding: 4px 8px; font-size: 0.8em;
            background-color: #16a34a; color: white;
        }

        .bracket-wrapper {
            padding: 30px;
            overflow-x: auto;
        }
        .bracket { display: flex; }
        .round {
            display: flex; flex-direction: column;
            justify-content: space-around;
            min-width: var(--match-width);
            margin-right: var(--bracket-gap);
        }
        .round-title {
            text-align: center; font-size: 1.1em;
            font-weight: 600; color: #6c757d; margin-bottom: 20px;
        }

        .match {
            display: flex; flex-direction: column; justify-content: center;
            background-color: #fff; border-radius: 6px;
            margin-bottom: var(--match-vert-gap);
            border: 1px solid var(--border-color);
            position: relative;
        }
        
        /* Garis konektor antar ronde */
        .match .match-lines {
            content: ''; position: absolute; left: 100%; top: 50%;
            width: calc(var(--bracket-gap) / 2); height: 2px;
            background: var(--connector-color);
        }
        /* Cabang vertikal */
        .match .match-lines::after {
            content: ''; position: absolute;
            width: 2px; background: var(--connector-color);
            top: 0; right: -1px;
        }
        /* Sesuaikan tinggi garis cabang vertikal */
        .match:nth-of-type(odd) .match-lines::after {
             height: calc(50% + var(--match-vert-gap) / 2 + 1px); top: -1px; /* ke bawah */
        }
        .match:nth-of-type(even) .match-lines::after {
            height: calc(50% + var(--match-vert-gap) / 2 + 1px); bottom: -1px; top: auto; /* ke atas */
        }
        /* Sembunyikan garis untuk ronde final */
        .round:last-of-type .match .match-lines { display: none; }
        
        .player-slot { padding: 8px 12px; }
        .player { display: flex; align-items: center; justify-content: space-between; }
        .player-name { font-weight: 500; font-size: 0.9em; }
        .player-contingent { font-size: 0.75em; color: #6c757d; }
        .player-score input {
            width: 40px; border: 1px solid #ccc;
            text-align: center; border-radius: 4px; padding: 4px;
        }
        .player-name.winner { color: #16a34a; font-weight: 700; }
        .player-bye { font-style: italic; color: #6c757d; font-size: 0.9em; padding: 8px 12px;}
    </style>
</head>

<body>
    <header class="header">
         <a href="{{ route('adminIndex') }}" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg hover:bg-gray-200 text-sm font-medium flex items-center space-x-2" title="Kembali ke Halaman Utama">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Home</span>
        </a>
        <div>
            <h1>Bracket: {{ $kelas->kelas->nama_kelas }} ({{ $kelas->gender }})</h1>
            <p style="margin: 4px 0 0; color: #6c757d;">{{ $kelas->kategoriPertandingan->nama_kategori }} - {{ $kelas->jenisPertandingan->nama_jenis }}</p>
        </div>
        <div class="actions" style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('adminIndex') }}#bracket" class="btn btn-home">Kembali</a>
            <form action="{{ route('bracket.generate', $kelas) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-draw" onclick="return confirm('Yakin ingin membuat ulang bracket? Semua data bracket saat ini akan hilang.')">
                    DRAW/UNDI ULANG
                </button>
            </form>
        </div>
    </header>

    @if(session('success')) <div style="background: #d1fae5; color: #065f46; padding: 15px; margin: 20px;">{{ session('success') }}</div> @endif
    @if(session('error')) <div style="background: #fee2e2; color: #991b1b; padding: 15px; margin: 20px;">{{ session('error') }}</div> @endif

    <div class="bracket-wrapper">
        <div class="bracket">
            @for ($r = 1; $r <= $totalRounds; $r++)
                <div class="round">
                    <div class="round-title">
                        @if ($r == $totalRounds) Final
                        @elseif ($r == $totalRounds - 1) Semi Final
                        @elseif ($r == $totalRounds - 2) Perempat Final
                        @else Babak Penyisihan {{ $r }}
                        @endif
                    </div>
                    @if(isset($rounds[$r]))
                        @foreach ($rounds[$r] as $match)
                            <div class="match">
                                <form action="{{ route('bracket.updateMatch', $match) }}" method="POST">
                                    @csrf
                                    <div class="player-slot">
                                        @if($match->player1)
                                            <div class="player">
                                                <div>
                                                    <span class="player-name @if($match->winner_id === $match->player1_id) winner @endif">
                                                        {{ $match->player1->name }}
                                                    </span>
                                                    <div class="player-contingent">{{ $match->player1->contingent->name }}</div>
                                                </div>
                                                <input class="player-score" type="number" name="score1" value="{{ $match->score1 }}">
                                            </div>
                                        @elseif($match->status === 'selesai')
                                            <div class="player-bye">(BYE)</div>
                                        @endif
                                    </div>

                                    <div style="border-bottom: 1px solid var(--border-color);"></div>
                                    
                                    <div class="player-slot">
                                        @if($match->player2)
                                            <div class="player">
                                                 <div>
                                                    <span class="player-name @if($match->winner_id === $match->player2_id) winner @endif">
                                                        {{ $match->player2->name }}
                                                    </span>
                                                    <div class="player-contingent">{{ $match->player2->contingent->name }}</div>
                                                </div>
                                                <input class="player-score" type="number" name="score2" value="{{ $match->score2 }}">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($match->player1_id && $match->player2_id && $match->status !== 'selesai')
                                    <div style="padding: 10px; display: flex; justify-content: flex-end; align-items:center; gap:10px; background-color: var(--bg-color);">
                                        <span>Pilih Pemenang:</span>
                                        <input type="radio" name="winner_id" value="{{ $match->player1_id }}" id="win1_{{ $match->id }}" {{ $match->winner_id == $match->player1_id ? 'checked' : '' }}>
                                        <label for="win1_{{ $match->id }}">Pemain 1</label>
                                        <input type="radio" name="winner_id" value="{{ $match->player2_id }}" id="win2_{{ $match->id }}" {{ $match->winner_id == $match->player2_id ? 'checked' : '' }}>
                                        <label for="win2_{{ $match->id }}">Pemain 2</label>
                                        <button type="submit" class="btn btn-save">Save</button>
                                    </div>
                                    @endif
                                </form>

                                {{-- Elemen untuk garis konektor --}}
                                <div class="match-lines"></div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endfor
        </div>
    </div>
</body>
</html>