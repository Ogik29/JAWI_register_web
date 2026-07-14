@extends('main')

@section('content')

    <nav class="navbar navbar-expand-lg navbar-dark p-0">
        <div class="container-fluid bg-dark">
            <a class="navbar-brand" href="/">
                <div class="d-flex flex-column container">
                    <h1 class="text-danger m-0"><b>JAWI</b></h1>
                    <span><b>Jawara Indonesia</b></span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-lg-5 mx-2"><a class="hover-underline nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item mx-lg-5 mx-2"><a class="nav-link hover-underline" href="/#about">About</a></li>
                    <li class="nav-item mx-lg-5 mx-2"><a class="nav-link hover-underline" href="{{ url('/event') }}">Event</a></li>
                    @auth    
                        <li class="nav-item mx-lg-5 mx-2">
                            <a class="nav-link hover-underline" href="{{ url('/datapeserta') }}">Data Peserta</a>
                        </li>
                    @endauth
                </ul>
                @guest
                    <form class="d-flex"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><img src="{{ asset('assets') }}/img/icon/logo-profile.png" alt="Login" style="width: 25px"></a></form>
                @endguest
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets') }}/img/icon/logo-profile.png" alt="{{ Auth::user()->nama_lengkap }}" style="width: 25px">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><h6 class="dropdown-header">Hy, {{ Auth::user()->nama_lengkap }}</h6></li>
                            @if (Auth::user()->role_id == 3)
                                <li><a class="dropdown-item" href="{{ route('user.edit.manager', Auth::user()->id) }}">Edit Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('history') }}">History</a></li>
                            @elseif (Auth::user()->role_id == 2)
                                <li><a class="dropdown-item" href="{{ route('adminIndex') }}">Admin</a></li>
                            @else
                                <li><a class="dropdown-item" href="/superadmin">Super Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Histori Pendaftaran Kontingen</h1>
                
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @forelse ($contingents as $contingent)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-3 mb-md-0">
                                    <h4 class="card-title fw-bold">{{ $contingent->name }}</h4>
                                    <p class="card-text text-muted mb-1"><i class="bi bi-calendar-event"></i> Event: <strong>{{ $contingent->event->name ?? 'N/A' }}</strong></p>
                                    <p class="card-text text-muted"><i class="bi bi-person-badge"></i> Manajer: {{ $contingent->manajer_name }}</p>
                                </div>
                                <div class="col-md-2 col-6 text-center">
                                    <span id="contingent-status-badge-{{ $contingent->id }}">
                                        @if ($contingent->status == 1)
                                            <span class="badge bg-success p-2">Disetujui</span>
                                        @elseif ($contingent->status == 2)
                                            <span class="badge bg-danger p-2">Ditolak</span>
                                            @if(!empty($contingent->catatan))
                                                <button class="btn btn-link btn-sm p-0 ms-1" data-bs-toggle="modal" data-bs-target="#noteContingentModal-{{ $contingent->id }}"><i class="bi bi-info-circle-fill"></i></button>
                                            @endif
                                        @elseif ($contingent->status == 3)
                                            <span class="badge bg-secondary text-light p-2">Menunggu Verifikasi Tahap 2</span>
                                        @else
                                            <span class="badge bg-warning text-dark p-2">Menunggu Verifikasi Tahap 1</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="col-md-4 col-6 text-end">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        @if ($contingent->players->where('status', 0)->count() > 0)
                                            <a href="{{ route('invoice.show', $contingent->id) }}" class="btn btn-info">Invoice Peserta</a>
                                        @endif

                                        @php
                                            // Ambil transaksi pertama untuk kontingen ini
                                            $transaction = $contingent->transactions->first();
                                        @endphp
                                        @if (($contingent->status == 0 || $contingent->status == 3) && $contingent->event->harga_contingent > 0 && (!$transaction || !$transaction->foto_invoice))
                                            <a href="{{ route('invoiceContingent.show', $contingent->id) }}" class="btn btn-info">Invoice Kontingen</a>
                                        @endif

                                        @if ($contingent->status == 0 || $contingent->status == 2 || $contingent->status == 3)
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editContingentModal-{{ $contingent->id }}">Edit</button>
                                        @endif
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailContingentModal-{{ $contingent->id }}">Lihat Detail</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Detail Kontingen --}}
                    <div class="modal fade" id="detailContingentModal-{{ $contingent->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Detail Kontingen: {{ $contingent->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Informasi Kontingen</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Manajer:</strong> {{ $contingent->manajer_name }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $contingent->email ?? '-' }}</li>
                                                <li class="list-group-item"><strong>No. Telp:</strong> {{ $contingent->no_telp ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Atlet:</strong> {{ $contingent->players->count() }} Orang</li>
                                                <li class="list-group-item"><strong>Status:</strong>
                                                    @if ($contingent->status == 1) <span class="badge bg-success">Aktif</span>
                                                    @elseif ($contingent->status == 2) <span class="badge bg-danger">Ditolak</span>
                                                    @else <span class="badge bg-warning text-dark">Pending</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6 mt-4 mt-md-0">
                                             <h5>Informasi Event</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Event:</strong> {{ $contingent->event->name ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Lokasi:</strong> {{ $contingent->event->lokasi ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($contingent->event->tgl_mulai_tanding)->format('d M Y') }}</li>
                                            </ul>
                                            <h5 class="mt-3">Pemilik Akun</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Nama:</strong> {{ $contingent->user->nama_lengkap ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $contingent->user->email ?? '-' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Daftar Peserta</h5>
                                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                                            @if ($contingent->status == 1 && $contingent->players->where('status', 1)->count() == 0)
                                                <a href="{{ route('peserta.event', $contingent->id) }}" class="btn btn-info"><i class="bi bi-plus-circle"></i> Tambah Peserta</a>
                                            @elseif ($contingent->status == 1)
                                                <div class="text-end">
                                                    <button class="btn btn-info" disabled title="Selesaikan verifikasi atlet yang ada terlebih dahulu.">
                                                        <i class="bi bi-plus-circle"></i> Tambah Peserta
                                                    </button>
                                                    <small class="d-block text-muted mt-1">Tunggu sampai atlet yang sebelumnya ditambah tidak pending</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover align-middle">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Kelas</th>
                                                    <th>Invoice</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($contingent->displayPlayers as $registration)
                                                    @php
                                                        $playersInRegistration = $registration['player_instances'];
                                                        $canBeModified = in_array($registration['status'], [0, 1, 3]);
                                                        $invoice = $registration['invoice'];
                                                    @endphp
                                                    <tr>
                                                        <th>{{ $loop->iteration }}</th>
                                                        <td>{{ $registration['player_names'] }}</td>
                                                        <td>{{ $registration['nama_kelas'] }} ({{ $registration['gender'] }})</td>
                                                        <td>
                                                            @if ($invoice)
                                                                <span class="font-monospace text-xs fw-bold text-dark">#INV-{{ $invoice->id }}</span>
                                                            @else
                                                                <span class="text-muted small italic">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($registration['status'] == 1) <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif ($registration['status'] == 2) <span class="badge bg-success">Terverifikasi</span>
                                                            @elseif ($registration['status'] == 0) <span class="badge bg-secondary">Belum Bayar</span>
                                                            @else <span class="badge bg-danger text-light">Ditolak</span>
                                                            @endif
                                                            
                                                            @if ($registration['rejected_players']->isNotEmpty())
                                                                @php
                                                                    $firstRejectedPlayer = $registration['rejected_players']->first();
                                                                @endphp
                                                                @if ($firstRejectedPlayer)
                                                                    <button class="btn btn-link btn-sm p-0 ms-1" data-bs-toggle="modal" data-bs-target="#notePlayerModal-{{ $firstRejectedPlayer->id }}" title="Lihat catatan penolakan">
                                                                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 1.1rem;"></i>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <div class="d-flex flex-column gap-2">
                                                                 @php
                                                                     $verifiedPlayers = $playersInRegistration->where('status', 2);
                                                                 @endphp

                                                                 @if ($verifiedPlayers->count() == 1)
                                                                     @php $p = $verifiedPlayers->first(); @endphp
                                                                     <div class="mb-1">
                                                                         <a href="{{ route('player.print.card', $p->id) }}" target="_blank" class="btn btn-info btn-sm text-white text-decoration-none" title="Cetak Kartu {{ $p->name }}">
                                                                             <i class="bi bi-printer"></i> Cetak Kartu
                                                                         </a>
                                                                     </div>
                                                                 @elseif ($verifiedPlayers->count() > 1)
                                                                     <div class="mb-1">
                                                                         <div class="dropdown">
                                                                             <button class="btn btn-info btn-sm dropdown-toggle text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                                                                 <i class="bi bi-printer"></i> Cetak Kartu ({{ $verifiedPlayers->count() }})
                                                                             </button>
                                                                             <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                                                 <li class="dropdown-header text-xs text-uppercase fw-bold text-muted">Pilih Peserta</li>
                                                                                 @foreach ($verifiedPlayers as $p)
                                                                                     <li>
                                                                                         <a class="dropdown-item small py-1" href="{{ route('player.print.card', $p->id) }}" target="_blank">
                                                                                             <i class="bi bi-person-fill me-1"></i> {{ $p->name }}
                                                                                         </a>
                                                                                     </li>
                                                                                 @endforeach
                                                                             </ul>
                                                                         </div>
                                                                     </div>
                                                                 @endif

                                                                @if ($canBeModified)
                                                                    @if ($invoice)
                                                                        {{-- HANYA ADA 1 TOMBOL EDIT PER INVOICE --}}
                                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPlayerInvoiceModal-{{ $invoice->id }}">
                                                                            <i class="bi bi-pencil-square"></i> Edit
                                                                        </button>
                                                                    @else
                                                                        {{-- HANYA ADA 1 TOMBOL EDIT PER GRUP TIM TANPA INVOICE --}}
                                                                        @php
                                                                            $groupUniqueId = $contingent->id . '-' . $loop->index;
                                                                        @endphp
                                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPlayerGroupModal-{{ $groupUniqueId }}">
                                                                            <i class="bi bi-pencil-square"></i> Edit
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            </div>

                                                            {{-- Modal Edit Invoice Peserta --}}
                                                            @if ($invoice)
                                                                <div class="modal fade" id="editPlayerInvoiceModal-{{ $invoice->id }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                                                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content border-0 shadow-lg text-start">
                                                                            <div class="modal-header bg-dark text-white">
                                                                                <h5 class="modal-title fw-bold"><i class="bi bi-receipt"></i> Edit Pembayaran &amp; Data Atlet #INV-{{ $invoice->id }}</h5>
                                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <form action="{{ route('invoice.player.reupload', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                                                    {{-- Bagian 1: Upload Bukti Transfer --}}
                                                                                    <div class="bg-light p-3 rounded border mb-4">
                                                                                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-credit-card-2-front"></i> Bukti Transfer Invoice</h6>
                                                                                        <p class="text-muted small">Perbarui bukti transfer jika sebelumnya salah atau ditolak oleh admin.</p>
                                                                                        <div class="mb-3">
                                                                                            @if ($invoice->foto_invoice)
                                                                                                <div class="mb-2">
                                                                                                    <a href="{{ Storage::url($invoice->foto_invoice) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i> Lihat Bukti Bayar Saat Ini</a>
                                                                                                </div>
                                                                                            @endif
                                                                                            <label for="foto_invoice_player-{{ $invoice->id }}" class="form-label fw-bold text-dark">Upload Bukti Transfer Baru</label>
                                                                                            <input type="file" class="form-control" name="foto_invoice" id="foto_invoice_player-{{ $invoice->id }}">
                                                                                            <small class="text-muted form-text d-block mt-1">Format: JPG, JPEG, PNG, PDF. Maksimal 5MB.</small>
                                                                                        </div>
                                                                                    </div>

                                                                                    {{-- Bagian 2: Edit Data Atlet --}}
                                                                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill"></i> Data Diri Atlet</h6>
                                                                                    <p class="text-muted small mb-3">Sesuaikan kolom-kolom di bawah jika terdapat kesalahan pada data diri atlet (seperti Nama, NIK, foto KTP, dll.):</p>
                                                                                    
                                                                                    @php
                                                                                        $lastClassId = null;
                                                                                    @endphp
                                                                                    @foreach ($playersInRegistration as $player)
                                                                                        @php
                                                                                            $currentClassId = $player->kelas_pertandingan_id;
                                                                                            $showDivider = ($loop->index > 0 && $lastClassId !== $currentClassId);
                                                                                            $lastClassId = $currentClassId;
                                                                                        @endphp
                                                                                        @if ($showDivider)
                                                                                            <div class="my-4 d-flex align-items-center">
                                                                                                <div class="flex-grow-1 border-top border-2 border-dashed border-danger"></div>
                                                                                                <span class="mx-3 text-xs text-danger fw-bold text-uppercase bg-white px-2" style="font-size: 11px; letter-spacing: 0.5px;">Kelas Tanding</span>
                                                                                                <div class="flex-grow-1 border-top border-2 border-dashed border-danger"></div>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                                                                                            <div class="border-b pb-2 mb-2 d-flex justify-content-between align-items-center">
                                                                                                <span class="fw-bold text-primary">{{ $player->name }}</span>
                                                                                                <span class="badge bg-secondary">{{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}</span>
                                                                                            </div>
                                                                                            
                                                                                            <!-- Rincian Kelas & Kategori Pemain -->
                                                                                            <div class="bg-light p-2 rounded border mb-3" style="font-size: 11px;">
                                                                                                <div class="row g-2 text-center text-md-start">
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Kategori:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->kategoriPertandingan->nama_kategori ?? 'N/A' }}</span></div>
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Jenis/Tipe:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->jenisPertandingan->nama_jenis ?? 'N/A' }}</span></div>
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Kelas:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}</span></div>
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Rentang Usia:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->kelas->rentangUsia->rentang_usia ?? 'N/A' }}</span></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="row g-3">
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">Nama Atlet</label>
                                                                                                    <input type="text" class="form-control form-control-sm" name="players[{{ $player->id }}][name]" value="{{ $player->name }}" required>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">NIK (16 Digit)</label>
                                                                                                    <input type="text" class="form-control form-control-sm font-monospace" name="players[{{ $player->id }}][nik]" value="{{ $player->nik }}" minlength="16" maxlength="16" required>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">Jenis Kelamin</label>
                                                                                                    <select class="form-select form-select-sm" name="players[{{ $player->id }}][gender]" required>
                                                                                                        <option value="Laki-laki" {{ $player->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                                                                        <option value="Perempuan" {{ $player->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">Tanggal Lahir</label>
                                                                                                    <input type="date" class="form-control form-control-sm" name="players[{{ $player->id }}][tgl_lahir]" value="{{ $player->tgl_lahir }}" required>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="row g-2 mt-2 pt-2 border-top">
                                                                                                <div class="col-md-4">
                                                                                                    <label class="form-label text-xs fw-bold">Foto KTP / KIA</label>
                                                                                                    @if ($player->foto_ktp)
                                                                                                        <div class="mb-1"><a href="{{ Storage::url($player->foto_ktp) }}" target="_blank" class="text-xs text-blue-600"><i class="bi bi-image"></i> Lihat KTP</a></div>
                                                                                                    @endif
                                                                                                    <input type="file" class="form-control form-control-sm" name="players[{{ $player->id }}][foto_ktp]">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label class="form-label text-xs fw-bold">Foto Diri</label>
                                                                                                    @if ($player->foto_diri)
                                                                                                        <div class="mb-1"><a href="{{ Storage::url($player->foto_diri) }}" target="_blank" class="text-xs text-blue-600"><i class="bi bi-image"></i> Lihat Foto Diri</a></div>
                                                                                                    @endif
                                                                                                    <input type="file" class="form-control form-control-sm" name="players[{{ $player->id }}][foto_diri]">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label class="form-label text-xs fw-bold">Izin Ortu (Opsional)</label>
                                                                                                    @if ($player->foto_persetujuan_ortu)
                                                                                                        <div class="mb-1"><a href="{{ Storage::url($player->foto_persetujuan_ortu) }}" target="_blank" class="text-xs text-blue-600"><i class="bi bi-file-earmark-pdf"></i> Lihat Izin</a></div>
                                                                                                    @endif
                                                                                                    <input type="file" class="form-control form-control-sm" name="players[{{ $player->id }}][foto_persetujuan_ortu]">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                                <div class="modal-footer bg-light">
                                                                                    <button type="button" class="btn btn-secondary text-dark" data-bs-dismiss="modal">Batal</button>
                                                                                    <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-check-circle"></i> Simpan &amp; Kirim Pembaruan</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            {{-- Modal Edit Group Atlet Tanpa Invoice --}}
                                                            @if (!$invoice)
                                                                @php
                                                                    $groupUniqueId = $contingent->id . '-' . $loop->index;
                                                                @endphp
                                                                <div class="modal fade" id="editPlayerGroupModal-{{ $groupUniqueId }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                                                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                                        <div class="modal-content border-0 shadow-lg text-start">
                                                                            <div class="modal-header bg-dark text-white">
                                                                                <h5 class="modal-title fw-bold"><i class="bi bi-people-fill"></i> Edit Data Atlet - {{ $registration['nama_kelas'] }}</h5>
                                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <form action="{{ route('player.group.update') }}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                                                    
                                                                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill"></i> Data Diri Atlet</h6>
                                                                                    <p class="text-muted small mb-3">Sesuaikan kolom-kolom di bawah jika terdapat kesalahan pada data diri atlet (seperti Nama, NIK, foto KTP, dll.):</p>
                                                                                    
                                                                                    @php
                                                                                        $lastClassId = null;
                                                                                    @endphp
                                                                                    @foreach ($playersInRegistration as $player)
                                                                                        @php
                                                                                            $currentClassId = $player->kelas_pertandingan_id;
                                                                                            $showDivider = ($loop->index > 0 && $lastClassId !== $currentClassId);
                                                                                            $lastClassId = $currentClassId;
                                                                                        @endphp
                                                                                        @if ($showDivider)
                                                                                            <div class="my-4 d-flex align-items-center">
                                                                                                <div class="flex-grow-1 border-top border-2 border-dashed border-danger"></div>
                                                                                                <span class="mx-3 text-xs text-danger fw-bold text-uppercase bg-white px-2" style="font-size: 11px; letter-spacing: 0.5px;">Kelas Tanding Berbeda</span>
                                                                                                <div class="flex-grow-1 border-top border-2 border-dashed border-danger"></div>
                                                                                            </div>
                                                                                        @endif
                                                                                        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                                                                                            <div class="border-b pb-2 mb-2 d-flex justify-content-between align-items-center">
                                                                                                <span class="fw-bold text-primary">{{ $player->name }}</span>
                                                                                                <span class="badge bg-secondary">{{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}</span>
                                                                                            </div>
                                                                                            
                                                                                            <!-- Rincian Kelas & Kategori Pemain -->
                                                                                            <div class="bg-light p-2 rounded border mb-3" style="font-size: 11px;">
                                                                                                <div class="row g-2 text-center text-md-start">
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Kategori:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->kategoriPertandingan->nama_kategori ?? 'N/A' }}</span></div>
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Jenis/Tipe:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->jenisPertandingan->nama_jenis ?? 'N/A' }}</span></div>
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Kelas:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->kelas->nama_kelas ?? 'N/A' }}</span></div>
                                                                                                    <div class="col-6 col-md-3"><span class="text-muted d-block">Rentang Usia:</span> <span class="fw-bold text-dark">{{ $player->kelasPertandingan->kelas->rentangUsia->rentang_usia ?? 'N/A' }}</span></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="row g-3">
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">Nama Atlet</label>
                                                                                                    <input type="text" class="form-control form-control-sm" name="players[{{ $player->id }}][name]" value="{{ $player->name }}" required>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">NIK (16 Digit)</label>
                                                                                                    <input type="text" class="form-control form-control-sm font-monospace" name="players[{{ $player->id }}][nik]" value="{{ $player->nik }}" minlength="16" maxlength="16" required>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">Jenis Kelamin</label>
                                                                                                    <select class="form-select form-select-sm" name="players[{{ $player->id }}][gender]" required>
                                                                                                        <option value="Laki-laki" {{ $player->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                                                                        <option value="Perempuan" {{ $player->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <label class="form-label text-xs fw-bold">Tanggal Lahir</label>
                                                                                                    <input type="date" class="form-control form-control-sm" name="players[{{ $player->id }}][tgl_lahir]" value="{{ $player->tgl_lahir }}" required>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="row g-2 mt-2 pt-2 border-top">
                                                                                                <div class="col-md-4">
                                                                                                    <label class="form-label text-xs fw-bold">Foto KTP / KIA</label>
                                                                                                    @if ($player->foto_ktp)
                                                                                                        <div class="mb-1"><a href="{{ Storage::url($player->foto_ktp) }}" target="_blank" class="text-xs text-blue-600"><i class="bi bi-image"></i> Lihat KTP</a></div>
                                                                                                    @endif
                                                                                                    <input type="file" class="form-control form-control-sm" name="players[{{ $player->id }}][foto_ktp]">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label class="form-label text-xs fw-bold">Foto Diri</label>
                                                                                                    @if ($player->foto_diri)
                                                                                                        <div class="mb-1"><a href="{{ Storage::url($player->foto_diri) }}" target="_blank" class="text-xs text-blue-600"><i class="bi bi-image"></i> Lihat Foto Diri</a></div>
                                                                                                    @endif
                                                                                                    <input type="file" class="form-control form-control-sm" name="players[{{ $player->id }}][foto_diri]">
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <label class="form-label text-xs fw-bold">Izin Ortu (Opsional)</label>
                                                                                                    @if ($player->foto_persetujuan_ortu)
                                                                                                        <div class="mb-1"><a href="{{ Storage::url($player->foto_persetujuan_ortu) }}" target="_blank" class="text-xs text-blue-600"><i class="bi bi-file-earmark-pdf"></i> Lihat Izin</a></div>
                                                                                                    @endif
                                                                                                    <input type="file" class="form-control form-control-sm" name="players[{{ $player->id }}][foto_persetujuan_ortu]">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="mt-2 text-end">
                                                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="if(confirm('Hapus peserta {{ $player->name }}?')) { document.getElementById('delete-player-form-{{ $player->id }}').submit(); }">
                                                                                                    <i class="bi bi-trash"></i> Hapus Peserta Ini
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                                <div class="modal-footer bg-light">
                                                                                    @if ($playersInRegistration->count() > 1)
                                                                                        <button type="button" class="btn btn-danger me-auto" onclick="if(confirm('Hapus seluruh tim?')) { document.getElementById('delete-team-form-{{ $groupUniqueId }}').submit(); }">
                                                                                            <i class="bi bi-trash"></i> Hapus Seluruh Tim
                                                                                        </button>
                                                                                    @endif
                                                                                    <button type="button" class="btn btn-secondary text-dark" data-bs-dismiss="modal">Batal</button>
                                                                                    <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-check-circle"></i> Simpan &amp; Kirim Pembaruan</button>
                                                                                </div>
                                                                            </form>
                                                                            
                                                                            {{-- Forms hapus tersembunyi --}}
                                                                            @foreach ($playersInRegistration as $player)
                                                                                <form id="delete-player-form-{{ $player->id }}" action="{{ route('player.destroy', $player->id) }}" method="POST" style="display: none;">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                </form>
                                                                            @endforeach

                                                                            @if ($playersInRegistration->count() > 1)
                                                                                <form id="delete-team-form-{{ $groupUniqueId }}" action="{{ route('registration.destroy') }}" method="POST" style="display: none;">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    @foreach ($playersInRegistration as $player)
                                                                                        <input type="hidden" name="player_ids[]" value="{{ $player->id }}">
                                                                                    @endforeach
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6" class="text-center">Belum ada peserta.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                     <a href="{{ route('contingent.invoices', $contingent->id) }}" class="btn btn-warning me-auto"><i class="bi bi-receipt"></i> Riwayat Invoice</a>
                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                 </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Edit Kontingen --}}
                    <div class="modal fade" id="editContingentModal-{{ $contingent->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Edit Data Kontingen</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                <form action="{{ route('contingent.update', $contingent->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @if ($contingent->status == 2)<div class="alert alert-warning" role="alert">Mengubah data akan mengubah status kontingen dari 'Ditolak' menjadi 'Menunggu Verifikasi'.</div>@endif
                                        <div class="mb-3"><label for="name-{{ $contingent->id }}" class="form-label">Nama Kontingen</label><input type="text" class="form-control" name="name" id="name-{{ $contingent->id }}" value="{{ $contingent->name }}" required></div><hr>
                                        <div class="mb-3">
                                            @if ($contingent->surat_rekomendasi)
                                                <div class="mb-2"><a href="{{ Storage::url($contingent->surat_rekomendasi) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Lihat Surat Saat Ini</a></div>
                                                <label for="surat_rekomendasi-{{ $contingent->id }}" class="form-label">Surat Rekomendasi</label>
                                                <input type="file" class="form-control" name="surat_rekomendasi" id="surat_rekomendasi-{{ $contingent->id }}"><small class="form-text text-muted">Unggah file baru untuk mengganti yang lama.</small>
                                            @endif
                                        </div>
                                        @php
                                            // Ambil transaksi pertama untuk kontingen ini
                                            $transaction = $contingent->transactions->first();
                                        @endphp
                                        @if (($contingent->status == 3 || $contingent->status == 2) && ($contingent->event->harga_contingent > 0 && $transaction->foto_invoice))
                                            <div class="mb-3">
                                                <label for="foto_invoice-{{ $contingent->id }}" class="form-label">Bukti Bayar Kontingen</label>
                                                @php $transaction = $contingent->transactions->first(); @endphp
                                                @if ($transaction && $transaction->foto_invoice)
                                                    <div class="mb-2"><a href="{{ Storage::url($transaction->foto_invoice) }}" target="_blank" class="btn btn-outline-secondary btn-sm">Lihat Bukti Bayar Saat Ini</a></div>
                                                @endif
                                                <input type="file" class="form-control" name="foto_invoice" id="foto_invoice-{{ $contingent->id }}"><small class="form-text text-muted">Unggah file baru untuk mengganti yang lama.</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal untuk Upload Ulang Invoice Peserta --}}
                    <div class="modal fade" id="uploadInvoiceModal-{{ $contingent->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload Ulang Bukti Bayar Peserta</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="contingent_id" value="{{ $contingent->id }}">
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            Halaman ini untuk melakukan pembayaran ulang. Sistem akan otomatis menyertakan semua peserta dengan status "Ditolak" dan "Belum Bayar" dalam invoice baru ini.
                                        </div>
                                        <p><strong>Peserta yang akan diproses:</strong></p>
                                        <ul>
                                            @foreach($contingent->players->whereIn('status', [0, 3]) as $player)
                                                <li>{{ $player->name }} (Status: {{ $player->status == 0 ? 'Belum Bayar' : 'Ditolak' }})</li>
                                            @endforeach
                                        </ul>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="foto_invoice_{{ $contingent->id }}" class="form-label">Upload File Bukti Bayar Baru</label>
                                            <input type="file" class="form-control" name="foto_invoice" id="foto_invoice_{{ $contingent->id }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Kirim Bukti Bayar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modals for Notes --}}
                    @if ($contingent->status == 2 && !empty($contingent->catatan))
                        <div class="modal fade" id="noteContingentModal-{{ $contingent->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Catatan Penolakan Kontingen</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                    <div class="modal-body"><p>{{ $contingent->catatan }}</p></div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @foreach($contingent->players as $player)
                        @if ($player->status == 3 && !empty($player->catatan))
                            <div class="modal fade" id="notePlayerModal-{{ $player->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Catatan Penolakan: {{ $player->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                                        <div class="modal-body"><p>{{ $player->catatan }}</p></div>
                                        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                @empty
                    <div class="alert alert-info text-center">Anda belum pernah mendaftarkan kontingen.</div>
                @endforelse
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-5">
       <div class="container">
            <div class="row justify-content-between g-4">
                <div class="col-lg-4 col-md-6 text-center text-md-start"><div class="h4 fw-bold text-danger mb-3">Jawara Indonesia</div><p class="text-muted">We look forward to working with you.</p></div>
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <h4 class="h6 fw-semibold mb-3">Menu Utama</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#about" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#team" class="text-muted text-decoration-none">Our Team</a></li>
                        <li class="mb-2"><a href="#contact" class="text-muted text-decoration-none">Event</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <h4 class="h6 fw-semibold mb-3">Hubungi Kami</h4>
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                        <a href="https://www.instagram.com/jawaraindonesia.co.id?igsh=cDVqZTJkNGcxeDRv" class="social-icon text-white text-decoration-none fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="mailto:jawaraindonesiam@gmail.com" class="social-icon text-white text-decoration-none fs-4"><i class="bi bi-envelope"></i></a>
                        <a href="https://maps.app.goo.gl/yNrmtc3NSemCFCBs9" class="social-icon text-white text-decoration-none fs-4" target="_blank"><i class="bi bi-house"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-muted"><p class="mb-0">&copy; 2025 Jawara Indonesia. All rights reserved.</p></div>
        </div>
    </footer>

@endsection