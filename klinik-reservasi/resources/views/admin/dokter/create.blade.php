@extends('layouts.admin')

@section('title', 'Tambah Dokter Baru')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dokters.index') }}">Dokter</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
                </ol>
            </nav>
            <h2 class="h4">Tambah Dokter Baru</h2>
            <p class="mb-0">Form tambah data dokter baru ke sistem</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.dokters.index') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form action="{{ route('admin.dokters.store') }}" method="POST" id="formDokter">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" value="{{ old('nama') }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="spesialisasi" class="form-label">Spesialisasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('spesialisasi') is-invalid @enderror" 
                                       id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi') }}"
                                       placeholder="Contoh: Umum, Spesialis Anak, dll" required>
                                @error('spesialisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                                       placeholder="Contoh: 081234567890">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3" 
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Informasi Tambahan (Optional) -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan (Opsional)</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div>
                                        <small>Informasi tambahan seperti riwayat pendidikan dan pengalaman dapat ditambahkan setelah data dokter dibuat.</small>
                                    </div>
                                </div>
                                <p class="text-muted mb-0">
                                    <small>Anda juga dapat menambahkan jadwal praktek di halaman detail dokter setelah data ini tersimpan.</small>
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline-gray-600">
                                <i class="fas fa-redo me-2"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Dokter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Format nomor telepon
    document.getElementById('no_telepon').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });
    
    // Validasi form sebelum submit
    document.getElementById('formDokter').addEventListener('submit', function(e) {
        const nama = document.getElementById('nama').value.trim();
        const spesialisasi = document.getElementById('spesialisasi').value.trim();
        const status = document.getElementById('status').value;
        
        if (!nama) {
            e.preventDefault();
            showAlert('error', 'Nama dokter harus diisi');
            document.getElementById('nama').focus();
            return false;
        }
        
        if (!spesialisasi) {
            e.preventDefault();
            showAlert('error', 'Spesialisasi harus diisi');
            document.getElementById('spesialisasi').focus();
            return false;
        }
        
        if (!status) {
            e.preventDefault();
            showAlert('error', 'Status harus dipilih');
            document.getElementById('status').focus();
            return false;
        }
    });
    
    function showAlert(type, message) {
        // Anda bisa menggunakan Toast atau Alert biasa
        alert(message); // Ganti dengan library notifikasi jika tersedia
    }
</script>

<style>
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6;
    }
    
    .btn-outline-gray-600 {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-gray-600:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endpush