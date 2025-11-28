@extends('layouts.admin')

@section('title', 'Detail Reservasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Detail Reservasi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.reservasis.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h6>Informasi Pasien</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Nama Pasien</th>
                        <td>{{ $reservasi->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $reservasi->user->email }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $reservasi->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terdaftar Sejak</th>
                        <td>{{ $reservasi->user->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h6>Informasi Reservasi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Dokter</th>
                        <td>{{ $reservasi->dokter->nama }}</td>
                    </tr>
                    <tr>
                        <th>Spesialisasi</th>
                        <td>{{ $reservasi->dokter->spesialisasi }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $reservasi->tanggal_reservasi->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Jam</th>
                        <td>{{ $reservasi->jam_mulai }} - {{ $reservasi->jam_selesai }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
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
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $reservasi->created_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($reservasi->keluhan)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Keluhan Pasien</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $reservasi->keluhan }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Aksi</h6>
            </div>
            <div class="card-body">
                <div class="btn-group">
                    @if($reservasi->status == 'pending')
                        <form action="{{ route('admin.reservasis.confirm', $reservasi) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Konfirmasi Reservasi
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($reservasi->status, ['pending', 'confirmed']))
                        <button type="button" class="btn btn-warning ms-2"
                                onclick="document.getElementById('complete-form').submit()">
                            <i class="fas fa-flag-checkered"></i> Tandai Selesai
                        </button>
                        <form id="complete-form" 
                              action="{{ route('admin.reservasis.complete', $reservasi) }}" 
                              method="POST" class="d-inline">
                            @csrf
                        </form>
                        
                        <button type="button" class="btn btn-danger ms-2"
                                data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="fas fa-times"></i> Batalkan Reservasi
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Reservasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reservasis.cancel', $reservasi) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Pembatalan</label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required placeholder="Masukkan alasan pembatalan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection