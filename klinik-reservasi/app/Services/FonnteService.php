<?php
// app/Services/FonnteService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN', 'Cd7HTvU8q8ZsDGhdAmST');
        $this->url = 'https://api.fonnte.com/send';
    }

    public function sendMessage($phone, $message)
    {
        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url, [
                'target' => $this->formatPhone($phone),
                'message' => $message,
                'countryCode' => '62',
            ]);

            Log::info('Fonnte Notification Sent', [
                'phone' => $phone,
                'status' => $response->successful(),
                'response' => $response->json()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Fonnte Error', [
                'error' => $e->getMessage(),
                'phone' => $phone
            ]);
            return false;
        }
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    public function sendReservasiConfirmation($reservasi)
    {
        $message = "✅ *KONFIRMASI RESERVASI KLINIK*\n\n";
        $message .= "Halo *{$reservasi->user->name}*,\n\n";
        $message .= "Reservasi Anda telah berhasil dibuat:\n\n";
        $message .= "📅 *Tanggal:* " . $reservasi->tanggal_reservasi->format('d F Y') . "\n";
        $message .= "⏰ *Jam:* {$reservasi->jam_mulai} - {$reservasi->jam_selesai}\n";
        $message .= "👨‍⚕️ *Dokter:* {$reservasi->dokter->nama}\n";
        $message .= "🎯 *Spesialisasi:* {$reservasi->dokter->spesialisasi}\n";
        $message .= "📝 *Status:* Menunggu Konfirmasi\n\n";
        
        if ($reservasi->keluhan) {
            $message .= "📋 *Keluhan:* {$reservasi->keluhan}\n\n";
        }
        
        $message .= "Kami akan mengirim konfirmasi kembali setelah admin memverifikasi.\n";
        $message .= "Terima kasih atas kepercayaan Anda! 🙏";

        return $this->sendMessage($reservasi->user->phone, $message);
    }

    public function sendReservasiAdminConfirmation($reservasi)
    {
        $message = "✅ *RESERVASI TELAH DIKONFIRMASI*\n\n";
        $message .= "Halo *{$reservasi->user->name}*,\n\n";
        $message .= "Reservasi Anda telah *DIKONFIRMASI* oleh admin:\n\n";
        $message .= "📅 *Tanggal:* " . $reservasi->tanggal_reservasi->format('d F Y') . "\n";
        $message .= "⏰ *Jam:* {$reservasi->jam_mulai} - {$reservasi->jam_selesai}\n";
        $message .= "👨‍⚕️ *Dokter:* {$reservasi->dokter->nama}\n";
        $message .= "🎯 *Spesialisasi:* {$reservasi->dokter->spesialisasi}\n\n";
        $message .= "📍 *Harap datang 15 menit sebelum jadwal*\n";
        $message .= "📞 *Bawa bukti reservasi ini*\n\n";
        $message .= "Terima kasih! 🙏";

        return $this->sendMessage($reservasi->user->phone, $message);
    }

    public function sendReservasiCancellation($reservasi, $reason = null)
    {
        $message = "❌ *PEMBATALAN RESERVASI KLINIK*\n\n";
        $message .= "Halo *{$reservasi->user->name}*,\n\n";
        $message .= "Mohon maaf, reservasi Anda telah dibatalkan:\n\n";
        $message .= "📅 *Tanggal:* " . $reservasi->tanggal_reservasi->format('d F Y') . "\n";
        $message .= "⏰ *Jam:* {$reservasi->jam_mulai}\n";
        $message .= "👨‍⚕️ *Dokter:* {$reservasi->dokter->nama}\n\n";
        
        if ($reason) {
            $message .= "📋 *Alasan Pembatalan:* {$reason}\n\n";
        }
        
        $message .= "Silakan buat reservasi baru untuk jadwal lain.\n";
        $message .= "Terima kasih atas pengertiannya. 🙏";

        return $this->sendMessage($reservasi->user->phone, $message);
    }

    public function sendReservasiCompleted($reservasi)
    {
        $message = "🏥 *KUNJUNGAN BERHASIL*\n\n";
        $message .= "Halo *{$reservasi->user->name}*,\n\n";
        $message .= "Kunjungan Anda telah selesai:\n\n";
        $message .= "📅 *Tanggal:* " . $reservasi->tanggal_reservasi->format('d F Y') . "\n";
        $message .= "👨‍⚕️ *Dokter:* {$reservasi->dokter->nama}\n";
        $message .= "🎯 *Spesialisasi:* {$reservasi->dokter->spesialisasi}\n\n";
        $message .= "Terima kasih telah menggunakan layanan kami.\n";
        $message .= "Semoga lekas sembuh! 💚";

        return $this->sendMessage($reservasi->user->phone, $message);
    }
}