<form action="/player_store" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="athlete-card">
        <div class="athlete-header">
            <h5 class="mb-0 athlete-title">
                <i class="fas fa-user-ninja me-2"></i>Data Atlet
            </h5>
        </div>
        <div class="p-4">
            <div class="row">
                <!-- Data Pribadi -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="namaLengkap" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NIK</label>
                    <input type="text" class="form-control" name="nik" pattern="[0-9]{16}" maxlength="16" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">No Telepon</label>
                    <input type="tel" class="form-control" name="noTelepon" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jenis Kelamin</label>
                    <select class="form-select" name="jenisKelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggalLahir" required>
                </div>
                
                <!-- Kategori -->
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select class="form-select" name="kategori" required>
                        <option value="1">adasd</option>
                    </select>
                    <div id="ageWarning"></div>
                </div>
                
                <!-- Upload Files -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Upload KK/KTP</label>
                    <div class="upload-area">
                        <i class="fas fa-cloud-upload-alt fa-2x text-danger mb-2"></i>
                        <p class="mb-2">Klik untuk upload KK/KTP</p>
                        <input type="file" class="form-control" name="uploadKTP" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Upload Foto Diri</label>
                    <div class="upload-area">
                        <i class="fas fa-camera fa-2x text-danger mb-2"></i>
                        <p class="mb-2">Klik untuk upload Foto</p>
                        <input type="file" class="form-control" name="uploadFoto" accept=".jpg,.jpeg,.png" required>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Upload Persetujuan Orang Tua</label>
                    <div class="upload-area">
                        <i class="fas fa-camera fa-2x text-danger mb-2"></i>
                        <p class="mb-2">Klik untuk upload Foto</p>
                        <input type="file" class="form-control" name="uploadPersetujuan" accept=".jpg,.jpeg,.png" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
