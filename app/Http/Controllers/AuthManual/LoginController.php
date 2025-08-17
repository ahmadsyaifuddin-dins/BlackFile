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

    public function login(Request $request) // Hapus type-hint RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Kirim respons JSON jika sukses
            return response()->json(['status' => 'success', 'redirect' => route('dashboard')]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
    
            // [BARU] Update timestamp aktivitas terakhir
            $user = Auth::user();
            $user->last_active_at = now();
            /** @var \App\Models\User $user */
            $user->save();
        }

        // Kirim respons JSON jika gagal
        return response()->json([
            'status' => 'denied',
            'message' => 'ACCESS DENIED. Invalid credentials.'
        ], 422); // 422 Unprocessable Entity
    }
}
