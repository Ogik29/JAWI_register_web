@extends('superadmin.layouts') {{-- Pastikan ini menunjuk ke layout "Aurora" Anda --}}

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Style untuk Kartu Statistik */
    .stat-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 25px;
        display: flex;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .stat-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 1.8rem;
    }
    .stat-icon-box.bg-primary-soft { background-color: rgba(78, 115, 223, 0.1); color: var(--aurora-primary); }
    .stat-icon-box.bg-success-soft { background-color: rgba(28, 200, 138, 0.1); color: #1cc88a; }
    .stat-icon-box.bg-warning-soft { background-color: rgba(246, 194, 62, 0.1); color: #f6c23e; }
    .stat-icon-box.bg-purple-soft { background-color: rgba(111, 66, 193, 0.1); color: #6f42c1; }

    .stat-card .stat-info .stat-title {
        font-size: 0.9rem;
        color: var(--aurora-secondary);
        font-weight: 500;
    }
    .stat-card .stat-info .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #3a3b45;
    }

    /* Style untuk Tabel Event Terbaru */
    .recent-events-table {
        border-collapse: separate;
        border-spacing: 0 10px; /* Jarak antar baris */
    }
    .recent-events-table thead th {
        border: none !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        color: var(--aurora-secondary);
        letter-spacing: 0.5px;
    }
    .recent-events-table tbody tr {
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 8px;
        transition: transform 0.2s ease;
    }
    .recent-events-table tbody tr:hover {
        transform: scale(1.015);
        z-index: 2;
        position: relative;
    }
    .recent-events-table tbody td {
        border: none !important;
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }
    .recent-events-table tbody td:first-child { border-radius: 8px 0 0 8px; }
    .recent-events-table tbody td:last-child { border-radius: 0 8px 8px 0; }
    .status-badge {
        padding: 0.35em 0.8em;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
{{-- Baris untuk kartu statistik --}}
<div class="row g-4">
    <!-- Kartu Total Event -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon-box bg-primary-soft">
                <i class="bi bi-calendar2-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Total Event</div>
                <div class="stat-number">{{ $totalEvent }}</div>
            </div>
        </div>
    </div>

    <!-- Kartu Total Admin -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon-box bg-success-soft">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Total Admin</div>
                <div class="stat-number">{{ $totalAdmin }}</div>
            </div>
        </div>
    </div>

    <!-- Kartu Event Aktif -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon-box bg-warning-soft">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Event Aktif</div>
                <div class="stat-number">{{ $eventAktif }}</div>
            </div>
        </div>
    </div>

    <!-- Kartu Event Selesai -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon-box bg-purple-soft">
                <i class="bi bi-trophy"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Event Selesai</div>
                <div class="stat-number">{{ $eventSelesai }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Event Terbaru --}}
<div class="card shadow-sm border-0 mt-5">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">Event Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table recent-events-table">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentEvents as $event)
                    <tr>
                        <td>
                            <strong>{{ $event->name }}</strong>
                        </td>
                        <td>{{ $event->kotaOrKabupaten }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->isoFormat('D MMM') }} - 
                            {{ \Carbon\Carbon::parse($event->tgl_selesai_tanding)->isoFormat('D MMM YYYY') }}
                        </td>
                        <td>
                            @if($event->status == 1)
                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis status-badge">Aktif</span>
                            @elseif($event->status == 2)
                                <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis status-badge">Selesai</span>
                            @else
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis status-badge">Segera</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <p class="mb-0 text-muted">Belum ada event yang dibuat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection