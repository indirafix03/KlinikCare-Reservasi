@extends('layouts.admin')

@section('title', 'Jadwal Praktek Dokter')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Jadwal Praktek Dokter</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.jadwal-praktek.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Dokter</th>
                        <th>Hari</th>
                        <th>Jam Praktek</th>
                        <th>Durasi per Pasien</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $jadwal->dokter->nama }}</strong>
                                <br><small class="text-muted">{{ $jadwal->dokter->spesialisasi }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $jadwal->hari }}</span>
                            </td>
                            <td>
                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                            </td>
                            <td>
                                {{ $jadwal->durasi_per_pasien }} menit
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.jadwal-praktek.edit', $jadwal) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwal-praktek.destroy', $jadwal) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Tidak ada data jadwal praktek</p>
                                <a href="{{ route('admin.jadwal-praktek.create') }}" class="btn btn-primary btn-sm">
                                    Tambah Jadwal Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $jadwals->links() }}
        </div>
    </div>
</div>
@endsection