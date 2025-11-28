<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokter;
use App\Models\JadwalPraktek;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '6281234567890',
        ]);

        // Create sample user
        User::create([
            'name' => 'Pasien Sample',
            'email' => 'pasien@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '6285167655225',
        ]);

        // Create sample doctors
        $dokter1 = Dokter::create([
            'nama' => 'Dr. Budi Santoso',
            'spesialisasi' => 'Dokter Umum',
            'no_telepon' => '6281122334455',
            'alamat' => 'Jl. Kesehatan No. 123',
            'status' => 'aktif',
        ]);

        $dokter2 = Dokter::create([
            'nama' => 'Dr. Sari Indah',
            'spesialisasi' => 'Dokter Gigi',
            'no_telepon' => '6281122334466',
            'alamat' => 'Jl. Sejahtera No. 456',
            'status' => 'aktif',
        ]);

        // Create sample schedules
        JadwalPraktek::create([
            'dokter_id' => $dokter1->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'durasi_per_pasien' => 30,
        ]);

        JadwalPraktek::create([
            'dokter_id' => $dokter1->id,
            'hari' => 'Rabu',
            'jam_mulai' => '14:00',
            'jam_selesai' => '17:00',
            'durasi_per_pasien' => 30,
        ]);

        JadwalPraktek::create([
            'dokter_id' => $dokter2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '15:00',
            'durasi_per_pasien' => 45,
        ]);
    }
}