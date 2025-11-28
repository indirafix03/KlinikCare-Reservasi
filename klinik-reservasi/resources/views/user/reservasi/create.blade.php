@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Buat Reservasi Baru</h4>
                </div>
                <div class="card-body">
                    <form id="reservasiForm" action="{{ route('user.reservasis.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dokter_id" class="form-label">Pilih Dokter</label>
                                    <select class="form-select @error('dokter_id') is-invalid @enderror" 
                                            id="dokter_id" name="dokter_id" required>
                                        <option value="">Pilih Dokter</option>
                                        @foreach($dokters as $dokter)
                                            <option value="{{ $dokter->id }}" 
                                                {{ old('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                                {{ $dokter->nama }} - {{ $dokter->spesialisasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dokter_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tanggal_reservasi" class="form-label">Pilih Tanggal</label>
                                    <input type="date" 
                                           class="form-control @error('tanggal_reservasi') is-invalid @enderror" 
                                           id="tanggal_reservasi" 
                                           name="tanggal_reservasi" 
                                           value="{{ old('tanggal_reservasi') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                           required>
                                    @error('tanggal_reservasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="keluhan" class="form-label">Keluhan (Opsional)</label>
                                    <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                                              id="keluhan" 
                                              name="keluhan" 
                                              rows="3" 
                                              placeholder="Jelaskan keluhan Anda...">{{ old('keluhan') }}</textarea>
                                    @error('keluhan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Waktu Reservasi</label>
                                    <div id="timeSlots" class="time-slots-container">
                                        <div class="alert alert-info">
                                            Pilih dokter dan tanggal terlebih dahulu untuk melihat jadwal yang tersedia
                                        </div>
                                    </div>
                                    <input type="hidden" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}">
                                    @error('jam_mulai')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="doctorInfo" class="mt-3" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>Informasi Dokter</h6>
                                            <p id="doctorSchedule" class="mb-1 small"></p>
                                            <p id="doctorContact" class="mb-0 small text-muted"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.reservasis.index') }}" class="btn btn-secondary me-md-2">Kembali</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Buat Reservasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dokterSelect = document.getElementById('dokter_id');
    const tanggalInput = document.getElementById('tanggal_reservasi');
    const timeSlotsContainer = document.getElementById('timeSlots');
    const submitBtn = document.getElementById('submitBtn');
    const jamMulaiInput = document.getElementById('jam_mulai');
    const doctorInfo = document.getElementById('doctorInfo');
    const doctorSchedule = document.getElementById('doctorSchedule');
    const doctorContact = document.getElementById('doctorContact');

    let selectedSlot = null;

    function loadTimeSlots() {
        const dokterId = dokterSelect.value;
        const tanggal = tanggalInput.value;
        
        if (!dokterId || !tanggal) {
            timeSlotsContainer.innerHTML = '<div class="alert alert-info">Pilih dokter dan tanggal terlebih dahulu</div>';
            submitBtn.disabled = true;
            doctorInfo.style.display = 'none';
            return;
        }
        
        // Show loading
        timeSlotsContainer.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">Memuat jadwal...</p></div>';
        
        fetch(`/user/available-slots?dokter_id=${dokterId}&tanggal_reservasi=${tanggal}`)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.slots.length === 0) {
                    timeSlotsContainer.innerHTML = '<div class="alert alert-warning">Tidak ada jadwal tersedia untuk hari ini</div>';
                    submitBtn.disabled = true;
                    return;
                }
                
                let slotsHTML = '';
                data.slots.forEach(slot => {
                    const slotClass = slot.available ? 'available' : 'unavailable';
                    slotsHTML += `
                        <div class="time-slot ${slotClass}" 
                             data-jam-mulai="${slot.jam_mulai}"
                             data-jam-selesai="${slot.jam_selesai}"
                             data-available="${slot.available}">
                            ${slot.display}
                        </div>
                    `;
                });
                
                timeSlotsContainer.innerHTML = slotsHTML;
                
                // Add click event listeners
                document.querySelectorAll('.time-slot.available').forEach(slot => {
                    slot.addEventListener('click', function() {
                        // Remove previous selection
                        if (selectedSlot) {
                            selectedSlot.classList.remove('selected');
                        }
                        
                        // Add selection to clicked slot
                        this.classList.add('selected');
                        selectedSlot = this;
                        jamMulaiInput.value = this.dataset.jamMulai;
                        submitBtn.disabled = false;
                    });
                });

                // Load doctor info
                loadDoctorInfo(dokterId);
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlotsContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat jadwal</div>';
            });
    }

    function loadDoctorInfo(dokterId) {
        // You can implement AJAX call to get doctor details
        // For now, we'll just show basic info
        const selectedDokter = dokterSelect.options[dokterSelect.selectedIndex];
        const dokterText = selectedDokter.text;
        
        doctorSchedule.textContent = `Jadwal akan ditampilkan berdasarkan hari praktek`;
        doctorContact.textContent = `Pilih tanggal untuk melihat slot waktu yang tersedia`;
        doctorInfo.style.display = 'block';
    }

    dokterSelect.addEventListener('change', loadTimeSlots);
    tanggalInput.addEventListener('change', loadTimeSlots);

    // Initial load if there are old values
    @if(old('dokter_id') || old('tanggal_reservasi'))
        loadTimeSlots();
    @endif
});
</script>
@endpush
@endsection