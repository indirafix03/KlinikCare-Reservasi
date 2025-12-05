<?php
// app/Http\Controllers\HomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Constructor untuk menerapkan middleware
     * Middleware 'auth' memastikan hanya user yang terautentikasi yang bisa mengakses
     */
    public function __construct()
    {
        // Terapkan middleware auth untuk semua method dalam controller ini
        $this->middleware('auth');
    }

    /**
     * Menangani redirect setelah login berdasarkan role user
     * 
     * Fungsi ini akan diakses setelah user berhasil login melalui Laravel's Auth
     * Sistem akan otomatis redirect ke halaman ini setelah login berhasil
     * 
     * @return \Illuminate\Http\RedirectResponse - Redirect ke dashboard sesuai role
     */
    public function index()
    {
        // Cek apakah user yang login adalah admin
        if (Auth::user()->isAdmin()) {
            // Redirect admin ke dashboard admin
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect user biasa ke dashboard user
        return redirect()->route('user.dashboard');
    }
}