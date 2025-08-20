<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta Kejuaraan Pencak Silat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: white; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-container { background: rgba(255, 255, 255, 0.95); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); margin: 20px auto; max-width: 1400px; }
        .header { background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: white; padding: 30px; border-radius: 20px 20px 0 0; text-align: center; }
        .stats-card { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px; padding: 20px; text-align: center; margin-bottom: 20px; transition: all 0.3s ease; }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3); }
        .stats-card.tanding { background: linear-gradient(135deg, #ff6b6b, #ee5a24); }
        .stats-card.seni { background: linear-gradient(135deg, #00b894, #00a085); }
        .stats-card.jurus { background: linear-gradient(135deg, #54a0ff, #2e86de); }
        .stats-card.kontingen { background: linear-gradient(135deg, #fdcb6e, #e17055); }
        .stats-number { font-size: 2.5rem; font-weight: bold; margin-bottom: 5px; }
        .stats-label { font-size: 1rem; opacity: 0.9; }
        .filter-section { background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 30px; border: 2px solid #e9ecef; }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 10px 15px; transition: all 0.3s ease; }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .btn-custom { background: linear-gradient(135deg, #667eea, #764ba2); border: none; border-radius: 25px; padding: 10px 25px; color: white; font-weight: 600; transition: all 0.3s ease; }
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3); color: white; }
        .table-container { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
        .table { margin-bottom: 0; }
        .table thead th { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 15px 10px; font-weight: 600; text-align: center; vertical-align: middle; }
        .table tbody td { padding: 12px 10px; vertical-align: middle; border-color: #e9ecef; text-align: center; }
        .table tbody tr:hover { background-color: rgba(102, 126, 234, 0.05); }
        .badge-custom { color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8em; }
        .badge-tanding { background: linear-gradient(135deg, #ff6b6b, #ee5a24); }
        .badge-seni { background: linear-gradient(135deg, #00b894, #00a085); }
        .badge-jurus { background: linear-gradient(135deg, #54a0ff, #2e86de); }
        .document-status { display: inline-flex; align-items: center; gap: 5px; }
        .document-complete { color: #00b894; }
        .document-incomplete { color: #ff6b6b; }
        .pagination-container { display: flex; justify-content: center; margin-top: 20px; }
        .page-link { color: #667eea; border-color: #667eea; }
        .page-link:hover, .page-item.active .page-link { background-color: #667eea; border-color: #667eea; color: white; }
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
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3 mb-4">
                    <div class="col"><div class="stats-card total"><div class="stats-number" id="totalPeserta">0</div><div class="stats-label">Total Peserta</div></div></div>
                    <div class="col"><div class="stats-card tanding"><div class="stats-number" id="totalTanding">0</div><div class="stats-label">Peserta Tanding</div></div></div>
                    <div class="col"><div class="stats-card seni"><div class="stats-number" id="totalSeni">0</div><div class="stats-label">Peserta Seni</div></div></div>
                    <div class="col"><div class="stats-card jurus"><div class="stats-number" id="totalJurus">0</div><div class="stats-label">Peserta Jurus Baku</div></div></div>
                    <div class="col"><div class="stats-card kontingen"><div class="stats-number" id="totalKontingen">{{ $totalContingents }}</div><div class="stats-label">Total Kontingen</div></div></div>
                </div>
                
                <div class="filter-section">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filter & Pencarian Data</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3"><label class="form-label fw-bold">Pencarian</label><input type="text" class="form-control" id="searchInput" placeholder="Cari nama, kontingen, atau kontak..."></div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Kontingen</label>
                            <select class="form-select" id="filterKontingen"><option value="">Semua Kontingen</option>@foreach($contingents as $contingent)<option value="{{ $contingent->name }}">{{ $contingent->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Jenis</label>
                            <select class="form-select" id="filterJenis"><option value="">Semua Jenis</option>@foreach($jenisPertandingan as $jenis)<option value="{{ $jenis->nama_jenis }}">{{ $jenis->nama_jenis }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Kelas</label>
                            <select class="form-select" id="filterKelas"><option value="">Semua Kelas</option>@foreach($kelasPertandingan as $kelas)<option value="{{ $kelas->nama_kelas }}">{{ $kelas->nama_kelas }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-2 mb-3"><label class="form-label fw-bold">Aksi</label><div class="d-flex gap-2"><button type="button" class="btn btn-custom" onclick="applyFilters()"><i class="fas fa-search me-1"></i>Filter</button><button type="button" class="btn btn-outline-secondary" onclick="resetFilters()"><i class="fas fa-undo me-1"></i>Reset</button></div></div>
                    </div>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive"><table class="table"><thead><tr><th style="width: 5%;">No</th><th style="width: 15%;">Nama</th><th style="width: 15%;">Kontingen</th><th style="width: 10%;">Kontak</th><th style="width: 10%;">Kategori</th><th style="width: 10%;">Jenis</th><th style="width: 10%;">Kelas</th><th style="width: 5%;">Usia</th><th style="width: 10%;">Tgl Daftar</th><th style="width: 10%;">Aksi</th></tr></thead><tbody id="participantTableBody"></tbody></table></div>
                    <div class="pagination-container"><nav><ul class="pagination" id="pagination"></ul></nav></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;"><h5 class="modal-title"><i class="fas fa-user me-2"></i>Detail Peserta</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body" id="modalBody"></div></div></div></div>

    @php
        $transformedPlayers = $players->map(function ($player) {
            $usia = $player->tgl_lahir ? \Carbon\Carbon::parse($player->tgl_lahir)->age : 'N/A';
            return [
                'id' => $player->id,
                'nama' => $player->name,
                'kontingen' => $player->contingent->name ?? 'N/A',
                'email' => $player->email ?? 'N/A',
                'telepon' => $player->no_telp ?? 'N/A',
                'kategori' => $player->kelasPertandingan->kategoriPertandingan->nama_kategori ?? 'N/A',
                'jenis' => $player->kelasPertandingan->jenisPertandingan->nama_jenis ?? 'N/A',
                'kelas' => $player->kelasPertandingan->nama_kelas ?? 'N/A',
                'tanggalLahir' => $player->tgl_lahir,
                'usia' => $usia,
                'dokumenKTP' => $player->foto_ktp,
                'dokumenFoto' => $player->foto_diri,
                'tanggalDaftar' => $player->created_at->toIso8601String(),
                'jenisKelamin' => $player->gender,
                'nik' => $player->nik,
            ];
        });
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const participantsData = @json($transformedPlayers);
        let filteredData = [...participantsData];
        let currentPage = 1;
        const itemsPerPage = 10;
        
        function initializeApp() {
            updateStatistics();
            displayParticipants();
            setupEventListeners();
        }
        
        function updateStatistics() {
            const totalPeserta = participantsData.length;
            const totalTanding = participantsData.filter(p => p.jenis.toLowerCase() === 'tanding').length;
            const totalSeni = participantsData.filter(p => p.jenis.toLowerCase() === 'seni').length;
            const totalJurus = participantsData.filter(p => p.jenis.toLowerCase() === 'jurus baku').length;
            // const totalKontingen = new Set(participantsData.map(p => p.kontingen)).size;
            
            document.getElementById('totalPeserta').textContent = totalPeserta;
            document.getElementById('totalTanding').textContent = totalTanding;
            document.getElementById('totalSeni').textContent = totalSeni;
            document.getElementById('totalJurus').textContent = totalJurus;
            // document.getElementById('totalKontingen').textContent = totalKontingen;
        }
        
        function displayParticipants() {
            const tbody = document.getElementById('participantTableBody');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);
            
            if (pageData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="10" class="text-center p-5 text-muted"><h5><i class="fas fa-search fa-2x mb-3"></i><br>Tidak ada data ditemukan</h5></td></tr>`;
                return;
            }
            
            tbody.innerHTML = pageData.map((p, index) => {
                let jenisBadge = '';
                if(p.jenis.toLowerCase() === 'tanding') jenisBadge = `<span class="badge-custom badge-tanding">Tanding</span>`;
                else if(p.jenis.toLowerCase() === 'seni') jenisBadge = `<span class="badge-custom badge-seni">Seni</span>`;

                return `
                    <tr>
                        <td>${startIndex + index + 1}</td>
                        <td class="text-start"><strong>${p.nama}</strong><br><small class="text-muted">NIK: ${p.nik}</small></td>
                        <td class="text-start">${p.kontingen}</td>
                        <td class="text-start"><small><i class="fas fa-envelope me-1"></i>${p.email}</small><br><small><i class="fas fa-phone me-1"></i>${p.telepon}</small></td>
                        <td>${p.kategori}</td>
                        <td>${jenisBadge}</td>
                        <td><strong>${p.kelas.toUpperCase()}</strong></td>
                        <td>${p.usia}</td>
                        <td>${formatDate(p.tanggalDaftar)}</td>
                        <td><button class="btn btn-sm btn-outline-primary" onclick="showDetail(${p.id})" title="Lihat Detail"><i class="fas fa-eye"></i></button></td>
                    </tr>
                `;
            }).join('');
            
            updatePagination();
        }
        
        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const pagination = document.getElementById('pagination');
            if (totalPages <= 1) { pagination.innerHTML = ''; return; }
            let paginationHTML = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="changePage(${currentPage - 1})"><i class="fas fa-chevron-left"></i></a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            paginationHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" onclick="changePage(${currentPage + 1})"><i class="fas fa-chevron-right"></i></a></li>`;
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
            const filterKontingen = document.getElementById('filterKontingen').value;
            const filterJenis = document.getElementById('filterJenis').value;
            const filterKelas = document.getElementById('filterKelas').value;
            
            filteredData = participantsData.filter(p => 
                (!searchTerm || p.nama.toLowerCase().includes(searchTerm) || p.kontingen.toLowerCase().includes(searchTerm) || p.telepon.includes(searchTerm)) &&
                (!filterKontingen || p.kontingen === filterKontingen) &&
                (!filterJenis || p.jenis === filterJenis) &&
                (!filterKelas || p.kelas === filterKelas)
            );
            
            currentPage = 1;
            displayParticipants();
        }
        
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterKontingen').value = '';
            document.getElementById('filterJenis').value = '';
            document.getElementById('filterKelas').value = '';
            filteredData = [...participantsData];
            currentPage = 1;
            displayParticipants();
        }
        
        function setupEventListeners() {
            document.getElementById('searchInput').addEventListener('input', applyFilters);
            document.getElementById('filterKontingen').addEventListener('change', applyFilters);
            document.getElementById('filterJenis').addEventListener('change', applyFilters);
            document.getElementById('filterKelas').addEventListener('change', applyFilters);
        }
        
        function showDetail(participantId) {
            const p = participantsData.find(p => p.id === participantId);
            if (!p) return;
            document.getElementById('modalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-6"><h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Data Pribadi</h6><table class="table table-borderless"><tr><td><strong>Nama:</strong></td><td>${p.nama}</td></tr><tr><td><strong>NIK:</strong></td><td>${p.nik}</td></tr><tr><td><strong>Gender:</strong></td><td>${p.jenisKelamin}</td></tr><tr><td><strong>Tgl Lahir:</strong></td><td>${formatDate(p.tanggalLahir)} (${p.usia} thn)</td></tr></table></div>
                    <div class="col-md-6"><h6 class="text-primary mb-3"><i class="fas fa-address-book me-2"></i>Kontak & Kontingen</h6><table class="table table-borderless"><tr><td><strong>Email:</strong></td><td>${p.email}</td></tr><tr><td><strong>Telepon:</strong></td><td>${p.telepon}</td></tr><tr><td><strong>Kontingen:</strong></td><td>${p.kontingen}</td></tr></table></div>
                </div><hr>
                <div class="row">
                    <div class="col-md-6"><h6 class="text-primary mb-3"><i class="fas fa-trophy me-2"></i>Pertandingan</h6><table class="table table-borderless"><tr><td><strong>Kategori:</strong></td><td>${p.kategori}</td></tr><tr><td><strong>Jenis:</strong></td><td>${p.jenis}</td></tr><tr><td><strong>Kelas:</strong></td><td>${p.kelas.toUpperCase()}</td></tr></table></div>
                    <div class="col-md-6"><h6 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Dokumen</h6><table class="table table-borderless"><tr><td><strong>KTP/KK:</strong></td><td>${p.dokumenKTP ? '<i class="fas fa-check text-success"></i> Tersedia' : '<i class="fas fa-times text-danger"></i> Belum'}</td></tr><tr><td><strong>Foto Diri:</strong></td><td>${p.dokumenFoto ? '<i class="fas fa-check text-success"></i> Tersedia' : '<i class="fas fa-times text-danger"></i> Belum'}</td></tr><tr><td><strong>Tgl Daftar:</strong></td><td>${formatDate(p.tanggalDaftar)}</td></tr></table></div>
                </div>`;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        }
        
        function formatDate(dateString) { return new Date(dateString).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }); }
        
        document.addEventListener('DOMContentLoaded', initializeApp);
    </script>
</body>
</html>