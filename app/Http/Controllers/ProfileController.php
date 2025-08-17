<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil dari user yang sedang login.
     */
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    /**
     * Menampilkan form untuk mengedit profil.
     */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * [BARU] Memperbarui profil pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi data yang masuk
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed', // Password opsional
        ]);

        // Update data utama
        $user->name = $validatedData['name'];
        $user->codename = $validatedData['codename'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        /** @var \App\Models\User $user */
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Personal dossier has been updated.');
    }
}