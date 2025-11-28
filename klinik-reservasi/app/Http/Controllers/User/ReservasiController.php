<?php
// app/Http/Controllers/User/ReservasiController.php
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
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function index()
    {
        $reservasis = Reservasi::with('dokter')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.reservasi.index', compact('reservasis'));
    }

    public function create()
    {
        $dokters = Dokter::with('jadwalPraktek')
            ->where('status', 'aktif')
            ->get();

        return view('user.reservasi.create', compact('dokters'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'tanggal_reservasi' => 'required|date',
        ]);

        $dokter = Dokter::findOrFail($request->dokter_id);
        $tanggal = Carbon::parse($request->tanggal_reservasi);
        
        $englishDay = $tanggal->englishDayOfWeek;
        $hariIndonesia = $this->convertToHariIndonesia($englishDay);

        $jadwal = JadwalPraktek::where('dokter_id', $dokter->id)
            ->where('hari', $hariIndonesia)
            ->first();

        if (!$jadwal) {
            return response()->json(['slots' => []]);
        }

        $slots = $this->generateTimeSlots($jadwal, $tanggal, $dokter->id);

        return response()->json(['slots' => $slots]);
    }

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

    private function generateTimeSlots($jadwal, $tanggal, $dokterId)
    {
        $slots = [];
        $start = Carbon::parse($jadwal->jam_mulai);
        $end = Carbon::parse($jadwal->jam_selesai);
        $interval = $jadwal->durasi_per_pasien;

        $current = $start->copy();
        while ($current < $end) {
            $slotTime = $current->format('H:i');
            $slotEnd = $current->copy()->addMinutes($interval)->format('H:i');

            $isBooked = Reservasi::where('dokter_id', $dokterId)
                ->where('tanggal_reservasi', $tanggal->format('Y-m-d'))
                ->where('jam_mulai', $slotTime)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            $slots[] = [
                'jam_mulai' => $slotTime,
                'jam_selesai' => $slotEnd,
                'available' => !$isBooked,
                'display' => $current->format('H:i') . ' - ' . $slotEnd,
            ];

            $current->addMinutes($interval);
        }

        return $slots;
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'tanggal_reservasi' => 'required|date|after:today',
            'jam_mulai' => 'required|date_format:H:i',
            'keluhan' => 'nullable|string|max:500',
        ]);

        $tanggal = Carbon::parse($request->tanggal_reservasi);
        $englishDay = $tanggal->englishDayOfWeek;
        $hariIndonesia = $this->convertToHariIndonesia($englishDay);

        $jadwal = JadwalPraktek::where('dokter_id', $request->dokter_id)
            ->where('hari', $hariIndonesia)
            ->first();

        if (!$jadwal) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter tidak praktek pada hari tersebut.');
        }

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamJadwalMulai = Carbon::parse($jadwal->jam_mulai);
        $jamJadwalSelesai = Carbon::parse($jadwal->jam_selesai);

        if ($jamMulai < $jamJadwalMulai || $jamMulai >= $jamJadwalSelesai) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jam reservasi diluar jadwal praktek dokter.');
        }

        $existingReservasi = Reservasi::where('dokter_id', $request->dokter_id)
            ->where('tanggal_reservasi', $request->tanggal_reservasi)
            ->where('jam_mulai', $request->jam_mulai)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existingReservasi) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Slot waktu sudah dipesan. Silakan pilih waktu lain.');
        }

        $jamSelesai = Carbon::parse($request->jam_mulai)
            ->addMinutes($jadwal->durasi_per_pasien)
            ->format('H:i');

        $reservasi = Reservasi::create([
            'user_id' => Auth::id(),
            'dokter_id' => $request->dokter_id,
            'tanggal_reservasi' => $request->tanggal_reservasi,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $jamSelesai,
            'keluhan' => $request->keluhan,
            'status' => 'pending',
        ]);

        $notificationSent = $this->fonnte->sendReservasiConfirmation($reservasi);

        return redirect()->route('user.reservasis.index')
            ->with('success', 'Reservasi berhasil dibuat.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    public function show(Reservasi $reservasi)
    {
        if ($reservasi->user_id !== Auth::id()) {
            abort(403);
        }

        $reservasi->load('dokter');
        return view('user.reservasi.show', compact('reservasi'));
    }

    public function destroy(Reservasi $reservasi)
    {
        if ($reservasi->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$reservasi->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Reservasi tidak dapat dibatalkan. Minimal pembatalan 2 jam sebelum jadwal.');
        }

        $reservasi->update(['status' => 'cancelled']);

        return redirect()->route('user.reservasis.index')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}