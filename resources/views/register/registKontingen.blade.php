<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Event - {{ $event->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #fdfeffff 0%, #e9ecef 100%); }
        .poster-section { background: rgba(0,0,0,0.1); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .poster-card { background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: translateY(0); transition: all 0.3s ease; max-width: 400px; width: 100%; }
        .poster-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.15); }
        .event-icon { width: 80px; height: 80px; background: linear-gradient(235deg, #c50000ff 0%, #ffffffff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .form-section { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .form-card { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        .form-control { border-radius: 12px; border: 2px solid #e9ecef; padding: 0.75rem 1rem; transition: all 0.3s ease; }
        .form-control:focus { border-color: #c50000ff; box-shadow: 0 0 0 0.2rem rgba(197, 0, 0, 0.25); transform: translateY(-2px); }
        .btn-register { background: linear-gradient(135deg, #c50000ff, #c86868ff); border: none; border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600; transition: all 0.3s ease; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(197, 0, 0, 0.3); }
        .prize-badge { background: linear-gradient(235deg, #c50000ff, #c86868ff); color: white; border-radius: 15px; padding: 1rem; text-align: center; margin-top: 1.5rem; }
        .success-alert { border-radius: 12px; border: none; background: linear-gradient(135deg, #198754, #20c997); color: white; }
        @media (max-width: 991.98px) {
            .poster-section { min-height: 50vh; padding: 2rem 1rem; }
            .form-section { min-height: auto; padding: 2rem 1rem; }
        }
    </style>
</head>
<body>
     <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-lg-6 poster-section">
                <div class="poster-card">
                    <div class="event-icon">
                        <i class="bi bi-trophy text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="text-center mb-4">
                        <h1 class="fw-bold text-dark mb-2" style="font-size: 2.5rem;">{{ $event->name }}</h1>
                        <p class="text-muted fw-medium fs-5 m-0">{{ $event->kotaOrKabupaten }}</p>
                    </div>
                    <div class="text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-calendar3 text-danger me-2"></i>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($event->tgl_mulai_tanding)->format('d M') }} - {{ \Carbon\Carbon::parse($event->tgl_selesai_tanding)->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-geo-alt text-danger me-2"></i>
                            <span class="fw-medium">{{ $event->lokasi }}</span>
                        </div>
                        <div class="prize-badge">
                            <h3 class="fw-bold mb-0">JOIN NOW</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 form-section">
                <div class="form-card">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark mb-2">Pendaftaran Kontingen</h2>
                        <p class="text-muted">Lengkapi data kontingen Anda untuk mendaftar</p>
                    </div>
                    
                    <form id="registrationForm" method="POST" action="{{ url('/kontingen/' . $event->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="mb-3">
                            <label for="namaKontingen" class="form-label fw-semibold">
                                <i class="bi bi-people-fill text-danger me-1"></i> Nama Kontingen
                            </label>
                            <input type="text" class="form-control" id="namaKontingen" name="namaKontingen" placeholder="Masukkan nama kontingen" required>
                            <div id="namaKontingenError" class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-danger me-1"></i> Nama Manajer
                            </label>
                            <div class="d-flex align-items-center p-2 border rounded bg-light">
                                <span class="fw-bold">{{ Auth::user()->nama_lengkap }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone-fill text-danger me-1"></i> No. Telepon
                            </label>
                            <div class="d-flex align-items-center p-2 border rounded bg-light">
                                <span class="fw-bold">{{ Auth::user()->no_telp }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-danger me-1"></i> Email
                            </label>
                            <input type="email" value="{{ Auth::user()->email }}" class="form-control" id="email" name="email" placeholder="contoh@email.com" required readonly>
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        @if ($event->surat_rekom == 'wajib')
                            <div class="mb-4">
                                <label for="suratRekomendasi" class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-image-fill text-danger me-1"></i> Kirim Surat Rekomendasi
                                </label>
                                <input type="file" class="form-control" id="suratRekomendasi" name="surat_rekomendasi" required>
                                <div id="suratRekomendasiError" class="invalid-feedback"></div>
                            </div>  
                        @endif

                        {{-- @if($event->harga_contingent > 0)
                            <div class="mb-4">
                                <label for="fotoInvoice" class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-image-fill text-danger me-1"></i> Kirim Bukti Pembayaran
                                </label>
                                <input type="file" class="form-control" id="fotoInvoice" name="fotoInvoice" required>
                                <div id="fotoInvoiceError" class="invalid-feedback"></div>
                            </div>
                        @endif --}}
                        
                        <div class="d-grid">
                            <button type="submit" id="submitButton" class="btn btn-primary btn-register btn-lg">
                                <i class="bi bi-check-circle-fill me-2"></i> Daftar Sekarang
                            </button>
                        </div>
                    </form>
                    
                    <div id="successMessage" class="alert success-alert mt-4 d-none"></div>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            Butuh bantuan? Hubungi kami di 
                            <a href="#" class="text-danger fw-semibold text-decoration-none">
                                <p><i class="bi bi-whatsapp me-1"></i>{!! $event->cp !!}</p>
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const form = document.getElementById('registrationForm');
        const submitButton = document.getElementById('submitButton');
        const successMessage = document.getElementById('successMessage');
        const allInputs = form.querySelectorAll('.form-control');

        function clearErrors() {
            allInputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
            successMessage.classList.add('d-none');
        }

        function showErrors(errors) {
            for (const field in errors) {
                // Konversi dari snake_case (jika ada) ke camelCase
                const camelField = field.replace(/_([a-z])/g, g => g[1].toUpperCase());
                const input = document.getElementById(camelField);
                const errorDiv = document.getElementById(camelField + 'Error');

                if (input && errorDiv) {
                    input.classList.add('is-invalid');
                    errorDiv.textContent = errors[field][0];
                }
            }
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Memproses...
            `;

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                return response.json().then(data => ({ status: response.status, body: data }));
            })
            .then(({ status, body }) => {
                if (status === 422) {
                    showErrors(body.errors);
                } else if (status >= 200 && status < 300) {
                    successMessage.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span class="fw-medium">${body.message}</span>
                        </div>
                    `;
                    successMessage.classList.remove('d-none');
                    form.reset();
                    allInputs.forEach(input => input.classList.remove('is-valid', 'is-invalid'));
                    setTimeout(() => {
                        window.location.href = body.redirect_url; 
                    }, 2000);
                } else {
                    alert(`Terjadi kesalahan: ${body.message || 'Gagal terhubung ke server.'}`);
                }
            })
            .catch(error => {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan jaringan, silakan coba lagi.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Daftar Sekarang';
            });
        });
    </script>
</body>
</html>