<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Absensi;

class AbsenController extends Controller
{
    // Menampilkan halaman scan QR code
    public function scanPage()
    {
        return view('scan-absen');
    }

    // Memproses hasil scan QR code
    public function processQrCode(Request $request)
    {
        $decodedText = $request->input('qrcode');

        // Ekstrak user_id dari hasil QR code
        $userId = $this->extractUserIdFromQrCode($decodedText);

        // Cari user di database
        $anggota = Anggota::find($userId);
        if ($anggota) {
            // Simpan absensi dengan status "Hadir"
            Absensi::create([
                'anggota_id' => $anggota->id,
                'status' => 'Hadir',
            ]);
            return redirect()->back()->with('success', 'Absensi berhasil');
        }
        return redirect()->back()->with('error', 'QR code tidak valid');
    }

    private function extractUserIdFromQrCode($qrText)
    {
        // Proses untuk mengekstrak user_id dari QR code, misalnya: 'user_id:1'
        return str_replace('user_id:', '', $qrText);
    }
}
