@extends('superadmin.layouts') {{-- Sesuaikan dengan file layout utama Anda --}}
@section('title', 'Edit Kelas')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Formulir Edit Kelas: {{ $kelas->nama_kelas }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                        @error('nama_kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="rentang_usia_id" class="form-label">Rentang Usia</label>
                        <select class="form-select @error('rentang_usia_id') is-invalid @enderror" id="rentang_usia_id" name="rentang_usia_id" required>
                            <option value="" disabled>-- Pilih Rentang Usia --</option>
                            @foreach($daftarRentangUsia as $usia)
                                <option value="{{ $usia->id }}" {{ old('rentang_usia_id', $kelas->rentang_usia_id) == $usia->id ? 'selected' : '' }}>
                                    {{ $usia->rentang_usia }}
                                </option>
                            @endforeach
                        </select>
                        @error('rentang_usia_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('superadmin.kelas.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-warning">Update Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection