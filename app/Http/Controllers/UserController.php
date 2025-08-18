<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua agen (direktori).
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->orderBy('codename')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan profil spesifik seorang agen (read-only).
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

     /**
     * [BARU] Menampilkan form untuk mengedit data user lain.
     */
    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'Director')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * [BARU] Memperbarui data user lain.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6|confirmed',
            'specialization' => 'nullable|string|max:255',
            'quotes' => 'nullable|string',
        ]);
        
        // Update data utama, kecuali password
        $user->update($request->except('password'));

        // [LOGIKA BARU] Hanya update password jika field-nya diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('agents.index')->with('success', "Agent {$user->codename}'s Agent has been updated.");
    }

    /**
     * [BARU] Menghapus user.
     */
    public function destroy(User $user)
    {
        // Pengaman: Director tidak bisa menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->withErrors(['msg' => 'A Director cannot terminate their own Agent.']);
        }
        
        $codename = $user->codename;
        $user->delete();

        return redirect()->route('agents.index')->with('success', "Agent {$codename} has been successfully terminated.");
    }
}