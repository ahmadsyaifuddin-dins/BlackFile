<?php

namespace App\Http\Controllers\AuthManual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // [DIUBAH] Tambahkan 'remember' ke validasi
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        // Pisahkan kredensial untuk login
        $credentials = [
            'username' => $validated['username'],
            'password' => $validated['password'],
        ];

        // [DIUBAH] Gunakan nilai 'remember' sebagai argumen kedua di Auth::attempt()
        if (Auth::attempt($credentials, $validated['remember'] ?? false)) {

            // 3. [PENGECEKAN BARU] Periksa apakah akun sudah dikonfirmasi
            if (Auth::user()->confirmed == false) {
                // Jika belum, langsung logout lagi
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kirim pesan error spesifik kembali ke form login
                return response()->json([
                    'status' => 'denied',
                    'message' => 'ACCESS DENIED. Akun Anda sedang menunggu persetujuan dari Direktur.'
                ], 403); // 403 Forbidden
            }

            // 4. Jika sudah dikonfirmasi, lanjutkan proses login seperti biasa
            $request->session()->regenerate();

            // Update last_active_at (jika Anda masih menggunakan ini)
            $user = Auth::user();
            $user->last_active_at = now();
            
            /** @var \App\Models\User $user */
            $user->save();

            return response()->json(['status' => 'success', 'redirect' => route('dashboard')]);
        }

        return response()->json([
            'status' => 'denied',
            'message' => 'ACCESS DENIED. Invalid credentials.'
        ], 422);
    }
}
