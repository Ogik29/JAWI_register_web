<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus event <strong id="eventNameToDelete" class="text-danger"></strong>? </p>
        <p class="small text-muted">Tindakan ini tidak dapat dibatalkan dan semua data terkait (kelas pertandingan, peserta, dll.) akan ikut terhapus.</p>
      </div>
      <div class="modal-footer">
        {{-- Form ini action-nya akan diisi oleh JavaScript --}}
        <form id="deleteForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus Permanen</button>
        </form>
      </div>
    </div>
  </div>
</div>