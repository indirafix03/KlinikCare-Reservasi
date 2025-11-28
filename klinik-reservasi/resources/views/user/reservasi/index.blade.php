@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Reservasi Saya</h4>
                    <a href="{{ route('user.reservasis.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Reservasi Baru
                    </a>
                </div>
                <div class="card-body">
                    @if($reservasis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Dokter</th>
                                        <th>Tanggal & Jam</th>
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasis as $reservasi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $reservasi->dokter->nama }}</strong>
                                                <br><small class="text-muted">{{ $reservasi->dokter->spesialisasi }}</small>
                                            </td>
                                            <td>
                                                {{ $reservasi->tanggal_reservasi->format('d F Y') }}
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
                                                    <a href="{{ route('user.reservasis.show', $reservasi) }}" class="btn btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($reservasi->canBeCancelled())
                                                        <form action="{{ route('user.reservasis.destroy', $reservasi) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" title="Batalkan"
                                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $reservasis->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>Belum ada reservasi</h5>
                            <p class="text-muted">Anda belum memiliki reservasi.</p>
                            <a href="{{ route('user.reservasis.create') }}" class="btn btn-primary">
                                Buat Reservasi Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection