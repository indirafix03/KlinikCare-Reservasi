@extends('layouts.admin')

@section('title', 'Tambah Jadwal Praktek')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Tambah Jadwal Praktek</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.jadwal-praktek.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dokter_id" class="form-label">Dokter</label>
                        <select class="form-select @error('dokter_id') is-invalid @enderror" 
                                id="dokter_id" name="dokter_id" required>
                            <option value="">Pilih Dokter</option>
                            @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}" {{ old('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                    {{ $dokter->nama }} - {{ $dokter->spesialisasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('dokter_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select class="form-select @error('hari') is-invalid @enderror" 
                                id="hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            @foreach($hari as $day)
                                <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        @error('hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" 
                               class="form-control @error('jam_mulai') is-invalid @enderror" 
                               id="jam_mulai" 
                               name="jam_mulai" 
                               value="{{ old('jam_mulai') }}" 
                               required>
                        @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" 
                               class="form-control @error('jam_selesai') is-invalid @enderror" 
                               id="jam_selesai" 
                               name="jam_selesai" 
                               value="{{ old('jam_selesai') }}" 
                               required>
                        @error('jam_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="durasi_per_pasien" class="form-label">Durasi per Pasien (menit)</label>
                        <input type="number" 
                               class="form-control @error('durasi_per_pasien') is-invalid @enderror" 
                               id="durasi_per_pasien" 
                               name="durasi_per_pasien" 
                               value="{{ old('durasi_per_pasien', 30) }}" 
                               min="15" 
                               max="120" 
                               required>
                        @error('durasi_per_pasien')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.jadwal-praktek.index') }}" class="btn btn-secondary me-md-2">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endsection