<?php
// app/Http/Controllers/Admin/DokterController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with('jadwalPraktek')->paginate(10);
        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        return view('admin.dokter.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Dokter::create($validated);

        return redirect()->route('admin.dokters.index')
            ->with('success', 'Dokter berhasil ditambahkan');
    }

    public function show(Dokter $dokter)
    {
        $dokter->load(['jadwalPraktek', 'reservasis' => function($query) {
            $query->latest()->take(10);
        }]);
        return view('admin.dokter.show', compact('dokter'));
    }

    public function edit(Dokter $dokter)
    {
        return view('admin.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, Dokter $dokter)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $dokter->update($validated);

        return redirect()->route('admin.dokters.index')
            ->with('success', 'Dokter berhasil diupdate');
    }

    public function destroy(Dokter $dokter)
    {
        if ($dokter->reservasis()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus dokter yang memiliki reservasi aktif');
        }

        $dokter->delete();
        
        return redirect()->route('admin.dokters.index')
            ->with('success', 'Dokter berhasil dihapus');
    }
}