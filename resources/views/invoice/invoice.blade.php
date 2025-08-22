<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $contingent->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- PERUBAHAN: Menambahkan support untuk plugin tailwind typography --}}
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .invoice-shadow { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .upload-area { border: 2px dashed #d1d5db; transition: all 0.3s ease; }
        .upload-area:hover { border-color: #c50000ff; background-color: #f8fafc; }
        .upload-area.dragover { border-color: #c50000ff; background-color: #eff6ff; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Tombol Kembali ke Riwayat -->
        <div class="mb-4 text-right">
            <a href="{{ route('history') }}" class="inline-block bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors shadow-sm font-medium">
                &larr; Kembali ke Riwayat
            </a>
        </div>


        @if (session('success'))
            <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100 border border-green-400" role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <span class="sr-only">Info</span>
                <div class="ms-3 text-sm font-medium">{{ session('success') }}</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
        @endif

        <!-- Invoice Container -->
        <div class="bg-white rounded-lg invoice-shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-950 text-white p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">INVOICE</h1>
                        <p class="text-neutral-100">{{ $contingent->event->name }}</p>
                        <p class="text-neutral-100 text-sm">{{ $contingent->event->lokasi }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white text-neutral-600 px-4 py-2 rounded-lg font-bold text-lg">
                            #INV-{{ $contingent->id }}-{{ now()->format('Ymd') }}
                        </div>
                        <p class="text-neutral-100 text-sm mt-2">Tanggal: {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="text-neutral-100 text-sm">Jatuh Tempo: {{ now()->addDays(14)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Client Info -->
            <div class="p-8 border-b border-gray-200">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Tagihan Kepada:</h3>
                        <div class="text-gray-600">
                            <p class="font-medium text-gray-800">{{ $contingent->name }}</p>
                            <p>Email: {{ $contingent->email ?? 'Email tidak tersedia' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Detail CP & Rek Pembayaran:</h3>
                        {{-- Class 'prose' digunakan untuk styling otomatis konten HTML dari database --}}
                        <div class="text-gray-600 prose prose-sm max-w-none">
                            {!! $contingent->event->cp !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-2 font-semibold text-gray-800">Deskripsi</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-800">Jumlah Pemain</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-800">Harga Kelas</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-800">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoiceItems as $item)
                            <tr class="border-b border-gray-100">
                                <td class="py-4 px-2">
                                    <div class="font-medium text-gray-800">{{ $item['nama_kelas'] }} ({{ $item['gender'] }})</div>
                                    <div class="text-sm text-gray-500">
                                        Pemain: {{ implode(', ', $item['nama_pemain']) }}
                                    </div>
                                </td>
                                <td class="text-center py-4 px-2 text-gray-600">{{ $item['jumlah_pemain'] }}</td>
                                <td class="text-right py-4 px-2 text-gray-600">Rp {{ number_format($item['harga_per_pendaftaran'], 0, ',', '.') }}</td>
                                <td class="text-right py-4 px-2 font-medium text-gray-800">Rp {{ number_format($item['harga_per_pendaftaran'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data pendaftaran yang perlu dibayar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="mt-8 flex justify-end">
                    <div class="w-full max-w-sm">
                        @php
                            $grandTotal = $totalHarga;
                        @endphp
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b-2 border-gray-300">
                            <span class="text-lg font-semibold text-gray-800">Total:</span>
                            <span class="text-lg font-bold text-neutral-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Proof Upload Section -->
            @if(count($invoiceItems) > 0)
            <div class="bg-gray-50 p-8 border-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Upload Bukti Transfer</h3>
                
                <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto">
                    @csrf
                    
                    <input type="hidden" name="total_price" value="{{ $grandTotal }}">
                    <input type="hidden" name="contingent_id" value="{{ $contingent->id }}">

                    @php $playerIndex = 0; @endphp
                    @foreach ($invoiceItems as $item)
                        @foreach ($item['player_ids'] as $playerId)
                            <input type="hidden" name="pemain[{{ $playerIndex }}][player_id]" value="{{ $playerId }}">
                            <input type="hidden" name="pemain[{{ $playerIndex }}][price]" value="{{ $item['harga_per_pendaftaran'] }}">
                            @php $playerIndex++; @endphp
                        @endforeach
                    @endforeach

                    <!-- Upload Area (Visual) -->
                    <div id="uploadArea" class="upload-area rounded-lg p-8 text-center cursor-pointer">
                        <div id="uploadContent">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <p class="text-lg font-medium text-gray-700 mb-2">Klik untuk upload atau drag & drop</p>
                            <p class="text-sm text-gray-500 mb-4">Format: JPG, PNG, PDF (Max 5MB)</p>
                            <button type="button" class="bg-neutral-600 text-white px-6 py-2 rounded-lg hover:bg-neutral-700 transition-colors">Pilih File</button>
                        </div>
                        <div id="previewArea" class="hidden">
                            <img id="imagePreview" class="mx-auto max-w-full max-h-64 rounded-lg shadow-md mb-4" />
                            <p id="fileName" class="text-sm font-medium text-gray-700 mb-2"></p>
                            <p id="fileSize" class="text-xs text-gray-500 mb-4"></p>
                            <button id="removeFile" type="button" class="text-red-600 hover:text-red-700 text-sm font-medium">Hapus File</button>
                        </div>
                    </div>
                    <input type="file" name="foto_invoice" id="fileInput" class="hidden" accept="image/*,.pdf" required />
                    <div class="mt-6 text-center">
                        <button id="submitProof" type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>Kirim Bukti Transfer</button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Footer -->
            <div class="bg-gray-800 text-white p-6 text-center rounded-b-lg">
                <p class="text-sm">Terima kasih atas kepercayaan Anda</p>
                <p class="text-xs text-gray-400 mt-1">Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan</p>
            </div>
        </div>
    </div>

    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const uploadContent = document.getElementById('uploadContent');
        const previewArea = document.getElementById('previewArea');
        const imagePreview = document.getElementById('imagePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeFile = document.getElementById('removeFile');
        const submitProof = document.getElementById('submitProof');
        const form = submitProof.closest('form');

        if(uploadArea) {
            uploadArea.addEventListener('click', () => fileInput.click());
            ['dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, (e) => { e.preventDefault(); e.stopPropagation(); });
            });
            uploadArea.addEventListener('dragover', () => uploadArea.classList.add('dragover'));
            uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
            uploadArea.addEventListener('drop', (e) => {
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) handleFile(files[0]);
            });

            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) handleFile(e.target.files[0]);
            });
        }


        function handleFile(file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('File terlalu besar! Maksimal 5MB.');
                return;
            }
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau PDF.');
                return;
            }
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => { imagePreview.src = e.target.result; showPreview(file); };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQwIDhIMTJDOS43OTA4NiA4IDggOS43OTA4NiA4IDEyVjUyQzggNTQuMjA5MSA5Ljc5MDg2IDU2IDEyIDU2SDUyQzU0LjIwOTEgNTYgNTYgNTQuMjA5MSA1NiA1MlYyMEw0MCA4WiIgZmlsbD0iI0Y1NjU2NSIvPgo8cGF0aCBkPSJNNDAgOFYyMEg1NiIgZmlsbD0iI0ZCQkZCRiIvPgo8dGV4dCB4PSIzMiIgeT0iNDAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaGyPSJtaWRkbGUiPlBERjwvdGV4dD4KPC9zdmc+';
                showPreview(file);
            }
        }

        function showPreview(file) {
            fileName.textContent = file.name;
            fileSize.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
            uploadContent.classList.add('hidden');
            previewArea.classList.remove('hidden');
            submitProof.disabled = false;
        }

        if(removeFile) {
            removeFile.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.value = '';
                uploadContent.classList.remove('hidden');
                previewArea.classList.add('hidden');
                submitProof.disabled = true;
            });
        }

        if(form) {
            form.addEventListener('submit', function(e) {
                submitProof.disabled = true;
                submitProof.textContent = 'Mengirim...';
            });
        }
    </script>
</body>
</html>