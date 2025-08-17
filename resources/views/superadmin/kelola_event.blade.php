@extends('superadmin.layouts') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Kelola Event')

@push('styles')
{{-- CSS untuk DataTables agar terlihat menyatu dengan Bootstrap 5 --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Menyesuaikan tampilan DataTables */
    #eventsTable_wrapper .row:first-child {
        padding-bottom: 1rem;
    }
    .event-image-thumbnail {
        width: 120px;
        height: 70px;
        object-fit: cover;
        border-radius: 5px;
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
        <div class="table-responsive">
            <table id="eventsTable" class="table table-hover table-bordered" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Poster</th>
                        <th>Nama Event</th>
                        <th>Tanggal Tanding</th>
                        <th>Status</th>
                        <th>Tipe</th>
                        <th>Jml. Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="event-image-thumbnail">
                            @else
                                <span class="text-muted">Tanpa Poster</span>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block">{{ $event->name }}</strong>
                            <small class="text-muted">{{ $event->kotaOrKabupaten }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->isoFormat('D MMM') }} - 
                            {{ \Carbon\Carbon::parse($event->tgl_selesai_tanding)->isoFormat('D MMM YYYY') }}
                        </td>
                        <td>
                            @if($event->status == 'sudah dibuka')
                                <span class="badge bg-success">Dibuka</span>
                            @elseif($event->status == 'ditutup')
                                <span class="badge bg-danger">Ditutup</span>
                            @else
                                <span class="badge bg-secondary">Belum Dibuka</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $event->type == 'official' ? 'bg-primary' : 'bg-info' }}">
                                {{ ucfirst($event->type) }}
                            </span>
                        </td>
                        <td>{{ $event->kelas_pertandingan_count }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Tombol Detail -->
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal" data-event='@json($event)'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <!-- Tombol Edit -->
                                <a href="{{ route('superadmin.event.edit', $event->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $event->id }}" data-name="{{ $event->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Event -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <img id="detailImage" src="" class="img-fluid rounded mb-3" alt="Poster Event">
                <p><strong><i class="bi bi-geo-alt-fill me-2"></i>Lokasi:</strong> <span id="detailLokasi"></span></p>
                <p><strong><i class="bi bi-calendar-range-fill me-2"></i>Tanggal Tanding:</strong> <span id="detailTanggalTanding"></span></p>
                <p><strong><i class="bi bi-calendar-x-fill me-2"></i>Batas Pendaftaran:</strong> <span id="detailBatasDaftar"></span></p>
                <hr>
                <p><strong><i class="bi bi-cash-stack me-2"></i>Harga Kontingen:</strong> <span id="detailHargaKontingen"></span></p>
                <p><strong><i class="bi bi-cash me-2"></i>Harga per Peserta:</strong> <span id="detailHargaPeserta"></span></p>
            </div>
            <div class="col-md-7">
                <h4 id="detailName">Nama Event</h4>
                <div class="mb-3" id="detailDesc"></div>
                <h5>Info Kontak</h5>
                <div id="detailCp"></div>
                <h5 class="mt-3">Juknis</h5>
                <a href="#" id="detailJuknis" target="_blank" class="btn btn-primary btn-sm">Lihat Juknis</a>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus event <strong id="eventNameToDelete"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer">
        <form id="deleteForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
{{-- JavaScript untuk DataTables dan interaksi Modal --}}
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi DataTables
    $('#eventsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });

    // Handle Modal Detail
    $('#detailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var eventData = button.data('event');
        var modal = $(this);
        
        var tglMulai = new Date(eventData.tgl_mulai_tanding).toLocaleDateString('id-ID', { day: 'numeric', month: 'long'});
        var tglSelesai = new Date(eventData.tgl_selesai_tanding).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        var tglBatas = new Date(eventData.tgl_batas_pendaftaran).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        modal.find('#detailName').text(eventData.name);
        modal.find('#detailImage').attr('src', '{{ asset('storage') }}/' + eventData.image);
        modal.find('#detailLokasi').text(eventData.lokasi + ', ' + eventData.kotaOrKabupaten);
        modal.find('#detailTanggalTanding').text(tglMulai + ' - ' + tglSelesai);
        modal.find('#detailBatasDaftar').text(tglBatas);
        modal.find('#detailHargaKontingen').text('Rp ' + new Intl.NumberFormat('id-ID').format(eventData.harga_contingent));
        modal.find('#detailHargaPeserta').text('Rp ' + new Intl.NumberFormat('id-ID').format(eventData.harga_peserta));
        modal.find('#detailDesc').html(eventData.desc);
        modal.find('#detailCp').html(eventData.cp);
        modal.find('#detailJuknis').attr('href', eventData.juknis);
    });

    // Handle Modal Hapus
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var eventId = button.data('id');
        var eventName = button.data('name');
        var modal = $(this);

        // Update nama event di body modal
        modal.find('#eventNameToDelete').text(eventName);

        // Buat URL action untuk form
        var actionUrl = "{{ url('superadmin/event') }}/" + eventId;
        modal.find('#deleteForm').attr('action', actionUrl);
    });
});
</script>
@endpush