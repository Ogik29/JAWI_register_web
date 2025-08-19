@extends('superadmin.layouts') {{-- Sesuaikan dengan nama file layout Anda --}}
@section('title', 'Tambah Event Baru')
@push('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<style>
    trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
    .trix-content { min-height: 150px; background-color: #fff; }
    .kelas-item .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endpush
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="card-title mb-0">Formulir Tambah Event Baru</h4>
    </div>
    <div class="card-body">
        {{-- PASTIKAN ANDA MENGGUNAKAN SINTAKS BLADE YANG BENAR UNTUK ROUTE --}}
        <form action="{{ route('superadmin.store_event') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Bagian Detail Event Utama --}}
    <div class="row">
        <!-- Name -->
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label fw-bold">Nama Event</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kejuaraan Pencak Silat Nasional">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Slug -->
        <div class="col-md-6 mb-3">
            <label for="slug" class="form-label fw-bold">Slug</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" required readonly placeholder="Akan terisi otomatis">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Description (Trix Editor) -->
    <div class="mb-3">
        <label for="desc" class="form-label fw-bold">Deskripsi Event</label>
        <input id="desc" type="hidden" name="desc" value="{{ old('desc') }}">
        <trix-editor input="desc" class="@error('desc') is-invalid @enderror"></trix-editor>
        @error('desc')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <!-- Type -->
        <div class="col-md-4 mb-3">
            <label for="type" class="form-label fw-bold">Tipe Event</label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="" selected disabled>Pilih Tipe...</option>
                <option value="official" {{ old('type') == 'official' ? 'selected' : '' }}>Official</option>
                <option value="non-official" {{ old('type') == 'non-official' ? 'selected' : '' }}>Non-Official</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Month -->
        <div class="col-md-4 mb-3">
            <label for="month" class="form-label fw-bold">Bulan Pelaksanaan</label>
            <input type="text" class="form-control @error('month') is-invalid @enderror" id="month" name="month" value="{{ old('month') }}" required placeholder="Contoh: Agustus">
            @error('month')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Status -->
        <div class="col-md-4 mb-3">
            <label for="status" class="form-label fw-bold">Status Pendaftaran</label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Belum Dibuka</option>
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Sudah Dibuka</option>
                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Ditutup</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <!-- Harga Kontingen -->
        <div class="col-md-6 mb-3">
            <label for="harga_contingent" class="form-label fw-bold">Harga Kontingen</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control @error('harga_contingent') is-invalid @enderror" id="harga_contingent" name="harga_contingent" value="{{ old('harga_contingent') }}" required placeholder="Contoh: 300000">
                @error('harga_contingent')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- Total Hadiah -->
        <div class="col-md-6 mb-3">
            <label for="total_hadiah" class="form-label fw-bold">Total Hadiah</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control @error('total_hadiah') is-invalid @enderror" id="total_hadiah" name="total_hadiah" value="{{ old('total_hadiah') }}" required placeholder="Contoh: 15000000">
                @error('total_hadiah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kota/Kabupaten -->
        <div class="col-md-6 mb-3">
            <label for="kotaOrKabupaten" class="form-label fw-bold">Kota / Kabupaten</label>
            <input type="text" class="form-control @error('kotaOrKabupaten') is-invalid @enderror" id="kotaOrKabupaten" name="kotaOrKabupaten" value="{{ old('kotaOrKabupaten') }}" required placeholder="Contoh: Kota Surabaya">
            @error('kotaOrKabupaten')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Lokasi -->
        <div class="col-md-6 mb-3">
            <label for="lokasi" class="form-label fw-bold">Lokasi Detail</label>
            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required placeholder="Contoh: GOR Pancasila">
            @error('lokasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <!-- Tgl Mulai Tanding -->
        <div class="col-md-4 mb-3">
            <label for="tgl_mulai_tanding" class="form-label fw-bold">Tgl Mulai Tanding</label>
            <input type="date" class="form-control @error('tgl_mulai_tanding') is-invalid @enderror" id="tgl_mulai_tanding" name="tgl_mulai_tanding" value="{{ old('tgl_mulai_tanding') }}" required>
            @error('tgl_mulai_tanding')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Tgl Selesai Tanding -->
        <div class="col-md-4 mb-3">
            <label for="tgl_selesai_tanding" class="form-label fw-bold">Tgl Selesai Tanding</label>
            <input type="date" class="form-control @error('tgl_selesai_tanding') is-invalid @enderror" id="tgl_selesai_tanding" name="tgl_selesai_tanding" value="{{ old('tgl_selesai_tanding') }}" required>
            @error('tgl_selesai_tanding')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Tgl Batas Pendaftaran -->
        <div class="col-md-4 mb-3">
            <label for="tgl_batas_pendaftaran" class="form-label fw-bold">Batas Pendaftaran</label>
            <input type="date" class="form-control @error('tgl_batas_pendaftaran') is-invalid @enderror" id="tgl_batas_pendaftaran" name="tgl_batas_pendaftaran" value="{{ old('tgl_batas_pendaftaran') }}" required>
            @error('tgl_batas_pendaftaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- BAGIAN KELAS PERTANDINGAN DINAMIS --}}
    <hr class="my-4">
    <h5 class="mb-3 fw-bold">Kelas Pertandingan</h5>
    <div id="kelas-container">
        @php
            $oldKelas = old('kelas', [[]]);
        @endphp
        @foreach($oldKelas as $index => $kelas)
        <div class="card mb-3 kelas-item">
            <div class="card-header bg-light">
                <h6 class="mb-0">Detail Kelas #{{ $index + 1 }}</h6>
                @if($index > 0)
                    <button type="button" class="btn btn-danger btn-sm remove-kelas-btn"><i class="bi bi-trash"></i> Hapus</button>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Kategori Pertandingan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label @error('kelas.'.$index.'.kategori_id') text-danger @enderror">Kategori Pertandingan</label>
                        <div>
                            @foreach ($kategori_pertandingan as $kategori)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('kelas.'.$index.'.kategori_id') is-invalid @enderror" type="radio" name="kelas[{{$index}}][kategori_id]" id="kategori_{{$index}}_{{ $kategori->id }}" value="{{ $kategori->id }}" {{ (isset($kelas['kategori_id']) && $kelas['kategori_id'] == $kategori->id) ? 'checked' : '' }} required>
                                <label class="form-check-label" for="kategori_{{$index}}_{{ $kategori->id }}">{{ $kategori->nama_kategori }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('kelas.'.$index.'.kategori_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Jenis Pertandingan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label @error('kelas.'.$index.'.jenis_id') text-danger @enderror">Jenis Pertandingan</label>
                        <div>
                            @foreach ($jenis_pertandingan as $jenis)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('kelas.'.$index.'.jenis_id') is-invalid @enderror" type="radio" name="kelas[{{$index}}][jenis_id]" id="jenis_{{$index}}_{{ $jenis->id }}" value="{{ $jenis->id }}" {{ (isset($kelas['jenis_id']) && $kelas['jenis_id'] == $jenis->id) ? 'checked' : '' }} required>
                                <label class="form-check-label" for="jenis_{{$index}}_{{ $jenis->id }}">{{ $jenis->nama_jenis }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('kelas.'.$index.'.jenis_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <!-- Nama Kelas -->
                    <div class="col-md-6 mb-3">
                        <label for="kelas_{{$index}}_nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control @error('kelas.'.$index.'.nama_kelas') is-invalid @enderror" name="kelas[{{$index}}][nama_kelas]" id="kelas_{{$index}}_nama_kelas" value="{{ $kelas['nama_kelas'] ?? '' }}" placeholder="Contoh: Kelas A Putra" required>
                        @error('kelas.'.$index.'.nama_kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Rentang Usia / Berat Badan -->
                    <div class="col-md-6 mb-3">
                        <label for="kelas_{{$index}}_rentang_usia" class="form-label">Rentang Usia / Berat Badan</label>
                        <input type="text" class="form-control @error('kelas.'.$index.'.rentang_usia') is-invalid @enderror" name="kelas[{{$index}}][rentang_usia]" id="kelas_{{$index}}_rentang_usia" value="{{ $kelas['rentang_usia'] ?? '' }}" placeholder="Contoh: 14-17 Tahun / 45-50 kg" required>
                        @error('kelas.'.$index.'.rentang_usia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label @error('kelas.'.$index.'.gender') text-danger @enderror">Gender</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('kelas.'.$index.'.gender') is-invalid @enderror" type="radio" name="kelas[{{$index}}][gender]" id="gender_{{$index}}_laki" value="Laki-laki" {{ (isset($kelas['gender']) && $kelas['gender'] == 'Laki-laki') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="gender_{{$index}}_laki">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('kelas.'.$index.'.gender') is-invalid @enderror" type="radio" name="kelas[{{$index}}][gender]" id="gender_{{$index}}_perempuan" value="Perempuan" {{ (isset($kelas['gender']) && $kelas['gender'] == 'Perempuan') ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_{{$index}}_perempuan">Perempuan</label>
                            </div>
                        </div>
                        @error('kelas.'.$index.'.gender')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Harga -->
                    <div class="col-md-6 mb-3">
                        <label for="kelas_{{$index}}_harga" class="form-label">Harga Pendaftaran Kelas Ini</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('kelas.'.$index.'.harga') is-invalid @enderror" name="kelas[{{$index}}][harga]" id="kelas_{{$index}}_harga" value="{{ $kelas['harga'] ?? '' }}" placeholder="Contoh: 150000" required>
                            @error('kelas.'.$index.'.harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button type="button" id="add-kelas-btn" class="btn btn-outline-success">
        <i class="bi bi-plus-circle-fill"></i> Tambah Kelas Pertandingan Lain
    </button>
    <hr class="my-4">

    {{-- Input File dan CP --}}
    <div class="row">
        <!-- Image -->
        <div class="col-md-6 mb-3">
            <label for="image" class="form-label fw-bold">Gambar/Poster Event</label>
            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
            <div class="form-text">Input gambar akan kosong jika validasi gagal, ini adalah perilaku standar browser untuk keamanan.</div>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- Juknis -->
        <div class="col-md-6 mb-3">
            <label for="juknis" class="form-label fw-bold">File Juknis (URL)</label>
            <input class="form-control @error('juknis') is-invalid @enderror" type="text" placeholder="Isi berupa link panduan" id="juknis" name="juknis" value="{{ old('juknis') }}">
            @error('juknis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <!-- Contact Person (Trix Editor) -->
    <div class="mb-4">
        <label for="cp" class="form-label fw-bold">Contact Person (CP)</label>
        <input id="cp" type="hidden" name="cp" value="{{ old('cp') }}">
        <trix-editor input="cp" class="@error('cp') is-invalid @enderror"></trix-editor>
        @error('cp')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('superadmin.kelola_event') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Event</button>
    </div>
</form>
</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- SLUG GENERATOR ---
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        if(nameInput) {
            nameInput.addEventListener('keyup', function() {
                slugInput.value = this.value.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-');
            });
        }
        
        // Mencegah Trix Editor meng-upload file
        document.addEventListener("trix-file-accept", event => event.preventDefault());

        // --- DINAMIS FORM KELAS PERTANDINGAN ---
        const container = document.getElementById('kelas-container');
        const addBtn = document.getElementById('add-kelas-btn');
        let kelasIndex = 1;

        addBtn.addEventListener('click', function() {
            // Clone item pertama sebagai template
            const template = container.querySelector('.kelas-item').cloneNode(true);
            
            // Perbarui header
            template.querySelector('h6').textContent = `Detail Kelas #${kelasIndex + 1}`;
            
            // Perbarui atribut 'name', 'id', 'for' untuk memastikan keunikan
            template.querySelectorAll('input, label').forEach(el => {
                // Update 'name'
                if(el.name) {
                    el.name = el.name.replace(/\[\d+\]/, `[${kelasIndex}]`);
                }
                // Update 'id'
                if(el.id) {
                    el.id = el.id.replace(/_\d+_/, `_${kelasIndex}_`);
                }
                // Update 'for'
                if(el.htmlFor) {
                    el.htmlFor = el.htmlFor.replace(/_\d+_/, `_${kelasIndex}_`);
                }
            });

            // Bersihkan value dari hasil clone
            template.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => input.value = '');
            template.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
            
            // Tambahkan item baru ke container
            container.appendChild(template);
            kelasIndex++;
        });

        // Event listener untuk tombol hapus (menggunakan event delegation)
        container.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('remove-kelas-btn')) {
                 // Cegah penghapusan item terakhir
                if (container.querySelectorAll('.kelas-item').length <= 1) {
                    alert('Minimal harus ada satu kelas pertandingan.');
                    return;
                }
                // Hapus parent card (.kelas-item) dari tombol yang diklik
                event.target.closest('.kelas-item').remove();
            }
        });
    });
</script>
@endpush