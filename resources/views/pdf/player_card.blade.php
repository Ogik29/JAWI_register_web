<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Kartu Peserta - {{ $player->name }}</title>
    <style>
        @page {
            margin: 0;
            size: 54mm 85.6mm;
            /* Ukuran standar kartu ID */
        }

        /* Menggunakan font dasar yang aman untuk PDF */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #212121;
            /* Warna background gelap */
            color: #ffffff;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .card {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .vertical-strip {
            position: absolute;
            left: 0;
            top: 0;
            width: 30pt;
            /* Sedikit diperlebar untuk memberi ruang */
            height: 100%;
            background-color: #ad0505;
        }

        .vertical-text-container {
            /* Kontainer untuk mempermudah pemusatan */
            position: absolute;
            top: 50%;
            /* left: 15pt; Setengah dari lebar strip */
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
            /* Trik pemusatan vertikal setelah rotasi */
            transform: translateY(-50%) rotate(-90deg);
        }

        .main-content {
            padding: 15pt 15pt 15pt 40pt;
            /* Jarak dari sisi kiri disesuaikan dengan strip baru */
            text-align: center;
        }

        .title-main {
            font-size: 15px;
            font-weight: bold;
            color: #ffffff;
            margin: 0 0 20pt 0;
        }

        .photo-section {
            margin-bottom: 15pt;
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
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: table-cell;
            vertical-align: middle;
            font-size: 10pt;
            font-weight: bold;
            color: #fff;
            background-color: rgba(0,0,0,0.2);
        }

        .info-section {
            width: 100%;
            padding-bottom: 5pt;
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
            margin: 12pt auto;
            border-bottom: 0.5pt solid rgba(255, 255, 255, 0.3);
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            font-size: 9pt;
            padding: 4pt 5pt;
            vertical-align: top;
            text-align: center;
        }

        .detail-label {
            font-size: 5pt;
            color: #d1d1d1;
            display: block;
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
    <div class="card">
        <div class="vertical-strip">
            <div class="vertical-text-container">
                <div class="vertical-text">{{ $player->contingent->event->name ?? 'JAWARA INDONESIA' }}</div>
            </div>
        </div>

        <div class="main-content">
            <h1 class="title-main">PESERTA</h1>

            <div class="photo-section">
                <div class="photo-circle">
                    @if ($player->foto_diri && file_exists(public_path('storage/' . $player->foto_diri)))
                        <img src="{{ public_path('storage/' . $player->foto_diri) }}" alt="Foto" class="photo-img">
                    @else
                        <div class="photo-placeholder">FOTO</div>
                    @endif
                </div>
            </div>
            
            <div class="info-section">
                <h2 class="player-name">{{ $player->name }}</h2>
                <p class="contingent-name">{{ $player->contingent->name }}</p>

                <div class="separator"></div>

                <table class="details-table">
                    <tr>
                        <td>
                            <span class="detail-label">Kategori</span>
                            <span class="detail-value">
                                @php
                                    $rentangUsiaText = $player->kelasPertandingan->kelas->rentangUsia->rentang_usia ?? 'UMUM';
                                    echo strtoupper(explode(' (', $rentangUsiaText)[0]);
                                @endphp
                            </span>
                        </td>
                        <td>
                            <span class="detail-label">Kelas</span>
                            <span class="detail-value">{{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}, {{ $player->gender }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>