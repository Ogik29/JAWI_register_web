@extends('superadmin.layouts') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Edit Event: ' . $event->name)

@push('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<style>
    trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
    .trix-content { min-height: 150px; background-color: #fff !important; }
    .checklist-wrapper { position: relative; min-height: 100px; max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 0.375rem; }
    .checklist-placeholder { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #6c757d; pointer-events: none; }
    .img-preview { width: 100%; max-width: 350px; height: auto; border-radius: 8px; border: 1px solid #ddd; padding: 5px; margin-top: 10px; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark">
        <h4 class="card-title mb-0">Formulir Edit Event</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ======================================================================= --}}
            {{-- BAGIAN DETAIL EVENT UTAMA (LENGKAP) --}}
            {{-- ======================================================================= --}}
            <h5 class="mb-3 fw-bold">Detail Utama Event</h5>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="name" class="form-label">Nama Event</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $event->name) }}" required>@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                <div class="col-md-6 mb-3"><label for="slug" class="form-label">Slug</label><input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $event->slug) }}" required readonly>@error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            </div>
            <div class="mb-3"><label for="desc" class="form-label">Deskripsi Event</label><input id="desc" type="hidden" name="desc" value="{{ old('desc', $event->desc) }}"><trix-editor input="desc" class="trix-content @error('desc') is-invalid @enderror"></trix-editor>@error('desc') <div class="text-danger small mt-1">{{ $message }}</div> @enderror</div>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="type" class="form-label">Tipe Event</label><select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required><option value="official" {{ old('type', $event->type) == 'official' ? 'selected' : '' }}>Official</option><option value="non-official" {{ old('type', $event->type) == 'non-official' ? 'selected' : '' }}>Non-Official</option></select>@error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                <div class="col-md-4 mb-3">
                    <label for="month" class="form-label fw-bold">Bulan Pelaksanaan</label>
                    <select class="form-select @error('month') is-invalid @enderror" id="month" name="month" required>
                        <option value="" disabled>Pilih Bulan</option>
                        @php
                            $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                        @endphp
                        @foreach($months as $month)
                            <option value="{{ $month }}" {{ old('month', $event->month) == $month ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                    @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                {{-- KODE BARU YANG BENAR --}}
<div class="col-md-4 mb-3">
    <label for="status" class="form-label fw-bold">Status Pendaftaran</label>
    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
        {{-- Value diubah menjadi angka 0, 1, 2 --}}
        <option value="0" {{ old('status', $event->status) == 0 ? 'selected' : '' }}>Belum Dibuka</option>
        <option value="1" {{ old('status', $event->status) == 1 ? 'selected' : '' }}>Sudah Dibuka</option>
        <option value="2" {{ old('status', $event->status) == 2 ? 'selected' : '' }}>Ditutup</option>
    </select>
    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="harga_contingent" class="form-label">Harga Kontingen</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control @error('harga_contingent') is-invalid @enderror" id="harga_contingent" name="harga_contingent" value="{{ old('harga_contingent', $event->harga_contingent) }}" required></div>@error('harga_contingent') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror</div>
                <div class="col-md-6 mb-3"><label for="total_hadiah" class="form-label">Total Hadiah</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control @error('total_hadiah') is-invalid @enderror" id="total_hadiah" name="total_hadiah" value="{{ old('total_hadiah', $event->total_hadiah) }}" required></div>@error('total_hadiah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="kotaOrKabupaten" class="form-label">Kota / Kabupaten</label><input type="text" class="form-control @error('kotaOrKabupaten') is-invalid @enderror" id="kotaOrKabupaten" name="kotaOrKabupaten" value="{{ old('kotaOrKabupaten', $event->kotaOrKabupaten) }}" required>@error('kotaOrKabupaten') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                <div class="col-md-6 mb-3"><label for="lokasi" class="form-label">Lokasi Detail</label><input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" required>@error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="tgl_mulai_tanding" class="form-label">Tgl Mulai Tanding</label><input type="date" class="form-control @error('tgl_mulai_tanding') is-invalid @enderror" id="tgl_mulai_tanding" name="tgl_mulai_tanding" value="{{ old('tgl_mulai_tanding', $event->tgl_mulai_tanding) }}" required>@error('tgl_mulai_tanding') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                <div class="col-md-4 mb-3"><label for="tgl_selesai_tanding" class="form-label">Tgl Selesai Tanding</label><input type="date" class="form-control @error('tgl_selesai_tanding') is-invalid @enderror" id="tgl_selesai_tanding" name="tgl_selesai_tanding" value="{{ old('tgl_selesai_tanding', $event->tgl_selesai_tanding) }}" required>@error('tgl_selesai_tanding') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                <div class="col-md-4 mb-3"><label for="tgl_batas_pendaftaran" class="form-label">Batas Pendaftaran</label><input type="date" class="form-control @error('tgl_batas_pendaftaran') is-invalid @enderror" id="tgl_batas_pendaftaran" name="tgl_batas_pendaftaran" value="{{ old('tgl_batas_pendaftaran', $event->tgl_batas_pendaftaran) }}" required>@error('tgl_batas_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            </div>

            {{-- ======================================================================= --}}
            {{-- BAGIAN PENGELOMPOKAN KELAS (BARU) --}}
            {{-- ======================================================================= --}}
            <hr class="my-4">
            <h5 class="mb-3 fw-bold">Pengelompokan Kelas Pertandingan</h5>
            <p class="text-muted small">Edit grup untuk menerapkan Rentang Usia, Kategori, Jenis, Harga, dan Gender yang sama ke beberapa kelas sekaligus.</p>

            <div id="grup-container">
                @php
                    $groupsToDisplay = old('groups', $eventGroups);
                    if (empty($groupsToDisplay)) { $groupsToDisplay = [[]]; }
                @endphp
                @foreach($groupsToDisplay as $index => $grup)
                <div class="card mb-3 grup-item">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Grup Aturan #<span class="grup-index">{{ $index + 1 }}</span></h6>
                        @if($index > 0) <button type="button" class="btn btn-danger btn-sm remove-grup-btn"><i class="bi bi-trash"></i> Hapus Grup</button> @endif
                    </div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label fw-bold">1. Pilih Rentang Usia untuk Grup Ini</label><div class="d-flex flex-wrap" style="gap: 1rem;">@foreach ($daftar_rentang_usia as $usia)<div class="form-check"><input class="form-check-input rentang-usia-radio" type="radio" name="groups[{{$index}}][rentang_usia_id]" id="rentang_{{$index}}_{{ $usia->id }}" value="{{ $usia->id }}" {{ ($grup['rentang_usia_id'] ?? null) == $usia->id ? 'checked' : '' }} required><label class="form-check-label" for="rentang_{{$index}}_{{ $usia->id }}">{{ $usia->rentang_usia }}</label></div>@endforeach</div>@error('groups.'.$index.'.rentang_usia_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror</div>
                        <hr>
                        <p class="fw-bold">2. Atur Detail Lainnya</p>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Kategori Pertandingan</label><div>@foreach ($kategori_pertandingan as $kategori)<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[{{$index}}][kategori_id]" id="kategori_{{$index}}_{{ $kategori->id }}" value="{{ $kategori->id }}" {{ ($grup['kategori_id'] ?? null) == $kategori->id ? 'checked' : '' }} required><label class="form-check-label" for="kategori_{{$index}}_{{ $kategori->id }}">{{ $kategori->nama_kategori }}</label></div>@endforeach</div></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Jenis Pertandingan</label><div>@foreach ($jenis_pertandingan as $jenis)<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[{{$index}}][jenis_id]" id="jenis_{{$index}}_{{ $jenis->id }}" value="{{ $jenis->id }}" {{ ($grup['jenis_id'] ?? null) == $jenis->id ? 'checked' : '' }} required><label class="form-check-label" for="jenis_{{$index}}_{{ $jenis->id }}">{{ $jenis->nama_jenis }}</label></div>@endforeach</div></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Harga Pendaftaran</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="groups[{{$index}}][harga]" value="{{ $grup['harga'] ?? '' }}" placeholder="150000" required></div></div>
                    
                            {{-- DENGAN KODE YANG SUDAH DIPERBAIKI INI --}}
<div class="col-md-6 mb-3">
    <label class="form-label">Gender</label>
    <div>
        @php
            // TRIM() untuk menghapus spasi, STRTOLOWER() untuk mengatasi besar/kecil huruf
            $currentGender = strtolower(trim($grup['gender'] ?? ''));
        @endphp
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="groups[{{$index}}][gender]" id="gender_{{$index}}_laki" value="Laki-laki" @if($currentGender == 'laki-laki') checked @endif required>
            <label class="form-check-label" for="gender_{{$index}}_laki">Laki-laki</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="groups[{{$index}}][gender]" id="gender_{{$index}}_perempuan" value="Perempuan" @if($currentGender == 'perempuan') checked @endif>
            <label class="form-check-label" for="gender_{{$index}}_perempuan">Perempuan</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="groups[{{$index}}][gender]" id="gender_{{$index}}_campuran" value="Campuran" @if($currentGender == 'campuran') checked @endif>
            <label class="form-check-label" for="gender_{{$index}}_campuran">Campuran</label>
        </div>
    </div>
    @error('groups.'.$index.'.gender') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>


                        </div>
                        <div class="mb-2"><label class="form-label fw-bold">3. Pilih Kelas yang Termasuk Grup Ini</label><div class="checklist-wrapper"><span class="checklist-placeholder">Pilih rentang usia di atas untuk menampilkan kelas.</span><div class="row">@foreach ($daftar_kelas as $kelas)<div class="col-md-4 col-sm-6 kelas-choice-item" data-rentang-id="{{ $kelas->rentang_usia_id }}" style="display: none;"><div class="form-check"><input class="form-check-input" type="checkbox" name="groups[{{$index}}][kelas_ids][]" value="{{ $kelas->id }}" id="kelas_{{$index}}_{{$kelas->id}}" {{ in_array($kelas->id, $grup['kelas_ids'] ?? []) ? 'checked' : '' }}><label class="form-check-label" for="kelas_{{$index}}_{{$kelas->id}}">{{ $kelas->nama_kelas }}</label></div></div>@endforeach</div></div>@error('groups.'.$index.'.kelas_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror</div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-grup-btn" class="btn btn-outline-success"><i class="bi bi-plus-circle-fill"></i> Tambah Grup Aturan Lain</button>
            <hr class="my-4">

            {{-- ======================================================================= --}}
            {{-- BAGIAN LAMPIRAN & KONTAK (LENGKAP) --}}
            {{-- ======================================================================= --}}
            <h5 class="mb-3 fw-bold">Lampiran & Kontak</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label fw-bold">Ganti Gambar/Poster Event</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                    <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($event->image)
                        <div class="mt-2"><label class="form-label">Gambar Saat Ini:</label><br><img src="{{ asset('storage/' . $event->image) }}" alt="Poster {{ $event->name }}" class="img-preview"></div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label for="juknis" class="form-label fw-bold">File Juknis (URL)</label>
                    <input class="form-control @error('juknis') is-invalid @enderror" type="text" placeholder="Isi berupa link panduan" id="juknis" name="juknis" value="{{ old('juknis', $event->juknis) }}">
                    @error('juknis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mb-4">
                <label for="cp" class="form-label fw-bold">Contact Person (CP)</label>
                <input id="cp" type="hidden" name="cp" value="{{ old('cp', $event->cp) }}">
                <trix-editor input="cp" class="trix-content @error('cp') is-invalid @enderror"></trix-editor>
                @error('cp') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('superadmin.kelola_event') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-warning">Update Event</button>
            </div>
        </form>
    </div>
</div>

<template id="grup-item-template">
    {{-- Template ini sama persis dengan yang ada di form create --}}
    <div class="card mb-3 grup-item"><div class="card-header bg-light d-flex justify-content-between align-items-center"><h6 class="mb-0">Grup Aturan #<span class="grup-index"></span></h6><button type="button" class="btn btn-danger btn-sm remove-grup-btn"><i class="bi bi-trash"></i> Hapus Grup</button></div><div class="card-body"><div class="mb-3"><label class="form-label fw-bold">1. Pilih Rentang Usia untuk Grup Ini</label><div class="d-flex flex-wrap" style="gap: 1rem;">@foreach ($daftar_rentang_usia as $usia)<div class="form-check"><input class="form-check-input rentang-usia-radio" type="radio" name="groups[__INDEX__][rentang_usia_id]" id="rentang___INDEX___{{ $usia->id }}" value="{{ $usia->id }}" required><label class="form-check-label" for="rentang___INDEX___{{ $usia->id }}">{{ $usia->rentang_usia }}</label></div>@endforeach</div></div><hr><p class="fw-bold">2. Atur Detail Lainnya</p><div class="row"><div class="col-md-6 mb-3"><label class="form-label">Kategori Pertandingan</label><div>@foreach ($kategori_pertandingan as $kategori)<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[__INDEX__][kategori_id]" id="kategori___INDEX___{{ $kategori->id }}" value="{{ $kategori->id }}" required><label class="form-check-label" for="kategori___INDEX___{{ $kategori->id }}">{{ $kategori->nama_kategori }}</label></div>@endforeach</div></div><div class="col-md-6 mb-3"><label class="form-label">Jenis Pertandingan</label><div>@foreach ($jenis_pertandingan as $jenis)<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[__INDEX__][jenis_id]" id="jenis___INDEX___{{ $jenis->id }}" value="{{ $jenis->id }}" required><label class="form-check-label" for="jenis___INDEX___{{ $jenis->id }}">{{ $jenis->nama_jenis }}</label></div>@endforeach</div></div></div><div class="row"><div class="col-md-6 mb-3"><label class="form-label">Harga Pendaftaran</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control" name="groups[__INDEX__][harga]" placeholder="150000" required></div></div><div class="col-md-6 mb-3"><label class="form-label">Gender</label><div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[__INDEX__][gender]" id="gender___INDEX___laki" value="Laki-laki" required><label class="form-check-label" for="gender___INDEX___laki">Laki-laki</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[__INDEX__][gender]" id="gender___INDEX___perempuan" value="Perempuan"><label class="form-check-label" for="gender___INDEX___perempuan">Perempuan</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="groups[__INDEX__][gender]" id="gender___INDEX___campuran" value="Campuran"><label class="form-check-label" for="gender___INDEX___campuran">Campuran</label></div></div></div></div><div class="mb-2"><label class="form-label fw-bold">3. Pilih Kelas yang Termasuk Grup Ini</label><div class="checklist-wrapper"><span class="checklist-placeholder">Pilih rentang usia di atas untuk menampilkan kelas.</span><div class="row">@foreach ($daftar_kelas as $kelas)<div class="col-md-4 col-sm-6 kelas-choice-item" data-rentang-id="{{ $kelas->rentang_usia_id }}" style="display: none;"><div class="form-check"><input class="form-check-input" type="checkbox" name="groups[__INDEX__][kelas_ids][]" value="{{ $kelas->id }}" id="kelas___INDEX___{{$kelas->id}}"><label class="form-check-label" for="kelas___INDEX___{{$kelas->id}}">{{ $kelas->nama_kelas }}</label></div></div>@endforeach</div></div></div></div></div>
</template>
@endsection

@push('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<script>
    // JavaScript di sini sama persis dengan yang ada di form create.
    // Tidak perlu ada perubahan.
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        if(nameInput) {
            nameInput.addEventListener('keyup', () => { slugInput.value = nameInput.value.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-'); });
        }
        document.addEventListener("trix-file-accept", e => e.preventDefault());

        const grupContainer = document.getElementById('grup-container');
        const addGrupBtn = document.getElementById('add-grup-btn');
        const template = document.getElementById('grup-item-template');
        
        const filterChecklistForGroup = (grupElement, selectedUsiaId) => {
            const placeholder = grupElement.querySelector('.checklist-placeholder');
            let hasVisibleItems = false;
            grupElement.querySelectorAll('.kelas-choice-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (item.dataset.rentangId === selectedUsiaId) {
                    item.style.display = 'block';
                    hasVisibleItems = true;
                } else {
                    item.style.display = 'none';
                    if (checkbox) checkbox.checked = false;
                }
            });
            if(placeholder) placeholder.style.display = hasVisibleItems ? 'none' : 'block';
        };

        grupContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('rentang-usia-radio')) {
                const parentGroup = e.target.closest('.grup-item');
                filterChecklistForGroup(parentGroup, e.target.value);
            }
        });

        addGrupBtn.addEventListener('click', function() {
            const newIndex = Date.now(); // Use timestamp for unique index to avoid conflict
            const clone = template.content.cloneNode(true);
            const newForm = clone.querySelector('.grup-item');
            
            newForm.querySelector('.grup-index').textContent = grupContainer.querySelectorAll('.grup-item').length + 1;
            
            newForm.querySelectorAll('[name*="__INDEX__"], [id*="__INDEX__"], [for*="__INDEX__"]').forEach(el => {
                const replaceIndex = (attr) => attr.replace(/__INDEX__/g, newIndex);
                if (el.name) el.name = replaceIndex(el.name);
                if (el.id) el.id = replaceIndex(el.id);
                if (el.htmlFor) el.htmlFor = replaceIndex(el.htmlFor);
            });
            grupContainer.appendChild(newForm);
        });

        grupContainer.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-grup-btn');
            if (removeBtn) {
                removeBtn.closest('.grup-item').remove();
                updateIndexes();
            }
        });

        const updateIndexes = () => {
            grupContainer.querySelectorAll('.grup-item').forEach((item, index) => {
                item.querySelector('.grup-index').textContent = index + 1;
                item.querySelectorAll('[name^="groups["]').forEach(el => {
                    if (el.name) el.name = el.name.replace(/\[.*?\]/, `[${index}]`);
                });
                item.querySelectorAll('[id*="_"], [for*="_"]').forEach(el => {
                    if (el.id) {
                        let newId = el.id.replace(/_(\d+|__INDEX__)_/, `_${index}_`);
                        let label = item.querySelector(`label[for="${el.id}"]`);
                        el.id = newId;
                        if(label) label.htmlFor = newId;
                    }
                });
            });
        };

        // KUNCI UNTUK EDIT: Jalankan filter untuk setiap grup yang sudah ada saat halaman dimuat
        grupContainer.querySelectorAll('.grup-item').forEach(grup => {
            const selectedRadio = grup.querySelector('.rentang-usia-radio:checked');
            if (selectedRadio) {
                filterChecklistForGroup(grup, selectedRadio.value);
            }
        });
    });
</script>
@endpush