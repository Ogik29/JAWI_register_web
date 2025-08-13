<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Manajemen Event Pencak Silat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg border-b-4 border-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-blue-600 text-3xl mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Super Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Selamat datang, Admin</span>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="showTab('dashboard')" id="tab-dashboard" class="tab-button py-4 px-2 border-b-2 border-blue-500 text-blue-600 font-medium">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </button>
                    <button onclick="showTab('add-event')" id="tab-add-event" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Event
                    </button>
                    <button onclick="showTab('manage-events')" id="tab-manage-events" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                        <i class="fas fa-calendar-alt mr-2"></i>Kelola Event
                    </button>
                    <button onclick="showTab('manage-admins')" id="tab-manage-admins" class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                        <i class="fas fa-users-cog mr-2"></i>Kelola Admin
                    </button>
                </nav>
            </div>
        </div>

        <!-- Dashboard Tab -->
        <div id="dashboard-content" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Event</p>
                            <p class="text-2xl font-semibold text-gray-900">12</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Admin</p>
                            <p class="text-2xl font-semibold text-gray-900">8</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Event Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">3</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-trophy text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Event Selesai</p>
                            <p class="text-2xl font-semibold text-gray-900">9</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kejuaraan Pencak Silat Nasional 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jakarta</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15-17 Des 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Event Tab -->
        <div id="add-event-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>Tambah Event Baru
                </h2>
                
                <form id="add-event-form" class="space-y-6">
                    <!-- Basic Event Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Event *</label>
                            <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama event">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Event *</label>
                            <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan lokasi event">
                        </div>
                    </div>

                    <!-- Poster Upload -->
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Poster Event</h3>
                        <div class="flex items-center justify-center w-full">
                            <label for="poster-upload" class="flex flex-col items-center justify-center w-full h-64 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-blue-50 hover:bg-blue-100">
                                <div id="poster-preview" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-blue-400 mb-4"></i>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 5MB)</p>
                                </div>
                                <input id="poster-upload" type="file" class="hidden" accept="image/*" onchange="previewPoster(this)">
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten *</label>
                            <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan kota/kabupaten">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                            <input type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                            <input type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Batas Pendaftaran *</label>
                            <input type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Biaya Pendaftaran Kontingen *</label>
                            <input type="number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                        </div>
                    </div>

                    <!-- Registration Fees -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Biaya Pendaftaran Atlet per Kategori</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">PEMASALAN</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Tanding/Tunggal/Perorangan</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Ganda/Pasangan</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Regu/Berkelompok</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">PRESTASI</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Tanding/Tunggal/Perorangan</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Ganda/Pasangan</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Regu/Berkelompok</label>
                                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Hadiah *</label>
                        <input type="number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                    </div>

                    <!-- Gender Selection -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Gender</h3>
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="gender" value="putra" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm font-medium text-gray-700">Putra</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="gender" value="putri" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm font-medium text-gray-700">Putri</span>
                            </label>
                        </div>
                    </div>

                    <!-- Age Categories -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Kategori Usia</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" onchange="toggleAgeCategory('pra-usia-dini')" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Pra Usia Dini (kurang dari 5 tahun)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" onchange="toggleAgeCategory('usia-dini-1')" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Usia Dini 1 (5-8 tahun)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" onchange="toggleAgeCategory('usia-dini-2')" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Usia Dini 2 (8-11 tahun)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" onchange="toggleAgeCategory('pra-remaja')" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Pra Remaja (11-14 tahun)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" onchange="toggleAgeCategory('remaja')" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Remaja (14-17 tahun)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" onchange="toggleAgeCategory('dewasa')" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-sm text-gray-700">Dewasa (17-35 tahun)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Competition Categories -->
                    <div id="competition-categories" class="space-y-6">
                        <!-- Categories will be dynamically added here -->
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan Event
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Manage Events Tab -->
        <div id="manage-events-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>Kelola Event
                    </h2>
                    <div class="flex space-x-3">
                        <input type="text" placeholder="Cari event..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Selesai</option>
                            <option>Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kejuaraan Pencak Silat Nasional 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jakarta</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15-17 Des 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Admin Jakarta</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Manage Admins Tab -->
        <div id="manage-admins-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-users-cog text-blue-600 mr-3"></i>Kelola Admin Event
                    </h2>
                    <button onclick="showAddAdminModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Admin
                    </button>
                </div>

                <div id="admin-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sample admin card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">Admin Jakarta</h3>
                                <p class="text-sm text-gray-600">admin.jakarta@email.com</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600"><span class="font-medium">Event Aktif:</span> 2</p>
                            <p class="text-sm text-gray-600"><span class="font-medium">Total Event:</span> 5</p>
                            <p class="text-sm text-gray-600"><span class="font-medium">Status:</span> <span class="text-green-600">Aktif</span></p>
                            <p class="text-sm text-gray-600"><span class="font-medium">Wilayah:</span> Jakarta</p>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="viewAdminEvents('Admin Jakarta')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm transition-colors">
                                <i class="fas fa-eye mr-1"></i>Lihat Event
                            </button>
                            <button onclick="editAdmin('Admin Jakarta')" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm transition-colors">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteAdmin('Admin Jakarta')" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm transition-colors">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="add-admin-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Admin Baru</h3>
                <button onclick="hideAddAdminModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="add-admin-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="admin-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="admin-email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="admin-password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                    <input type="text" id="admin-region" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Jakarta, Bandung, dll">
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="hideAddAdminModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Events Modal -->
    <div id="admin-events-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-6xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Event yang Dikelola - <span id="admin-name-title"></span></h3>
                <button onclick="hideAdminEventsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Admin Event Management Interface -->
            <div class="space-y-6">
                <!-- Event Selection -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Pilih Event untuk Dikelola</h4>
                    <select id="admin-event-select" onchange="loadEventData()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Event --</option>
                        <option value="event1">Kejuaraan Pencak Silat Nasional 2024</option>
                        <option value="event2">Turnamen Pencak Silat Regional Jawa Barat</option>
                    </select>
                </div>

                <!-- Event Management Content -->
                <div id="event-management-content" class="hidden">
                    <!-- Verification Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8">
                            <button onclick="showVerificationTab('contingent')" id="tab-contingent" class="verification-tab py-2 px-1 border-b-2 border-blue-500 text-blue-600 font-medium">
                                <i class="fas fa-users mr-2"></i>Verifikasi Kontingen
                            </button>
                            <button onclick="showVerificationTab('participant')" id="tab-participant" class="verification-tab py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                                <i class="fas fa-user-check mr-2"></i>Verifikasi Peserta
                            </button>
                            <button onclick="showVerificationTab('payment')" id="tab-payment" class="verification-tab py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                                <i class="fas fa-credit-card mr-2"></i>Verifikasi Pembayaran
                            </button>
                        </nav>
                    </div>

                    <!-- Contingent Verification -->
                    <div id="contingent-verification" class="verification-content">
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h5 class="text-lg font-semibold text-gray-800">Daftar Kontingen Menunggu Verifikasi</h5>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kontingen</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penanggung Jawab</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Peserta</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kontingen Jakarta Selatan</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Budi Santoso</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 peserta</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button onclick="viewContingentDetails('Kontingen Jakarta Selatan')" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                <button onclick="approveContingent('Kontingen Jakarta Selatan')" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                                <button onclick="rejectContingent('Kontingen Jakarta Selatan')" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Participant Verification -->
                    <div id="participant-verification" class="verification-content hidden">
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h5 class="text-lg font-semibold text-gray-800">Daftar Peserta Menunggu Verifikasi</h5>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ahmad Rizki</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jakarta Selatan</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Dewasa Putra Tanding A</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button onclick="viewParticipantDetails('Ahmad Rizki')" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                <button onclick="approveParticipant('Ahmad Rizki')" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                                <button onclick="rejectParticipant('Ahmad Rizki')" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Verification -->
                    <div id="payment-verification" class="verification-content hidden">
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h5 class="text-lg font-semibold text-gray-800">Daftar Pembayaran Menunggu Verifikasi</h5>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Pembayaran</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Upload</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Jakarta Selatan</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Pendaftaran Kontingen</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 500.000</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Des 2024</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button onclick="viewPaymentProof('Jakarta Selatan')" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i> Bukti
                                                </button>
                                                <button onclick="approvePayment('Jakarta Selatan')" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                                <button onclick="rejectPayment('Jakarta Selatan')" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modals -->
    <div id="detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-60">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 id="detail-modal-title" class="text-lg font-semibold text-gray-800"></h3>
                <button onclick="hideDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detail-modal-content">
                <!-- Content will be dynamically loaded -->
            </div>
        </div>
    </div>

    <script>
        // Competition categories data
        const competitionData = {
            'pra-usia-dini': {
                name: 'Pra Usia Dini',
                categories: {
                    'SENI': {
                        'PEMASALAN': ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1-6', 'Tunggal Bebas Tangan Kosong', 'Tunggal Bebas Bersenjata'],
                        'PRESTASI': ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas']
                    }
                }
            },
            'usia-dini-1': {
                name: 'Usia Dini 1',
                categories: {
                    'SENI': {
                        'PEMASALAN': ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1-6', 'Tunggal Bebas Tangan Kosong', 'Tunggal Bebas Bersenjata'],
                        'PRESTASI': ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas']
                    },
                    'TANDING': ['A (18-19 kg)', 'B (19-20 kg)', 'C (20-21 kg)', 'D (21-22 kg)', 'E (22-23 kg)', 'F (23-24 kg)', 'G (24-25 kg)', 'H (25-26 kg)', 'I (26-27 kg)', 'J (27-28 kg)', 'Open (28-29 kg)'],
                    'JURUS BAKU': ['Perseorangan', 'Berpasangan', 'Berkelompok']
                }
            },
            'usia-dini-2': {
                name: 'Usia Dini 2',
                categories: {
                    'SENI': {
                        'PEMASALAN': ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1-6', 'Tunggal Bebas Tangan Kosong', 'Tunggal Bebas Bersenjata'],
                        'PRESTASI': ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas']
                    },
                    'TANDING': ['A (26-28 kg)', 'B (28-30 kg)', 'C (30-32 kg)', 'D (32-34 kg)', 'E (34-36 kg)', 'F (36-38 kg)', 'G (38-40 kg)', 'H (40-42 kg)', 'I (42-44 kg)', 'J (44-46 kg)', 'K (46-48 kg)', 'L (48-50 kg)', 'M (50-52 kg)', 'N (52-54 kg)', 'O (54-56 kg)', 'P (56-58 kg)', 'Q (58-60 kg)', 'R (60-62 kg)', 'S (62-64 kg)', 'Open (64-68 kg)'],
                    'JURUS BAKU': ['Perseorangan', 'Berpasangan', 'Berkelompok']
                }
            },
            'pra-remaja': {
                name: 'Pra Remaja',
                categories: {
                    'SENI': {
                        'PEMASALAN': ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1-6', 'Tunggal Bebas Tangan Kosong', 'Tunggal Bebas Bersenjata'],
                        'PRESTASI': ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas']
                    },
                    'TANDING': ['Under (<39 kg)', 'A (30-33 kg)', 'B (33-36 kg)', 'C (36-39 kg)', 'D (39-42 kg)', 'E (42-45 kg)', 'F (45-48 kg)', 'G (48-51 kg)', 'H (51-54 kg)', 'I (54-57 kg)', 'J (57-60 kg)', 'K (60-63 kg)', 'L (63-66 kg)', 'M (66-69 kg)', 'N (69-72 kg)', 'O (72-75 kg)', 'P (75-78 kg)', 'Open (78-84 kg)'],
                    'JURUS BAKU': ['Perseorangan', 'Berpasangan', 'Berkelompok']
                }
            },
            'remaja': {
                name: 'Remaja',
                categories: {
                    'SENI': {
                        'PEMASALAN': ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1-6', 'Tunggal Bebas Tangan Kosong', 'Tunggal Bebas Bersenjata'],
                        'PRESTASI': ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas']
                    },
                    'TANDING': ['Under (<39 kg)', 'A (39-43 kg)', 'B (43-47 kg)', 'C (47-51 kg)', 'D (51-55 kg)', 'E (55-59 kg)', 'F (59-63 kg)', 'G (63-67 kg)', 'H (67-71 kg)', 'I (71-75 kg)', 'J (75-79 kg)', 'K (79-83 kg)', 'L (83-87 kg)', 'Open 1 (87-100 kg)', 'Open 2 (>100 kg)'],
                    'JURUS BAKU': ['Perseorangan', 'Berpasangan', 'Berkelompok']
                }
            },
            'dewasa': {
                name: 'Dewasa',
                categories: {
                    'SENI': {
                        'PEMASALAN': ['Tunggal Tangan Kosong', 'Tunggal Bersenjata', 'Ganda Tangan Kosong', 'Ganda Bersenjata', 'Beregu Jurus 1-6', 'Tunggal Bebas Tangan Kosong', 'Tunggal Bebas Bersenjata'],
                        'PRESTASI': ['Tunggal', 'Ganda', 'Beregu', 'Tunggal Bebas']
                    },
                    'TANDING': ['Under (<45 kg)', 'A (45-50 kg)', 'B (50-55 kg)', 'C (55-60 kg)', 'D (60-65 kg)', 'E (65-70 kg)', 'F (70-75 kg)', 'G (75-80 kg)', 'H (80-85 kg)', 'I (85-90 kg)', 'J (90-95 kg)', 'Open 1 (95-110 kg)', 'Open 2 (>110 kg)'],
                    'JURUS BAKU': ['Perseorangan', 'Berpasangan', 'Berkelompok']
                }
            }
        };

        // Store admin data
        let adminData = [];

        // Poster preview function
        function previewPoster(input) {
            const file = input.files[0];
            const previewDiv = document.getElementById('poster-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" alt="Poster Preview" class="max-h-48 rounded-lg shadow-md">
                            <button type="button" onclick="removePoster()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">${file.name}</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        }

        function removePoster() {
            document.getElementById('poster-upload').value = '';
            document.getElementById('poster-preview').innerHTML = `
                <i class="fas fa-cloud-upload-alt text-4xl text-blue-400 mb-4"></i>
                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 5MB)</p>
            `;
        }

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }

        // Age category toggle
        function toggleAgeCategory(categoryId) {
            const container = document.getElementById('competition-categories');
            const existingCategory = document.getElementById('category-' + categoryId);
            
            if (existingCategory) {
                existingCategory.remove();
            } else {
                const categoryData = competitionData[categoryId];
                if (categoryData) {
                    const categoryHtml = createCategorySection(categoryId, categoryData);
                    container.insertAdjacentHTML('beforeend', categoryHtml);
                }
            }
        }

        function createCategorySection(categoryId, categoryData) {
            // Get selected genders
            const selectedGenders = [];
            document.querySelectorAll('input[name="gender"]:checked').forEach(checkbox => {
                selectedGenders.push(checkbox.value);
            });

            let html = `
                <div id="category-${categoryId}" class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">${categoryData.name}</h3>
                    <div class="space-y-6">
            `;
            
            // If no gender selected, show message
            if (selectedGenders.length === 0) {
                html += `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>Pilih gender terlebih dahulu untuk melihat kategori</p>
                    </div>
                `;
            } else {
                // Create main category checkboxes (SENI, TANDING, JURUS BAKU)
                html += `
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-800 mb-3">Pilih Kategori Lomba:</h4>
                        <div class="flex flex-wrap gap-4 mb-4">
                `;
                
                Object.keys(categoryData.categories).forEach(type => {
                    const typeColor = type === 'SENI' ? 'green' : type === 'TANDING' ? 'red' : 'purple';
                    const typeIcon = type === 'SENI' ? 'palette' : type === 'TANDING' ? 'fist-raised' : 'book';
                    
                    html += `
                        <label class="flex items-center bg-${typeColor}-50 border border-${typeColor}-200 rounded-lg px-4 py-2 hover:bg-${typeColor}-100 cursor-pointer">
                            <input type="checkbox" onchange="toggleCategoryType('${categoryId}', '${type}')" class="mr-2 h-4 w-4 text-${typeColor}-600 focus:ring-${typeColor}-500 border-gray-300 rounded">
                            <i class="fas fa-${typeIcon} text-${typeColor}-600 mr-2"></i>
                            <span class="font-medium text-${typeColor}-700">${type}</span>
                        </label>
                    `;
                });
                
                html += `
                        </div>
                        <div id="category-details-${categoryId}" class="space-y-4">
                            <!-- Category details will be inserted here -->
                        </div>
                    </div>
                `;
            }
            
            html += `
                    </div>
                </div>
            `;
            
            return html;
        }

        // Modal functions
        function showAddAdminModal() {
            document.getElementById('add-admin-modal').classList.remove('hidden');
            document.getElementById('add-admin-modal').classList.add('flex');
        }

        function hideAddAdminModal() {
            document.getElementById('add-admin-modal').classList.add('hidden');
            document.getElementById('add-admin-modal').classList.remove('flex');
            // Reset form
            document.getElementById('add-admin-form').reset();
        }

        function showAdminEventsModal() {
            document.getElementById('admin-events-modal').classList.remove('hidden');
            document.getElementById('admin-events-modal').classList.add('flex');
        }

        function hideAdminEventsModal() {
            document.getElementById('admin-events-modal').classList.add('hidden');
            document.getElementById('admin-events-modal').classList.remove('flex');
        }

        function showDetailModal() {
            document.getElementById('detail-modal').classList.remove('hidden');
            document.getElementById('detail-modal').classList.add('flex');
        }

        function hideDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.getElementById('detail-modal').classList.remove('flex');
        }

        // Admin management functions
        function viewAdminEvents(adminName) {
            document.getElementById('admin-name-title').textContent = adminName;
            showAdminEventsModal();
        }

        function editAdmin(adminName) {
            alert(`Edit admin: ${adminName}`);
        }

        function deleteAdmin(adminName) {
            if (confirm(`Apakah Anda yakin ingin menghapus admin ${adminName}?`)) {
                alert(`Admin ${adminName} telah dihapus`);
                // Here you would remove the admin from the list
            }
        }

        // Event management functions
        function loadEventData() {
            const selectedEvent = document.getElementById('admin-event-select').value;
            const eventContent = document.getElementById('event-management-content');
            
            if (selectedEvent) {
                eventContent.classList.remove('hidden');
                showVerificationTab('contingent');
            } else {
                eventContent.classList.add('hidden');
            }
        }

        function showVerificationTab(tabName) {
            // Hide all verification contents
            document.querySelectorAll('.verification-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all verification tabs
            document.querySelectorAll('.verification-tab').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected verification content
            document.getElementById(tabName + '-verification').classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }

        // Verification functions
        function viewContingentDetails(contingentName) {
            document.getElementById('detail-modal-title').textContent = `Detail Kontingen - ${contingentName}`;
            document.getElementById('detail-modal-content').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Kontingen</label>
                            <p class="text-sm text-gray-900">${contingentName}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penanggung Jawab</label>
                            <p class="text-sm text-gray-900">Budi Santoso</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="text-sm text-gray-900">budi@email.com</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telepon</label>
                            <p class="text-sm text-gray-900">081234567890</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <p class="text-sm text-gray-900">Jakarta Selatan</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Peserta</label>
                            <p class="text-sm text-gray-900">15 peserta</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Pendukung</label>
                        <div class="space-y-2">
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-pdf mr-2"></i>Surat Rekomendasi.pdf
                            </a>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-pdf mr-2"></i>Daftar Peserta.pdf
                            </a>
                        </div>
                    </div>
                </div>
            `;
            showDetailModal();
        }

        function approveContingent(contingentName) {
            if (confirm(`Setujui pendaftaran kontingen ${contingentName}?`)) {
                alert(`Kontingen ${contingentName} telah disetujui`);
            }
        }

        function rejectContingent(contingentName) {
            const reason = prompt(`Alasan penolakan kontingen ${contingentName}:`);
            if (reason) {
                alert(`Kontingen ${contingentName} ditolak dengan alasan: ${reason}`);
            }
        }

        function viewParticipantDetails(participantName) {
            document.getElementById('detail-modal-title').textContent = `Detail Peserta - ${participantName}`;
            document.getElementById('detail-modal-content').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <p class="text-sm text-gray-900">${participantName}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kontingen</label>
                            <p class="text-sm text-gray-900">Jakarta Selatan</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <p class="text-sm text-gray-900">15 Januari 1995</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <p class="text-sm text-gray-900">Laki-laki</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <p class="text-sm text-gray-900">Dewasa Putra Tanding A</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Berat Badan</label>
                            <p class="text-sm text-gray-900">48 kg</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Peserta</label>
                        <div class="space-y-2">
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-image mr-2"></i>Foto KTP
                            </a>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-image mr-2"></i>Pas Foto
                            </a>
                            <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-pdf mr-2"></i>Sertifikat Sabuk.pdf
                            </a>
                        </div>
                    </div>
                </div>
            `;
            showDetailModal();
        }

        function approveParticipant(participantName) {
            if (confirm(`Setujui pendaftaran peserta ${participantName}?`)) {
                alert(`Peserta ${participantName} telah disetujui`);
            }
        }

        function rejectParticipant(participantName) {
            const reason = prompt(`Alasan penolakan peserta ${participantName}:`);
            if (reason) {
                alert(`Peserta ${participantName} ditolak dengan alasan: ${reason}`);
            }
        }

        function viewPaymentProof(contingentName) {
            document.getElementById('detail-modal-title').textContent = `Bukti Pembayaran - ${contingentName}`;
            document.getElementById('detail-modal-content').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kontingen</label>
                            <p class="text-sm text-gray-900">${contingentName}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Pembayaran</label>
                            <p class="text-sm text-gray-900">Pendaftaran Kontingen</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <p class="text-sm text-gray-900">Rp 500.000</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Transfer</label>
                            <p class="text-sm text-gray-900">10 Desember 2024</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank</label>
                            <p class="text-sm text-gray-900">BCA</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. Rekening</label>
                            <p class="text-sm text-gray-900">1234567890</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2Y3ZjdmNyIvPgogIDx0ZXh0IHg9IjE1MCIgeT0iMTAwIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTk5OTkiIHRleHQtYW5jaG9yPSJtaWRkbGUiPkJ1a3RpIFRyYW5zZmVyPC90ZXh0Pgo8L3N2Zz4K" alt="Bukti Transfer" class="max-w-full h-auto rounded">
                        </div>
                    </div>
                </div>
            `;
            showDetailModal();
        }

        function approvePayment(contingentName) {
            if (confirm(`Setujui pembayaran dari kontingen ${contingentName}?`)) {
                alert(`Pembayaran dari kontingen ${contingentName} telah disetujui`);
            }
        }

        function rejectPayment(contingentName) {
            const reason = prompt(`Alasan penolakan pembayaran dari kontingen ${contingentName}:`);
            if (reason) {
                alert(`Pembayaran dari kontingen ${contingentName} ditolak dengan alasan: ${reason}`);
            }
        }

        // Add admin form submission
        document.getElementById('add-admin-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('admin-name').value;
            const email = document.getElementById('admin-email').value;
            const password = document.getElementById('admin-password').value;
            const region = document.getElementById('admin-region').value;
            
            // Create new admin card
            const adminCard = `
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">${name}</h3>
                            <p class="text-sm text-gray-600">${email}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-medium">Event Aktif:</span> 0</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Total Event:</span> 0</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Status:</span> <span class="text-green-600">Aktif</span></p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Wilayah:</span> ${region}</p>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button onclick="viewAdminEvents('${name}')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm transition-colors">
                            <i class="fas fa-eye mr-1"></i>Lihat Event
                        </button>
                        <button onclick="editAdmin('${name}')" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        <button onclick="deleteAdmin('${name}')" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm transition-colors">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('admin-list').insertAdjacentHTML('beforeend', adminCard);
            hideAddAdminModal();
            alert(`Admin ${name} berhasil ditambahkan!`);
        });

        // Toggle category type (SENI, TANDING, JURUS BAKU)
        function toggleCategoryType(categoryId, type) {
            const detailsContainer = document.getElementById(`category-details-${categoryId}`);
            const existingDetail = document.getElementById(`detail-${categoryId}-${type}`);
            
            if (existingDetail) {
                existingDetail.remove();
            } else {
                const categoryData = competitionData[categoryId];
                const selectedGenders = [];
                document.querySelectorAll('input[name="gender"]:checked').forEach(checkbox => {
                    selectedGenders.push(checkbox.value);
                });
                
                const typeColor = type === 'SENI' ? 'green' : type === 'TANDING' ? 'red' : 'purple';
                
                let detailHtml = `
                    <div id="detail-${categoryId}-${type}" class="bg-${typeColor}-50 p-4 rounded-lg border border-${typeColor}-200">
                        <h5 class="font-semibold text-${typeColor}-800 mb-3 flex items-center">
                            <i class="fas fa-${type === 'SENI' ? 'palette' : type === 'TANDING' ? 'fist-raised' : 'book'} mr-2"></i>
                            ${type}
                        </h5>
                        <div class="space-y-4">
                `;
                
                // Check if SENI has subcategories (PEMASALAN/PRESTASI)
                if (type === 'SENI' && typeof categoryData.categories[type] === 'object' && !Array.isArray(categoryData.categories[type])) {
                    // SENI with subcategories
                    Object.keys(categoryData.categories[type]).forEach(subType => {
                        detailHtml += `
                            <div class="bg-white p-3 rounded border border-${typeColor}-200">
                                <h6 class="font-bold text-${typeColor}-700 mb-3 text-center border-b pb-2">
                                    ${subType}
                                </h6>
                                <div class="space-y-3">
                        `;
                        
                        selectedGenders.forEach(gender => {
                            const genderColor = gender === 'putra' ? 'blue' : 'pink';
                            const genderIcon = gender === 'putra' ? 'mars' : 'venus';
                            const genderName = gender === 'putra' ? 'PUTRA' : 'PUTRI';
                            
                            detailHtml += `
                                <div class="bg-gray-50 p-2 rounded border border-${genderColor}-200">
                                    <h7 class="font-medium text-${genderColor}-700 mb-2 flex items-center text-sm">
                                        <i class="fas fa-${genderIcon} mr-2"></i>${genderName}
                                    </h7>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-1">
                            `;
                            
                            categoryData.categories[type][subType].forEach(item => {
                                detailHtml += `
                                    <label class="flex items-start p-1 hover:bg-white rounded text-xs">
                                        <input type="checkbox" name="${categoryId}-${gender}-${type}-${subType}" value="${item}" class="mr-1 h-3 w-3 text-${genderColor}-600 focus:ring-${genderColor}-500 border-gray-300 rounded mt-0.5">
                                        <span class="leading-tight">${item}</span>
                                    </label>
                                `;
                            });
                            
                            // Add custom class input for this subcategory
                            detailHtml += `
                                <div class="mt-2 p-2 bg-gray-100 rounded border border-gray-300">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tambah Kelas Custom:</label>
                                    <div class="flex gap-1">
                                        <input type="text" id="custom-${categoryId}-${gender}-${type}-${subType}" placeholder="Nama kelas baru..." class="flex-1 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-${genderColor}-500">
                                        <button type="button" onclick="addCustomClass('${categoryId}', '${gender}', '${type}', '${subType}')" class="px-2 py-1 bg-${genderColor}-600 text-white text-xs rounded hover:bg-${genderColor}-700">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            
                            detailHtml += `
                                    </div>
                                </div>
                            `;
                        });
                        
                        detailHtml += `
                                </div>
                            </div>
                        `;
                    });
                } else {
                    // Regular categories (TANDING, JURUS BAKU, or simple SENI)
                    selectedGenders.forEach(gender => {
                        const genderColor = gender === 'putra' ? 'blue' : 'pink';
                        const genderIcon = gender === 'putra' ? 'mars' : 'venus';
                        const genderName = gender === 'putra' ? 'PUTRA' : 'PUTRI';
                        
                        detailHtml += `
                            <div class="bg-white p-3 rounded border border-${genderColor}-200">
                                <h6 class="font-medium text-${genderColor}-700 mb-2 flex items-center">
                                    <i class="fas fa-${genderIcon} mr-2"></i>${genderName}
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        `;
                        
                        const items = Array.isArray(categoryData.categories[type]) ? categoryData.categories[type] : [];
                        items.forEach(item => {
                            detailHtml += `
                                <label class="flex items-start p-1 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="${categoryId}-${gender}-${type}" value="${item}" class="mr-2 h-4 w-4 text-${genderColor}-600 focus:ring-${genderColor}-500 border-gray-300 rounded mt-0.5">
                                    <span class="text-sm leading-tight">${item}</span>
                                </label>
                            `;
                        });
                        
                        // Add custom class input for regular categories
                        detailHtml += `
                            <div class="col-span-full mt-2 p-2 bg-gray-100 rounded border border-gray-300">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tambah Kelas Custom:</label>
                                <div class="flex gap-2">
                                    <input type="text" id="custom-${categoryId}-${gender}-${type}" placeholder="Nama kelas baru..." class="flex-1 px-3 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-${genderColor}-500">
                                    <button type="button" onclick="addCustomClass('${categoryId}', '${gender}', '${type}')" class="px-3 py-1 bg-${genderColor}-600 text-white text-sm rounded hover:bg-${genderColor}-700">
                                        <i class="fas fa-plus mr-1"></i>Tambah
                                    </button>
                                </div>
                            </div>
                        `;
                        
                        detailHtml += `
                                </div>
                            </div>
                        `;
                    });
                }
                
                detailHtml += `
                        </div>
                    </div>
                `;
                
                detailsContainer.insertAdjacentHTML('beforeend', detailHtml);
            }
        }

        // Update categories when gender selection changes
        function updateCategoriesOnGenderChange() {
            // Get all currently displayed age categories
            const displayedCategories = document.querySelectorAll('[id^="category-"]');
            
            displayedCategories.forEach(categoryDiv => {
                const categoryId = categoryDiv.id.replace('category-', '');
                const categoryData = competitionData[categoryId];
                
                if (categoryData) {
                    // Replace the category content
                    const newCategoryHtml = createCategorySection(categoryId, categoryData);
                    categoryDiv.outerHTML = newCategoryHtml;
                }
            });
        }

        // Add event listeners to gender checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            showTab('dashboard');
            
            // Add listeners to gender checkboxes
            document.querySelectorAll('input[name="gender"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateCategoriesOnGenderChange);
            });
        });

        // Add custom class function
        function addCustomClass(categoryId, gender, type, subType = null) {
            const inputId = subType ? 
                `custom-${categoryId}-${gender}-${type}-${subType}` : 
                `custom-${categoryId}-${gender}-${type}`;
            
            const input = document.getElementById(inputId);
            const customClassName = input.value.trim();
            
            if (!customClassName) {
                alert('Masukkan nama kelas terlebih dahulu!');
                return;
            }
            
            // Find the container where we should add the new checkbox
            const container = input.closest('.bg-gray-100').previousElementSibling;
            
            // Create new checkbox for the custom class
            const genderColor = gender === 'putra' ? 'blue' : 'pink';
            const checkboxName = subType ? 
                `${categoryId}-${gender}-${type}-${subType}` : 
                `${categoryId}-${gender}-${type}`;
            
            const newCheckboxHtml = `
                <label class="flex items-start p-1 hover:bg-gray-50 rounded custom-class">
                    <input type="checkbox" name="${checkboxName}" value="${customClassName}" class="mr-2 h-4 w-4 text-${genderColor}-600 focus:ring-${genderColor}-500 border-gray-300 rounded mt-0.5">
                    <span class="text-sm leading-tight">${customClassName}</span>
                    <button type="button" onclick="removeCustomClass(this)" class="ml-auto text-red-500 hover:text-red-700 text-xs">
                        <i class="fas fa-times"></i>
                    </button>
                </label>
            `;
            
            container.insertAdjacentHTML('beforeend', newCheckboxHtml);
            
            // Clear the input
            input.value = '';
            
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.className = 'text-green-600 text-xs mt-1';
            successMsg.textContent = `Kelas "${customClassName}" berhasil ditambahkan!`;
            input.parentNode.appendChild(successMsg);
            
            setTimeout(() => {
                successMsg.remove();
            }, 3000);
        }
        
        // Remove custom class function
        function removeCustomClass(button) {
            if (confirm('Hapus kelas custom ini?')) {
                button.closest('label').remove();
            }
        }

        // Form submission
        document.getElementById('add-event-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get selected genders
            const selectedGenders = [];
            document.querySelectorAll('input[name="gender"]:checked').forEach(checkbox => {
                selectedGenders.push(checkbox.value);
            });
            
            // Get poster file
            const posterFile = document.getElementById('poster-upload').files[0];
            
            // Collect selected categories by gender
            const selectedCategories = {};
            document.querySelectorAll('[id^="category-"]').forEach(categoryDiv => {
                const categoryId = categoryDiv.id.replace('category-', '');
                selectedCategories[categoryId] = {};
                
                selectedGenders.forEach(gender => {
                    selectedCategories[categoryId][gender] = {};
                    
                    Object.keys(competitionData[categoryId].categories).forEach(type => {
                        if (type === 'SENI' && typeof competitionData[categoryId].categories[type] === 'object' && !Array.isArray(competitionData[categoryId].categories[type])) {
                            // Handle SENI with subcategories
                            selectedCategories[categoryId][gender][type] = {};
                            Object.keys(competitionData[categoryId].categories[type]).forEach(subType => {
                                const selectedItems = [];
                                categoryDiv.querySelectorAll(`input[name="${categoryId}-${gender}-${type}-${subType}"]:checked`).forEach(item => {
                                    selectedItems.push(item.value);
                                });
                                if (selectedItems.length > 0) {
                                    selectedCategories[categoryId][gender][type][subType] = selectedItems;
                                }
                            });
                        } else {
                            // Handle regular categories
                            const selectedItems = [];
                            categoryDiv.querySelectorAll(`input[name="${categoryId}-${gender}-${type}"]:checked`).forEach(item => {
                                selectedItems.push(item.value);
                            });
                            if (selectedItems.length > 0) {
                                selectedCategories[categoryId][gender][type] = selectedItems;
                            }
                        }
                    });
                });
            });
            
            console.log('Selected Genders:', selectedGenders);
            console.log('Selected Categories by Gender:', selectedCategories);
            console.log('Poster File:', posterFile ? posterFile.name : 'No poster uploaded');
            
            alert('Event berhasil ditambahkan dengan kategori terpisah untuk setiap gender! Lihat console untuk detail.');
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96e0abe9f3aa5e3e',t:'MTc1NTAwOTAzNi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
