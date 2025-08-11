<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta Kejuaraan Pencak Silat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: white;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1400px;
        }
        
        .header {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 30px;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
        }
        
        .stats-card.tanding {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        }
        
        .stats-card.seni {
            background: linear-gradient(135deg, #00b894, #00a085);
        }
        
        .stats-card.kontingen {
            background: linear-gradient(135deg, #fdcb6e, #e17055);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .filter-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 10px;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
        }
        
        .table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            border-color: #e9ecef;
            text-align: center;
        }
        
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .badge-tanding {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
        
        .badge-seni {
            background: linear-gradient(135deg, #00b894, #00a085);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
        
        .document-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .document-complete {
            color: #00b894;
        }
        
        .document-incomplete {
            color: #ff6b6b;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .page-link {
            color: #667eea;
            border-color: #667eea;
        }
        
        .page-link:hover {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
        }
        
        .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-select optgroup {
            font-weight: bold;
            color: #667eea;
            background-color: #f8f9fa;
        }
        
        .form-select optgroup option {
            font-weight: normal;
            color: #495057;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container shadow mb-5 bg-body rounded">
            <div class="header">
                <h1><i class="fas fa-users me-3"></i>Data Peserta Kejuaraan Pencak Silat</h1>
                <p class="mb-0">Sistem Manajemen Data Peserta Pencak Silat Indonesia</p>
            </div>
            
            <div class="p-4">
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number" id="totalPeserta">0</div>
                            <div class="stats-label">Total Peserta</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card tanding">
                            <div class="stats-number" id="totalTanding">0</div>
                            <div class="stats-label">Peserta Tanding</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card seni">
                            <div class="stats-number" id="totalSeni">0</div>
                            <div class="stats-label">Peserta Seni</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card kontingen">
                            <div class="stats-number" id="totalKontingen">0</div>
                            <div class="stats-label">Total Kontingen</div>
                        </div>
                    </div>
                </div>
                
                <!-- Filter Section -->
                <div class="filter-section">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filter & Pencarian Data</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Pencarian</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, kontingen, atau kontak...">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select class="form-select" id="filterKategori">
                                <option value="">Semua Kategori</option>
                                <option value="pra-usia-dini">Pra Usia Dini</option>
                                <option value="usia-dini-1">Usia Dini 1</option>
                                <option value="usia-dini-2">Usia Dini 2</option>
                                <option value="pra-remaja">Pra Remaja</option>
                                <option value="remaja">Remaja</option>
                                <option value="dewasa">Dewasa</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Pertandingan</label>
                            <select class="form-select" id="filterPertandingan">
                                <option value="">Semua Jenis</option>
                                <option value="tanding">Tanding</option>
                                <option value="seni">Seni</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Kelas</label>
                            <select class="form-select" id="filterKelas">
                                <option value="">Semua Kelas</option>
                                <optgroup label="Kelas Tanding">
                                    <option value="under">UNDER</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                    <option value="e">E</option>
                                    <option value="f">F</option>
                                    <option value="g">G</option>
                                    <option value="h">H</option>
                                    <option value="i">I</option>
                                    <option value="j">J</option>
                                    <option value="open">OPEN</option>
                                </optgroup>
                                <optgroup label="Kelas Seni">
                                    <option value="tunggal-tangan-kosong">Tunggal Tangan Kosong</option>
                                    <option value="tunggal-bersenjata">Tunggal Bersenjata</option>
                                    <option value="tunggal">Tunggal</option>
                                    <option value="ganda-tangan-kosong">Ganda Tangan Kosong</option>
                                    <option value="ganda-bersenjata">Ganda Bersenjata</option>
                                    <option value="ganda">Ganda</option>
                                    <option value="regu-1">Regu 1</option>
                                    <option value="regu-2">Regu 2</option>
                                    <option value="regu">Regu</option>
                                    <option value="solo-kreatif-1">Solo Kreatif 1</option>
                                    <option value="solo-kreatif">Solo Kreatif</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Aksi</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-custom" onclick="applyFilters()">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Data -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Nama</th>
                                    <th style="width: 12%;">Kontingen</th>
                                    <th style="width: 12%;">Kontak</th>
                                    <th style="width: 10%;">Kategori</th>
                                    <th style="width: 10%;">Pertandingan</th>
                                    <th style="width: 8%;">Kelas</th>
                                    <th style="width: 6%;">Usia</th>
                                    <th style="width: 8%;">Dokumen</th>
                                    <th style="width: 10%;">Tgl Daftar</th>
                                    <th style="width: 4%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="participantTableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-container">
                        <nav>
                            <ul class="pagination" id="pagination">
                                <!-- Pagination akan diisi oleh JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Peserta -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Detail Peserta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Detail akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data sample peserta (dalam implementasi nyata, ini akan diambil dari database)
        let participantsData = [
            {
                id: 1,
                nama: "Ahmad Rizki Pratama",
                kontingen: "Perguruan Silat Nusantara",
                email: "ahmad.rizki@email.com",
                telepon: "081234567890",
                kategori: "remaja",
                pertandingan: "tanding",
                kelas: "C",
                tanggalLahir: "2007-05-15",
                usia: 16,
                dokumenKTP: "ahmad_ktp.jpg",
                dokumenFoto: "ahmad_foto.jpg",
                tanggalDaftar: "2024-01-15",
                jenisKelamin: "laki-laki",
                nik: "3201234567890123"
            },
            {
                id: 2,
                nama: "Siti Nurhaliza",
                kontingen: "Padepokan Pencak Silat Betawi",
                email: "siti.nurhaliza@email.com",
                telepon: "081234567891",
                kategori: "dewasa",
                pertandingan: "seni",
                kelas: "tunggal-tangan-kosong",
                tanggalLahir: "2001-08-22",
                usia: 22,
                dokumenKTP: "siti_ktp.jpg",
                dokumenFoto: "siti_foto.jpg",
                tanggalDaftar: "2024-01-16",
                jenisKelamin: "perempuan",
                nik: "3201234567890124"
            },
            {
                id: 3,
                nama: "Budi Santoso",
                kontingen: "Perguruan Silat Nusantara",
                email: "budi.santoso@email.com",
                telepon: "081234567892",
                kategori: "pra-remaja",
                pertandingan: "tanding",
                kelas: "B",
                tanggalLahir: "2010-03-10",
                usia: 13,
                dokumenKTP: "budi_ktp.jpg",
                dokumenFoto: "",
                tanggalDaftar: "2024-01-17",
                jenisKelamin: "laki-laki",
                nik: "3201234567890125"
            },
            {
                id: 4,
                nama: "Dewi Sartika",
                kontingen: "Silat Tradisional Jakarta",
                email: "dewi.sartika@email.com",
                telepon: "081234567893",
                kategori: "usia-dini-2",
                pertandingan: "seni",
                kelas: "ganda-tangan-kosong",
                tanggalLahir: "2014-11-05",
                usia: 9,
                dokumenKTP: "dewi_ktp.jpg",
                dokumenFoto: "dewi_foto.jpg",
                tanggalDaftar: "2024-01-18",
                jenisKelamin: "perempuan",
                nik: "3201234567890126"
            },
            {
                id: 5,
                nama: "Reza Firmansyah",
                kontingen: "Perguruan Pencak Silat Merpati Putih",
                email: "reza.firmansyah@email.com",
                telepon: "081234567894",
                kategori: "dewasa",
                pertandingan: "tanding",
                kelas: "F",
                tanggalLahir: "1999-12-20",
                usia: 24,
                dokumenKTP: "reza_ktp.jpg",
                dokumenFoto: "reza_foto.jpg",
                tanggalDaftar: "2024-01-19",
                jenisKelamin: "laki-laki",
                nik: "3201234567890127"
            },
            {
                id: 6,
                nama: "Maya Sari",
                kontingen: "Padepokan Pencak Silat Betawi",
                email: "maya.sari@email.com",
                telepon: "081234567895",
                kategori: "remaja",
                pertandingan: "seni",
                kelas: "tunggal-bersenjata",
                tanggalLahir: "2006-07-30",
                usia: 17,
                dokumenKTP: "maya_ktp.jpg",
                dokumenFoto: "maya_foto.jpg",
                tanggalDaftar: "2024-01-20",
                jenisKelamin: "perempuan",
                nik: "3201234567890128"
            }
        ];
        
        let filteredData = [...participantsData];
        let currentPage = 1;
        const itemsPerPage = 10;
        
        const categoryNames = {
            'pra-usia-dini': 'Pra Usia Dini',
            'usia-dini-1': 'Usia Dini 1',
            'usia-dini-2': 'Usia Dini 2',
            'pra-remaja': 'Pra Remaja',
            'remaja': 'Remaja',
            'dewasa': 'Dewasa'
        };
        
        const allClasses = [
            'under', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'open',
            'tunggal-tangan-kosong', 'tunggal-bersenjata', 'tunggal',
            'ganda-tangan-kosong', 'ganda-bersenjata', 'ganda',
            'regu-1', 'regu-2', 'regu', 'solo-kreatif-1', 'solo-kreatif'
        ];
        
        function initializeApp() {
            populateClassFilter();
            updateStatistics();
            displayParticipants();
            setupEventListeners();
        }
        
        function populateClassFilter() {
            // Class options are now hardcoded in HTML with proper grouping
            // No need to populate dynamically
        }
        
        function updateStatistics() {
            const totalPeserta = participantsData.length;
            const totalTanding = participantsData.filter(p => p.pertandingan === 'tanding').length;
            const totalSeni = participantsData.filter(p => p.pertandingan === 'seni').length;
            const totalKontingen = new Set(participantsData.map(p => p.kontingen)).size;
            
            document.getElementById('totalPeserta').textContent = totalPeserta;
            document.getElementById('totalTanding').textContent = totalTanding;
            document.getElementById('totalSeni').textContent = totalSeni;
            document.getElementById('totalKontingen').textContent = totalKontingen;
        }
        
        function displayParticipants() {
            const tbody = document.getElementById('participantTableBody');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);
            
            if (pageData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="11" class="no-data">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <h5>Tidak ada data yang ditemukan</h5>
                            <p>Coba ubah filter pencarian Anda</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = pageData.map((participant, index) => {
                const globalIndex = startIndex + index + 1;
                const pertandinganBadge = participant.pertandingan === 'tanding' ? 
                    '<span class="badge-tanding">Tanding</span>' : 
                    '<span class="badge-seni">Seni</span>';
                
                const dokumenStatus = (participant.dokumenKTP && participant.dokumenFoto) ?
                    '<span class="document-status document-complete"><i class="fas fa-check-circle"></i> Lengkap</span>' :
                    '<span class="document-status document-incomplete"><i class="fas fa-exclamation-circle"></i> Belum Lengkap</span>';
                
                return `
                    <tr>
                        <td>${globalIndex}</td>
                        <td class="text-start">
                            <strong>${participant.nama}</strong><br>
                            <small class="text-muted">NIK: ${participant.nik}</small>
                        </td>
                        <td class="text-start">${participant.kontingen}</td>
                        <td class="text-start">
                            <small><i class="fas fa-envelope me-1"></i>${participant.email}</small><br>
                            <small><i class="fas fa-phone me-1"></i>${participant.telepon}</small>
                        </td>
                        <td>${categoryNames[participant.kategori]}</td>
                        <td>${pertandinganBadge}</td>
                        <td><strong>${participant.kelas.toUpperCase()}</strong></td>
                        <td>${participant.usia} tahun</td>
                        <td>${dokumenStatus}</td>
                        <td>${formatDate(participant.tanggalDaftar)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="showDetail(${participant.id})" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            updatePagination();
        }
        
        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const pagination = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }
            
            let paginationHTML = '';
            
            // Previous button
            paginationHTML += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    paginationHTML += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                        </li>
                    `;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            
            // Next button
            paginationHTML += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
            
            pagination.innerHTML = paginationHTML;
        }
        
        function changePage(page) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                displayParticipants();
            }
        }
        
        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filterKategori = document.getElementById('filterKategori').value;
            const filterPertandingan = document.getElementById('filterPertandingan').value;
            const filterKelas = document.getElementById('filterKelas').value;
            
            filteredData = participantsData.filter(participant => {
                const matchesSearch = !searchTerm || 
                    participant.nama.toLowerCase().includes(searchTerm) ||
                    participant.kontingen.toLowerCase().includes(searchTerm) ||
                    participant.email.toLowerCase().includes(searchTerm) ||
                    participant.telepon.includes(searchTerm);
                
                const matchesKategori = !filterKategori || participant.kategori === filterKategori;
                const matchesPertandingan = !filterPertandingan || participant.pertandingan === filterPertandingan;
                const matchesKelas = !filterKelas || participant.kelas === filterKelas;
                
                return matchesSearch && matchesKategori && matchesPertandingan && matchesKelas;
            });
            
            currentPage = 1;
            displayParticipants();
        }
        
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterKategori').value = '';
            document.getElementById('filterPertandingan').value = '';
            document.getElementById('filterKelas').value = '';
            
            filteredData = [...participantsData];
            currentPage = 1;
            displayParticipants();
        }
        
        function setupEventListeners() {
            // Real-time search
            document.getElementById('searchInput').addEventListener('input', applyFilters);
            
            // Filter change events
            document.getElementById('filterKategori').addEventListener('change', applyFilters);
            document.getElementById('filterPertandingan').addEventListener('change', applyFilters);
            document.getElementById('filterKelas').addEventListener('change', applyFilters);
        }
        
        function showDetail(participantId) {
            const participant = participantsData.find(p => p.id === participantId);
            if (!participant) return;
            
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Data Pribadi</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>Nama Lengkap:</strong></td><td>${participant.nama}</td></tr>
                            <tr><td><strong>NIK:</strong></td><td>${participant.nik}</td></tr>
                            <tr><td><strong>Jenis Kelamin:</strong></td><td>${participant.jenisKelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan'}</td></tr>
                            <tr><td><strong>Tanggal Lahir:</strong></td><td>${formatDate(participant.tanggalLahir)}</td></tr>
                            <tr><td><strong>Usia:</strong></td><td>${participant.usia} tahun</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3"><i class="fas fa-address-book me-2"></i>Kontak</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>Email:</strong></td><td>${participant.email}</td></tr>
                            <tr><td><strong>Telepon:</strong></td><td>${participant.telepon}</td></tr>
                            <tr><td><strong>Kontingen:</strong></td><td>${participant.kontingen}</td></tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3"><i class="fas fa-trophy me-2"></i>Pertandingan</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>Kategori:</strong></td><td>${categoryNames[participant.kategori]}</td></tr>
                            <tr><td><strong>Jenis Pertandingan:</strong></td><td>${participant.pertandingan === 'tanding' ? 'Tanding' : 'Seni'}</td></tr>
                            <tr><td><strong>Kelas:</strong></td><td>${participant.kelas.toUpperCase()}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Dokumen & Pendaftaran</h6>
                        <table class="table table-borderless">
                            <tr><td><strong>KTP/KK:</strong></td><td>${participant.dokumenKTP ? '<i class="fas fa-check text-success"></i> Tersedia' : '<i class="fas fa-times text-danger"></i> Belum Upload'}</td></tr>
                            <tr><td><strong>Foto:</strong></td><td>${participant.dokumenFoto ? '<i class="fas fa-check text-success"></i> Tersedia' : '<i class="fas fa-times text-danger"></i> Belum Upload'}</td></tr>
                            <tr><td><strong>Tanggal Daftar:</strong></td><td>${formatDate(participant.tanggalDaftar)}</td></tr>
                        </table>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }
        
        // Initialize app when page loads
        document.addEventListener('DOMContentLoaded', initializeApp);
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96b887dde15de780',t:'MTc1NDU4ODEyOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
