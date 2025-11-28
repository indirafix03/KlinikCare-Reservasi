@extends('layouts.admin')

@section('title', 'Manajemen Reservasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Manajemen Reservasi</h1>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-3">
                <label for="dokter_id" class="form-label">Dokter</label>
                <select name="dokter_id" id="dokter_id" class="form-select">
                    <option value="">Semua Dokter</option>
                    @foreach($dokters as $dokter)
                        <option value="{{ $dokter->id }}" {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                            {{ $dokter->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.reservasis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Tanggal & Jam</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $reservasi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $reservasi->user->name }}</strong>
                                <br><small class="text-muted">{{ $reservasi->user->phone }}</small>
                            </td>
                            <td>
                                <strong>{{ $reservasi->dokter->nama }}</strong>
                                <br><small class="text-muted">{{ $reservasi->dokter->spesialisasi }}</small>
                            </td>
                            <td>
                                {{ $reservasi->tanggal_reservasi->format('d/m/Y') }}
                                <br><small class="text-muted">{{ $reservasi->jam_mulai }} - {{ $reservasi->jam_selesai }}</small>
                            </td>
                            <td>
                                @if($reservasi->keluhan)
                                    <span title="{{ $reservasi->keluhan }}">
                                        {{ Str::limit($reservasi->keluhan, 50) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($reservasi->status == 'confirmed') bg-success
                                    @elseif($reservasi->status == 'pending') bg-warning
                                    @elseif($reservasi->status == 'cancelled') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($reservasi->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.reservasis.show', $reservasi) }}" class="btn btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($reservasi->status == 'pending')
                                        <form action="{{ route('admin.reservasis.confirm', $reservasi) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Konfirmasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(in_array($reservasi->status, ['pending', 'confirmed']))
                                        <button type="button" class="btn btn-warning" title="Selesaikan"
                                                onclick="document.getElementById('complete-form-{{ $reservasi->id }}').submit()">
                                            <i class="fas fa-flag-checkered"></i>
                                        </button>
                                        <form id="complete-form-{{ $reservasi->id }}" 
                                              action="{{ route('admin.reservasis.complete', $reservasi) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                        </form>
                                        
                                        <button type="button" class="btn btn-danger" title="Batalkan"
                                                data-bs-toggle="modal" data-bs-target="#cancelModal{{ $reservasi->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <form action="{{ route('admin.reservasis.destroy', $reservasi) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-dark" title="Hapus" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Cancel Modal -->
                                <div class="modal fade" id="cancelModal{{ $reservasi->id }}" tabindex="-1">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Tidak ada data reservasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $reservasis->links() }}
        </div>
    </div>
</div>
@endsection