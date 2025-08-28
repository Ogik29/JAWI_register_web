@extends('superadmin.layouts')

@section('title', 'Kelola Kelas Pertandingan')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Daftar Kelas Pertandingan</h4>
            <a href="{{ route('superadmin.kelas.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kelas Baru
            </a>
        </div>
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
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Kelas</th>
                        <th scope="col">Rentang Usia</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarKelas as $kelas)
                    <tr>
                        <th scope="row">{{ $daftarKelas->firstItem() + $loop->index }}</th>
                        <td>{{ $kelas->nama_kelas }}</td>
                        <td>{{ $kelas->rentangUsia->rentang_usia ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('superadmin.kelas.edit', $kelas->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('superadmin.kelas.destroy', $kelas->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data kelas yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $daftarKelas->links() }}
        </div>
    </div>
</div>
@endsection