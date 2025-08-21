<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MasterPasswordController extends Controller
{
    /**
     * Menampilkan form untuk membuat Master Password.
     */
    public function create()
    {
        // Jika pengguna sudah punya Master Password, jangan biarkan mereka membuat lagi.
        if (Auth::user()->master_password) {
            return redirect()->route('encrypted-contacts.index');
        }

        return view('master-password.setup');
    }

    /**
     * Menyimpan Master Password yang baru dibuat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'master_password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $user = Auth::user();

        // Simpan HASH dari master password, bukan password aslinya.
        $user->master_password = Hash::make($request->master_password);
        /** @var \App\Models\User $user */
        $user->save();

        return redirect()->route('encrypted-contacts.index')->with('success', 'Master Password has been set. The vault is now accessible.');
    }
}
