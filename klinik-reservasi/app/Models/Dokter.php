<?php
// app/Models/Dokter.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokters';

    protected $fillable = [
        'nama',
        'spesialisasi',
        'no_telepon',
        'alamat',
        'status',
    ];

    public function jadwalPraktek()
    {
        return $this->hasMany(JadwalPraktek::class);
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function getJadwalHariIniAttribute()
    {
        $hari = now()->translatedFormat('l');
        $hariIndonesia = $this->convertToHariIndonesia($hari);
        
        return $this->jadwalPraktek->where('hari', $hariIndonesia)->first();
    }

    private function convertToHariIndonesia($englishDay)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $days[$englishDay] ?? $englishDay;
    }
}