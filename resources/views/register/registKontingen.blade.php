<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Event - Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fdfeffff 0%, #e9ecef 100%);
        }
        
        .poster-section {
            background: rgba(0,0,0,0.1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .poster-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transform: translateY(0);
            transition: all 0.3s ease;
            max-width: 400px;
            width: 100%;
        }
        
        .poster-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }
        
        .event-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(235deg,  #c50000ff 0%, #ffffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .form-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
            transform: translateY(-2px);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #c50000ff, #c86868ff);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(111, 66, 193, 0.3);
        }
        
        .prize-badge {
            background: linear-gradient(235deg, #c50000ff, #c86868ff);
            color: white;
            border-radius: 15px;
            padding: 1rem;
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .success-alert {
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #198754, #20c997);
            color: white;
        }
        
        @media (max-width: 991.98px) {
            .poster-section {
                min-height: 50vh;
                padding: 2rem 1rem;
            }
            
            .form-section {
                min-height: auto;
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
     <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Left Side - Event Poster -->
            <div class="col-lg-6 poster-section ">
                <div class="poster-card">
                    <!-- Event Icon -->
                    <div class="event-icon">
                        <i class="bi bi-trophy text-white" style="font-size: 2rem;"></i>
                    </div>
                    
                    <!-- Event Title -->
                    <div class="text-center mb-4">
                        <h1 class="fw-bold text-dark mb-2" style="font-size: 2.5rem;">KEJURCAB IX</h1>
                        <p class="text-muted fw-medium fs-5 m-0">PAGAR NUSA SIDOARJO 2025</p>
                        <p class="text-muted fw-medium fs-5 m-0">ROAD TO KEJURWIL JATIM</p>
                    </div>
                    
                    <!-- Event Details -->
                    <div class="text-center">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-calendar3 text-danger me-2"></i>
                            <span class="fw-medium">7 Agustus 2025 - 23 September 2025</span>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-geo-alt text-danger me-2"></i>
                            <span class="fw-medium">Gedung Olahraga Sidoarjo</span>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-people text-danger me-2"></i>
                            <span class="fw-medium">100+ Peserta</span>
                        </div>
                        
                        <!-- Prize Badge -->
                        <div class="prize-badge">
                            <!-- <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="bi bi-trophy-fill me-2"></i>
                                <span class="fw-medium">Total Hadiah</span>
                            </div> -->
                            <h3 class="fw-bold mb-0">JOIN NOW</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Registration Form -->
            <div class="col-lg-6 form-section">
                <div class="form-card">
                    <!-- Form Header -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark mb-2">Pendaftaran Kontingen</h2>
                        <p class="text-muted">Lengkapi data kontingen Anda untuk mendaftar</p>
                    </div>
                    
                    <!-- Registration Form -->
                    <form id="registrationForm" method="POST">
                        @csrf
                        <!-- Nama Kontingen -->
                        <input type="hidden" name="user_id" value="1">
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div class="mb-3">
                            <label for="namaKontingen" class="form-label fw-semibold">
                                <i class="bi bi-people-fill text-danger me-1"></i>
                                Nama Kontingen
                            </label>
                            <input type="text" class="form-control" id="namaKontingen" name="namaKontingen" 
                                   placeholder="Masukkan nama kontingen" required>
                        </div>
                        
                        <!-- Nama Manajer -->
                        <div class="mb-3">
                            <label for="namaManajer" class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-danger me-1"></i>
                                Nama Manajer
                            </label>
                            <input type="text" class="form-control" id="namaManajer" name="namaManajer" 
                                   placeholder="Masukkan nama manajer" required>
                        </div>
                        
                        <!-- No. Telepon -->
                        <div class="mb-3">
                            <label for="noTelepon" class="form-label fw-semibold">
                                <i class="bi bi-telephone-fill text-danger me-1"></i>
                                No. Telepon
                            </label>
                            <input type="tel" class="form-control" id="noTelepon" name="noTelepon" 
                                   placeholder="Contoh: 08123456789" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill text-danger me-1"></i>
                                Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="contoh@email.com" required>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-register btn-lg">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>
                    
                    <!-- Success Message -->
                    <div id="successMessage" class="alert success-alert mt-4 d-none">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span class="fw-medium">Pendaftaran berhasil! Kami akan menghubungi Anda segera.</span>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            Butuh bantuan? Hubungi kami di 
                            <a href="tel:0896-8807-9877" class="text-danger fw-semibold text-decoration-none">
                                <p><i class="bi bi-whatsapp me-1"></i>0896-8807-9877 a.n. Tasya Shofwa Mahira</p>
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form submission handler
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const data = {
                namaKontingen: formData.get('namaKontingen'),
                namaManajer: formData.get('namaManajer'),
                noTelepon: formData.get('noTelepon'),
                email: formData.get('email'),
                user_id: formData.get('user_id'),
                event_id: formData.get('event_id')
            };

            // Kirim data ke endpoint /kontingen/event_id via POST
            fetch(`/kontingen/${data.event_id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                console.log('Pendaftaran berhasil:', result);
            })
            .catch(error => {
                console.error('Terjadi kesalahan:', error);
            });
            
            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.remove('d-none');
            
            // Add animation
            successMessage.style.opacity = '0';
            successMessage.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                successMessage.style.transition = 'all 0.3s ease';
                successMessage.style.opacity = '1';
                successMessage.style.transform = 'translateY(0)';
            }, 100);
            
            // Reset form
            this.reset();
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.classList.add('d-none');
                }, 300);
            }, 5000);
        });

        // Phone number formatting
        document.getElementById('noTelepon').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Format Indonesian phone number
            if (value.length > 0) {
                if (value.startsWith('08')) {
                    // Format: 0812-3456-7890
                    value = value.replace(/(\d{4})(\d{4})(\d{0,4})/, function(match, p1, p2, p3) {
                        if (p3) return p1 + '-' + p2 + '-' + p3;
                        if (p2) return p1 + '-' + p2;
                        return p1;
                    });
                }
            }
            
            e.target.value = value;
        });

        // Email validation with visual feedback
        document.getElementById('email').addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                e.target.classList.add('is-invalid');
                e.target.classList.remove('is-valid');
            } else if (email) {
                e.target.classList.add('is-valid');
                e.target.classList.remove('is-invalid');
            } else {
                e.target.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Add validation feedback for all required fields
        const requiredFields = ['namaKontingen', 'namaManajer', 'noTelepon'];
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            
            field.addEventListener('blur', function(e) {
                if (e.target.value.trim()) {
                    e.target.classList.add('is-valid');
                    e.target.classList.remove('is-invalid');
                } else {
                    e.target.classList.add('is-invalid');
                    e.target.classList.remove('is-valid');
                }
            });
        });

        // Add smooth scrolling for mobile
        if (window.innerWidth <= 991) {
            document.querySelector('.form-section').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96b7f59ff18b9b07',t:'MTc1NDU4MjEzOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
