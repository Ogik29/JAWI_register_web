@extends('superadmin.layouts')
@section('title', 'Impor Jadwal Pertandingan')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-4">Impor Jadwal dari CSV/Excel</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <p class="text-muted">
            Unggah file CSV atau Excel yang berisi jadwal pertandingan (sesuai template). Sistem akan secara otomatis mencari pemain di database dan membuat struktur bracket berdasarkan kelas pertandingan yang terdaftar pada pemain tersebut.
        </p>

        <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="file" class="form-label fw-semibold">Pilih File Jadwal</label>
                <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary mt-3">
                <i class="bi bi-upload me-2"></i> Mulai Proses Impor
            </button>
        </form>

    </div>
</div>
@endsection