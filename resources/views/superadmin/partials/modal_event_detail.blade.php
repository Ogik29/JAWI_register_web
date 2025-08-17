<!-- Modal Detail Event -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <img id="detailImage" src="https://via.placeholder.com/400x250?text=Loading..." class="img-fluid rounded mb-3" alt="Poster Event">
                <p><strong><i class="bi bi-geo-alt-fill text-primary me-2"></i>Lokasi:</strong> <span id="detailLokasi"></span></p>
                <p><strong><i class="bi bi-calendar-range-fill text-primary me-2"></i>Tanggal Tanding:</strong> <span id="detailTanggalTanding"></span></p>
                <p><strong><i class="bi bi-calendar-x-fill text-primary me-2"></i>Batas Pendaftaran:</strong> <span id="detailBatasDaftar"></span></p>
                <hr>
                <p><strong><i class="bi bi-cash-stack text-primary me-2"></i>Harga Kontingen:</strong> <span id="detailHargaKontingen"></span></p>
                <p><strong><i class="bi bi-tags-fill text-primary me-2"></i>Harga Kelas:</strong> <span id="detailHargaKelas"></span></p> 
            </div>
            <div class="col-md-7">
                <h4 id="detailName" class="fw-bold"></h4>
                <div class="mb-3" id="detailDesc"></div>
                <h5 class="mt-4">Info Kontak</h5>
                <div id="detailCp" class="p-3 bg-light rounded"></div>
                
                <div id="juknis-section" class="mt-4">
                  <h5 class="mt-3">Juknis</h5>
                  <a href="#" id="detailJuknis" target="_blank" class="btn btn-primary btn-sm">
                      <i class="bi bi-download me-1"></i> Lihat/Unduh Juknis
                  </a>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>