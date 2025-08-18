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
            'remember' => 'boolean', // Memastikan nilainya true/false
        ]);

        // Pisahkan kredensial untuk login
        $credentials = [
            'username' => $validated['username'],
            'password' => $validated['password'],
        ];

        // [DIUBAH] Gunakan nilai 'remember' sebagai argumen kedua di Auth::attempt()
        if (Auth::attempt($credentials, $validated['remember'] ?? false)) {
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
