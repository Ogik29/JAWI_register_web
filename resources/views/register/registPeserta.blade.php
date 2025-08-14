<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kejuaraan Pencak Silat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        body {
            background: linear-gradient(135deg, #ffffffff 0%, #dfdfdfff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }
        
        .header {
            background: linear-gradient(135deg, #000000ff, #494949ff);
            color: white;
            padding: 30px;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }
        
        .athlete-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .athlete-card:hover {
            border-color: #c86868ff;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
        }
        
        .athlete-header {
            background: linear-gradient(135deg, #c50000ff, #c86868ff);
            color: white;
            padding: 15px 20px;
            border-radius: 13px 13px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #c50000ff, #c86868ff);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .btn-danger-custom {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
        }
        
        .btn-danger-custom:hover {
            background: linear-gradient(135deg, #ee5a24, #ff6b6b);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #c86868ff;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .age-warning {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            margin-top: 10px;
            font-size: 0.9em;
            font-weight: 500;
        }
        
        .class-restriction {
            background: linear-gradient(135deg, #fab1a0, #e17055);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            margin-top: 10px;
            font-size: 0.9em;
            font-weight: 500;
        }
        
        .upload-area {
            border: 2px dashed #c86868ff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: rgba(102, 126, 234, 0.05);
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .file-info {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header">
                <h1><i class="fas fa-fist-raised me-3"></i>Pendaftaran Kejuaraan Pencak Silat</h1>
                <p class="mb-0">Sistem Pendaftaran Atlet Pencak Silat Indonesia</p>
            </div>

            {{ $event->classCategories[0]->playerCategories }}

            <div class="p-4">
                <form id="registrationForm">
                    <!-- Informasi Kontingen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-danger mb-3"><i class="fas fa-users me-2"></i>Informasi Kontingen</h4>
                            <div class="mb-3">
                                <h1 class="text-center">Nama Kontingen</h1>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Container untuk Atlet -->
                    <div id="athletesContainer">
                        <!-- Atlet pertama akan ditambahkan di sini -->
                    </div>
                    
                    <!-- Tombol Tambah Atlet -->
                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-custom" onclick="addAthlete()">
                            <i class="fas fa-plus me-2"></i>Tambah Atlet
                        </button>
                    </div>
                    
                    <!-- Tombol Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-custom btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Daftar Kejuaraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let athleteCount = 0;
        let athleteIdCounter = 0; // Counter untuk ID unik
        
        // Definisi kategori dan aturan
        const categoryRules = {
            'pra-usia-dini': {
                name: 'Pra Usia Dini',
                ageRange: '3-5 tahun',
                allowedCompetitions: ['seni'],
                allowedClasses: ['tunggal-tangan-kosong', 'tunggal-bersenjata', 'ganda-tangan-kosong', 'ganda-bersenjata', 'regu-1', 'regu-2']
            },
            'usia-dini-1': {
                name: 'Usia Dini 1',
                ageRange: '5-8 tahun',
                allowedCompetitions: ['tanding', 'seni'],
                restrictedClasses: ['tunggal', 'ganda', 'regu', 'solo-kreatif-1', 'solo-kreatif'],
                allowedClasses: ['under', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'open', 'tunggal-tangan-kosong', 'tunggal-bersenjata', 'ganda-tangan-kosong', 'ganda-bersenjata', 'regu-1', 'regu-2']
            },
            'usia-dini-2': {
                name: 'Usia Dini 2',
                ageRange: '8-11 tahun',
                allowedCompetitions: ['tanding', 'seni'],
                restrictedClasses: ['tunggal', 'ganda', 'regu', 'solo-kreatif-1', 'solo-kreatif'],
                allowedClasses: ['under', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'open', 'tunggal-tangan-kosong', 'tunggal-bersenjata', 'ganda-tangan-kosong', 'ganda-bersenjata', 'regu-1', 'regu-2']
            },
            'pra-remaja': {
                name: 'Pra Remaja',
                ageRange: '11-14 tahun',
                allowedCompetitions: ['tanding', 'seni'],
                allowedClasses: 'all'
            },
            'remaja': {
                name: 'Remaja',
                ageRange: '14-17 tahun',
                allowedCompetitions: ['tanding', 'seni'],
                allowedClasses: 'all'
            },
            'dewasa': {
                name: 'Dewasa',
                ageRange: '17-25 tahun',
                allowedCompetitions: ['tanding', 'seni'],
                allowedClasses: 'all'
            }
        };
        
        const allClasses = [
            { value: 'under', text: 'UNDER' },
            { value: 'a', text: 'A' },
            { value: 'b', text: 'B' },
            { value: 'c', text: 'C' },
            { value: 'd', text: 'D' },
            { value: 'e', text: 'E' },
            { value: 'f', text: 'F' },
            { value: 'g', text: 'G' },
            { value: 'h', text: 'H' },
            { value: 'i', text: 'I' },
            { value: 'j', text: 'J' },
            { value: 'open', text: 'OPEN' },
            { value: 'tunggal-tangan-kosong', text: 'Tunggal Tangan Kosong' },
            { value: 'tunggal-bersenjata', text: 'Tunggal Bersenjata' },
            { value: 'tunggal', text: 'Tunggal' },
            { value: 'ganda-tangan-kosong', text: 'Ganda Tangan Kosong' },
            { value: 'ganda-bersenjata', text: 'Ganda Bersenjata' },
            { value: 'ganda', text: 'Ganda' },
            { value: 'regu-1', text: 'Regu 1' },
            { value: 'regu-2', text: 'Regu 2' },
            { value: 'regu', text: 'Regu' },
            { value: 'solo-kreatif-1', text: 'Solo Kreatif 1' },
            { value: 'solo-kreatif', text: 'Solo Kreatif' }
        ];
        

        // Data kategori + kelas dalam bentuk JSON dari Blade
    const categoriesData = @json($event->classCategories);


    const kelasOptionHtml = `
        <option value="">Pilih Kelas</option>
        @foreach ($event->classCategories as $kategori)
            @foreach ($kategori->playerCategories as $kelas)
                <option value="{{ $kelas->id }}">{{ $kelas->category }} {{ $kelas->range }} ({{ $kategori->name }})</option>
            @endforeach
        @endforeach
    `;


        function addAthlete() {
            athleteCount++;
            athleteIdCounter++;
            const uniqueId = athleteIdCounter;

            const athleteHtml = `
                <div class="athlete-card" id="athlete-${uniqueId}" data-athlete-id="${uniqueId}">
                    <div class="athlete-header">
                        <h5 class="mb-0 athlete-title"><i class="fas fa-user-ninja me-2"></i>Atlet ${athleteCount}</h5>
                        <button type="button" class="btn btn-danger-custom btn-sm" onclick="removeAthlete(${uniqueId})">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <!-- Data Pribadi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" name="namaLengkap_${uniqueId}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">NIK</label>
                                <input type="text" class="form-control" name="nik_${uniqueId}" pattern="[0-9]{16}" maxlength="16" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No Telepon</label>
                                <input type="tel" class="form-control" name="noTelepon_${uniqueId}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" name="email_${uniqueId}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <select class="form-select" name="jenisKelamin_${uniqueId}" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="laki-laki">Laki-laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggalLahir_${uniqueId}" onchange="calculateAge(${uniqueId})" required>
                            </div>
                            
                            <!-- Kategori dan Pertandingan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select class="form-select" name="kategori_${uniqueId}" onchange="updateCompetitionOptions(${uniqueId})" required>
                                    ${kelasOptionHtml}
                                </select>
                                <div id="ageWarning_${uniqueId}"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Pertandingan</label>
                                <select class="form-select" name="jenisPertandingan_${uniqueId}" onchange="updateClassOptions(${uniqueId})" required>
                                    <option value="">Pilih Jenis Pertandingan</option>
                                    <option value="tanding">Tanding</option>
                                    <option value="seni">Seni</option>
                                </select>
                            </div>
                            
                            <!-- Upload Files -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Upload KK/KTP</label>
                                <div class="upload-area">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-danger mb-2"></i>
                                    <p class="mb-2">Klik untuk upload KK/KTP</p>
                                    <input type="file" class="form-control" name="uploadKTP_${uniqueId}" accept=".jpg,.jpeg,.png,.pdf" onchange="showFileInfo(this, 'ktpInfo_${uniqueId}')" required>
                                </div>
                                <div id="ktpInfo_${uniqueId}"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Upload Foto Diri</label>
                                <div class="upload-area">
                                    <i class="fas fa-camera fa-2x text-danger mb-2"></i>
                                    <p class="mb-2">Klik untuk upload Foto</p>
                                    <input type="file" class="form-control" name="uploadFoto_${uniqueId}" accept=".jpg,.jpeg,.png" onchange="showFileInfo(this, 'fotoInfo_${uniqueId}')" required>
                                </div>
                                <div id="fotoInfo_${uniqueId}"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Upload Persetujuan Orang tua</label>
                                <div class="upload-area">
                                    <i class="fas fa-camera fa-2x text-danger mb-2"></i>
                                    <p class="mb-2">Klik untuk upload Foto</p>
                                    <input type="file" class="form-control" name="upload_Persetujuan${uniqueId}" accept=".jpg,.jpeg,.png" onchange="showFileInfo(this, 'fotoInfo_${uniqueId}')" required>
                                </div>
                                <div id="fotoInfo_${uniqueId}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('athletesContainer').insertAdjacentHTML('beforeend', athleteHtml);
        }


        function removeAthlete(athleteId) {
            if (athleteCount > 1) {
                document.getElementById(`athlete-${athleteId}`).remove();
                athleteCount--;
                updateAthleteNumbers();
            } else {
                alert('Minimal harus ada satu atlet yang didaftarkan!');
            }
        }
        
        function updateAthleteNumbers() {
            const athleteCards = document.querySelectorAll('.athlete-card');
            athleteCards.forEach((card, index) => {
                const titleElement = card.querySelector('.athlete-title');
                if (titleElement) {
                    titleElement.innerHTML = `<i class="fas fa-user-ninja me-2"></i>Atlet ${index + 1}`;
                }
            });
        }
        
        function calculateAge(athleteId) {
            const birthDate = document.querySelector(`input[name="tanggalLahir_${athleteId}"]`).value;
            const category = document.querySelector(`select[name="kategori_${athleteId}"]`).value;
            
            if (birthDate && category) {
                const today = new Date();
                const birth = new Date(birthDate);
                let age = today.getFullYear() - birth.getFullYear();
                const monthDiff = today.getMonth() - birth.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                    age--;
                }
                
                const warningDiv = document.getElementById(`ageWarning_${athleteId}`);
                const categoryRule = categoryRules[category];
                
                if (categoryRule) {
                    warningDiv.innerHTML = `
                        <div class="age-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            Umur Anda: ${age} tahun. Kategori ${categoryRule.name} disarankan untuk umur ${categoryRule.ageRange}
                        </div>
                    `;
                }
            }
        }
        
        function updateCompetitionOptions(athleteId) {
            const category = document.querySelector(`select[name="kategori_${athleteId}"]`).value;
            const competitionSelect = document.querySelector(`select[name="jenisPertandingan_${athleteId}"]`);
            const classSelect = document.querySelector(`select[name="kelas_${athleteId}"]`);
            
            // Reset selections
            competitionSelect.value = '';
            classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
            
            if (category && categoryRules[category]) {
                const rule = categoryRules[category];
                const allowedCompetitions = rule.allowedCompetitions;
                
                // Update competition options
                Array.from(competitionSelect.options).forEach(option => {
                    if (option.value === '') return;
                    option.style.display = allowedCompetitions.includes(option.value) ? 'block' : 'none';
                });
                
                // Show age warning
                calculateAge(athleteId);
            }
        }
        
        function updateClassOptions(athleteId) {
            const category = document.querySelector(`select[name="kategori_${athleteId}"]`).value;
            const competition = document.querySelector(`select[name="jenisPertandingan_${athleteId}"]`).value;
            const classSelect = document.querySelector(`select[name="kelas_${athleteId}"]`);
            const restrictionDiv = document.getElementById(`classRestriction_${athleteId}`);
            
            classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
            restrictionDiv.innerHTML = '';
            
            if (category && competition && categoryRules[category]) {
                const rule = categoryRules[category];
                let availableClasses = [];
                let restrictionMessage = '';
                
                if (rule.allowedClasses === 'all') {
                    availableClasses = allClasses;
                } else if (rule.allowedClasses) {
                    availableClasses = allClasses.filter(cls => rule.allowedClasses.includes(cls.value));
                } else if (rule.restrictedClasses && competition === 'seni') {
                    availableClasses = allClasses.filter(cls => !rule.restrictedClasses.includes(cls.value));
                    restrictionMessage = `Untuk kategori ${rule.name} pada kelas seni, tidak boleh memilih: ${rule.restrictedClasses.map(cls => allClasses.find(c => c.value === cls)?.text).join(', ')}`;
                } else {
                    availableClasses = allClasses;
                }
                
                // Add available classes to select
                availableClasses.forEach(cls => {
                    const option = document.createElement('option');
                    option.value = cls.value;
                    option.textContent = cls.text;
                    classSelect.appendChild(option);
                });
                
                // Show restriction message if any
                if (restrictionMessage) {
                    restrictionDiv.innerHTML = `
                        <div class="class-restriction">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${restrictionMessage}
                        </div>
                    `;
                }
            }
        }
        
        function showFileInfo(input, infoId) {
            const infoDiv = document.getElementById(infoId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                infoDiv.innerHTML = `
                    <div class="file-info">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>${file.name}</strong> (${fileSize} MB)
                    </div>
                `;
            }
        }
        
            const contingent_id = {{ $contingent->id }};

        // Form submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect all form data
            const formData = new FormData(this);
            const registrationData = {
                namaKontingen: formData.get('namaKontingen'),
                athletes: []
            };
            
            
            // Collect athlete data
            const athleteCards = document.querySelectorAll('.athlete-card');
            athleteCards.forEach((card) => {
                const athleteId = card.getAttribute('data-athlete-id');
                const athlete = {
                    contingent_id: contingent_id,
                    namaLengkap: formData.get(`namaLengkap_${athleteId}`),
                    nik: formData.get(`nik_${athleteId}`),
                    noTelepon: formData.get(`noTelepon_${athleteId}`),
                    email: formData.get(`email_${athleteId}`),
                    jenisKelamin: formData.get(`jenisKelamin_${athleteId}`),
                    tanggalLahir: formData.get(`tanggalLahir_${athleteId}`),
                    kategori: formData.get(`kategori_${athleteId}`),
                    jenisPertandingan_id: formData.get(`jenisPertandingan_${athleteId}`),
                    player_category_id: formData.get(`kelas_${athleteId}`),
                    uploadKTP: formData.get(`uploadKTP_${athleteId}`)?.name || '',
                    uploadFoto: formData.get(`uploadFoto_${athleteId}`)?.name || '',
                    uploadPersetujuan: formData.get(`upload_Persetujuan${athleteId}`)?.name || ''
                };
                registrationData.athletes.push(athlete);
            });


            fetch('/player_store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    athletes: registrationData.athletes
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal menyimpan data');
                }
                return response.json();
            })
            .then(data => {
                console.log('Sukses:', data);
                // Arahkan ke halaman lain kalau mau redirect
                // window.location.href = '/halaman-sukses';
            })
            .catch(error => {
                console.error('Error:', error);
            });
            
            // Show success message
            alert(`Pendaftaran berhasil!\n\nKontingen: ${contingent_id}\nJumlah Atlet: ${registrationData.athletes.length}\n\nData telah tersimpan dan siap untuk diproses.`);
            
            console.log('Data Pendaftaran:', registrationData);
        });
        
        // Initialize with first athlete
        addAthlete();
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96b85e5211d6c649',t:'MTc1NDU4NjQyNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
