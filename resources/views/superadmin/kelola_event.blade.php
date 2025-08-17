@extends('superadmin.layouts') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Kelola Event')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* ... (Semua style CSS Anda dari jawaban sebelumnya tetap di sini) ... */
    .event-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.3s ease;
        background-color: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .event-card-img-container {
        position: relative;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        height: 180px;
    }
    .event-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .event-card-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        gap: 5px;
    }
    .event-card-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .event-card-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2c3e50;
    }
    .event-card-info {
        list-style: none;
        padding: 0;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: #555;
    }
    .event-card-info li {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .event-card-info .bi {
        margin-right: 0.75rem;
        color: #3498db;
        font-size: 1.1rem;
    }
    .event-card-footer {
        border-top: 1px solid #e0e0e0;
        padding: 0.75rem 1.25rem;
        background-color: #f8f9fa;
    }
    .datatables-custom-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Daftar Event</h4>
        <a href="{{ route('superadmin.tambah_event') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Event Baru
        </a>
    </div>
    <div class="card-body">

        <!-- Kontrol Kustom untuk DataTables -->
        <div class="datatables-custom-controls">
            <div class="col-md-4">
                <input type="text" id="customSearchInput" class="form-control" placeholder="Cari event...">
            </div>
        </div>

        <!-- Wadah untuk Kartu yang akan dirender oleh JavaScript -->
        <div id="event-cards-container" class="row g-4">
            {{-- Kartu akan diisi di sini oleh JavaScript --}}
        </div>
        
        <!-- Paginasi akan dirender di sini oleh DataTables -->
        <div id="custom-pagination" class="d-flex justify-content-end mt-4"></div>

    </div>
</div>

{{-- Tabel Sumber Data untuk DataTables (Disembunyikan) --}}
<div class="d-none">
    <table id="eventsSourceTable">
        <thead>
            <tr>
                <th>DataJSON</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tipe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
            <tr>
                <td>@json($event)</td>
                <td>{{ $event->name }}</td>
                <td>{{ $event->kotaOrKabupaten }}</td>
                <td>{{ $event->status }}</td>
                <td>{{ $event->type }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Memanggil file partial untuk modal --}}
@include('superadmin.partials.modal_event_detail')
@include('superadmin.partials.modal_event_delete')

@endsection

@push('scripts')
{{-- JavaScript untuk DataTables dan interaksi Modal --}}
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTables pada tabel sumber yang tersembunyi
    const table = $('#eventsSourceTable').DataTable({
        "dom": "p",
        "pageLength": 6,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
            "paginate": { "previous": "<<", "next": ">>" }
        },
        "initComplete": function() {
            $(".dataTables_paginate").appendTo("#custom-pagination");
        }
    });

    // 2. Fungsi untuk merender kartu dari data (ini sudah benar)
    function renderCards() {
        const container = $('#event-cards-container');
        container.empty();
        table.rows({ page: 'current' }).data().each(function(rowData) {
            const eventData = JSON.parse(rowData[0]);
            let hargaKelasText = 'N/A';
            if (eventData.kelas_pertandingan && eventData.kelas_pertandingan.length > 0) {
                const prices = eventData.kelas_pertandingan.map(k => Number(k.harga));
                const minPrice = Math.min(...prices);
                const maxPrice = Math.max(...prices);
                const fMin = new Intl.NumberFormat('id-ID').format(minPrice);
                const fMax = new Intl.NumberFormat('id-ID').format(maxPrice);
                hargaKelasText = minPrice === maxPrice ? `Rp ${fMin}` : `Rp ${fMin} - ${fMax}`;
            }
            const tglMulai = new Date(eventData.tgl_mulai_tanding).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            const tglSelesai = new Date(eventData.tgl_selesai_tanding).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            let statusBadge;
            if (eventData.status === 'sudah dibuka') statusBadge = '<span class="badge bg-success">Dibuka</span>';
            else if (eventData.status === 'ditutup') statusBadge = '<span class="badge bg-danger">Ditutup</span>';
            else statusBadge = '<span class="badge bg-secondary">Segera</span>';
            const typeBadge = `<span class="badge ${eventData.type === 'official' ? 'bg-primary' : 'bg-info'}">${eventData.type}</span>`;
            const cardHtml = `
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="event-card">
                        <div class="event-card-img-container">
                            <img src="${eventData.image ? '{{ asset('storage') }}/' + eventData.image : 'https://via.placeholder.com/400x250?text=Event'}" class="event-card-img" alt="${eventData.name}">
                            <div class="event-card-badges">${statusBadge} ${typeBadge}</div>
                        </div>
                        <div class="event-card-body">
                            <div>
                                <h5 class="event-card-title mb-1">${eventData.name}</h5>
                                <p class="text-muted small"><i class="bi bi-geo-alt"></i> ${eventData.kotaOrKabupaten}</p>
                            </div>
                            <ul class="event-card-info mt-auto">
                                <li><i class="bi bi-calendar-range"></i> ${tglMulai} - ${tglSelesai}</li>
                                <li><i class="bi bi-tags"></i> ${hargaKelasText}</li>
                                <li><i class="bi bi-people-fill"></i> ${eventData.kelas_pertandingan_count} Kelas</li>
                            </ul>
                        </div>
                        <div class="event-card-footer">
                             <div class="btn-group w-100">
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal" data-event='${JSON.stringify(eventData)}'>Detail</button>
                                <a href="/superadmin/event/${eventData.id}/edit" class="btn btn-outline-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="${eventData.id}" data-name="${eventData.name}">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>`;
            container.append(cardHtml);
        });
    }

    // 3. Hubungkan kontrol kustom (ini sudah benar)
    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
    table.on('draw.dt', function () {
        renderCards();
    });
    renderCards();
    
    // =========================================================================
    // KODE YANG DIPERBAIKI ADA DI SINI: LOGIKA LENGKAP UNTUK MENGISI MODAL
    // =========================================================================

    // Handle Modal Detail
    $('#detailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var eventData = button.data('event'); // Ambil seluruh data event dari atribut data-event
        var modal = $(this);
        
        // Format tanggal
        var tglMulai = new Date(eventData.tgl_mulai_tanding).toLocaleDateString('id-ID', { day: 'numeric', month: 'long'});
        var tglSelesai = new Date(eventData.tgl_selesai_tanding).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        var tglBatas = new Date(eventData.tgl_batas_pendaftaran).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        // Mengisi semua data umum ke dalam modal
        modal.find('#detailName').text(eventData.name);
        modal.find('#detailImage').attr('src', eventData.image ? '{{ asset('storage') }}/' + eventData.image : 'https://via.placeholder.com/400x250?text=No+Image');
        modal.find('#detailLokasi').text(eventData.lokasi + ', ' + eventData.kotaOrKabupaten);
        modal.find('#detailTanggalTanding').text(tglMulai + ' - ' + tglSelesai);
        modal.find('#detailBatasDaftar').text(tglBatas);
        modal.find('#detailDesc').html(eventData.desc);
        modal.find('#detailCp').html(eventData.cp);
        
        // Mengisi harga kontingen
        modal.find('#detailHargaKontingen').text('Rp ' + new Intl.NumberFormat('id-ID').format(eventData.harga_contingent));

        // Logika untuk mengisi rentang harga kelas (sama seperti di fungsi renderCards)
        var hargaKelasText = 'Tidak ada kelas terdaftar';
        if (eventData.kelas_pertandingan && eventData.kelas_pertandingan.length > 0) {
            const prices = eventData.kelas_pertandingan.map(kelas => Number(kelas.harga));
            const minPrice = Math.min(...prices);
            const maxPrice = Math.max(...prices);
            const formattedMinPrice = 'Rp ' + new Intl.NumberFormat('id-ID').format(minPrice);
            const formattedMaxPrice = 'Rp ' + new Intl.NumberFormat('id-ID').format(maxPrice);
            if (minPrice === maxPrice) {
                hargaKelasText = formattedMinPrice;
            } else {
                hargaKelasText = `${formattedMinPrice} - ${formattedMaxPrice}`;
            }
        }
        modal.find('#detailHargaKelas').text(hargaKelasText);

        // Menangani link Juknis (sembunyikan jika tidak ada)
        if(eventData.juknis){
            modal.find('#detailJuknis').attr('href', eventData.juknis).parent().show();
        } else {
            modal.find('#juknis-section').hide();
        }
    });

    // Handle Modal Hapus (ini sudah benar)
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var eventId = button.data('id');
        var eventName = button.data('name');
        var modal = $(this);
        modal.find('#eventNameToDelete').text(eventName);
        var actionUrl = "{{ url('superadmin/event') }}/" + eventId;
        modal.find('#deleteForm').attr('action', actionUrl);
    });
});
</script>
@endpush