@extends('superadmin.layouts')

@section('title', 'Kelola Admin Event')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Admin Event</h5>
        <a href="{{ route('superadmin.admin.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin Baru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email & No. Telp</th>
                        <th>Event Ditugaskan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>
                            <strong class="d-block">{{ $admin->nama_lengkap }}</strong>
                            <small class="text-muted">{{ $admin->jenis_kelamin }}</small>
                        </td>
                        <td>
                            <span class="d-block">{{ $admin->email }}</span>
                            <small class="text-muted"><i class="bi bi-telephone-fill"></i> {{ $admin->no_telp }}</small>
                        </td>
                        <td>
                            @forelse($admin->events as $event)
                                <span class="badge bg-primary me-1">{{ $event->name }}</span>
                            @empty
                                <span class="badge bg-secondary">Belum ditugaskan</span>
                            @endforelse
                        </td>
                        <td>
                            @if($admin->status)
                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Aktif</span>
                            @else
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            {{-- ====================================================== --}}
                            {{-- KODE HAPUS TANPA MODAL ADA DI SINI --}}
                            {{-- ====================================================== --}}
                            <div class="d-flex gap-1">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('superadmin.admin.edit', $admin->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                
                                {{-- Form Hapus untuk setiap baris --}}
                                <form action="{{ route('superadmin.admin.destroy', $admin->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    
                                    {{-- Tombol Hapus dengan konfirmasi JavaScript --}}
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus admin \'{{ $admin->nama_lengkap }}\'? Tindakan ini tidak dapat dibatalkan.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="mb-1">Belum ada admin event.</p>
                            <a href="{{ route('superadmin.admin.create') }}" class="btn btn-primary btn-sm">Tambahkan Sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- TIDAK ADA MODAL SAMA SEKALI --}}
{{-- KITA TIDAK MEMBUTUHKAN @include() untuk modal di sini --}}

@endsection

@push('scripts')
{{-- TIDAK PERLU SCRIPT TAMBAHAN UNTUK FITUR HAPUS INI --}}
{{-- Anda bisa membiarkan skrip Select2 dari halaman edit jika masih ada --}}
@endpush