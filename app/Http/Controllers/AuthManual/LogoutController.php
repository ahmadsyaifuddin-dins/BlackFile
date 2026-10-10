<?php

namespace App\Http\Controllers\AuthManual;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Tandai offline sebelum sesi diakhiri (indikasi realtime di /agents).
        if (Auth::id()) {
            User::query()
                ->where('id', Auth::id())
                ->update(['last_active_at' => null]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
