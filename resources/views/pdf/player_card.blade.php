<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kartu Peserta - {{ $player->name }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #ffffff;
        }
        .card {
            width: 153pt;  /* 54mm */
            height: 242.6pt; /* 85.6mm */
            position: relative;
            overflow: hidden;
            background-color: #1a1a1a; /* Latar belakang gelap jika gambar gagal dimuat */
        }
        .background {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            /* Ganti dengan path ke gambar background Anda jika punya */
            /* background-image: url({{ public_path('path/to/your/background.jpg') }}); */
            background-color: #1a1a1a;
            background-size: cover;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: linear-gradient(to top right, rgba(197, 0, 0, 0.85), rgba(0, 0, 0, 0.7));
        }
        .logo {
            position: absolute;
            top: 10pt;
            right: 10pt;
            width: 30pt;
            height: auto;
        }
        .header-text {
            position: absolute;
            top: 12pt;
            left: 10pt;
            text-transform: uppercase;
        }
        .header-text .event-title {
            font-size: 8pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        .photo-container {
            position: absolute;
            top: 45pt;
            left: 10pt;
            text-align: center;
        }
        .photo {
            width: 80pt;
            height: 105pt;
            border: 3px solid white;
            border-radius: 5px;
            object-fit: cover;
        }
        .main-details {
            position: absolute;
            top: 155pt; /* Posisi di bawah foto */
            left: 10pt;
            right: 10pt;
        }
        .player-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            line-height: 1.1;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .details-table {
            width: 100%;
            margin-top: 8pt;
            font-size: 8.5pt;
        }
        .details-table td {
            padding-bottom: 4pt;
        }
        .details-table .label {
            font-weight: bold;
            opacity: 0.8;
            width: 50pt;
            vertical-align: top;
        }
        .footer-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #c50000;
            padding: 5pt 10pt;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="background"></div>
        <div class="overlay"></div>

        {{-- Ganti path logo ini jika perlu --}}
        @if (file_exists(public_path('assets/img/icon/logo-jawi-white.png')))
            <img src="{{ public_path('assets/img/icon/logo-jawi-white.png') }}" class="logo" alt="Logo">
        @endif

        <div class="header-text">
            <p class="event-title">{{ $player->contingent->event->name }}</p>
        </div>
        
        <div class="photo-container">
            @if ($player->foto_diri && file_exists(public_path(Storage::url($player->foto_diri))))
                <img src="{{ public_path(Storage::url($player->foto_diri)) }}" alt="Foto Peserta" class="photo">
            @else
                <div style="width:80pt; height:105pt; border:3px solid white; border-radius:5px; text-align:center; padding-top:45pt; background:rgba(0,0,0,0.2); font-size:8pt;">FOTO</div>
            @endif
        </div>
        
        <div class="main-details">
            <h2 class="player-name">{{ $player->name }}</h2>
            <table class="details-table">
                <tr>
                    <td class="label">KONTINGEN</td>
                    <td>: {{ $player->contingent->name }}</td>
                </tr>
                <tr>
                    <td class="label">KELAS</td>
                    <td>: {{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="footer-bar">
            JAWARA INDONESIA
        </div>
    </div>
</body>
</html>