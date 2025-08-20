<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Atlet: {{ $event->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background: linear-gradient(135deg, #ffffffff 0%, #dfdfdfff 100%); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-container { background: rgba(255, 255, 255, 0.95); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); margin: 20px auto; max-width: 1200px; }
        .header { background: linear-gradient(135deg, #000000ff, #494949ff); color: white; padding: 30px; border-radius: 20px 20px 0 0; text-align: center; }
        .athlete-card { background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 15px; margin-bottom: 30px; transition: all 0.3s ease; }
        .athlete-card:hover { border-color: #c86868ff; }
        .athlete-header { background: linear-gradient(135deg, #c50000ff, #c86868ff); color: white; padding: 15px 20px; border-radius: 13px 13px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .btn-custom { background: linear-gradient(135deg, #c50000ff, #c86868ff); border: none; border-radius: 25px; padding: 12px 30px; color: white; font-weight: 600; transition: all 0.3s ease; }
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3); color: white; }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px 15px; }
        .form-control:focus, .form-select:focus { border-color: #c86868ff; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .upload-area { border: 2px dashed #c86868ff; border-radius: 10px; padding: 20px; text-align: center; background: rgba(102, 126, 234, 0.05); }
        .file-info { background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 10px; margin-top: 10px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header">
                <h1><i class="fas fa-fist-raised me-3"></i>Pendaftaran Kejuaraan: {{ $event->name }}</h1>
                <p class="mb-0">Sistem Pendaftaran Atlet Pencak Silat Indonesia</p>
            </div>

            <div class="p-4">
                <div id="alert-container"></div>
                <form id="registrationForm">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <h4 class="text-danger mb-2"><i class="fas fa-users me-2"></i>Informasi Kontingen</h4>
                            <h1>{{ $contingent->name }}</h1>
                        </div>
                    </div>
                    
                    <div id="athletesContainer"></div>
                    
                    <div class="text-center mb-4"><button type="button" class="btn btn-custom" id="addAthleteBtn"><i class="fas fa-plus me-2"></i>Tambah Atlet</button></div>
                    <div class="text-center"><button type="submit" class="btn btn-custom btn-lg" id="submitBtn"><i class="fas fa-paper-plane me-2"></i>Daftar Kejuaraan</button></div>
                </form>
            </div>
        </div>
    </div>

<template id="athleteTemplate">
    <div class="athlete-card" data-athlete-id="__ID__">
        <div class="athlete-header">
            <h5 class="mb-0 athlete-title"><i class="fas fa-user-ninja me-2"></i>Atlet __COUNT__</h5>
            <button type="button" class="btn btn-sm btn-outline-light remove-athlete-btn"><i class="fas fa-trash me-1"></i>Hapus</button>
        </div>
        <div class="p-4">
            <h5 class="text-danger mb-3">Data Diri Atlet</h5>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Nama Lengkap</label><input type="text" class="form-control" name="namaLengkap" required></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">NIK</label><input type="text" class="form-control" name="nik" pattern="[0-9]{16}" maxlength="16" required></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Jenis Kelamin</label><select class="form-select" name="jenisKelamin" required><option value="" selected disabled>Pilih...</option><option value="laki-laki">Laki-laki</option><option value="perempuan">Perempuan</option></select></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-bold">Tanggal Lahir</label><input type="date" class="form-control" name="tanggalLahir" required></div>
            </div>
            <hr>
            <h5 class="text-danger mb-3">Pilihan Kelas Pertandingan</h5>
            <div class="row filter-controls">
                <div class="col-md-4 mb-3"><label class="form-label fw-bold">1. Pilih Rentang Usia</label><div class="rentang-usia-options"></div></div>
                <div class="col-md-4 mb-3"><label class="form-label fw-bold">2. Pilih Kategori</label><div class="kategori-options"></div></div>
                <div class="col-md-4 mb-3"><label class="form-label fw-bold">3. Pilih Jenis</label><div class="jenis-options"></div></div>
            </div>
            <div class="row">
                <div class="col-12 mb-3"><label class="form-label fw-bold">4. Pilih Kelas Pertandingan</label><select class="form-select" name="kelas_pertandingan_id" required><option value="">Lengkapi semua filter di atas</option></select></div>
            </div>
            <hr>
            <h5 class="text-danger mb-3">Upload Dokumen</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><label class="form-label fw-bold">KK/KTP</label><div class="upload-area"><i class="fas fa-cloud-upload-alt fa-2x text-danger mb-2"></i><input type="file" class="form-control" name="uploadKTP" accept=".jpg,.jpeg,.png,.pdf" required></div><div class="file-info-display"></div></div>
                <div class="col-md-4 mb-3"><label class="form-label fw-bold">Foto Diri</label><div class="upload-area"><i class="fas fa-camera fa-2x text-danger mb-2"></i><input type="file" class="form-control" name="uploadFoto" accept=".jpg,.jpeg,.png" required></div><div class="file-info-display"></div></div>
                <div class="col-md-4 mb-3"><label class="form-label fw-bold">Persetujuan Ortu</label><div class="upload-area"><i class="fas fa-user-check fa-2x text-danger mb-2"></i><input type="file" class="form-control" name="uploadPersetujuan" accept=".jpg,.jpeg,.png,.pdf" required></div><div class="file-info-display"></div></div>
            </div>
        </div>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- DATA DARI CONTROLLER ---
    const CONTINGENT_ID = {{ $contingent->id }};
    const RENTANG_USIA_DATA = @json($rentangUsia);
    const KATEGORI_DATA = @json($kategoriPertandingan);
    const JENIS_DATA = @json($jenisPertandingan);
    const KELAS_PERTANDINGAN_DATA = @json($availableClasses);

    // --- ELEMENT REFERENCES ---
    const athletesContainer = document.getElementById('athletesContainer');
    const addAthleteBtn = document.getElementById('addAthleteBtn');
    const registrationForm = document.getElementById('registrationForm');
    const template = document.getElementById('athleteTemplate');
    const alertContainer = document.getElementById('alert-container');

    let athleteIdCounter = 0;

    const addAthlete = () => {
        athleteIdCounter++;
        const uniqueId = athleteIdCounter;
        
        const clone = template.content.cloneNode(true);
        const newCard = clone.querySelector('.athlete-card');
        
        newCard.dataset.athleteId = uniqueId;
        newCard.querySelector('.athlete-title').textContent = `Atlet ${athletesContainer.children.length + 1}`;
        
        buildRadioGroup(RENTANG_USIA_DATA, 'rentang_usia', 'rentang_usia', newCard.querySelector('.rentang-usia-options'), uniqueId);
        buildRadioGroup(KATEGORI_DATA, 'kategori', 'nama_kategori', newCard.querySelector('.kategori-options'), uniqueId);
        buildRadioGroup(JENIS_DATA, 'jenis', 'nama_jenis', newCard.querySelector('.jenis-options'), uniqueId);

        athletesContainer.appendChild(clone);
    };

    const buildRadioGroup = (data, name, labelKey, container, id) => {
        data.forEach(item => {
            const wrapper = document.createElement('div');
            wrapper.className = 'form-check';
            const input = document.createElement('input');
            input.type = 'radio';
            input.className = 'form-check-input';
            input.name = `${name}_${id}`;
            input.value = item.id;
            input.id = `${name}_${item.id}_${id}`;
            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = input.id;
            label.textContent = item[labelKey];
            
            wrapper.appendChild(input);
            wrapper.appendChild(label);
            container.appendChild(wrapper);
        });
    };

    const updateAvailableClasses = (card) => {
        const uniqueId = card.dataset.athleteId;
        // =======================================================================
        // PERBAIKAN 1: Ambil nilai dari dropdown Jenis Kelamin
        // =======================================================================
        const selectedGender = card.querySelector('select[name="jenisKelamin"]').value;
        const selectedRentang = card.querySelector(`input[name="rentang_usia_${uniqueId}"]:checked`)?.value;
        const selectedKategori = card.querySelector(`input[name="kategori_${uniqueId}"]:checked`)?.value;
        const selectedJenis = card.querySelector(`input[name="jenis_${uniqueId}"]:checked`)?.value;
        const kelasSelect = card.querySelector('select[name="kelas_pertandingan_id"]');

        kelasSelect.innerHTML = '<option value="">Pilih...</option>';
        
        // =======================================================================
        // PERBAIKAN 2: Pastikan semua 4 filter sudah dipilih
        // =======================================================================
        if (!selectedRentang || !selectedKategori || !selectedJenis || !selectedGender) {
            kelasSelect.firstElementChild.textContent = "Lengkapi 4 filter di atas";
            return;
        }

        // =======================================================================
        // PERBAIKAN 3: Tambahkan filter gender ke dalam logika
        // =======================================================================
        const filteredClasses = KELAS_PERTANDINGAN_DATA.filter(k => 
            k.rentang_usia_id == selectedRentang &&
            k.kategori_pertandingan_id == selectedKategori &&
            k.jenis_pertandingan_id == selectedJenis &&
            (k.gender.toLowerCase() === selectedGender || k.gender === 'Campuran') // Cek gender atau 'Campuran'
        );

        if (filteredClasses.length > 0) {
            filteredClasses.forEach(k => {
                const option = document.createElement('option');
                option.value = k.kelas_pertandingan_id;
                option.textContent = `${k.nama_kelas} (${k.gender})`;
                kelasSelect.appendChild(option);
            });
        } else {
            kelasSelect.firstElementChild.textContent = "Tidak ada kelas yang sesuai";
        }
    };

    athletesContainer.addEventListener('change', (e) => {
        const card = e.target.closest('.athlete-card');
        if (!card) return;

        // =======================================================================
        // PERBAIKAN 4: Picu filter saat dropdown jenis kelamin diubah
        // =======================================================================
        if (e.target.type === 'radio' || e.target.name === 'jenisKelamin') {
            updateAvailableClasses(card);
        }

        if (e.target.type === 'file') {
            const infoDiv = e.target.closest('.upload-area').nextElementSibling;
            const file = e.target.files[0];
            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                infoDiv.innerHTML = `<div class="file-info"><i class="fas fa-check-circle text-success me-2"></i><strong>${file.name}</strong> (${fileSize} MB)</div>`;
            } else {
                infoDiv.innerHTML = '';
            }
        }
    });

    athletesContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-athlete-btn')) {
            if (athletesContainer.children.length > 1) {
                e.target.closest('.athlete-card').remove();
                document.querySelectorAll('.athlete-title').forEach((title, index) => {
                    title.textContent = `Atlet ${index + 1}`;
                });
            } else {
                showAlert('Minimal harus ada satu atlet terdaftar.', 'warning');
            }
        }
    });
    
    // ... (Sisa kode untuk submit form tidak berubah)
    registrationForm.addEventListener('submit', function(e) { /* ... */ });
    const showAlert = (message, type = 'danger') => { /* ... */ };
    addAthleteBtn.addEventListener('click', addAthlete);
    addAthlete();
});
</script>
</body>
</html>