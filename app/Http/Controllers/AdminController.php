<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin utama.
     */
    public function dashboard()
    {
        // Ambil semua user yang belum dikonfirmasi (role: applicant)
        $pendingApplicants = User::where('confirmed', false)
            ->whereHas('role', function ($query) {
                $query->where('name', 'applicant');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua token undangan yang pernah dibuat
        $invites = Invite::with('creator')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('pendingApplicants', 'invites'));
    }

    public function approveUser(User $user)
    {
        // Ambil role 'affiliate' dari database
        $affiliateRole = Role::where('name', 'affiliate')->firstOrFail();

        // Update user: konfirmasi dan ubah role
        $user->update([
            'confirmed' => true,
            'role_id' => $affiliateRole->id,
        ]);

        // Di sini Anda bisa menambahkan notifikasi email ke user
        // Mail::to($user->email)->send(new AccountApprovedMail());

        return redirect()->route('admin.dashboard')->with('success', "User '{$user->codename}' has been approved and is now an Allied Operative.");
    }

    /**
     * Menolak dan menghapus aplikasi user.
     */
    public function rejectUser(User $user)
    {
        $codename = $user->codename;
        $user->delete();

        // Di sini Anda bisa menambahkan notifikasi email ke user
        // Mail::to($user->email)->send(new AccountRejectedMail());

        return redirect()->route('admin.dashboard')->with('success', "Application for '{$codename}' has been rejected and deleted.");
    }

    /**
     * Membuat token undangan baru.
     */
    public function generateInvite()
    {
        Invite::create([
            'code' => 'inviteByAbsolute-' . Str::random(16),
            'created_by' => Auth::id(),
            // [DIUBAH] Atur waktu kadaluwarsa 1 jam dari sekarang
            'expires_at' => now()->addHour(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'New 1-hour invite token has been generated.');
    }

    // [BARU] Method untuk mengambil detail applicant via AJAX
    public function getApplicantDetails(User $user)
    {
        // Pastikan hanya applicant yang bisa dilihat
        if ($user->confirmed || strtolower($user->role->name) !== 'applicant') {
            abort(404);
        }
        return response()->json($user);
    }
}
