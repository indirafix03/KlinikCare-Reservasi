<?php
// app/Http/Controllers/Admin/JadwalPraktekController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalPraktek;
use Illuminate\Http\Request;

class JadwalPraktekController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPraktek::with('dokter')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(15);

        return view('admin.jadwal-praktek.index', compact('jadwals'));
    }

    public function create()
    {
        $dokters = Dokter::where('status', 'aktif')->get();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.jadwal-praktek.create', compact('dokters', 'hari'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'durasi_per_pasien' => 'required|integer|min:15|max:120',
        ]);

        $existing = JadwalPraktek::where('dokter_id', $validated['dokter_id'])
            ->where('hari', $validated['hari'])
            ->exists();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter sudah memiliki jadwal di hari tersebut');
        }

        JadwalPraktek::create($validated);

        return redirect()->route('admin.jadwal-praktek.index')
            ->with('success', 'Jadwal praktek berhasil ditambahkan');
    }

    public function edit(JadwalPraktek $jadwalPraktek)
    {
        $dokters = Dokter::where('status', 'aktif')->get();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.jadwal-praktek.edit', compact('jadwalPraktek', 'dokters', 'hari'));
    }

    public function update(Request $request, JadwalPraktek $jadwalPraktek)
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'durasi_per_pasien' => 'required|integer|min:15|max:120',
        ]);

        $existing = JadwalPraktek::where('dokter_id', $validated['dokter_id'])
            ->where('hari', $validated['hari'])
            ->where('id', '!=', $jadwalPraktek->id)
            ->exists();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter sudah memiliki jadwal di hari tersebut');
        }

        $jadwalPraktek->update($validated);

        return redirect()->route('admin.jadwal-praktek.index')
            ->with('success', 'Jadwal praktek berhasil diupdate');
    }

    public function destroy(JadwalPraktek $jadwalPraktek)
    {
        $jadwalPraktek->delete();

        return redirect()->route('admin.jadwal-praktek.index')
            ->with('success', 'Jadwal praktek berhasil dihapus');
    }
}