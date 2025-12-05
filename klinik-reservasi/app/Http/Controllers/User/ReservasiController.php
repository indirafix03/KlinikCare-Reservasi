<?php
// app/Http\Controllers\User/ReservasiController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalPraktek;
use App\Models\Reservasi;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    // Properti untuk service WhatsApp notification
    protected $fonnte;

    // Constructor untuk dependency injection
    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    /**
     * Menampilkan daftar reservasi milik user yang sedang login
     * 
     * @return \Illuminate\View\View - View dengan data reservasi user
     */
    public function index()
    {
        // Mengambil reservasi user yang sedang login dengan eager loading dokter
        $reservasis = Reservasi::with('dokter')
            ->where('user_id', Auth::id()) // Filter berdasarkan user yang login
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Pagination 10 item per halaman

        return view('user.reservasi.index', compact('reservasis'));
    }

    /**
     * Menampilkan form untuk membuat reservasi baru
     * 
     * @return \Illuminate\View\View - Form create reservasi
     */
    public function create()
    {
        // Mengambil data dokter aktif beserta jadwal praktek mereka
        $dokters = Dokter::with('jadwalPraktek')
            ->where('status', 'aktif')
            ->get();

        return view('user.reservasi.create', compact('dokters'));
    }

    /**
     * Mendapatkan slot waktu yang tersedia untuk reservasi
     * (API endpoint untuk AJAX request)
     * 
     * @param Request $request - Berisi dokter_id dan tanggal_reservasi
     * @return \Illuminate\Http\JsonResponse - JSON dengan slot waktu yang tersedia
     */
    public function getAvailableSlots(Request $request)
    {
        // Validasi input request
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'tanggal_reservasi' => 'required|date',
        ]);

        // Mendapatkan data dokter
        $dokter = Dokter::findOrFail($request->dokter_id);
        $tanggal = Carbon::parse($request->tanggal_reservasi);
        
        // Konversi hari dari bahasa Inggris ke Indonesia
        $englishDay = $tanggal->englishDayOfWeek;
        $hariIndonesia = $this->convertToHariIndonesia($englishDay);

        // Mencari jadwal praktek dokter pada hari tersebut
        $jadwal = JadwalPraktek::where('dokter_id', $dokter->id)
            ->where('hari', $hariIndonesia)
            ->first();

        // Jika tidak ada jadwal, kembalikan array kosong
        if (!$jadwal) {
            return response()->json(['slots' => []]);
        }

        // Generate slot waktu berdasarkan jadwal praktek
        $slots = $this->generateTimeSlots($jadwal, $tanggal, $dokter->id);

        return response()->json(['slots' => $slots]);
    }

    /**
     * Mengkonversi hari dari bahasa Inggris ke Indonesia
     * 
     * @param string $englishDay - Hari dalam bahasa Inggris
     * @return string - Hari dalam bahasa Indonesia
     */
    private function convertToHariIndonesia($englishDay)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $days[$englishDay] ?? $englishDay;
    }

    /**
     * Menghasilkan slot waktu reservasi berdasarkan jadwal praktek
     * 
     * @param JadwalPraktek $jadwal - Jadwal praktek dokter
     * @param Carbon $tanggal - Tanggal reservasi
     * @param int $dokterId - ID dokter
     * @return array - Array slot waktu dengan informasi ketersediaan
     */
    private function generateTimeSlots($jadwal, $tanggal, $dokterId)
    {
        $slots = [];
        $start = Carbon::parse($jadwal->jam_mulai);
        $end = Carbon::parse($jadwal->jam_selesai);
        $interval = $jadwal->durasi_per_pasien; // Durasi per pasien dalam menit

        // Generate slot dari jam mulai sampai jam selesai dengan interval tertentu
        $current = $start->copy();
        while ($current < $end) {
            $slotTime = $current->format('H:i');
            $slotEnd = $current->copy()->addMinutes($interval)->format('H:i');

            // Cek apakah slot sudah dipesan
            $isBooked = Reservasi::where('dokter_id', $dokterId)
                ->where('tanggal_reservasi', $tanggal->format('Y-m-d'))
                ->where('jam_mulai', $slotTime)
                ->whereIn('status', ['pending', 'confirmed']) // Hanya cek reservasi aktif
                ->exists();

            // Format data slot
            $slots[] = [
                'jam_mulai' => $slotTime,
                'jam_selesai' => $slotEnd,
                'available' => !$isBooked, // Status ketersediaan
                'display' => $current->format('H:i') . ' - ' . $slotEnd, // Format tampilan
            ];

            $current->addMinutes($interval); // Pindah ke slot berikutnya
        }

        return $slots;
    }

    /**
     * Menyimpan reservasi baru ke database
     * 
     * @param Request $request - Data reservasi dari form
     * @return \Illuminate\Http\RedirectResponse - Redirect dengan pesan sukses/error
     */
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'tanggal_reservasi' => 'required|date|after:today', // Harus setelah hari ini
            'jam_mulai' => 'required|date_format:H:i',
            'keluhan' => 'nullable|string|max:500',
        ]);

        // Konversi tanggal dan cek jadwal praktek dokter
        $tanggal = Carbon::parse($request->tanggal_reservasi);
        $englishDay = $tanggal->englishDayOfWeek;
        $hariIndonesia = $this->convertToHariIndonesia($englishDay);

        // Cek apakah dokter praktek pada hari tersebut
        $jadwal = JadwalPraktek::where('dokter_id', $request->dokter_id)
            ->where('hari', $hariIndonesia)
            ->first();

        if (!$jadwal) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter tidak praktek pada hari tersebut.');
        }

        // Validasi apakah jam reservasi sesuai dengan jadwal praktek
        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamJadwalMulai = Carbon::parse($jadwal->jam_mulai);
        $jamJadwalSelesai = Carbon::parse($jadwal->jam_selesai);

        if ($jamMulai < $jamJadwalMulai || $jamMulai >= $jamJadwalSelesai) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jam reservasi diluar jadwal praktek dokter.');
        }

        // Cek apakah slot waktu sudah dipesan
        $existingReservasi = Reservasi::where('dokter_id', $request->dokter_id)
            ->where('tanggal_reservasi', $request->tanggal_reservasi)
            ->where('jam_mulai', $request->jam_mulai)
            ->whereIn('status', ['pending', 'confirmed']) // Hanya cek reservasi aktif
            ->exists();

        if ($existingReservasi) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Slot waktu sudah dipesan. Silakan pilih waktu lain.');
        }

        // Hitung jam selesai berdasarkan durasi per pasien
        $jamSelesai = Carbon::parse($request->jam_mulai)
            ->addMinutes($jadwal->durasi_per_pasien)
            ->format('H:i');

        // Buat reservasi baru
        $reservasi = Reservasi::create([
            'user_id' => Auth::id(),
            'dokter_id' => $request->dokter_id,
            'tanggal_reservasi' => $request->tanggal_reservasi,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $jamSelesai,
            'keluhan' => $request->keluhan,
            'status' => 'pending', // Status awal selalu pending
        ]);

        // Kirim notifikasi WhatsApp konfirmasi
        $notificationSent = $this->fonnte->sendReservasiConfirmation($reservasi);

        return redirect()->route('user.reservasis.index')
            ->with('success', 'Reservasi berhasil dibuat.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    /**
     * Menampilkan detail reservasi tertentu
     * 
     * @param Reservasi $reservasi - Reservasi yang akan ditampilkan
     * @return \Illuminate\View\View - View detail reservasi
     * @throws \Illuminate\Auth\Access\AuthorizationException - Jika user tidak memiliki akses
     */
    public function show(Reservasi $reservasi)
    {
        // Authorization: hanya pemilik reservasi yang bisa melihat
        if ($reservasi->user_id !== Auth::id()) {
            abort(403);
        }

        $reservasi->load('dokter'); // Load relasi dokter
        return view('user.reservasi.show', compact('reservasi'));
    }

    /**
     * Membatalkan reservasi milik user
     * 
     * @param Reservasi $reservasi - Reservasi yang akan dibatalkan
     * @return \Illuminate\Http\RedirectResponse - Redirect dengan pesan sukses/error
     * @throws \Illuminate\Auth\Access\AuthorizationException - Jika user tidak memiliki akses
     */
    public function destroy(Reservasi $reservasi)
    {
        // Authorization: hanya pemilik reservasi yang bisa membatalkan
        if ($reservasi->user_id !== Auth::id()) {
            abort(403);
        }

        // Cek apakah reservasi bisa dibatalkan (misal: minimal 2 jam sebelumnya)
        if (!$reservasi->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Reservasi tidak dapat dibatalkan. Minimal pembatalan 2 jam sebelum jadwal.');
        }

        // Update status reservasi menjadi cancelled
        $reservasi->update(['status' => 'cancelled']);

        return redirect()->route('user.reservasis.index')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}