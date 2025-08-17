@extends('superadmin.layouts')

@section('title', 'Tambah Admin Baru')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header">
        <h5 class="card-title mb-0">Formulir Tambah Admin Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.admin.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3"><label for="nama_lengkap" class="form-label">Nama Lengkap</label><input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>@error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="password_confirmation" class="form-label">Konfirmasi Password</label><input type="password" class="form-control" name="password_confirmation" required></div>
            </div>
            <div class="mb-3"><label for="alamat" class="form-label">Alamat</label><textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="2" required>{{ old('alamat') }}</textarea>@error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Jenis Kelamin</label><div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} required><label class="form-check-label">Laki-laki</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}><label class="form-check-label">Perempuan</label></div></div>@error('jenis_kelamin')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                <div class="col-md-6 mb-3"><label for="no_telp" class="form-label">No. Telepon</label><input type="text" class="form-control @error('no_telp') is-invalid @enderror" name="no_telp" value="{{ old('no_telp') }}" required>@error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="tempat_lahir" class="form-label">Tempat Lahir</label><input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>@error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="tanggal_lahir" class="form-label">Tanggal Lahir</label><input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>@error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4 mb-3"><label for="negara" class="form-label">Negara</label><input type="text" class="form-control @error('negara') is-invalid @enderror" name="negara" value="{{ old('negara') }}" required>@error('negara')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_ids" class="form-label fw-bold">Tugaskan ke Event</label>
                    {{-- DROPDOWN MULTIPLE SELECT DENGAN SELECT2 --}}
                    <select class="form-select" name="event_ids[]" id="event_ids" required multiple>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ (is_array(old('event_ids')) && in-array($event->id, old('event_ids'))) ? 'selected' : '' }}>{{ $event->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Tahan Ctrl/Cmd untuk memilih lebih dari satu.</div>
                    @error('event_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    @error('event_ids.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-bold">Status Akun</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'checked' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <input type="hidden" name="role_id" value="2">
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('superadmin.kelola_admin') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Skrip untuk mengaktifkan Select2 pada halaman ini --}}
<script>
    $(document).ready(function() {
        $('#event_ids').select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: "Cari dan pilih event...",
        });
    });
</script>
@endpush