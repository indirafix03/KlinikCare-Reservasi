<?php
// app/Http/Controllers/Admin/JadwalPraktekController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalPraktek;
use Illuminate\Http\Request;

class JadwalPraktekController extends Controller
{
    /**
     * Menampilkan daftar semua jadwal praktek dengan paginasi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data jadwal praktek beserta relasi ke model Dokter.
        // `with('dokter')` digunakan untuk Eager Loading, menghindari N+1 query problem.
        // Data diurutkan berdasarkan hari dan jam mulai untuk keterbacaan.
        $jadwals = JadwalPraktek::with('dokter')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(15);

        // Mengirim data jadwal ke view.
        return view('admin.jadwal-praktek.index', compact('jadwals'));
    }

    /**
     * Menampilkan form untuk membuat jadwal praktek baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengambil daftar dokter yang berstatus 'aktif' untuk ditampilkan di dropdown.
        $dokters = Dokter::where('status', 'aktif')->get();
        // Menyiapkan array hari untuk dropdown pilihan hari.
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Mengirim data dokter dan hari ke view create.
        return view('admin.jadwal-praktek.create', compact('dokters', 'hari'));
    }

    /**
     * Menyimpan jadwal praktek baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari form.
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id', // Pastikan dokter_id ada di tabel dokters.
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu', // Hari harus sesuai dengan yang ditentukan.
            'jam_mulai' => 'required|date_format:H:i', // Format jam harus HH:MM.
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai', // Jam selesai harus setelah jam mulai.
            'durasi_per_pasien' => 'required|integer|min:15|max:120', // Durasi dalam menit, minimal 15, maksimal 120.
        ]);

        // Cek apakah dokter yang sama sudah memiliki jadwal di hari yang sama.
        // Ini untuk mencegah duplikasi jadwal.
        $existing = JadwalPraktek::where('dokter_id', $validated['dokter_id'])
            ->where('hari', $validated['hari'])
            ->exists();

        // Jika jadwal sudah ada, kembalikan ke form dengan pesan error.
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter sudah memiliki jadwal di hari tersebut');
        }

        // Jika tidak ada duplikasi, buat jadwal praktek baru.
        JadwalPraktek::create($validated);

        // Arahkan kembali ke halaman index dengan pesan sukses.
        return redirect()->route('admin.jadwal-praktek.index')
            ->with('success', 'Jadwal praktek berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk mengedit jadwal praktek.
     *
     * @param  \App\Models\JadwalPraktek  $jadwalPraktek
     * @return \Illuminate\View\View
     */
    public function edit(JadwalPraktek $jadwalPraktek)
    {
        // Mengambil daftar dokter aktif dan array hari untuk form edit.
        $dokters = Dokter::where('status', 'aktif')->get();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Mengirim data jadwal yang akan diedit, beserta data dokter dan hari ke view.
        return view('admin.jadwal-praktek.edit', compact('jadwalPraktek', 'dokters', 'hari'));
    }

    /**
     * Memperbarui data jadwal praktek di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JadwalPraktek  $jadwalPraktek
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, JadwalPraktek $jadwalPraktek)
    {
        // Validasi data yang dikirim dari form edit.
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'durasi_per_pasien' => 'required|integer|min:15|max:120',
        ]);

        // Cek duplikasi jadwal, dengan mengabaikan jadwal yang sedang diedit saat ini.
        $existing = JadwalPraktek::where('dokter_id', $validated['dokter_id'])
            ->where('hari', $validated['hari'])
            ->where('id', '!=', $jadwalPraktek->id)
            ->exists();

        // Jika ditemukan duplikasi, kembalikan dengan pesan error.
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter sudah memiliki jadwal di hari tersebut');
        }

        // Perbarui data jadwal praktek.
        $jadwalPraktek->update($validated);

        return redirect()->route('admin.jadwal-praktek.index')
            ->with('success', 'Jadwal praktek berhasil diupdate');
    }

    /**
     * Menghapus jadwal praktek dari database.
     *
     * @param  \App\Models\JadwalPraktek  $jadwalPraktek
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JadwalPraktek $jadwalPraktek)
    {
        // TODO: Tambahkan pengecekan apakah ada reservasi yang terkait dengan jadwal ini
        // sebelum menghapusnya untuk mencegah data inkonsisten.
        // Contoh: if ($jadwalPraktek->reservasis()->where('tanggal_reservasi', '>=', today())->exists()) { ... }

        // Hapus data jadwal praktek.
        $jadwalPraktek->delete();

        return redirect()->route('admin.jadwal-praktek.index')
            ->with('success', 'Jadwal praktek berhasil dihapus');
    }
}