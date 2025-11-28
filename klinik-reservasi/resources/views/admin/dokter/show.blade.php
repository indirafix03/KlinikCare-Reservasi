@extends('layouts.admin')

@section('title', 'Detail Dokter')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Detail Dokter</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.dokters.edit', $dokter) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.dokters.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h6>Informasi Dokter</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Nama</th>
                        <td>{{ $dokter->nama }}</td>
                    </tr>
                    <tr>
                        <th>Spesialisasi</th>
                        <td>{{ $dokter->spesialisasi }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $dokter->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $dokter->status == 'aktif' ? 'success' : 'danger' }}">
                                {{ ucfirst($dokter->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $dokter->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Jadwal Praktek</h6>
            </div>
            <div class="card-body">
                @if($dokter->jadwalPraktek->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($dokter->jadwalPraktek as $jadwal)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $jadwal->hari }}</h6>
                                    <span class="badge bg-primary">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                </div>
                                <p class="mb-1">Durasi: {{ $jadwal->durasi_per_pasien }} menit per pasien</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Belum ada jadwal praktek</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Reservasi Terbaru</h6>
            </div>
            <div class="card-body">
                @if($dokter->reservasis->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Pasien</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokter->reservasis as $reservasi)
                                    <tr>
                                        <td>{{ $reservasi->tanggal_reservasi->format('d/m/Y') }}</td>
                                        <td>{{ $reservasi->jam_mulai }}</td>
                                        <td>{{ $reservasi->user->name }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($reservasi->status == 'confirmed') bg-success
                                                @elseif($reservasi->status == 'pending') bg-warning
                                                @elseif($reservasi->status == 'cancelled') bg-danger
                                                @else bg-secondary @endif">
                                                {{ ucfirst($reservasi->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada reservasi</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection