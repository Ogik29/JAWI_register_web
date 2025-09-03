@extends('superadmin.layouts') {{-- Pastikan path ini sesuai dengan layout Anda --}}
@section('title', 'Kelola Kelas Pertandingan')

@section('content')
<div class.container-fluid>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kelas Pertandingan</h5>
            <a href="{{ route('superadmin.kelas.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Kelas Baru
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                {{-- Tambahkan ID unik 'tabelKelas' untuk target DataTables --}}
                <table class="table table-striped table-hover align-middle" id="tabelKelas" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th>Nama Kelas</th>
                            <th>Rentang Usia</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarKelas as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kelas }}</td>
                            <td>{{ $item->rentangUsia->rentang_usia ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.kelas.edit', ['kela' => $item->id]) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('superadmin.kelas.destroy', ['kela' => $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi DataTables setelah halaman selesai dimuat
    $(document).ready(function() {
        $('#tabelKelas').DataTable({
            "language": {
                // Menerjemahkan DataTables ke Bahasa Indonesia
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            // Menjaga penomoran tetap benar setelah sorting
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [], // Menonaktifkan pengurutan awal
        });
    });
</script>
@endpush