<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Peserta - {{ $player->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background: linear-gradient(135deg, #ffffffff 0%, #dfdfdfff 100%); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-container { background: rgba(255, 255, 255, 0.95); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); margin: 20px auto; max-width: 1200px; }
        .header { background: linear-gradient(135deg, #000000ff, #494949ff); color: white; padding: 30px; border-radius: 20px 20px 0 0; text-align: center; }
        .athlete-card { background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 15px; margin-bottom: 30px; }
        .athlete-header { background: linear-gradient(135deg, #c50000ff, #c86868ff); color: white; padding: 15px 20px; border-radius: 13px 13px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .btn-custom { background: linear-gradient(135deg, #c50000ff, #c86868ff); border: none; border-radius: 25px; padding: 12px 30px; color: white; font-weight: 600; transition: all 0.3s ease; }
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(197, 0, 0, 0.3); color: white; }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px 15px; transition: all 0.3s ease; }
        .form-control:focus, .form-select:focus { border-color: #c86868ff; box-shadow: 0 0 0 0.2rem rgba(197, 0, 0, 0.25); }
        .upload-area { border: 2px dashed #c86868ff; border-radius: 10px; padding: 20px; text-align: center; background: rgba(197, 0, 0, 0.05); }
        .file-info { background: #e2e3e5; border: 1px solid #d6d8db; border-radius: 8px; padding: 10px; margin-top: 10px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header">
                <h1><i class="fas fa-edit me-3"></i>Edit Data Peserta</h1>
                <p class="mb-0">Perbarui informasi untuk: <strong>{{ $player->name }}</strong></p>
            </div>

            <div class="p-4 p-md-5">
                <form id="editForm" method="POST" action="{{ route('player.update', $player->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div id="athleteCard" class="athlete-card">
                        <div class="athlete-header"><h5 class="mb-0"><i class="fas fa-user-ninja me-2"></i>Data Atlet</h5></div>
                        <div class="p-4">
                            @if ($player->contingent->status == 2)<div class="alert alert-warning" role="alert"><i class="fas fa-exclamation-triangle me-2"></i>Kontingen induk peserta ini sedang ditolak. Menyimpan perubahan akan mengubah status kontingen menjadi 'Menunggu Verifikasi'.</div>@endif
                            @if ($player->status == 3)<div class="alert alert-danger" role="alert"><i class="fas fa-info-circle me-2"></i>Peserta ini ditolak. Catatan: {{ $player->catatan ?: 'Tidak ada.' }}<br>Menyimpan perubahan akan mengubah status peserta kembali menjadi 'Pending'.</div>@endif

                            <h5 class="text-danger mb-3">Data Diri Atlet</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Nama Lengkap</label><input type="text" class="form-control" name="name" value="{{ old('name', $player->name) }}" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold">NIK</label><input type="text" class="form-control" name="nik" value="{{ old('nik', $player->nik) }}" pattern="[0-9]{16}" maxlength="16" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Jenis Kelamin</label><select class="form-select" name="gender" required><option value="" disabled>Pilih...</option><option value="Laki-laki" {{ old('gender', $player->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option><option value="Perempuan" {{ old('gender', $player->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option></select></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Tanggal Lahir</label><input type="date" class="form-control" name="tgl_lahir" value="{{ old('tgl_lahir', $player->tgl_lahir) }}" required></div>
                            </div>
                            <hr>
                            <h5 class="text-danger mb-3">Pilihan Kelas Pertandingan</h5>
                            <div class="row filter-controls">
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold">1. Pilih Rentang Usia</label><div class="rentang-usia-options"></div></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold">2. Pilih Kategori</label><div class="kategori-options"></div></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold">3. Pilih Jenis</label><div class="jenis-options"></div></div>
                            </div>
                            <div class="row"><div class="col-12 mb-3"><label class="form-label fw-bold">4. Pilih Kelas Pertandingan</label><select class="form-select" name="kelas_pertandingan_id" required><option value="">Lengkapi filter di atas</option></select></div></div>
                            <hr>
                            <h5 class="text-danger mb-3">Upload Dokumen</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">KK/KTP</label>
                                    @if ($player->foto_ktp)<div class="file-info"><a href="{{ Storage::url($player->foto_ktp) }}" target="_blank">Lihat file saat ini</a></div>@endif
                                    <div class="upload-area mt-2"><p class="mb-2">Ganti file (opsional)</p><input type="file" class="form-control" name="foto_ktp" accept=".jpg,.jpeg,.png,.pdf"></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto Diri</label>
                                    @if ($player->foto_diri)<div class="file-info"><a href="{{ Storage::url($player->foto_diri) }}" target="_blank">Lihat foto saat ini</a></div>@endif
                                    <div class="upload-area mt-2"><p class="mb-2">Ganti foto (opsional)</p><input type="file" class="form-control" name="foto_diri" accept=".jpg,.jpeg,.png"></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Persetujuan Ortu</label>
                                    @if ($player->foto_persetujuan_ortu)<div class="file-info"><a href="{{ Storage::url($player->foto_persetujuan_ortu) }}" target="_blank">Lihat file saat ini</a></div>@endif
                                    <div class="upload-area mt-2"><p class="mb-2">Ganti file (opsional)</p><input type="file" class="form-control" name="foto_persetujuan_ortu" accept=".jpg,.jpeg,.png,.pdf"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4 d-flex justify-content-end">
                        <a href="{{ route('history') }}" class="btn btn-secondary btn-lg me-3"><i class="fas fa-times me-2"></i>Batal</a>
                        <button type="submit" class="btn btn-custom btn-lg"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const KELAS_PERTANDINGAN_DATA = @json($availableClasses);
        const card = document.getElementById('athleteCard');

        const buildRadioGroup = (data, name, labelKey, container, selectedValue = null) => {
            data.forEach(item => {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-check';
                const input = document.createElement('input');
                input.type = 'radio';
                input.className = 'form-check-input';
                input.name = `filter_${name}`;
                input.value = item.id;
                input.id = `${name}_${item.id}`;
                if (item.id == selectedValue) {
                    input.checked = true;
                }
                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.htmlFor = input.id;
                label.textContent = item[labelKey];
                wrapper.appendChild(input);
                wrapper.appendChild(label);
                container.appendChild(wrapper);
            });
        };

        const updateAvailableClasses = () => {
            const selectedGender = card.querySelector('select[name="gender"]').value;
            const selectedRentang = card.querySelector('input[name="filter_rentang_usia"]:checked')?.value;
            const selectedKategori = card.querySelector('input[name="filter_kategori"]:checked')?.value;
            const selectedJenis = card.querySelector('input[name="filter_jenis"]:checked')?.value;
            const kelasSelect = card.querySelector('select[name="kelas_pertandingan_id"]');
            const currentPlayerKelasId = "{{ $player->kelas_pertandingan_id }}";

            kelasSelect.innerHTML = '<option value="">Pilih...</option>';
            if (!selectedRentang || !selectedKategori || !selectedJenis || !selectedGender) {
                kelasSelect.firstElementChild.textContent = "Lengkapi 4 filter di atas";
                return;
            }

            const filteredClasses = KELAS_PERTANDINGAN_DATA.filter(k => 
                k.rentang_usia_id == selectedRentang &&
                k.kategori_pertandingan_id == selectedKategori &&
                k.jenis_pertandingan_id == selectedJenis &&
                (k.gender.toLowerCase() === selectedGender.toLowerCase() || k.gender === 'Campuran')
            );
            
            if (filteredClasses.length > 0) {
                filteredClasses.forEach(k => {
                    const option = document.createElement('option');
                    option.value = k.kelas_pertandingan_id;
                    option.textContent = `${k.nama_kelas} (${k.gender})`;
                    if (k.kelas_pertandingan_id == currentPlayerKelasId) {
                        option.selected = true;
                    }
                    kelasSelect.appendChild(option);
                });
            } else {
                kelasSelect.firstElementChild.textContent = "Tidak ada kelas yang sesuai";
            }
        };
        
        // --- Inisialisasi Filter dengan Data Player Saat Ini ---
        const initialKelas = KELAS_PERTANDINGAN_DATA.find(k => k.kelas_pertandingan_id == "{{ $player->kelas_pertandingan_id }}");

        buildRadioGroup(@json($rentangUsia), 'rentang_usia', 'rentang_usia', card.querySelector('.rentang-usia-options'), initialKelas?.rentang_usia_id);
        buildRadioGroup(@json($kategoriPertandingan), 'kategori', 'nama_kategori', card.querySelector('.kategori-options'), initialKelas?.kategori_pertandingan_id);
        buildRadioGroup(@json($jenisPertandingan), 'jenis', 'nama_jenis', card.querySelector('.jenis-options'), initialKelas?.jenis_pertandingan_id);
        
        // Panggil updateAvailableClasses() sekali di awal untuk mengisi dropdown kelas
        updateAvailableClasses();

        // Tambahkan event listener
        card.addEventListener('change', (e) => {
            if (e.target.closest('.filter-controls') || e.target.name === 'gender') {
                updateAvailableClasses();
            }
        });
    });
    </script>
</body>
</html>