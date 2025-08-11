<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - TechFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color:rgb(0, 0, 0);
        }
        .bg-color{
           background: linear-gradient(90deg,rgb(67, 66, 66),rgb(213, 213, 213),rgb(255, 255, 255));
        }
        .bg-gradient {
            background: linear-gradient(135deg,rgb(237, 60, 60) 0%,rgb(125, 125, 125) 100%);
        }
        
        .logo-animation {
            animation: float 12s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .particle {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-100 relative overflow-hidden">
        <!-- Background Decorative Elements -->
        <div class="absolute inset-0">
            <div class="particle w-2 h-2 bg-blue-300 opacity-20" style="left: 10%; top: 15%;"></div>
            <div class="particle w-3 h-3 bg-purple-300 opacity-20" style="left: 85%; top: 25%;"></div>
            <div class="particle w-1 h-1 bg-indigo-300 opacity-30" style="left: 20%; top: 75%;"></div>
            <div class="particle w-2 h-2 bg-blue-300 opacity-20" style="left: 90%; top: 80%;"></div>
            <div class="particle w-1 h-1 bg-purple-300 opacity-30" style="left: 5%; top: 60%;"></div>
        </div>
        
        <!-- Main Content Container -->
        <div class="relative z-10 flex items-center justify-center min-h-screen p-8 bg-color">
            <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Form Section -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-white/20">
                        <div class="text-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun Baru</h1>
                            <!-- <p class="text-gray-600">Bergabunglah dengan TechFlow dan mulai perjalanan digital Anda</p> -->
                        </div>
                        
                        <form class="space-y-4" onsubmit="handleSubmit(event)">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70" placeholder="Masukkan nama lengkap">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70" placeholder="nama@email.com">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                                    <input type="password" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70" placeholder="Minimal 8 karakter">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi</label>
                                    <input type="password" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70" placeholder="Ulangi kata sandi">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none bg-white/70" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                    <select required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="laki-laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                    <input type="text" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70" placeholder="Kota kelahiran">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                                    <select required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70">
                                        <option value="">Pilih negara</option>
                                        <option value="indonesia">Indonesia</option>
                                        <option value="malaysia">Malaysia</option>
                                        <option value="singapura">Singapura</option>
                                        <option value="thailand">Thailand</option>
                                        <option value="filipina">Filipina</option>
                                        <option value="vietnam">Vietnam</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="tel" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/70" placeholder="+62 812-3456-7890">
                            </div>
                            
                            <!-- <div class="flex items-center">
                                <input type="checkbox" required class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-700">
                                    Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-500">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div> -->
                            
                            <button type="submit" class="submit-btn w-full bg-gradient text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform">
                                Daftar Sekarang
                            </button>
                        </form>
                        
                        <!-- <div class="mt-6 text-center">
                            <p class="text-gray-600">Sudah punya akun? <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">Masuk di sini</a></p>
                        </div> -->
                    </div>
                </div>
                
                <!-- Logo Section -->
                <div class="order-1 lg:order-2 flex items-center justify-center">
                    <div class="text-center logo-animation">
                        <!-- Creative Logo Design -->
                        <div class="mb-8">
                            <!-- <svg width="280" height="280" viewBox="0 0 200 200" class="mx-auto drop-shadow-2xl">
                                
                                <circle cx="100" cy="100" r="90" fill="none" stroke="rgba(102, 126, 234, 0.3)" stroke-width="2"/>
                                
                               
                                <g transform="translate(100,100)">
                                    
                                    <polygon points="-30,0 -15,-26 15,-26 30,0 15,26 -15,26" fill="rgba(102, 126, 234, 0.9)" transform="rotate(0)"/>
                                    
                                   
                                    <g class="animate-spin" style="animation-duration: 40s;">
                                        <circle cx="0" cy="-50" r="8" fill="rgba(118, 75, 162, 0.8)"/>
                                        <circle cx="43" cy="-25" r="6" fill="rgba(102, 126, 234, 0.8)"/>
                                        <circle cx="43" cy="25" r="8" fill="rgba(118, 75, 162, 0.8)"/>
                                        <circle cx="0" cy="50" r="6" fill="rgba(102, 126, 234, 0.8)"/>
                                        <circle cx="-43" cy="25" r="8" fill="rgba(118, 75, 162, 0.8)"/>
                                        <circle cx="-43" cy="-25" r="6" fill="rgba(102, 126, 234, 0.8)"/>
                                    </g>
                                    
                                    
                                    <g opacity="0.6">
                                        <path d="M-60,-60 L-20,-20 M60,-60 L20,-20 M60,60 L20,20 M-60,60 L-20,20" stroke="rgba(102, 126, 234, 0.7)" stroke-width="2" fill="none"/>
                                    </g>
                                    
                                   
                                    <text x="-8" y="8" font-family="Inter" font-size="24" font-weight="bold" fill="white">T</text>
                                    <text x="2" y="8" font-family="Inter" font-size="24" font-weight="bold" fill="white">F</text>
                                </g>
                                
                                
                                <g opacity="0.4">
                                    <rect x="20" y="20" width="4" height="4" fill="#667eea" transform="rotate(45 22 22)"/>
                                    <rect x="176" y="20" width="4" height="4" fill="#764ba2" transform="rotate(45 178 22)"/>
                                    <rect x="176" y="176" width="4" height="4" fill="#667eea" transform="rotate(45 178 178)"/>
                                    <rect x="20" y="176" width="4" height="4" fill="#764ba2" transform="rotate(45 22 178)"/>
                                </g>
                            </svg> -->
                            <div class="mb-4">
                                <img src="{{ asset('assets/img/icon/logo-jawi2.png') }}" alt="TechFlow Logo" style="280px">
                            </div>

                        <!-- <h2 class="text-5xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">TechFlow</h2> -->
                        <p class="text-xl mb-6 text-gray-700">Innovate • Connect • Transform</p>
                        <div class="space-y-3 text-lg text-gray-600">
                            <p>🚀 Platform Digital Terdepan</p>
                            <!-- <p>🌐 Solusi Teknologi Terintegrasi</p> -->
                            <p>💡 Inovasi Tanpa Batas</p>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <!-- <div class="mt-12 flex justify-center space-x-4">
                            <div class="w-3 h-3 bg-blue-500 rounded-full opacity-60"></div>
                            <div class="w-3 h-3 bg-purple-500 rounded-full opacity-40"></div>
                            <div class="w-3 h-3 bg-indigo-500 rounded-full opacity-60"></div>
                        </div> -->
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <script>
        function handleSubmit(event) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            // Simple validation
            const password = event.target.querySelector('input[type="password"]').value;
            const confirmPassword = event.target.querySelectorAll('input[type="password"]')[1].value;
            
            if (password !== confirmPassword) {
                alert('Kata sandi dan konfirmasi sandi tidak cocok!');
                return;
            }
            
            if (password.length < 8) {
                alert('Kata sandi harus minimal 8 karakter!');
                return;
            }
            
            // Show success message
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Mendaftar...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.textContent = '✓ Berhasil Terdaftar!';
                submitBtn.classList.add('bg-green-600');
                
                setTimeout(() => {
                    alert('Selamat! Akun Anda berhasil dibuat. Silakan cek email untuk verifikasi.');
                    event.target.reset();
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('bg-green-600');
                }, 2000);
            }, 1500);
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96a7f24725ed3e20',t:'MTc1NDQxNDIzMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
