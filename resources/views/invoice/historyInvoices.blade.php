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
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('history') }}" class="btn btn-outline-secondary mb-2"><i class="bi bi-arrow-left"></i> Kembali ke History</a>
                <h1 class="h2 fw-bold text-dark mt-1">Riwayat Pembayaran &amp; Invoice</h1>
                <p class="text-muted mb-0">Kontingen: <strong>{{ $contingent->name }}</strong> • Event: {{ $contingent->event->name }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @php
                    $totalPlayerInvoiced = 0;
                    foreach($playerInvoices as $pi) {
                        $totalPlayerInvoiced += $pi->total_price;
                    }
                    $totalContingentInvoiced = 0;
                    if ($contingentTransaction) {
                        $totalContingentInvoiced = $contingent->event->harga_contingent;
                    }
                    $totalOverallInvoiced = $totalPlayerInvoiced + $totalContingentInvoiced;
                @endphp
                <div class="bg-light p-2.5 px-3 rounded border border-info shadow-sm">
                    <span class="d-block text-muted small" style="font-size: 0.75rem;">Total Pengeluaran Atlet</span>
                    <span class="h5 fw-bold text-primary mb-0">Rp {{ number_format($totalPlayerInvoiced) }}</span>
                </div>
                @if ($contingent->event->harga_contingent > 0)
                <div class="bg-light p-2.5 px-3 rounded border border-secondary shadow-sm">
                    <span class="d-block text-muted small" style="font-size: 0.75rem;">Total Pengeluaran Kontingen</span>
                    <span class="h5 fw-bold text-secondary mb-0">Rp {{ number_format($totalContingentInvoiced) }}</span>
                </div>
                @endif
                <div class="bg-light p-2.5 px-3 rounded border border-warning shadow-sm">
                    <span class="d-block text-muted small" style="font-size: 0.75rem;">Total Pengeluaran Keseluruhan</span>
                    <span class="h5 fw-bold text-success mb-0">Rp {{ number_format($totalOverallInvoiced) }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Bagian Invoice Pendaftaran Kontingen --}}
            @if ($contingent->event->harga_contingent > 0)
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-header bg-dark text-white py-3">
                            <h5 class="mb-0"><i class="bi bi-building"></i> Invoice Pendaftaran Kontingen</h5>
                        </div>
                        <div class="card-body p-4">
                            @if ($contingentTransaction)
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold text-primary mb-1">Invoice Registrasi Kontingen</h5>
                                        <p class="text-muted small mb-2">Invoice pendaftaran dasar untuk bergabung ke event kontingen.</p>
                                        <div class="d-flex flex-wrap gap-3 text-secondary small">
                                            <span><i class="bi bi-calendar3"></i> Tanggal: <strong>{{ \Carbon\Carbon::parse($contingentTransaction->date)->format('d M Y') }}</strong></span>
                                            <span><i class="bi bi-wallet2"></i> Status: 
                                                @if ($contingent->status == 1)
                                                    <span class="badge bg-success">Disetujui / Lunas</span>
                                                @elseif ($contingent->status == 2)
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Dalam Verifikasi</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <span class="h5 fw-bold text-success d-block mb-2">Rp {{ number_format($contingent->event->harga_contingent) }}</span>
                                        <a href="{{ route('invoice.contingent.detail', $contingent->id) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail Bukti
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4 bg-light rounded border">
                                    <p class="text-muted mb-0">Belum ada pembayaran pendaftaran kontingen yang tercatat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Bagian Invoice Pembayaran Atlet --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-people-fill"></i> Riwayat Invoice Peserta / Atlet</h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($playerInvoices->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Daftar Atlet</th>
                                            <th>Jumlah Peserta</th>
                                            <th>Total Nominal</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($playerInvoices as $invoice)
                                            <tr>
                                                <td><span class="font-monospace fw-bold text-dark">#INV-{{ $invoice->id }}</span></td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}</td>
                                                <td>
                                                    @php
                                                        $atlets = $invoice->transactionDetails->map(function($detail) {
                                                            return $detail->player->name ?? '-';
                                                        })->implode(', ');
                                                    @endphp
                                                    <span class="text-truncate d-inline-block" style="max-width: 280px;" title="{{ $atlets }}">
                                                        {{ $atlets }}
                                                    </span>
                                                </td>
                                                <td>{{ $invoice->transactionDetails->count() }} Atlet</td>
                                                <td><span class="fw-bold text-success">Rp {{ number_format($invoice->total_price) }}</span></td>
                                                <td class="text-end">
                                                    <a href="{{ route('invoice.player.detail', $invoice->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 bg-light rounded border">
                                <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada riwayat pembayaran atlet yang tercatat untuk kontingen ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection
