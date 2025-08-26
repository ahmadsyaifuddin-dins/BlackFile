<?php

namespace App\Http\Controllers\AuthManual;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        $inviteCode = $request->query('invite_code', '');
        return view('auth.register', ['inviteCode' => $inviteCode]);
    }

    public function register(Request $request)
    {
        // 1. Validasi Input Awal (tidak berubah)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'codename' => ['required', 'string', 'max:50', 'unique:users,codename'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'invite_code' => ['nullable', 'string'],
        ]);

        // 2. Logika Anti-Spam (tidak berubah)
        $limiterKey = 'register-attempt:' . Str::lower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($limiterKey, 1)) {
            $seconds = RateLimiter::availableIn($limiterKey);
            $days = ceil($seconds / 86400);
            return back()->withErrors(['email' => "You must wait {$days} more day(s) to register again."])->withInput();
        }

        // 3. Tentukan Role dan Status berdasarkan Token
        $applicantRole = Role::where('name', 'applicant')->firstOrFail();
        $affiliateRole = Role::where('name', 'affiliate')->firstOrFail();

        $roleId = $applicantRole->id;
        $isConfirmed = false;

        if ($request->filled('invite_code')) {
            $invite = Invite::where('code', $request->invite_code)
                            ->where('used', false)
                            ->where('expires_at', '>', now())
                            ->first();

            if (!$invite) {
                return back()->withErrors(['invite_code' => 'The provided invite token is invalid, has expired, or has already been used.'])->withInput();
            }

            $roleId = $affiliateRole->id;
            $isConfirmed = true;
            $invite->update(['used' => true]);
        }
        
        // 4. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'codename' => $request->codename,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'temp_password' => $request->password,
            'role_id' => $roleId,
            'confirmed' => $isConfirmed,
            'last_active_at' => now(),
        ]);
        
        RateLimiter::hit($limiterKey, 3 * 24 * 60 * 60);

        // 5. [LOGIKA BARU] Redirect berdasarkan status konfirmasi
        if ($isConfirmed) {
            // Jika dikonfirmasi via token, langsung login dan redirect ke dashboard
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('status', 'Welcome! Registration successful.');
        } else {
            // Jika menunggu persetujuan, redirect kembali ke halaman register dengan pesan khusus
            return redirect()->route('register')->with('pending_approval', true);
        }
    }
}