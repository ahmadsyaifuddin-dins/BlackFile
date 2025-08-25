<?php

namespace App\Http\Controllers\AuthManual;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        // Ambil semua role KECUALI 'Director' untuk pilihan
        // Karena seorang Director seharusnya tidak bisa membuat Director lain
        $roles = Role::where('name', '!=', 'Director')->get();

        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        if (strtolower(Auth::user()->role->name) !== 'director') {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'codename' => 'required|string|max:50|unique:users,codename',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'codename' => $data['codename'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'parent_id' => Auth::id(),
        ]);

        return redirect('/agents')->with('success', 'Agent berhasil dibuat.');
    }
}
