<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Silat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">🥋</span>
                </div>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                    <p class="text-sm text-gray-500">Event Management System</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Admin User</p>
                    <p class="text-xs text-gray-500">Super Admin</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-gray-600 text-sm">👤</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="bg-white border-b">
        <div class="px-6">
            <div class="flex space-x-8">
                <button onclick="showSection('dashboard')" class="nav-btn py-4 px-2 border-b-2 border-red-500 text-red-600 font-medium">
                    📊 Dashboard
                </button>
                <button onclick="showSection('events')" class="nav-btn py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                    🏆 Kelola Event
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="p-6">
        <!-- Dashboard Section -->
        <div id="dashboard" class="section">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
                <p class="text-gray-600">Ringkasan aktivitas event silat</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Atlet Terdaftar</p>
                            <p class="text-2xl font-bold text-gray-900">248</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-green-600 text-xl">👥</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Menunggu Verifikasi</p>
                            <p class="text-2xl font-bold text-orange-600">15</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="text-orange-600 text-xl">⏳</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Aktif</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Kejuaraan Silat Nasional 2024</h4>
                            <p class="text-sm text-gray-600">15-17 Maret 2024 • Jakarta</p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Piala Gubernur Silat 2024</h4>
                            <p class="text-sm text-gray-600">22-24 Maret 2024 • Bandung</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Pendaftaran</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Section -->
        <div id="events" class="section hidden">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Event</h2>
                    <p class="text-gray-600">Manajemen event, kontingen, dan atlet</p>
                </div>
                <button onclick="openEventModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium">
                    + Tambah Event
                </button>
            </div>

            <!-- Event List -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Event</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Kejuaraan Silat Nasional 2024</div>
                                        <div class="text-sm text-gray-500">Kategori: Tanding & Tunggal</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">15-17 Mar 2024</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Jakarta</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">156 atlet</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="editEvent(1)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                                        <button onclick="viewEventDetail(1)" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Detail</button>
                                        <button onclick="downloadEventReport(1)" class="text-green-600 hover:text-green-800 text-sm font-medium">Laporan</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Piala Gubernur Silat 2024</div>
                                        <div class="text-sm text-gray-500">Kategori: Tanding</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">22-24 Mar 2024</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Bandung</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Pendaftaran</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">92 atlet</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="editEvent(2)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                                        <button onclick="viewEventDetail(2)" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Detail</button>
                                        <button onclick="downloadEventReport(2)" class="text-green-600 hover:text-green-800 text-sm font-medium">Laporan</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kontingen Management -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Kontingen</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penanggung Jawab</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Atlet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">DKI Jakarta</div>
                                        <div class="text-sm text-gray-500">Padepokan Silat Jakarta</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                        <div class="text-sm text-gray-500">0812-3456-7890</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">24 atlet</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Lengkap</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Lunas</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="verifyContingentDocs(1)" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi Dokumen</button>
                                        <button onclick="verifyContingentPayment(1)" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Verifikasi Bayar</button>
                                        <button onclick="viewContingentDetail(1)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Jawa Barat</div>
                                        <div class="text-sm text-gray-500">Perguruan Silat Bandung</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Siti Aminah</div>
                                        <div class="text-sm text-gray-500">0813-2345-6789</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">18 atlet</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">Menunggu</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">Sebagian</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="verifyContingentDocs(2)" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi Dokumen</button>
                                        <button onclick="verifyContingentPayment(2)" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Verifikasi Bayar</button>
                                        <button onclick="viewContingentDetail(2)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Athletes Management -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Atlet</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Atlet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontingen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-gray-600 text-sm">👤</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Ahmad Rizki</div>
                                            <div class="text-sm text-gray-500">ID: ATL001</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">Kejuaraan Nasional 2024</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Tanding Putra -60kg</td>
                                <td class="px-6 py-4 text-sm text-gray-900">DKI Jakarta</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">Menunggu</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">Menunggu</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="verifyAthleteDocs(1)" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Verifikasi Dokumen</button>
                                        <button onclick="verifyAthletePayment(1)" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Verifikasi Bayar</button>
                                        <button onclick="viewAthleteDetail(1)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-gray-600 text-sm">👤</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Sari Dewi</div>
                                            <div class="text-sm text-gray-500">ID: ATL002</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">Piala Gubernur 2024</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Tanding Putri -55kg</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Jawa Barat</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Terverifikasi</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Lunas</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button onclick="viewAthleteDetail(2)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Event Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Event Baru</h3>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="text-xl">×</span>
                </button>
            </div>
            <form id="eventForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Event</label>
                    <input type="text" id="eventName" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Masukkan nama event" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="startDate" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="endDate" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" id="location" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Masukkan lokasi event" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="category" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Pilih Kategori</option>
                        <option value="tanding">Tanding</option>
                        <option value="tunggal">Tunggal</option>
                        <option value="both">Tanding & Tunggal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Pendaftaran</label>
                    <input type="number" id="registrationFee" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Masukkan biaya pendaftaran" required>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEventModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Simpan Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-4xl mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 id="detailModalTitle" class="text-lg font-semibold text-gray-900">Detail</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="text-xl">×</span>
                </button>
            </div>
            <div id="detailModalContent" class="space-y-4">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="flex justify-end pt-4">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 id="verificationModalTitle" class="text-lg font-semibold text-gray-900">Verifikasi</h3>
                <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="text-xl">×</span>
                </button>
            </div>
            <div id="verificationModalContent" class="space-y-4">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button onclick="closeVerificationModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button id="rejectBtn" onclick="processVerification('reject')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Tolak
                </button>
                <button id="approveBtn" onclick="processVerification('approve')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Setujui
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentVerificationData = {};

        // Navigation
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show selected section
            document.getElementById(sectionId).classList.remove('hidden');
            
            // Update navigation buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('border-red-500', 'text-red-600', 'font-medium');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Highlight active button
            event.target.classList.remove('border-transparent', 'text-gray-500');
            event.target.classList.add('border-red-500', 'text-red-600', 'font-medium');
        }

        // Event Modal Functions
        function openEventModal() {
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
            document.getElementById('eventForm').reset();
        }

        // Detail Modal Functions
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Verification Modal Functions
        function closeVerificationModal() {
            document.getElementById('verificationModal').classList.add('hidden');
            currentVerificationData = {};
        }

        // Event Management Functions
        function editEvent(eventId) {
            const eventData = {
                1: { name: 'Kejuaraan Silat Nasional 2024', location: 'Jakarta', startDate: '2024-03-15', endDate: '2024-03-17' },
                2: { name: 'Piala Gubernur Silat 2024', location: 'Bandung', startDate: '2024-03-22', endDate: '2024-03-24' }
            };
            
            const event = eventData[eventId];
            if (event) {
                document.getElementById('eventName').value = event.name;
                document.getElementById('location').value = event.location;
                document.getElementById('startDate').value = event.startDate;
                document.getElementById('endDate').value = event.endDate;
                openEventModal();
            }
        }

        function viewEventDetail(eventId) {
            const eventDetails = {
                1: {
                    name: 'Kejuaraan Silat Nasional 2024',
                    date: '15-17 Maret 2024',
                    location: 'Jakarta Convention Center',
                    category: 'Tanding & Tunggal',
                    participants: 156,
                    fee: 'Rp 250.000',
                    status: 'Aktif'
                },
                2: {
                    name: 'Piala Gubernur Silat 2024',
                    date: '22-24 Maret 2024',
                    location: 'GOR Pajajaran Bandung',
                    category: 'Tanding',
                    participants: 92,
                    fee: 'Rp 200.000',
                    status: 'Pendaftaran'
                }
            };

            const event = eventDetails[eventId];
            if (event) {
                document.getElementById('detailModalTitle').textContent = 'Detail Event';
                document.getElementById('detailModalContent').innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Event</label>
                            <p class="text-sm text-gray-900 mt-1">${event.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="text-sm text-gray-900 mt-1">${event.status}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="text-sm text-gray-900 mt-1">${event.date}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <p class="text-sm text-gray-900 mt-1">${event.location}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <p class="text-sm text-gray-900 mt-1">${event.category}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Peserta</label>
                            <p class="text-sm text-gray-900 mt-1">${event.participants} atlet</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Biaya Pendaftaran</label>
                            <p class="text-sm text-gray-900 mt-1">${event.fee}</p>
                        </div>
                    </div>
                `;
                document.getElementById('detailModal').classList.remove('hidden');
            }
        }

        function downloadEventReport(eventId) {
            const eventNames = {
                1: 'Kejuaraan Silat Nasional 2024',
                2: 'Piala Gubernur Silat 2024'
            };
            
            // Simulate report download
            const reportData = `Laporan Event: ${eventNames[eventId]}\nTanggal: ${new Date().toLocaleDateString('id-ID')}\n\nData lengkap event dan peserta...`;
            const blob = new Blob([reportData], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `laporan-event-${eventId}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            alert(`Laporan ${eventNames[eventId]} berhasil diunduh!`);
        }

        // Contingent Verification Functions
        function verifyContingentDocs(contingentId) {
            const contingentData = {
                1: { name: 'DKI Jakarta', responsible: 'Budi Santoso', athletes: 24 },
                2: { name: 'Jawa Barat', responsible: 'Siti Aminah', athletes: 18 }
            };

            const contingent = contingentData[contingentId];
            currentVerificationData = { type: 'contingent-docs', id: contingentId, data: contingent };

            document.getElementById('verificationModalTitle').textContent = 'Verifikasi Dokumen Kontingen';
            document.getElementById('verificationModalContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kontingen</label>
                        <p class="text-sm text-gray-900 mt-1">${contingent.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penanggung Jawab</label>
                        <p class="text-sm text-gray-900 mt-1">${contingent.responsible}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Atlet</label>
                        <p class="text-sm text-gray-900 mt-1">${contingent.athletes} atlet</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dokumen yang Diperiksa</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="doc1" class="mr-2" checked>
                                <label for="doc1" class="text-sm text-gray-700">Surat Pengantar Organisasi</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="doc2" class="mr-2" checked>
                                <label for="doc2" class="text-sm text-gray-700">Daftar Nama Atlet</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="doc3" class="mr-2" checked>
                                <label for="doc3" class="text-sm text-gray-700">Surat Keterangan Sehat</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1" rows="3" placeholder="Tambahkan catatan verifikasi..."></textarea>
                    </div>
                </div>
            `;
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function verifyContingentPayment(contingentId) {
            const contingentData = {
                1: { name: 'DKI Jakarta', amount: 'Rp 6.000.000', method: 'Transfer Bank' },
                2: { name: 'Jawa Barat', amount: 'Rp 3.600.000', method: 'Transfer Bank' }
            };

            const contingent = contingentData[contingentId];
            currentVerificationData = { type: 'contingent-payment', id: contingentId, data: contingent };

            document.getElementById('verificationModalTitle').textContent = 'Verifikasi Pembayaran Kontingen';
            document.getElementById('verificationModalContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kontingen</label>
                        <p class="text-sm text-gray-900 mt-1">${contingent.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</label>
                        <p class="text-sm text-gray-900 mt-1">${contingent.amount}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p class="text-sm text-gray-900 mt-1">${contingent.method}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bukti Transfer</label>
                        <div class="mt-2 p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                            <p class="text-sm text-gray-500">📄 bukti_transfer_${contingentId}.jpg</p>
                            <button class="text-blue-600 hover:text-blue-800 text-sm mt-2">Lihat Bukti Transfer</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1" rows="3" placeholder="Tambahkan catatan verifikasi..."></textarea>
                    </div>
                </div>
            `;
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function viewContingentDetail(contingentId) {
            const contingentDetails = {
                1: {
                    name: 'DKI Jakarta',
                    organization: 'Padepokan Silat Jakarta',
                    responsible: 'Budi Santoso',
                    phone: '0812-3456-7890',
                    athletes: 24,
                    docStatus: 'Lengkap',
                    paymentStatus: 'Lunas',
                    totalPayment: 'Rp 6.000.000'
                },
                2: {
                    name: 'Jawa Barat',
                    organization: 'Perguruan Silat Bandung',
                    responsible: 'Siti Aminah',
                    phone: '0813-2345-6789',
                    athletes: 18,
                    docStatus: 'Menunggu',
                    paymentStatus: 'Sebagian',
                    totalPayment: 'Rp 3.600.000'
                }
            };

            const contingent = contingentDetails[contingentId];
            if (contingent) {
                document.getElementById('detailModalTitle').textContent = 'Detail Kontingen';
                document.getElementById('detailModalContent').innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Kontingen</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Organisasi</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.organization}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penanggung Jawab</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.responsible}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.phone}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Atlet</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.athletes} atlet</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Dokumen</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.docStatus}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.paymentStatus}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Pembayaran</label>
                            <p class="text-sm text-gray-900 mt-1">${contingent.totalPayment}</p>
                        </div>
                    </div>
                `;
                document.getElementById('detailModal').classList.remove('hidden');
            }
        }

        // Athlete Verification Functions
        function verifyAthleteDocs(athleteId) {
            const athleteData = {
                1: { name: 'Ahmad Rizki', id: 'ATL001', contingent: 'DKI Jakarta' },
                2: { name: 'Sari Dewi', id: 'ATL002', contingent: 'Jawa Barat' }
            };

            const athlete = athleteData[athleteId];
            currentVerificationData = { type: 'athlete-docs', id: athleteId, data: athlete };

            document.getElementById('verificationModalTitle').textContent = 'Verifikasi Dokumen Atlet';
            document.getElementById('verificationModalContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Atlet</label>
                        <p class="text-sm text-gray-900 mt-1">${athlete.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Atlet</label>
                        <p class="text-sm text-gray-900 mt-1">${athlete.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kontingen</label>
                        <p class="text-sm text-gray-900 mt-1">${athlete.contingent}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dokumen yang Diperiksa</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="athlete-doc1" class="mr-2" checked>
                                <label for="athlete-doc1" class="text-sm text-gray-700">KTP/Identitas</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="athlete-doc2" class="mr-2" checked>
                                <label for="athlete-doc2" class="text-sm text-gray-700">Surat Keterangan Sehat</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="athlete-doc3" class="mr-2" checked>
                                <label for="athlete-doc3" class="text-sm text-gray-700">Pas Foto</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="athlete-doc4" class="mr-2" checked>
                                <label for="athlete-doc4" class="text-sm text-gray-700">Sertifikat Sabuk</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1" rows="3" placeholder="Tambahkan catatan verifikasi..."></textarea>
                    </div>
                </div>
            `;
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function verifyAthletePayment(athleteId) {
            const athleteData = {
                1: { name: 'Ahmad Rizki', amount: 'Rp 250.000', method: 'Transfer Bank' },
                2: { name: 'Sari Dewi', amount: 'Rp 200.000', method: 'E-Wallet' }
            };

            const athlete = athleteData[athleteId];
            currentVerificationData = { type: 'athlete-payment', id: athleteId, data: athlete };

            document.getElementById('verificationModalTitle').textContent = 'Verifikasi Pembayaran Atlet';
            document.getElementById('verificationModalContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Atlet</label>
                        <p class="text-sm text-gray-900 mt-1">${athlete.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</label>
                        <p class="text-sm text-gray-900 mt-1">${athlete.amount}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p class="text-sm text-gray-900 mt-1">${athlete.method}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                        <div class="mt-2 p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                            <p class="text-sm text-gray-500">📄 bukti_bayar_${athleteId}.jpg</p>
                            <button class="text-blue-600 hover:text-blue-800 text-sm mt-2">Lihat Bukti Pembayaran</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1" rows="3" placeholder="Tambahkan catatan verifikasi..."></textarea>
                    </div>
                </div>
            `;
            document.getElementById('verificationModal').classList.remove('hidden');
        }

        function viewAthleteDetail(athleteId) {
            const athleteDetails = {
                1: {
                    name: 'Ahmad Rizki',
                    id: 'ATL001',
                    event: 'Kejuaraan Nasional 2024',
                    category: 'Tanding Putra -60kg',
                    contingent: 'DKI Jakarta',
                    age: '22 tahun',
                    belt: 'Sabuk Hitam',
                    docStatus: 'Menunggu',
                    paymentStatus: 'Menunggu'
                },
                2: {
                    name: 'Sari Dewi',
                    id: 'ATL002',
                    event: 'Piala Gubernur 2024',
                    category: 'Tanding Putri -55kg',
                    contingent: 'Jawa Barat',
                    age: '20 tahun',
                    belt: 'Sabuk Hitam',
                    docStatus: 'Terverifikasi',
                    paymentStatus: 'Lunas'
                }
            };

            const athlete = athleteDetails[athleteId];
            if (athlete) {
                document.getElementById('detailModalTitle').textContent = 'Detail Atlet';
                document.getElementById('detailModalContent').innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Atlet</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Atlet</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.id}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.event}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.category}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kontingen</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.contingent}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Usia</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.age}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tingkat Sabuk</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.belt}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Dokumen</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.docStatus}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <p class="text-sm text-gray-900 mt-1">${athlete.paymentStatus}</p>
                        </div>
                    </div>
                `;
                document.getElementById('detailModal').classList.remove('hidden');
            }
        }

        // Process Verification
        function processVerification(action) {
            const { type, id, data } = currentVerificationData;
            const actionText = action === 'approve' ? 'disetujui' : 'ditolak';
            
            let message = '';
            switch(type) {
                case 'contingent-docs':
                    message = `Dokumen kontingen ${data.name} berhasil ${actionText}`;
                    break;
                case 'contingent-payment':
                    message = `Pembayaran kontingen ${data.name} berhasil ${actionText}`;
                    break;
                case 'athlete-docs':
                    message = `Dokumen atlet ${data.name} berhasil ${actionText}`;
                    break;
                case 'athlete-payment':
                    message = `Pembayaran atlet ${data.name} berhasil ${actionText}`;
                    break;
            }
            
            alert(message);
            closeVerificationModal();
        }

        // Form submission
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const eventName = document.getElementById('eventName').value;
            alert(`Event "${eventName}" berhasil ditambahkan!`);
            closeEventModal();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'96e0be961538f8f8',t:'MTc1NTAwOTgwMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
