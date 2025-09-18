<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Semua Kartu Peserta - {{ $event->name }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .card {
            width: 54mm;
            height: 85.6mm;
            position: relative;
            overflow: hidden;
            background-color: #212121;
            color: #ffffff;
            page-break-inside: avoid !important;
            display: inline-block;
            vertical-align: top;
            margin: 2mm;
        }

        .vertical-strip {
            position: absolute;
            left: 0;
            top: 0;
            width: 30pt;
            height: 100%;
            background-color: #ad0505;
        }

        .vertical-text-container {
            position: absolute;
            top: 50%;
            width: 100%;
        }

        .vertical-text {
            color: white;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-align: center;
            text-transform: uppercase;
            white-space: nowrap;
            transform: translateY(-50%) rotate(-90deg);
        }

        .main-content {
            padding: 15pt 15pt 15pt 40pt;
            text-align: center;
        }

        .title-main {
            font-size: 15px;
            font-weight: bold;
            color: #ffffff;
            margin: 0 0 20pt 0;
        }

        .photo-section {
            margin-bottom: 12pt;
        }

        .photo-circle {
            width: 70pt;
            height: 70pt;
            border-radius: 50%;
            border: 2.5pt solid white;
            display: inline-block;
            overflow: hidden;
            background-color: #333;
        }

        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===================================== */
        /* INI ADALAH FIX UTAMANYA */
        /* ===================================== */
        .photo-placeholder {
            width: 100%;
            height: 100%;
            font-size: 10pt;
            font-weight: bold;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.2);
            /* Hapus "display: table-cell" dan ganti dengan line-height */
            text-align: center;
            line-height: 70pt; /* HARUS SAMA DENGAN height dari .photo-circle */
        }
        /* ===================================== */
        /* AKHIR DARI FIX */
        /* ===================================== */

        .info-section {
            width: 100%;
        }

        .player-name {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .contingent-name {
            font-size: 7px;
            color: #d1d1d1;
            margin-top: 2pt;
            text-transform: uppercase;
        }

        .separator {
            width: 80%;
            margin: 10pt auto;
            border-bottom: 0.5pt solid rgba(255, 255, 255, 0.3);
        }

        .details-container {
            width: 100%;
            font-size: 0;
        }

        .detail-item {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            text-align: center;
        }

        .detail-label {
            font-size: 5pt;
            color: #d1d1d1;
            margin-bottom: 2pt;
            text-transform: uppercase;
        }

        .detail-value {
            font-weight: bold;
            font-size: 5pt;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    @foreach($players as $player)
        <div class="card">
            <div class="vertical-strip">
                <div class="vertical-text-container">
                    <div class="vertical-text">{{ $event->name ?? 'JAWARA' }}</div>
                </div>
            </div>
            <div class="main-content">
                <h1 class="title-main">PESERTA</h1>
                <div class="photo-section">
                    <div class="photo-circle">
                        @if ($player->foto_diri && file_exists(public_path('storage/' . $player->foto_diri)))
                            <img src="{{ public_path('storage/' . $player->foto_diri) }}" alt="Foto"
                                 class="photo-img">
                        @else
                            <div class="photo-placeholder">FOTO</div>
                        @endif
                    </div>
                </div>
                <div class="info-section">
                    <h2 class="player-name">{{ $player->name }}</h2>
                    <p class="contingent-name">{{ $player->contingent->name }}</p>
                    <div class="separator"></div>
                    <div class="details-container">
                        <div class="detail-item">
                            <div class="detail-label">Kategori</div>
                            <div class="detail-value">
                                @php
                                    $rentangUsiaText = $player->kelasPertandingan->kelas->rentangUsia->rentang_usia ?? 'UMUM';
                                    echo strtoupper(explode(' (', $rentangUsiaText)[0]);
                                @endphp
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Kelas</div>
                            <div class="detail-value">{{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}
                                , {{ $player->gender }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>