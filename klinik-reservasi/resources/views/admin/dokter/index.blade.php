@extends('layouts.admin')

@section('title', 'Data Dokter')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Data Dokter</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.dokters.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Dokter
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Spesialisasi</th>
                        <th>No. Telepon</th>
                        <th>Status</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokters as $dokter)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $dokter->nama }}</strong>
                                @if($dokter->alamat)
                                    <br><small class="text-muted">{{ Str::limit($dokter->alamat, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $dokter->spesialisasi }}</td>
                            <td>{{ $dokter->no_telepon ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $dokter->status == 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($dokter->status) }}
                                </span>
                            </td>
                            <td>
                                @if($dokter->jadwalPraktek->count() > 0)
                                    <small>
                                        @foreach($dokter->jadwalPraktek as $jadwal)
                                            <span class="badge bg-info">{{ $jadwal->hari }}: {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }}</span><br>
                                        @endforeach
                                    </small>
                                @else
                                    <span class="text-muted">Belum ada jadwal</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.dokters.show', $dokter) }}" class="btn btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.dokters.edit', $dokter) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.dokters.destroy', $dokter) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-user-md fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Tidak ada data dokter</p>
                                <a href="{{ route('admin.dokters.create') }}" class="btn btn-primary btn-sm">
                                    Tambah Dokter Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $dokters->links() }}
        </div>
    </div>
</div>
@endsection