<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

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

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'specialization' => 'nullable|string|max:255',
            'quotes' => 'nullable|string|max:255',
        ]);

        // Update data teks
        /** @var \App\Models\User $user */
        $user->fill($request->except('password', 'avatar'));

        // [BARU] Logika handle upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            // Buat nama file baru yang unik
            $imageName = strtolower(str_replace(' ', '_', $user->codename)) . '_' . time() . '.' . $request->avatar->extension();

            // Pindahkan file ke public/avatars
            $request->avatar->move(public_path('avatars'), $imageName);

            // [DIUBAH] Simpan path baru ke database DENGAN leading slash
            $user->avatar = '/avatars/' . $imageName;
        }

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Personal Agent has been updated.');
    }
}
