@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Dokter</h5>
                        <p class="card-text display-4">{{ $dokterCount }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-md fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('admin.dokters.index') }}" class="text-white text-decoration-none">
                    <small>Lihat detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Reservasi</h5>
                        <p class="card-text display-4">{{ $reservasiCount }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
                <a href="{{ route('admin.reservasis.index') }}" class="text-white text-decoration-none">
                    <small>Lihat detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Reservasi Hari Ini</h5>
                        <p class="card-text display-4">{{ $reservasiHariIni }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Menunggu Konfirmasi</h5>
                        <p class="card-text display-4">{{ $reservasiPending }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-list"></i> Reservasi Terbaru</h6>
                <a href="{{ route('admin.reservasis.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($reservasiTerbaru->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($reservasiTerbaru as $reservasi)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $reservasi->user->name }}</h6>
                                    <small class="text-muted">{{ $reservasi->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">
                                    <strong>Dokter:</strong> {{ $reservasi->dokter->nama }}<br>
                                    <strong>Tanggal:</strong> {{ $reservasi->tanggal_reservasi->format('d/m/Y') }}<br>
                                    <strong>Jam:</strong> {{ $reservasi->jam_mulai }}
                                </p>
                                <small class="text-muted">
                                    <span class="badge bg-{{ $reservasi->status == 'confirmed' ? 'success' : ($reservasi->status == 'pending' ? 'warning' : ($reservasi->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($reservasi->status) }}
                                    </span>
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada reservasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-user-md"></i> Dokter Aktif</h6>
                <a href="{{ route('admin.dokters.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($doktersAktif->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($doktersAktif as $dokter)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $dokter->nama }}</h6>
                                    <span class="badge bg-primary">{{ $dokter->reservasis_count }} reservasi hari ini</span>
                                </div>
                                <p class="mb-1">{{ $dokter->spesialisasi }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> {{ $dokter->no_telepon ?? 'Tidak ada telepon' }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-user-md fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada dokter aktif</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar"></i> Statistik Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary">{{ $userCount }}</h4>
                            <small class="text-muted">Total Pasien</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-success">{{ \App\Models\Reservasi::where('status', 'confirmed')->count() }}</h4>
                            <small class="text-muted">Terkonfirmasi</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <h4 class="text-danger">{{ \App\Models\Reservasi::where('status', 'cancelled')->count() }}</h4>
                            <small class="text-muted">Dibatalkan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
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
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-bolt"></i> Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.dokters.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus"></i> Tambah Dokter
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.jadwal-praktek.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-calendar-plus"></i> Atur Jadwal
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.reservasis.index') }}?status=pending" class="btn btn-outline-warning w-100">
                            <i class="fas fa-clock"></i> Lihat Pending
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.reservasis.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-list"></i> Semua Reservasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection