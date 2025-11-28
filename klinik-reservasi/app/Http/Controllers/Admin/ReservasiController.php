<?php
// app/Http/Controllers/Admin/ReservasiController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function index(Request $request)
    {
        $query = Reservasi::with(['user', 'dokter']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal_reservasi', $request->tanggal);
        }

        if ($request->has('dokter_id') && $request->dokter_id) {
            $query->where('dokter_id', $request->dokter_id);
        }

        $reservasis = $query->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate(20);

        $dokters = \App\Models\Dokter::where('status', 'aktif')->get();

        return view('admin.reservasi.index', compact('reservasis', 'dokters'));
    }

    public function show(Reservasi $reservasi)
    {
        $reservasi->load(['user', 'dokter']);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function confirm(Reservasi $reservasi)
    {
        if ($reservasi->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya reservasi pending yang dapat dikonfirmasi');
        }

        $reservasi->update(['status' => 'confirmed']);

        $notificationSent = $this->fonnte->sendReservasiAdminConfirmation($reservasi);

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil dikonfirmasi.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    public function cancel(Reservasi $reservasi, Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $reservasi->update([
            'status' => 'cancelled',
        ]);

        $notificationSent = $this->fonnte->sendReservasiCancellation($reservasi, $request->alasan);

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil dibatalkan.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    public function complete(Reservasi $reservasi)
    {
        if (!in_array($reservasi->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Hanya reservasi pending atau confirmed yang dapat diselesaikan');
        }

        $reservasi->update(['status' => 'completed']);

        $notificationSent = $this->fonnte->sendReservasiCompleted($reservasi);

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil diselesaikan.' . ($notificationSent ? ' Notifikasi WhatsApp telah dikirim.' : ''));
    }

    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();

        return redirect()->route('admin.reservasis.index')
            ->with('success', 'Reservasi berhasil dihapus');
    }
}