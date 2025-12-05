<?php
// app/Http\Controllers/Admin/ReservasiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Services\FonnteService;
use Illuminate\Http\Request;

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
     * Menampilkan daftar reservasi dengan fitur filter
     * 
     * @param Request $request - Request dari user untuk filter dan pencarian
     * @return \Illuminate\View\View - View dengan data reservasi
     */
    public function index(Request $request)
    {
        // Query dasar dengan eager loading relasi user dan dokter
        $query = Reservasi::with(['user', 'dokter']);

        // Filter berdasarkan status reservasi
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal reservasi
        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal_reservasi', $request->tanggal);
        }

        // Filter berdasarkan dokter
        if ($request->has('dokter_id') && $request->dokter_id) {
            $query->where('dokter_id', $request->dokter_id);
        }

        // Sorting dan pagination hasil query
        $reservasis = $query->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate(20);

        // Mengambil data dokter aktif untuk filter
        $dokters = \App\Models\Dokter::where('status', 'aktif')->get();

        // Return view dengan data reservasi dan dokter
        return view('admin.reservasi.index', compact('reservasis', 'dokters'));
    }

    /**
     * Menampilkan detail reservasi tertentu
     * 
     * @param Reservasi $reservasi - Model reservasi yang akan ditampilkan
     * @return \Illuminate\View\View - View detail reservasi
     */
    public function show(Reservasi $reservasi)
    {
        // Load relasi user dan dokter untuk detail view
        $reservasi->load(['user', 'dokter']);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    /**
     * Mengkonfirmasi reservasi yang berstatus pending
     * 
     * @param Reservasi $reservasi - Reservasi yang akan dikonfirmasi
     * @return \Illuminate\Http\RedirectResponse - Redirect dengan pesan sukses/error
     */
    public function confirm(Reservasi $reservasi)
    {
        // Validasi: hanya reservasi pending yang dapat dikonfirmasi
        if ($reservasi->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya reservasi pending yang dapat dikonfirmasi');
        }

        // Update status reservasi menjadi confirmed
        $reservasi->update(['status' => 'confirmed']);

        // Kirim notifikasi WhatsApp konfirmasi ke pasien
        $notificationSent = $this->fonnte->sendReservasiAdminConfirmation($reservasi);

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil dikonfirmasi.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    /**
     * Membatalkan reservasi dengan alasan tertentu
     * 
     * @param Reservasi $reservasi - Reservasi yang akan dibatalkan
     * @param Request $request - Request yang berisi alasan pembatalan
     * @return \Illuminate\Http\RedirectResponse - Redirect dengan pesan sukses
     */
    public function cancel(Reservasi $reservasi, Request $request)
    {
        // Validasi input alasan pembatalan
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        // Update status reservasi menjadi cancelled
        $reservasi->update([
            'status' => 'cancelled',
        ]);

        // Kirim notifikasi WhatsApp pembatalan ke pasien
        $notificationSent = $this->fonnte->sendReservasiCancellation($reservasi, $request->alasan);

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil dibatalkan.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    /**
     * Menandai reservasi sebagai selesai
     * 
     * @param Reservasi $reservasi - Reservasi yang akan diselesaikan
     * @return \Illuminate\Http\RedirectResponse - Redirect dengan pesan sukses/error
     */
    public function complete(Reservasi $reservasi)
    {
        // Validasi: hanya reservasi pending atau confirmed yang dapat diselesaikan
        if (!in_array($reservasi->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Hanya reservasi pending atau confirmed yang dapat diselesaikan');
        }

        // Update status reservasi menjadi completed
        $reservasi->update(['status' => 'completed']);

        // Kirim notifikasi WhatsApp penyelesaian ke pasien
        $notificationSent = $this->fonnte->sendReservasiCompleted($reservasi);

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil diselesaikan.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    /**
     * Menghapus reservasi dari database
     * 
     * @param Reservasi $reservasi - Reservasi yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse - Redirect dengan pesan sukses
     */
    public function destroy(Reservasi $reservasi)
    {
        // Hapus reservasi dari database
        $reservasi->delete();

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil dihapus');
    }
}