<?php
// app/Models/JadwalPraktek.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPraktek extends Model
{
    use HasFactory;

    protected $table = 'jadwal_praktek';

    protected $fillable = [
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'durasi_per_pasien',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function generateTimeSlots()
    {
        $slots = [];
        $start = strtotime($this->jam_mulai);
        $end = strtotime($this->jam_selesai);
        $duration = $this->durasi_per_pasien * 60;

        $current = $start;
        while ($current + $duration <= $end) {
            $slots[] = [
                'start' => date('H:i', $current),
                'end' => date('H:i', $current + $duration),
            ];
            $current += $duration;
        }

        return $slots;
    }
}