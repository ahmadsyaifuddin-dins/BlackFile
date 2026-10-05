<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint untuk pengecekan lisensi "Dead Hand"
Route::get('/verify-license', function (Request $request) {

    // 1. Tangkap Data dari Klien
    $clientKey = $request->query('key'); // License Key
    $clientApp = $request->query('app_name'); // Nama Project

    // 2. DATABASE LISENSI SEDERHANA (Hardcode dulu biar cepat)
    // Format: 'LICENSE-KEY' => ['status' => 'active/blocked', 'message' => 'Pesan untuk klien']
    $licenses = [
        'JOKI-PROJECT-SMAN3-2026' => [
            'status' => 'active',
            'message' => 'Lisensi Valid.',
        ],
        'JOKI-PROJECT-LIZA-PKL' => [
            'status' => 'blocked',
            'message' => 'Masa percobaan aplikasi telah habis. Silakan hubungi developer untuk perpanjangan.',
        ],
        // Tambahkan user lain di sini nanti
    ];

    // 3. Logika Pengecekan
    if (array_key_exists($clientKey, $licenses)) {
        // Jika Key Ditemukan, kembalikan status sesuai database
        return response()->json($licenses[$clientKey]);
    }

    // 4. Jika Key Tidak Dikenal (Maling/Bajakan)
    return response()->json([
        'status' => 'blocked',
        'message' => 'Lisensi Tidak Terdaftar! Akses Ditolak.',
    ], 403);
});
