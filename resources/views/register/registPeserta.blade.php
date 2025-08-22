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
        body { background: linear-gradient(135deg, #ffffffff 0%, #dfdfdfff 100%); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-bottom: 150px; }
        .main-container { background: rgba(255, 255, 255, 0.95); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); margin: 20px auto; max-width: 1200px; }
        .header { background: linear-gradient(135deg, #000000ff, #494949ff); color: white; padding: 30px; border-radius: 20px 20px 0 0; text-align: center; }
        .athlete-card { background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 15px; margin-bottom: 30px; transition: all 0.3s ease; }
        .athlete-card:hover { border-color: #c86868ff; }
        .athlete-header { background: linear-gradient(135deg, #c50000ff, #c86868ff); color: white; padding: 15px 20px; border-radius: 13px 13px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .btn-custom { background: linear-gradient(135deg, #c50000ff, #c86868ff); border: none; border-radius: 25px; padding: 12px 30px; color: white; font-weight: 600; transition: all 0.3s ease; }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px 15px; }
        .form-control:focus, .form-select:focus { border-color: #c86868ff; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .upload-area { border: 2px dashed #c86868ff; border-radius: 10px; padding: 20px; text-align: center; background: rgba(102, 126, 234, 0.05); }
        .file-info { background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 10px; margin-top: 10px; font-size: 0.9em; }
        .sub-athlete-form { border-left: 3px solid #fd7e14; padding-left: 15px; margin-left: 10px; margin-top: 20px; background-color: #fff; border-radius: 0 8px 8px 0; padding-bottom: 15px; }
        .total-price-footer { background: #ffffff; border-top: 3px solid #c50000ff; padding: 20px 40px; position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 -10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header"><h1><i class="fas fa-fist-raised me-3"></i>Pendaftaran Kejuaraan: {{ $event->name }}</h1><p class="mb-0">Sistem Pendaftaran Atlet Pencak Silat Indonesia</p></div>
            <div class="p-4">
                <div id="alert-container"></div>
                <form id="registrationForm">
                    <div class="row mb-4"><div class="col-12 text-center"><h4 class="text-danger mb-2"><i class="fas fa-users me-2"></i>Informasi Kontingen</h4><h1>{{ $contingent->name }}</h1></div></div>
                    <div id="athletesContainer"></div>
                    <div class="text-center my-4"><button type="button" class="btn btn-custom" id="addAthleteBtn"><i class="fas fa-plus me-2"></i>Tambah Pendaftaran Kelas</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="total-price-footer d-flex justify-content-end align-items-center">
        <div class="me-4 text-end"><h5 class="mb-0">Total Biaya Pendaftaran:</h5><h2 class="fw-bold text-danger" id="totalPriceDisplay">Rp 0</h2></div>
        <button type="submit" form="registrationForm" class="btn btn-custom btn-lg" id="submitBtn"><i class="fas fa-paper-plane me-2"></i>Daftar & Bayar</button>
    </div>

<template id="athleteCardTemplate">
    <div class="athlete-card" data-card-id="__ID__">
        <div class="athlete-header"><h5 class="mb-0 athlete-title"><i class="fas fa-ticket-alt me-2"></i>Pendaftaran Kelas #__COUNT__</h5><button type="button" class="btn btn-sm btn-outline-light remove-card-btn"><i class="fas fa-trash me-1"></i>Hapus</button></div>
        <div class="p-4">
            <h5 class="text-danger mb-3">Filter Pilihan Kelas</h5>
            <div class="row filter-controls">
                <div class="col-md-3 mb-3"><label class="form-label fw-bold">1. Rentang Usia</label><div class="rentang-usia-options"></div></div>
                <div class="col-md-3 mb-3"><label class="form-label fw-bold">2. Kategori</label><div class="kategori-options"></div></div>
                <div class="col-md-3 mb-3"><label class="form-label fw-bold">3. Jenis</label><div class="jenis-options"></div></div>
                <div class="col-md-3 mb-3"><label class="form-label fw-bold">4. Jenis Kelamin</label><div class="gender-options"></div></div>
            </div>
            <div class="row">
                <div class="col-12 mb-3"><label class="form-label fw-bold">5. Pilih Kelas Pertandingan</label><select class="form-select" name="kelas_pertandingan_id" required><option value="">Lengkapi semua filter di atas</option></select></div>
            </div>
            <hr>
            <div class="sub-athlete-container"></div> 
        </div>
    </div>
</template>

<template id="subAthleteFormTemplate">
    <div class="sub-athlete-form">
        <h6 class="fw-bold text-dark pt-3">Data Atlet __SUB_COUNT__</h6>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="namaLengkap" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">NIK</label><input type="text" class="form-control" name="nik" pattern="[0-9]{16}" maxlength="16" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">No.Telp</label><input type="tel" class="form-control" name="noTelepon" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Jenis Kelamin</label><select class="form-select" name="jenisKelamin" required><option value="" selected disabled>Otomatis terisi</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></div>
            <div class="col-md-6 mb-3"><label class="form-label">Tanggal Lahir</label><input type="date" class="form-control" name="tanggalLahir" required></div>
        </div>
        <h6 class="fw-bold text-dark mt-2">Dokumen Atlet __SUB_COUNT__</h6>
        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">KK/KTP</label><div class="upload-area"><i class="fas fa-cloud-upload-alt fa-2x text-danger mb-2"></i><input type="file" class="form-control" name="uploadKTP" accept=".jpg,.jpeg,.png,.pdf" required></div><div class="file-info-display"></div></div>
            <div class="col-md-4 mb-3"><label class="form-label">Foto Diri</label><div class="upload-area"><i class="fas fa-camera fa-2x text-danger mb-2"></i><input type="file" class="form-control" name="uploadFoto" accept=".jpg,.jpeg,.png" required></div><div class="file-info-display"></div></div>
            <div class="col-md-4 mb-3"><label class="form-label">Persetujuan Ortu</label><div class="upload-area"><i class="fas fa-user-check fa-2x text-danger mb-2"></i><input type="file" class="form-control" name="uploadPersetujuan" accept=".jpg,.jpeg,.png,.pdf" required></div><div class="file-info-display"></div></div>
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
    const GENDER_DATA = [ {id: 'Laki-laki', name: 'Laki-laki'}, {id: 'Perempuan', name: 'Perempuan'} ];

    // --- ELEMENT REFERENCES ---
    const athletesContainer = document.getElementById('athletesContainer');
    const addAthleteBtn = document.getElementById('addAthleteBtn');
    const registrationForm = document.getElementById('registrationForm');
    const cardTemplate = document.getElementById('athleteCardTemplate');
    const subAthleteTemplate = document.getElementById('subAthleteFormTemplate');
    const alertContainer = document.getElementById('alert-container');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');

    let cardIdCounter = 0;

    const addRegistrationCard = () => {
        cardIdCounter++;
        const uniqueId = cardIdCounter;
        const clone = cardTemplate.content.cloneNode(true);
        const newCard = clone.querySelector('.athlete-card');
        newCard.dataset.cardId = uniqueId;
        newCard.querySelector('.athlete-title').textContent = `Pendaftaran Kelas #${athletesContainer.children.length + 1}`;
        buildRadioGroup(RENTANG_USIA_DATA, 'rentang_usia', 'rentang_usia', newCard.querySelector('.rentang-usia-options'), uniqueId);
        buildRadioGroup(KATEGORI_DATA, 'kategori', 'nama_kategori', newCard.querySelector('.kategori-options'), uniqueId);
        buildRadioGroup(JENIS_DATA, 'jenis', 'nama_jenis', newCard.querySelector('.jenis-options'), uniqueId);
        buildRadioGroup(GENDER_DATA, 'gender', 'name', newCard.querySelector('.gender-options'), uniqueId);
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
        const uniqueId = card.dataset.cardId;
        const selectedRentang = card.querySelector(`input[name="rentang_usia_${uniqueId}"]:checked`)?.value;
        const selectedKategori = card.querySelector(`input[name="kategori_${uniqueId}"]:checked`)?.value;
        const selectedJenis = card.querySelector(`input[name="jenis_${uniqueId}"]:checked`)?.value;
        const selectedGender = card.querySelector(`input[name="gender_${uniqueId}"]:checked`)?.value;
        const kelasSelect = card.querySelector('select[name="kelas_pertandingan_id"]');
        
        kelasSelect.innerHTML = '<option value="">Pilih...</option>';
        if (!selectedRentang || !selectedKategori || !selectedJenis || !selectedGender) {
            kelasSelect.firstElementChild.textContent = "Lengkapi semua filter di atas";
            return;
        }

        const filteredClasses = KELAS_PERTANDINGAN_DATA.filter(k => 
            k.rentang_usia_id == selectedRentang &&
            k.kategori_pertandingan_id == selectedKategori &&
            k.jenis_pertandingan_id == selectedJenis &&
            (k.gender === selectedGender || k.gender === 'Campuran')
        );

        if (filteredClasses.length > 0) {
            filteredClasses.forEach(k => {
                const option = document.createElement('option');
                option.value = k.kelas_pertandingan_id;
                option.textContent = `${k.nama_kelas} (${k.gender}) - Rp ${k.harga.toLocaleString('id-ID')}`;
                kelasSelect.appendChild(option);
            });
        } else {
            kelasSelect.firstElementChild.textContent = "Tidak ada kelas yang sesuai";
        }
    };

    const generateSubAthleteForms = (card, playerCount, gender) => {
        const subContainer = card.querySelector('.sub-athlete-container');
        subContainer.innerHTML = '';
        for (let i = 1; i <= playerCount; i++) {
            const clone = subAthleteTemplate.content.cloneNode(true);
            clone.querySelectorAll('.sub-athlete-form h6').forEach(h6 => h6.textContent = h6.textContent.replace('__SUB_COUNT__', i));
            clone.querySelectorAll('input, select').forEach(input => input.name = `${input.name}_${i-1}`);
            
            const genderSelect = clone.querySelector('select[name^="jenisKelamin"]');
            if(genderSelect) {
                genderSelect.value = gender;
                if (gender !== 'Campuran') {
                    genderSelect.disabled = true;
                } else {
                    genderSelect.disabled = false;
                }
            }
            subContainer.appendChild(clone);
        }
    };
    
    const updateTotalPrice = () => {
        let total = 0;
        document.querySelectorAll('.athlete-card').forEach(card => {
            const kelasId = card.querySelector('select[name="kelas_pertandingan_id"]').value;
            if (kelasId) {
                const selectedClass = KELAS_PERTANDINGAN_DATA.find(k => k.kelas_pertandingan_id == kelasId);
                if (selectedClass) { total += selectedClass.harga; }
            }
        });
        totalPriceDisplay.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    };

    athletesContainer.addEventListener('change', (e) => {
        const card = e.target.closest('.athlete-card');
        if (!card) return;
        if (e.target.type === 'radio') {
            updateAvailableClasses(card);
            card.querySelector('.sub-athlete-container').innerHTML = '';
            updateTotalPrice();
        }
        if (e.target.name === 'kelas_pertandingan_id') {
            const kelasId = e.target.value;
            if (kelasId) {
                const selectedClassData = KELAS_PERTANDINGAN_DATA.find(k => k.kelas_pertandingan_id == kelasId);
                const selectedGenderFilter = card.querySelector(`input[name^="gender_"]:checked`)?.value;
                if (selectedClassData && selectedGenderFilter) {
                    const genderForForm = selectedClassData.gender === 'Campuran' ? selectedGenderFilter : selectedClassData.gender;
                    generateSubAthleteForms(card, selectedClassData.jumlah_pemain || 1, genderForForm);
                }
            } else {
                card.querySelector('.sub-athlete-container').innerHTML = '';
            }
            updateTotalPrice();
        }
        if (e.target.type === 'file') {
            const infoDiv = e.target.closest('.upload-area').nextElementSibling;
            const file = e.target.files[0];
            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                infoDiv.innerHTML = `<div class="file-info"><i class="fas fa-check-circle text-success me-2"></i><strong>${file.name}</strong> (${fileSize} MB)</div>`;
            } else { infoDiv.innerHTML = ''; }
        }
    });

    athletesContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-card-btn')) {
            if (athletesContainer.children.length > 1) {
                e.target.closest('.athlete-card').remove();
                document.querySelectorAll('.athlete-title').forEach((title, index) => { title.textContent = `Pendaftaran Kelas #${index + 1}`; });
                updateTotalPrice();
            } else {
                showAlert('Minimal harus ada satu pendaftaran kelas.', 'warning');
            }
        }
    });
    
    registrationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Mengirim...`;
        const formData = new FormData();
        formData.append('contingent_id', CONTINGENT_ID);
        let hasError = false;
        
        document.querySelectorAll('.athlete-card').forEach((card, cardIndex) => {
            const kelasId = card.querySelector('select[name="kelas_pertandingan_id"]').value;
            if (!kelasId) {
                showAlert(`Pendaftaran #${cardIndex + 1} belum memilih Kelas Pertandingan.`, 'danger');
                hasError = true;
            }
            
            // Mengirim kelas_pertandingan_id di level registrasi
            formData.append(`registrations[${cardIndex}][kelas_pertandingan_id]`, kelasId);
            
            card.querySelectorAll('.sub-athlete-form').forEach((subForm, subIndex) => {
                const prefix = `registrations[${cardIndex}][players][${subIndex}]`;
                
                formData.append(`${prefix}[namaLengkap]`, subForm.querySelector(`input[name="namaLengkap_${subIndex}"]`).value);
                formData.append(`${prefix}[nik]`, subForm.querySelector(`input[name="nik_${subIndex}"]`).value);
                formData.append(`${prefix}[noTelepon]`, subForm.querySelector(`input[name="noTelepon_${subIndex}"]`).value);
                formData.append(`${prefix}[email]`, subForm.querySelector(`input[name="email_${subIndex}"]`).value);
                
                const genderSelect = subForm.querySelector(`select[name^="jenisKelamin"]`);
                genderSelect.disabled = false;
                formData.append(`${prefix}[jenisKelamin]`, genderSelect.value);

                formData.append(`${prefix}[tanggalLahir]`, subForm.querySelector(`input[name="tanggalLahir_${subIndex}"]`).value);
                formData.append(`${prefix}[uploadKTP]`, subForm.querySelector(`input[name="uploadKTP_${subIndex}"]`).files[0]);
                formData.append(`${prefix}[uploadFoto]`, subForm.querySelector(`input[name="uploadFoto_${subIndex}"]`).files[0]);
                formData.append(`${prefix}[uploadPersetujuan]`, subForm.querySelector(`input[name="uploadPersetujuan_${subIndex}"]`).files[0]);
            });
        });

        if (hasError) {
             submitBtn.disabled = false;
             submitBtn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Daftar & Bayar`;
             return;
        }

        fetch('/player_store', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status >= 400) {
                let errorMessages = 'Terjadi kesalahan:<br>';
                if(body.errors){ for (const key in body.errors) { errorMessages += `- ${body.errors[key][0]}<br>`; } } 
                else { errorMessages = body.message || 'Error tidak diketahui.'; }
                showAlert(errorMessages, 'danger');
            } else {
                showAlert('Pendaftaran berhasil! Anda akan dialihkan...', 'success');
                setTimeout(() => { window.location.href = `/invoice/${body.contingent}`; }, 2000);
            }
        })
        .catch(error => {
            showAlert('Gagal terhubung ke server. Silakan coba lagi.', 'danger');
            console.error('Error:', error);
        })
        .finally(() => {
             if (!window.location.href.includes('/invoice/')) {
                 submitBtn.disabled = false;
                 submitBtn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Daftar & Bayar`;
             }
        });
    });

    const showAlert = (message, type = 'danger') => {
        alertContainer.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        window.scrollTo(0, 0);
    };

    const addCardAndPriceUpdate = () => { addRegistrationCard(); updateTotalPrice(); };
    addAthleteBtn.addEventListener('click', addCardAndPriceUpdate);
    addRegistrationCard();
});
</script>