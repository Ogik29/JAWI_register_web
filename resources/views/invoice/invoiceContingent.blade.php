<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Kontingen - {{ $contingent->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        <!-- Invoice Container -->
        <div class="bg-white rounded-lg invoice-shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-950 text-white p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">INVOICE KONTINGEN</h1>
                        <p class="text-neutral-100">{{ $contingent->event->name }}</p>
                        <p class="text-neutral-100 text-sm">{{ $contingent->event->lokasi }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white text-neutral-600 px-4 py-2 rounded-lg font-bold text-lg">#INV-KONT-{{ $contingent->id }}</div>
                        <p class="text-neutral-100 text-sm mt-2">Tanggal: {{ $transaction->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Client Info -->
            <div class="p-8 border-b border-gray-200">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Tagihan Kepada:</h3>
                        <div class="text-gray-600"><p class="font-medium text-gray-800">{{ $contingent->name }}</p><p>Email: {{ $contingent->email ?? 'Email tidak tersedia' }}</p><p>No Telp: {{ $contingent->no_telp }}</p></div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Detail CP & Rek Pembayaran:</h3>
                        <div class="text-gray-600 prose prose-sm max-w-none">{!! $contingent->event->cp !!}</div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200"><th class="text-left py-3 px-2 font-semibold text-gray-800">Deskripsi</th><th class="text-right py-3 px-2 font-semibold text-gray-800">Total</th></tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-4 px-2">
                                    <div class="font-medium text-gray-800">Biaya Pendaftaran Kontingen</div>
                                    <div class="text-sm text-gray-800">Kontingen: ({{ $contingent->name }})</div>
                                    <div class="text-sm text-gray-500">Event: {{ $contingent->event->name }}</div>
                                </td>
                                <td class="text-right py-4 px-2 font-medium text-gray-800">Rp {{ number_format($contingent->event->harga_contingent, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="mt-8 flex justify-end">
                    <div class="w-full max-w-sm">
                        <div class="flex justify-between py-3 border-t-2 border-gray-300">
                            <span class="text-lg font-semibold text-gray-800">Total Tagihan:</span>
                            <span class="text-lg font-bold text-neutral-600">Rp {{ number_format($contingent->event->harga_contingent, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Proof Upload Section -->
            <div class="bg-gray-50 p-8 border-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Upload Bukti Transfer Pendaftaran Kontingen</h3>
                <form id="paymentForm" action="{{ route('invoice.contingent.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                    <div id="uploadArea" class="upload-area rounded-lg p-8 text-center cursor-pointer">
                        <div id="uploadContent">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            <p class="text-lg font-medium text-gray-700 mb-2">Klik untuk upload atau drag & drop</p>
                            <p class="text-sm text-gray-500 mb-4">Format: JPG, PNG, PDF (Max 5MB)</p>
                            <button type="button" class="bg-neutral-600 text-white px-6 py-2 rounded-lg hover:bg-neutral-700 transition-colors">Pilih File</button>
                        </div>
                        <div id="previewArea" class="hidden">
                            @if ($transaction->foto_invoice)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 font-medium">Bukti saat ini:</p>
                                <a href="{{ Storage::url($transaction->foto_invoice) }}" target="_blank" class="text-blue-600 underline">Lihat file</a>
                            </div>
                            <hr class="my-4">
                            @endif
                            <img id="imagePreview" class="mx-auto max-w-full max-h-64 rounded-lg shadow-md mb-4" />
                            <p id="fileName" class="text-sm font-medium text-gray-700 mb-2"></p>
                            <p id="fileSize" class="text-xs text-gray-500 mb-4"></p>
                            <button id="removeFile" type="button" class="text-red-600 hover:text-red-700 text-sm font-medium">Hapus File</button>
                        </div>
                    </div>
                    <input type="file" name="foto_invoice" id="fileInput" class="hidden" accept="image/*,.pdf" required />
                    
                    <div class="mt-6 flex flex-col sm:flex-row justify-center items-center gap-4">
                        <a href="{{ route('history') }}" class="w-full sm:w-auto text-center bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">&larr; Kembali ke Riwayat</a>
                        <button id="submitProof" type="submit" class="w-full sm:w-auto bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>Kirim Bukti Transfer</button>
                    </div>
                </form>
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
        const form = document.getElementById('paymentForm');

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
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => { imagePreview.src = e.target.result; showPreview(file); };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQwIDhIMTJDOS43OTA4NiA4IDggOS43OTA4NiA4IDEyVjUyQzggNTQuMjA5MSA5Ljc5MDg2IDU2IDEyIDU2SDUyQzU0LjIwOTEgNTYgNTYgNTQuMjA5MSA1NiA1MlYyMEw0MCA4WiIgZmlsbD0iI0Y1NjU2NSIvPgo8cGF0aCBkPSJNNDAgOFYyMEg1NiIgZmlsbD0iI0ZCQkZCRiIvPgo8dGV4dCB4PSIzMiIgeT0iNDAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPlBERjwvdGV4dD4KPC9zdmc+';
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
    </script>
</body>
</html>