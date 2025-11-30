@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="stat-card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Total Dokter</h5>
                        <p class="card-text display-4">{{ $dokterCount }}</p>
                        <a href="{{ route('admin.dokters.index') }}" class="text-white text-decoration-none small">
                            Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Total Reservasi</h5>
                        <p class="card-text display-4">{{ $reservasiCount }}</p>
                        <a href="{{ route('admin.reservasis.index') }}" class="text-white text-decoration-none small">
                            Lihat detail <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Reservasi Hari Ini</h5>
                        <p class="card-text display-4">{{ $reservasiHariIni }}</p>
                        <span class="small">Update terbaru</span>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">Menunggu Konfirmasi</h5>
                        <p class="card-text display-4">{{ $reservasiPending }}</p>
                        <span class="small">Perlu tindakan</span>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-list me-2"></i> Reservasi Terbaru</h6>
                <a href="{{ route('admin.reservasis.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($reservasiTerbaru->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($reservasiTerbaru as $reservasi)
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $reservasi->user->name }}</h6>
                                    <small class="text-muted">{{ $reservasi->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2 text-muted">
                                    <strong>Dokter:</strong> {{ $reservasi->dokter->nama }}<br>
                                    <strong>Tanggal:</strong> {{ $reservasi->tanggal_reservasi->format('d/m/Y') }}<br>
                                    <strong>Jam:</strong> {{ $reservasi->jam_mulai }}
                                </p>
                                <small>
                                    <span class="badge stat-badge bg-{{ $reservasi->status == 'confirmed' ? 'success' : ($reservasi->status == 'pending' ? 'warning' : ($reservasi->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($reservasi->status) }}
                                    </span>
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada reservasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-user-md me-2"></i> Dokter Aktif</h6>
                <a href="{{ route('admin.dokters.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($doktersAktif->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($doktersAktif as $dokter)
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $dokter->nama }}</h6>
                                    <span class="badge stat-badge bg-primary">{{ $dokter->reservasis_count }} reservasi hari ini</span>
                                </div>
                                <p class="mb-2 text-muted">{{ $dokter->spesialisasi }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i> {{ $dokter->no_telepon ?? 'Tidak ada telepon' }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-md fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada dokter aktif</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cepat -->
<div class="row mb-4">
    <div class="col-12">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar me-2"></i> Statistik Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border-stat">
                            <h4 class="text-primary">{{ $userCount }}</h4>
                            <small class="text-muted">Total Pasien</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-stat">
                            <h4 class="text-success">{{ \App\Models\Reservasi::where('status', 'confirmed')->count() }}</h4>
                            <small class="text-muted">Terkonfirmasi</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-stat">
                            <h4 class="text-danger">{{ \App\Models\Reservasi::where('status', 'cancelled')->count() }}</h4>
                            <small class="text-muted">Dibatalkan</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border-stat">
                            <h4 class="text-info">{{ \App\Models\Reservasi::where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-bolt me-2"></i> Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.dokters.create') }}" class="quick-action-btn text-decoration-none">
                            <i class="fas fa-plus text-primary"></i>
                            <div class="fw-medium text-dark">Tambah Dokter</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.jadwal-praktek.create') }}" class="quick-action-btn text-decoration-none">
                            <i class="fas fa-calendar-plus text-success"></i>
                            <div class="fw-medium text-dark">Atur Jadwal</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.reservasis.index') }}?status=pending" class="quick-action-btn text-decoration-none">
                            <i class="fas fa-clock text-warning"></i>
                            <div class="fw-medium text-dark">Lihat Pending</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.reservasis.index') }}" class="quick-action-btn text-decoration-none">
                            <i class="fas fa-list text-info"></i>
                            <div class="fw-medium text-dark">Semua Reservasi</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection