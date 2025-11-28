<?php
// app/Models/Reservasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasis';

    protected $fillable = [
        'user_id',
        'dokter_id',
        'tanggal_reservasi',
        'jam_mulai',
        'jam_selesai',
        'keluhan',
        'status',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_reservasi', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('tanggal_reservasi', '>=', today())
                    ->whereIn('status', ['pending', 'confirmed']);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->tanggal_reservasi > now()->addHours(2);
    }
}