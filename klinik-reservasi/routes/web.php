<?php
// routes/web.php
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\JadwalPraktekController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\ReservasiController as UserReservasiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard utama
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            $reservasiCount = auth()->user()->reservasis()->count();
            $reservasiAktif = auth()->user()->reservasis()
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();
            
            return view('user.dashboard', compact('reservasiCount', 'reservasiAktif'));
        })->name('dashboard');
        
        Route::resource('reservasis', UserReservasiController::class);
        Route::get('/available-slots', [UserReservasiController::class, 'getAvailableSlots'])->name('available-slots');
    });

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('dokters', DokterController::class);
        Route::resource('jadwal-praktek', JadwalPraktekController::class);
        Route::resource('reservasis', AdminReservasiController::class)->only(['index', 'show', 'destroy']);
        Route::post('/reservasis/{reservasi}/confirm', [AdminReservasiController::class, 'confirm'])->name('reservasis.confirm');
        Route::post('/reservasis/{reservasi}/cancel', [AdminReservasiController::class, 'cancel'])->name('reservasis.cancel');
        Route::post('/reservasis/{reservasi}/complete', [AdminReservasiController::class, 'complete'])->name('reservasis.complete');
        Route::get('/admin/dokters/create', [DokterController::class, 'create'])->name('admin.dokters.create');
        Route::post('/admin/dokters', [DokterController::class, 'store'])->name('admin.dokters.store');
    });
});