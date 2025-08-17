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
                    <li class="nav-item mx-lg-5 mx-2">
                        <a class=" hover-underline nav-link " aria-current="page" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item mx-lg-5 mx-2">
                        <a class="nav-link hover-underline" href="/#about">About</a>
                    </li>
                    <li class="nav-item mx-lg-5 mx-2">
                        <a class="nav-link hover-underline" href="{{ url('/event') }}">Event</a>
                    </li>
                </ul>
                @guest
                    <form class="d-flex">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" ><img src="{{ asset('assets') }}/img/icon/logo-profile.png"
                        alt="Login" style="width: 25px"></a>
                    </form>
                @endguest
                @auth
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('assets') }}/img/icon/logo-profile.png" alt="{{ Auth::user()->nama_lengkap }}" style="width: 25px">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><h6 class="dropdown-header">Hy, {{ Auth::user()->nama_lengkap }}</h6></li>
                            @if (Auth::user()->role_id == 3)
                                <li><a class="dropdown-item" href="{{ route('history') }}">History</a></li>
                            @elseif (Auth::user()->role_id == 2)
                                <li><a class="dropdown-item" href="{{ route('adminIndex') }}">Admin</a></li>
                            @else
                                <li><a class="dropdown-item" href="/superadmin">Super Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout"> Logout</a></li>
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

                @forelse ($contingents as $contingent)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-3 mb-md-0">
                                    <h4 class="card-title fw-bold" id="contingent-name-{{ $contingent->id }}">{{ $contingent->name }}</h4>
                                    <p class="card-text text-muted mb-1"><i class="bi bi-calendar-event"></i> Event: <strong>{{ $contingent->event->name ?? 'N/A' }}</strong></p>
                                    <p class="card-text text-muted"><i class="bi bi-person-badge"></i> Manajer: {{ $contingent->manajer_name }}</p>
                                </div>
                                <div class="col-md-2 col-6 text-center">
                                    <span id="contingent-status-badge-{{ $contingent->id }}">
                                        @if ($contingent->status == 1)
                                            <span class="badge bg-success p-2">Disetujui</span>
                                        @elseif ($contingent->status == 2)
                                            <span class="badge bg-danger p-2">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark p-2">Menunggu Verifikasi</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="col-md-4 col-6 text-end">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        @if ($contingent->players->where('status', 0)->count() > 0)
                                            <a href="{{ route('invoice.show', $contingent->id) }}" class="btn btn-info">Invoice Pelunasan</a>
                                        @endif
                                        @if ($contingent->status == 0 || $contingent->status == 2)
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editContingentModal-{{ $contingent->id }}">
                                                Edit
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailContingentModal-{{ $contingent->id }}">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detailContingentModal-{{ $contingent->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Kontingen: {{ $contingent->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Informasi Kontingen</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Nama Manajer:</strong> {{ $contingent->manajer_name }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $contingent->email ?? '-' }}</li>
                                                <li class="list-group-item"><strong>No. Telepon:</strong> {{ $contingent->no_telp ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Jumlah Atlet:</strong> {{ $contingent->players->count() }} Orang</li>
                                                <li class="list-group-item"><strong>Status:</strong>
                                                    @if ($contingent->status == 1) <span class="badge bg-success">Aktif</span>
                                                    @elseif ($contingent->status == 2) <span class="badge bg-danger">Ditolak</span>
                                                    @else <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6 mt-4 mt-md-0">
                                            <h5>Informasi Event</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Nama Event:</strong> {{ $contingent->event->name ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Lokasi:</strong> {{ $contingent->event->lokasi ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($contingent->event->tgl_mulai_tanding)->format('d M Y') }}</li>
                                            </ul>
                                            <h5 class="mt-3">Informasi Pemilik Akun</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Nama:</strong> {{ $contingent->user->nama_lengkap ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Email Akun:</strong> {{ $contingent->user->email ?? '-' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Daftar Peserta</h5>
                                        @if ($contingent->status != 1)
                                            <a href="{{ route('peserta.event', $contingent->id) }}" class="btn btn-info">
                                                <i class="bi bi-plus-circle"></i> Tambah Peserta
                                            </a>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Gender</th>
                                                    <th>Kelas</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($contingent->players as $player)
                                                    <tr>
                                                        <th>{{ $loop->iteration }}</th>
                                                        <td>{{ $player->name }}</td>
                                                        <td>{{ $player->gender }}</td>
                                                        <td>{{ $player->kelasPertandingan->nama_kelas ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($player->status == 1) <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif ($player->status == 2) <span class="badge bg-success">Terverifikasi</span>
                                                            @elseif ($player->status == 0) <span class="badge bg-secondary">Belum Bayar</span>
                                                            @else <span class="badge bg-danger text-light">Ditolak</span>
                                                            @endif
                                                        </td>
                                                        {{-- PERBAIKAN: Gunakan d-flex agar tombol tidak turun ke bawah --}}
                                                        <td class="d-flex flex-wrap gap-2">
                                                            @if ($player->status == 0 || $player->status == 1 || $player->status == 3)
                                                                <a href="{{ route('player.edit', $player->id) }}" class="btn btn-success btn-sm">
                                                                    <i class="bi bi-pencil-square"></i> Edit
                                                                </a>
                                                            @endif
                                                                
                                                            @if ($player->status == 0)    
                                                                <form action="{{ route('player.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                                        <i class="bi bi-trash"></i> Hapus
                                                                    </button>
                                                                </form>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editContingentModal-{{ $contingent->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Nama Kontingen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('contingent.update', $contingent->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @if ($contingent->status == 2)
                                        <div class="alert alert-warning" role="alert">
                                            Mengubah nama akan mengubah status kontingen dari 'Ditolak' menjadi 'Menunggu Verifikasi'.
                                        </div>
                                        @endif
                                        <div class="mb-3">
                                            <label for="name-{{ $contingent->id }}" class="form-label">Nama Kontingen</label>
                                            <input type="text" class="form-control" name="name" id="name-{{ $contingent->id }}" value="{{ $contingent->name }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">Anda belum pernah mendaftarkan kontingen.</div>
                @endforelse
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row justify-content-between g-4">
                {{-- PERUBAHAN: Bagian footer Anda sudah responsif, tidak ada perubahan signifikan yang diperlukan --}}
                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <div class="h4 fw-bold text-danger mb-3">Jawara Indonesia</div>
                    <p class="text-muted">We look forward to working with you.</p>
                </div>
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
                        <a href="https://www.instagram.com/jawaraindonesia.co.id?igsh=cDVqZTJkNGcxeDRv" class="social-icon text-white text-decoration-none fs-4">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="mailto:jawaraindonesiam@gmail.com" class="social-icon text-white text-decoration-none fs-4">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <a href="https://maps.app.goo.gl/yNrmtc3NSemCFCBs9" class="social-icon text-white text-decoration-none fs-4" target="_blank">
                            <i class="bi bi-house"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; 2025 Jawara Indonesia. All rights reserved.</p>
            </div>
        </div>
    </footer>

@endsection