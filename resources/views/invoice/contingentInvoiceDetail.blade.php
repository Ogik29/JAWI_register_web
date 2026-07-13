<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Kontingen - {{ $contingent->name }} - #INV-K-{{ $transaction->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                padding: 0 !important;
            }

            .invoice-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center mb-6 no-print">
            <a href="{{ route('contingent.invoices', $contingent->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 bg-white border px-4 py-2 rounded-lg shadow-sm font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Riwayat
            </a>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center text-sm text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak PDF / Print
                </button>
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden invoice-card">
            <!-- Header Banner -->
            <div class="text-white p-8 bg-gradient-to-r from-emerald-800 to-teal-800">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div>
                        <div class="inline-block bg-emerald-600 text-xs font-bold px-2.5 py-1 rounded mb-3 tracking-wider text-uppercase text-white">SUDAH TERBAYAR</div>
                        <h1 class="text-3xl font-bold tracking-tight mb-2">INVOICE PENDAFTARAN KONTINGEN</h1>
                        <p class="text-emerald-100 text-sm">Event: {{ $contingent->event->name }}</p>
                        <p class="text-emerald-100 text-sm">{{ $contingent->event->lokasi }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <div class="bg-white bg-opacity-10 text-white px-4 py-2 rounded-lg font-bold text-lg border border-white border-opacity-20 inline-block">#INV-K-{{ $transaction->id }}</div>
                        <p class="text-emerald-100 text-sm mt-3">Tanggal: {{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('d F Y') }}</p>
                        <p class="text-emerald-100 text-sm">Status Pembayaran: <span class="font-semibold text-white">LUNAS</span></p>
                    </div>
                </div>
            </div>

            <!-- Client & Event Info -->
            <div class="p-8 border-b border-gray-200">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3 uppercase tracking-wider text-xs">Informasi Tagihan:</h3>
                        <div class="text-gray-600 text-sm">
                            <p class="font-bold text-gray-800 text-base mb-1">{{ $contingent->name }}</p>
                            <p class="mb-1"><span class="font-medium text-gray-700">Manajer:</span> {{ $contingent->manajer_name }}</p>
                            <p class="mb-1"><span class="font-medium text-gray-700">Email:</span> {{ $contingent->email ?? 'N/A' }}</p>
                            <p><span class="font-medium text-gray-700">No. Telp:</span> {{ $contingent->no_telp }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3 uppercase tracking-wider text-xs">Kontak Panitia & Informasi Rekening:</h3>
                        <div class="text-gray-600 text-sm prose prose-sm max-w-none">{!! $contingent->event->cp !!}</div>
                    </div>
                </div>
            </div>

            <!-- Invoice Details Table -->
            <div class="p-8">
                <h3 class="font-semibold text-gray-800 mb-4 uppercase tracking-wider text-xs">Detail Rincian Pendaftaran Kontingen:</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200 text-gray-700 text-sm">
                                <th class="text-left py-3 px-2 font-semibold text-gray-800">Deskripsi</th>
                                <th class="text-center py-3 px-2 font-semibold text-gray-800">Jumlah</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-800">Harga</th>
                                <th class="text-right py-3 px-2 font-semibold text-gray-800">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr>
                                <td class="py-4 px-2">
                                    <div class="font-semibold text-gray-900">Pendaftaran Kontingen Dasar</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        Biaya pendaftaran awal kontingen untuk berpartisipasi dalam event {{ $contingent->event->name }}.
                                    </div>
                                </td>
                                <td class="text-center py-4 px-2 text-gray-600">1</td>
                                <td class="text-right py-4 px-2 text-gray-600">Rp {{ number_format($contingent->event->harga_contingent, 0, ',', '.') }}</td>
                                <td class="text-right py-4 px-2 font-semibold text-emerald-700">Rp {{ number_format($contingent->event->harga_contingent, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="mt-8 flex justify-end">
                    <div class="w-full max-w-sm">
                        <div class="flex justify-between py-2 border-b border-gray-200 text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($contingent->event->harga_contingent, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b-2 border-gray-300">
                            <span class="text-base font-semibold text-gray-800">Total Pembayaran:</span>
                            <span class="text-lg font-bold text-emerald-800">Rp {{ number_format($contingent->event->harga_contingent, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proof Image Section -->
            <div class="bg-gray-50 p-8 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 uppercase tracking-wider text-center md:text-start">Bukti Lampiran Pembayaran</h3>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2 text-center">
                        @if ($transaction->foto_invoice)
                            <div class="p-3 bg-white border rounded-lg shadow-sm inline-block">
                                <img src="{{ Storage::url($transaction->foto_invoice) }}" class="max-h-64 rounded object-contain mx-auto" alt="Bukti Transfer">
                            </div>
                            <div class="mt-3 no-print">
                                <a href="{{ Storage::url($transaction->foto_invoice) }}" target="_blank" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900 border px-3 py-1.5 rounded bg-white shadow-sm font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Lihat Ukuran Penuh
                                </a>
                            </div>
                        @else
                            <div class="py-12 bg-white border border-dashed rounded-lg text-gray-400 text-sm">
                                Tidak ada lampiran bukti transfer.
                            </div>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 text-gray-600 text-sm">
                        <div class="bg-white p-4 border rounded-lg shadow-sm">
                            <h5 class="font-bold text-gray-800 mb-2">Catatan Transaksi:</h5>
                            <ul class="list-disc pl-5 space-y-1.5 text-xs text-gray-600">
                                <li>Pembayaran kontingen telah berhasil diverifikasi oleh administrator Jawara Indonesia.</li>
                                <li>Status kontingen Anda resmi terdaftar aktif pada event ini.</li>
                                <li>Pendaftaran atlet dan penambahan peserta baru dapat dilakukan melalui halaman histori pendaftaran kontingen.</li>
                                <li>Bila ada pertanyaan lebih lanjut, silakan hubungi kontak panitia yang tertera di atas.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer info -->
            <div class="bg-gray-950 text-neutral-400 p-6 text-center text-xs">
                <p>Terima kasih atas pembayaran Anda. Pendaftaran kontingen Anda telah kami terima.</p>
                <p class="mt-1 text-neutral-500">Invoice pembayaran otomatis #INV-K-{{ $transaction->id }} • JAWI Jawara Indonesia</p>
            </div>
        </div>
    </div>
</body>

</html>
