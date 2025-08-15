<?php

namespace App\Http\Controllers\AuthManual;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        // Pastikan hanya Director yang bisa akses
        if (Auth::user()->role->name !== 'director') {
            abort(403, 'Unauthorized');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (Auth::user()->role->name !== 'director') {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => 'required|string|max:50|unique:users,codename',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $data['name'],
            'codename' => $data['codename'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return redirect('/dashboard')->with('success', 'Akun berhasil dibuat.');
    }
}
