<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestFonnteController extends Controller
{
    public function test()
    {
        $token = "Cd7HTvU8q8ZsDGhdAmST";
        $target = "6285167655225";
        
        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => '🎉 Test notifikasi dari Laravel!\n\nIni test sederhana dalam folder Laravel.',
                'countryCode' => '62',
            ]);

            $responseData = $response->json();

            if ($responseData['status']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil dikirim!',
                    'data' => $responseData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim notifikasi',
                    'error' => $responseData['reason'] ?? 'Unknown error'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function testForm()
    {
        return view('test-fonnte');
    }

    public function sendCustomMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string'
        ]);

        $token = "Cd7HTvU8q8ZsDGhdAmST";
        
        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $request->phone,
                'message' => $request->message,
                'countryCode' => '62',
            ]);

            $responseData = $response->json();

            if ($responseData['status']) {
                return back()->with('success', 'Notifikasi berhasil dikirim!');
            } else {
                return back()->with('error', 'Gagal: ' . ($responseData['reason'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}