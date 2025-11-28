<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Reservasi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $dokterCount = Dokter::count();
            $reservasiCount = Reservasi::count();
            $reservasiHariIni = Reservasi::whereDate('tanggal_reservasi', today())->count();
            $reservasiPending = Reservasi::where('status', 'pending')->count();
            $userCount = User::where('role', 'user')->count();
            
            // Reservasi terbaru untuk ditampilkan
            $reservasiTerbaru = Reservasi::with(['user', 'dokter'])
                ->latest()
                ->take(5)
                ->get();

            // Dokter aktif dengan jumlah reservasi hari ini
            $doktersAktif = Dokter::where('status', 'aktif')
                ->withCount(['reservasis' => function($query) {
                    $query->whereDate('tanggal_reservasi', today());
                }])
                ->get();

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
            // Fallback jika ada error
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