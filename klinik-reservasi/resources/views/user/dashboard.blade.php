@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard Pasien</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Reservasi Aktif</h5>
                                    <p class="card-text display-4">{{ $reservasiAktif }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Reservasi</h5>
                                    <p class="card-text display-4">{{ $reservasiCount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Aksi Cepat</h5>
                                    <div class="mt-3">
                                        <a href="{{ route('user.reservasis.create') }}" class="btn btn-light btn-sm">
                                            Buat Reservasi Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Reservasi Terbaru</h5>
                        @php
                            $reservasiTerbaru = auth()->user()->reservasis()
                                ->with('dokter')
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        
                        @if($reservasiTerbaru->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
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
                                                <td>{{ $reservasi->tanggal_reservasi->format('d/m/Y') }}</td>
                                                <td>{{ $reservasi->jam_mulai }}</td>
                                                <td>{{ $reservasi->dokter->nama }}</td>
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
                                                    <a href="{{ route('user.reservasis.show', $reservasi) }}" class="btn btn-sm btn-info">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Belum ada reservasi. <a href="{{ route('user.reservasis.create') }}">Buat reservasi pertama Anda!</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection