@extends('layouts.app')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
            <p>Kelola reservasi kesehatan Anda dengan mudah dan cepat</p>
        </div>
        <div class="col-md-4 text-end">
            <i class="fas fa-heartbeat fa-3x opacity-75"></i>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="stat-card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Reservasi Aktif</h5>
                        <p class="card-text display-4">{{ $reservasiAktif }}</p>
                        <span class="small">Dalam proses</span>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stat-card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Total Reservasi</h5>
                        <p class="card-text display-4">{{ $reservasiCount }}</p>
                        <span class="small">Semua waktu</span>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="stat-card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Aksi Cepat</h5>
                        <div class="mt-3">
                            <a href="{{ route('user.reservasis.create') }}" class="btn btn-light btn-modern">
                                <i class="fas fa-plus me-2"></i>Buat Reservasi Baru
                            </a>
                        </div>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reservasi Terbaru -->
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-history me-2"></i>Reservasi Terbaru</h5>
        <a href="{{ route('user.reservasis.index') }}" class="btn btn-primary btn-modern">
            <i class="fas fa-list me-1"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body">
        @php
            $reservasiTerbaru = auth()->user()->reservasis()
                ->with('dokter')
                ->latest()
                ->take(5)
                ->get();
        @endphp
        
        @if($reservasiTerbaru->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasiTerbaru as $reservasi)
                            <tr>
                                <td>
                                    <strong>{{ $reservasi->tanggal_reservasi->format('d/m/Y') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-clock me-1"></i>{{ $reservasi->jam_mulai }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-md text-primary me-2"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            {{ $reservasi->dokter->nama }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-modern 
                                        @if($reservasi->status == 'confirmed') bg-success
                                        @elseif($reservasi->status == 'pending') bg-warning
                                        @elseif($reservasi->status == 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        <i class="fas 
                                            @if($reservasi->status == 'confirmed') fa-check-circle
                                            @elseif($reservasi->status == 'pending') fa-clock
                                            @elseif($reservasi->status == 'cancelled') fa-times-circle
                                            @else fa-question-circle @endif me-1">
                                        </i>
                                        {{ ucfirst($reservasi->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('user.reservasis.show', $reservasi) }}" 
                                       class="btn btn-info btn-sm btn-modern">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada reservasi</h5>
                <p class="text-muted mb-4">Mulai buat reservasi pertama Anda untuk konsultasi dengan dokter</p>
                <a href="{{ route('user.reservasis.create') }}" class="btn btn-primary btn-modern">
                    <i class="fas fa-plus me-2"></i>Buat Reservasi Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-card">
    <div class="card-header">
        <h5><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('user.reservasis.create') }}" class="quick-action-btn">
                    <i class="fas fa-calendar-plus"></i>
                    <div class="fw-medium text-dark">Buat Reservasi</div>
                    <small class="text-muted">Jadwalkan konsultasi baru</small>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('user.reservasis.index') }}" class="quick-action-btn">
                    <i class="fas fa-list"></i>
                    <div class="fw-medium text-dark">Reservasi Saya</div>
                    <small class="text-muted">Lihat semua reservasi</small>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('user.reservasis.index') }}?status=pending" class="quick-action-btn">
                    <i class="fas fa-clock"></i>
                    <div class="fw-medium text-dark">Menunggu Konfirmasi</div>
                    <small class="text-muted">Periksa status reservasi</small>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection