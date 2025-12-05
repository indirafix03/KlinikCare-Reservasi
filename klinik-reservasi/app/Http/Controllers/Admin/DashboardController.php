<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Reservasi;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data statistik.
     *
     * Metode ini mengambil berbagai data agregat dari database untuk ditampilkan
     * sebagai ringkasan di dashboard admin. Ini termasuk jumlah dokter, reservasi,
     * pengguna, serta daftar reservasi terbaru dan aktivitas dokter.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Menghitung jumlah total dokter.
            $dokterCount = Dokter::count();
            // Menghitung jumlah total reservasi yang pernah dibuat.
            $reservasiCount = Reservasi::count();
            // Menghitung jumlah reservasi yang dijadwalkan untuk hari ini.
            $reservasiHariIni = Reservasi::whereDate('tanggal_reservasi', today())->count();
            // Menghitung jumlah reservasi yang masih berstatus 'pending'.
            $reservasiPending = Reservasi::where('status', 'pending')->count();
            // Menghitung jumlah pengguna dengan role 'user' (pasien).
            $userCount = User::where('role', 'user')->count();
            
            // Mengambil 5 data reservasi terbaru beserta relasi ke user dan dokter.
            $reservasiTerbaru = Reservasi::with(['user', 'dokter'])
                ->latest()
                ->take(5)
                ->get();

            // Mengambil dokter yang berstatus 'aktif'.
            // Beserta jumlah reservasi yang mereka miliki untuk hari ini.
            $doktersAktif = Dokter::where('status', 'aktif')
                ->withCount(['reservasis' => function($query) {
                    $query->whereDate('tanggal_reservasi', today());
                }])
                ->get();

            // Mengirimkan semua data yang telah diambil ke view 'admin.dashboard'.
            // compact() digunakan untuk membuat array dari variabel yang ada.
            return view('admin.dashboard', compact(
                'dokterCount', 
                'reservasiCount', 
                'reservasiHariIni',
                'reservasiPending',
                'userCount',
                'reservasiTerbaru',
                'doktersAktif'
            ));
            
        } catch (\Exception $e) {
            // Blok ini akan dieksekusi jika terjadi error saat mengambil data dari database.
            // Ini adalah mekanisme fallback untuk memastikan halaman dashboard tetap bisa dimuat
            // meskipun dengan data kosong, sehingga tidak menyebabkan aplikasi crash.
            return view('admin.dashboard', [
                'dokterCount' => 0,
                'reservasiCount' => 0,
                'reservasiHariIni' => 0,
                'reservasiPending' => 0,
                'userCount' => 0,
                'reservasiTerbaru' => collect(),
                'doktersAktif' => collect()
            ]);
        }
    }
}