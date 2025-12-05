<?php
// app/Http/Controllers/Admin/DokterController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    /**
     * Menampilkan daftar semua dokter dengan paginasi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data dokter beserta relasi jadwal prakteknya.
        // `with('jadwalPraktek')` digunakan untuk Eager Loading agar menghindari N+1 query problem.
        // `paginate(10)` akan menampilkan 10 dokter per halaman.
        $dokters = Dokter::with('jadwalPraktek')->paginate(10);
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Menampilkan form untuk membuat data dokter baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.dokter.create');
    }

    /**
     * Menyimpan data dokter baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form.
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif', // Status harus 'aktif' atau 'nonaktif'.
        ]);

        // Membuat record baru di tabel 'dokters' menggunakan data yang sudah divalidasi.
        Dokter::create($validated);

        // Mengarahkan kembali ke halaman index dokter dengan pesan sukses.
        return redirect()->route('admin.dokters.index')
            ->with('success', 'Dokter berhasil ditambahkan');
    }

    /**
     * Menampilkan detail dari seorang dokter.
     *
     * @param  \App\Models\Dokter  $dokter
     * @return \Illuminate\View\View
     */
    public function show(Dokter $dokter)
    {
        // Memuat relasi 'jadwalPraktek' dan 10 'reservasis' terbaru untuk dokter ini.
        // Ini adalah contoh Lazy Eager Loading.
        $dokter->load(['jadwalPraktek', 'reservasis' => function($query) {
            $query->latest()->take(10);
        }]);
        return view('admin.dokter.show', compact('dokter'));
    }

    /**
     * Menampilkan form untuk mengedit data dokter.
     *
     * @param  \App\Models\Dokter  $dokter
     * @return \Illuminate\View\View
     */
    public function edit(Dokter $dokter)
    {
        return view('admin.dokter.edit', compact('dokter'));
    }

    /**
     * Memperbarui data dokter yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dokter  $dokter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Dokter $dokter)
    {
        // Validasi data yang masuk dari form edit.
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Memperbarui data dokter dengan data yang sudah divalidasi.
        $dokter->update($validated);

        // Mengarahkan kembali ke halaman index dokter dengan pesan sukses.
        return redirect()->route('admin.dokters.index')
            ->with('success', 'Dokter berhasil diupdate');
    }

    /**
     * Menghapus data dokter dari database.
     *
     * @param  \App\Models\Dokter  $dokter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Dokter $dokter)
    {
        // Pengecekan keamanan: jangan hapus dokter jika masih punya reservasi aktif ('pending' atau 'confirmed').
        if ($dokter->reservasis()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            // Jika ada, kembalikan ke halaman sebelumnya dengan pesan error.
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus dokter yang memiliki reservasi aktif');
        }

        // Hapus jadwal praktek terkait (jika ada cascade delete di database, ini tidak perlu).
        // $dokter->jadwalPraktek()->delete(); // Opsional, tergantung setup relasi.
        $dokter->delete();
        
        return redirect()->route('admin.dokters.index')
            ->with('success', 'Dokter berhasil dihapus');
    }
}