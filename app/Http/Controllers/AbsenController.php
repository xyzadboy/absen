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

        // Ekstrak NIM dari hasil QR code
        $nim = $this->extractNimFromQrCode($decodedText);

        // Cari anggota berdasarkan NIM di database
        $anggota = Anggota::where('nim', $nim)->first();
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

    private function extractNimFromQrCode($qrText)
    {
        // Proses untuk mengekstrak NIM dari QR code, misalnya: 'nim:1234567890'
        return str_replace('nim:', '', $qrText);
    }
}
