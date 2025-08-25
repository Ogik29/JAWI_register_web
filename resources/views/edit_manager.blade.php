<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - {{ $user->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Anda tetap sama, tidak ada perubahan */
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

        .readonly-style {
        background-color: #e9ecef; /* Warna abu-abu yang umum untuk disabled */
        cursor: not-allowed;      /* Mengubah kursor mouse agar terlihat tidak bisa diklik */
        opacity: 0.7;             /* Sedikit transparan */
        }

        .readonly-style:focus {
            box-shadow: none; /* Opsional: hilangkan shadow saat di-klik */
            border-color: #ced4da; /* Opsional: kembalikan warna border saat di-klik */
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-100 relative overflow-hidden">
        <!-- Background Decorative Elements -->
        <div class="absolute inset-0">
            <div class="particle w-2 h-2 bg-blue-300 opacity-20" style="left: 10%; top: 15%;"></div>
        </div>
        
        <!-- Main Content Container -->
        <div class="relative z-10 flex items-center justify-center min-h-screen p-8 bg-color">
            <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Form Section -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-white/20">
                        <div class="text-center mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profil Anda</h1>
                        </div>

                        @if(session('status'))
                            <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3 relative" role="alert">
                                <span class="block sm:inline">{{ session('status') }}</span>
                                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Tutup</title><path d="M14.348 5.652a.5.5 0 0 0-.707 0L10 9.293 6.36 5.652a.5.5 0 1 0-.707.707L9.293 10l-3.64 3.64a.5.5 0 0 0 .707.707L10 10.707l3.64 3.64a.5.5 0 0 0 .707-.707L10.707 10l3.64-3.64a.5.5 0 0 0 0-.708z"/></svg>
                                </button>
                            </div>
                        @endif
                         @if ($errors->any())
                            <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-800 px-4 py-3">
                                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form class="space-y-4" method="POST" action="{{ route('user.update.manager', Auth::user()->id) }}">
                            @csrf
                            @method('PUT') {{-- PENTING: Gunakan method PUT untuk update --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" required
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 readonly-style"
                                        readonly 
                                        value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                                    <input type="password" name="password" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Isi jika ingin diubah">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi Baru</label>
                                    <input type="password" name="password_confirmation" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Ulangi kata sandi baru">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 resize-none" rows="2">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="Laki-laki" @if(old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki') selected @endif>Laki-laki</option>
                                        <option value="Perempuan" @if(old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan') selected @endif>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                                    <select name="negara" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="Indonesia" @if(old('negara', $user->negara) == 'Indonesia') selected @endif>Indonesia</option>
                                        <option value="Malaysia" @if(old('negara', $user->negara) == 'Malaysia') selected @endif>Malaysia</option>
                                        <option value="Singapura" @if(old('negara', $user->negara) == 'Singapura') selected @endif>Singapura</option>
                                        <option value="Thailand" @if(old('negara', $user->negara) == 'Thailand') selected @endif>Thailand</option>
                                        <option value="Filipina" @if(old('negara', $user->negara) == 'Filipina') selected @endif>Filipina</option>
                                        <option value="Vietnam" @if(old('negara', $user->negara) == 'Vietnam') selected @endif>Vietnam</option>
                                        <option value="Lainnya" @if(old('negara', $user->negara) == 'Lainnya') selected @endif>Lainnya</option>
                                        <!-- Tambahkan negara lain sesuai kebutuhan -->
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="tel" name="no_telp" required class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ old('no_telp', $user->no_telp) }}">
                            </div>
                            
                            <button type="submit" class="submit-btn w-full bg-gradient text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Logo Section (tidak ada perubahan) -->
                <div class="order-1 lg:order-2 flex items-center justify-center">
                    <div class="text-center logo-animation">
                         <div class="mb-4">
                            <img src="{{ asset('assets/img/icon/logo-jawi2.png') }}" alt="TechFlow Logo" style="width: 280px;">
                        </div>
                        <p class="text-xl mb-6 text-gray-700">Innovate • Connect • Transform</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>