@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Buat Reservasi Baru</h4>
                </div>
                <div class="card-body">
                    <form id="reservasiForm" action="{{ route('user.reservasis.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="dokter_id" class="form-label fw-semibold">Pilih Dokter</label>
                                    <select class="form-select form-select-lg @error('dokter_id') is-invalid @enderror" 
                                            id="dokter_id" name="dokter_id" required>
                                        <option value="">Pilih Dokter...</option>
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
                                
                                <div class="mb-4">
                                    <label for="tanggal_reservasi" class="form-label fw-semibold">Pilih Tanggal</label>
                                    <input type="date" 
                                           class="form-control form-control-lg @error('tanggal_reservasi') is-invalid @enderror" 
                                           id="tanggal_reservasi" 
                                           name="tanggal_reservasi" 
                                           value="{{ old('tanggal_reservasi') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                           required>
                                    @error('tanggal_reservasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="keluhan" class="form-label fw-semibold">Keluhan (Opsional)</label>
                                    <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                                              id="keluhan" 
                                              name="keluhan" 
                                              rows="4" 
                                              placeholder="Jelaskan keluhan atau gejala yang Anda alami...">{{ old('keluhan') }}</textarea>
                                    @error('keluhan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Pilih Waktu Reservasi</label>
                                    <div class="time-selection-container">
                                        <div id="timeSlots" class="time-slots-grid">
                                            <div class="time-slots-placeholder">
                                                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">Pilih Dokter & Tanggal</h6>
                                                <p class="text-muted small">Pilih dokter dan tanggal terlebih dahulu untuk melihat jadwal yang tersedia</p>
                                            </div>
                                        </div>
                                        <input type="hidden" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}">
                                        @error('jam_mulai')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="doctorInfo" class="doctor-info-card" style="display: none;">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title"><i class="fas fa-user-md me-2 text-primary"></i>Informasi Dokter</h6>
                                            <div id="doctorSchedule" class="mb-2 small text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                <span>Jadwal akan ditampilkan setelah memilih tanggal</span>
                                            </div>
                                            <div id="doctorContact" class="small text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                <span>Informasi kontak tersedia</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="selectionSummary" class="selection-summary mt-3" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary mb-2"><i class="fas fa-check-circle me-2"></i>Waktu Dipilih</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong id="selectedTimeDisplay">-</strong>
                                                    <div class="small text-muted" id="selectedDateDisplay">-</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="small text-muted">Dokter</div>
                                                    <strong id="selectedDoctorDisplay">-</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="{{ route('user.reservasis.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4" id="submitBtn" disabled>
                                <i class="fas fa-calendar-check me-2"></i>Buat Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.time-selection-container {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    border: 2px dashed #e9ecef;
}

.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 1rem;
}

.time-slots-placeholder {
    grid-column: 1 / -1;
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.time-slot {
    padding: 14px 8px;
    border: 2px solid;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.9rem;
    position: relative;
    overflow: hidden;
}

.time-slot.available {
    background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
    border-color: #28a745;
    color: #155724;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.1);
}

.time-slot.available:hover {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
}

.time-slot.available.selected {
    background: linear-gradient(135deg, #28a745 0%, #20a04b 100%);
    color: white;
    border-color: #1e7e34;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.time-slot.unavailable {
    background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
    border-color: #dc3545;
    color: #721c24;
    cursor: not-allowed;
    opacity: 0.7;
    position: relative;
}

.time-slot.unavailable::after {
    content: "✕";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5rem;
    font-weight: bold;
    color: #dc3545;
    opacity: 0.3;
}

.time-slot.booked {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-color: #ffc107;
    color: #856404;
    cursor: not-allowed;
}

.time-slot-time {
    font-size: 1.1rem;
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
}

.time-slot-duration {
    font-size: 0.75rem;
    opacity: 0.8;
    display: block;
}

.time-slot-status {
    position: absolute;
    top: 4px;
    right: 4px;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 4px;
}

.time-slot.available .time-slot-status {
    background: #28a745;
    color: white;
}

.time-slot.unavailable .time-slot-status {
    background: #dc3545;
    color: white;
}

.time-slot.booked .time-slot-status {
    background: #ffc107;
    color: #856404;
}

.doctor-info-card {
    margin-top: 1.5rem;
}

.selection-summary {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.loading-spinner {
    grid-column: 1 / -1;
    text-align: center;
    padding: 2rem;
}

.slot-legend {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
    font-size: 0.8rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 2px solid;
}

.legend-available {
    background: #e8f5e8;
    border-color: #28a745;
}

.legend-unavailable {
    background: #f8d7da;
    border-color: #dc3545;
}

.legend-booked {
    background: #fff3cd;
    border-color: #ffc107;
}

@media (max-width: 768px) {
    .time-slots-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .time-selection-container {
        padding: 1rem;
    }
}
</style>

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
    const selectionSummary = document.getElementById('selectionSummary');
    const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');
    const selectedDateDisplay = document.getElementById('selectedDateDisplay');
    const selectedDoctorDisplay = document.getElementById('selectedDoctorDisplay');

    let selectedSlot = null;

    function formatTimeDisplay(time) {
        return time.replace(':00', '') + ' WIB';
    }

    function formatDateDisplay(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function loadTimeSlots() {
        const dokterId = dokterSelect.value;
        const tanggal = tanggalInput.value;
        
        if (!dokterId || !tanggal) {
            timeSlotsContainer.innerHTML = `
                <div class="time-slots-placeholder">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Pilih Dokter & Tanggal</h6>
                    <p class="text-muted small">Pilih dokter dan tanggal terlebih dahulu untuk melihat jadwal yang tersedia</p>
                </div>
            `;
            submitBtn.disabled = true;
            doctorInfo.style.display = 'none';
            selectionSummary.style.display = 'none';
            return;
        }
        
        // Show loading
        timeSlotsContainer.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner-border text-primary mb-3"></div>
                <p class="text-muted mb-0">Memuat jadwal yang tersedia...</p>
            </div>
        `;
        
        fetch(`/user/available-slots?dokter_id=${dokterId}&tanggal_reservasi=${tanggal}`)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.slots.length === 0) {
                    timeSlotsContainer.innerHTML = `
                        <div class="time-slots-placeholder">
                            <i class="fas fa-calendar-times fa-3x text-warning mb-3"></i>
                            <h6 class="text-warning">Tidak Ada Jadwal Tersedia</h6>
                            <p class="text-muted small">Tidak ada jadwal tersedia untuk tanggal yang dipilih. Silakan pilih tanggal lain.</p>
                        </div>
                    `;
                    submitBtn.disabled = true;
                    return;
                }
                
                let slotsHTML = '';
                let availableCount = 0;
                
                data.slots.forEach(slot => {
                    const slotType = slot.available ? 'available' : (slot.booked ? 'booked' : 'unavailable');
                    const statusText = slot.available ? 'Tersedia' : (slot.booked ? 'Sudah Dipesan' : 'Tidak Tersedia');
                    const statusIcon = slot.available ? '✓' : (slot.booked ? '⏰' : '✕');
                    
                    if (slot.available) availableCount++;
                    
                    slotsHTML += `
                        <div class="time-slot ${slotType}" 
                             data-jam-mulai="${slot.jam_mulai}"
                             data-jam-selesai="${slot.jam_selesai}"
                             data-available="${slot.available}">
                            <span class="time-slot-time">${slot.display.split(' - ')[0]}</span>
                            <span class="time-slot-duration">${slot.display.split(' - ')[1]}</span>
                            <span class="time-slot-status">${statusIcon}</span>
                        </div>
                    `;
                });
                
                // Add legend
                slotsHTML += `
                    <div class="slot-legend">
                        <div class="legend-item">
                            <div class="legend-color legend-available"></div>
                            <span>Tersedia (${availableCount})</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-unavailable"></div>
                            <span>Tidak Tersedia</span>
                        </div>
                    </div>
                `;
                
                timeSlotsContainer.innerHTML = slotsHTML;
                
                // Add click event listeners to available slots only
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
                        
                        // Update selection summary
                        updateSelectionSummary();
                    });
                });

                // Load doctor info
                loadDoctorInfo(dokterId);
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlotsContainer.innerHTML = `
                    <div class="time-slots-placeholder">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h6 class="text-danger">Gagal Memuat Jadwal</h6>
                        <p class="text-muted small">Terjadi kesalahan saat memuat jadwal. Silakan refresh halaman.</p>
                    </div>
                `;
            });
    }

    function loadDoctorInfo(dokterId) {
        const selectedDokter = dokterSelect.options[dokterSelect.selectedIndex];
        const dokterText = selectedDokter.text.split(' - ');
        
        doctorSchedule.innerHTML = `<i class="fas fa-clock me-1"></i>Jadwal praktek reguler`;
        doctorContact.innerHTML = `<i class="fas fa-user-md me-1"></i>${dokterText[0]} - ${dokterText[1]}`;
        doctorInfo.style.display = 'block';
    }

    function updateSelectionSummary() {
        if (selectedSlot && dokterSelect.value && tanggalInput.value) {
            const selectedDokter = dokterSelect.options[dokterSelect.selectedIndex].text.split(' - ')[0];
            const selectedTime = formatTimeDisplay(selectedSlot.dataset.jamMulai);
            const selectedDate = formatDateDisplay(tanggalInput.value);
            
            selectedTimeDisplay.textContent = selectedTime;
            selectedDateDisplay.textContent = selectedDate;
            selectedDoctorDisplay.textContent = selectedDokter;
            
            selectionSummary.style.display = 'block';
        }
    }

    dokterSelect.addEventListener('change', function() {
        loadTimeSlots();
        if (selectedSlot) {
            updateSelectionSummary();
        }
    });

    tanggalInput.addEventListener('change', function() {
        loadTimeSlots();
        if (selectedSlot) {
            updateSelectionSummary();
        }
    });

    // Initial load if there are old values
    @if(old('dokter_id') || old('tanggal_reservasi'))
        loadTimeSlots();
        if ('{{ old('jam_mulai') }}') {
            // Simulate selection of old value
            setTimeout(() => {
                const oldSlot = document.querySelector(`[data-jam-mulai="{{ old('jam_mulai') }}"]`);
                if (oldSlot && oldSlot.classList.contains('available')) {
                    oldSlot.click();
                }
            }, 500);
        }
    @endif
});
</script>
@endpush
@endsection