@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Detail Reservasi</h4>
                    <a href="{{ route('user.reservasis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6>Informasi Dokter</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Nama Dokter</th>
                                            <td>{{ $reservasi->dokter->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Spesialisasi</th>
                                            <td>{{ $reservasi->dokter->spesialisasi }}</td>
                                        </tr>
                                        <tr>
                                            <th>No. Telepon</th>
                                            <td>{{ $reservasi->dokter->no_telepon ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $reservasi->dokter->alamat ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6>Detail Reservasi</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Tanggal</th>
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
                    <div class="card">
                        <div class="card-header">
                            <h6>Keluhan</h6>
                        </div>
                        <div class="card-body">
                            <p>{{ $reservasi->keluhan }}</p>
                        </div>
                    </div>
                    @endif

                    @if($reservasi->canBeCancelled())
                    <div class="mt-4">
                        <form action="{{ route('user.reservasis.destroy', $reservasi) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                <i class="fas fa-times"></i> Batalkan Reservasi
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection