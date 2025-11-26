<?php

namespace App\Http\Controllers;

use App\Mail\AgentNotification;
use App\Mail\MaintenanceAlert;
use App\Models\Invite;
use App\Models\Role;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        // Ambil semua agent (user terkonfirmasi & BUKAN admin)
        $agents = User::where('confirmed', true)
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin'); // Target semua role KECUALI admin
            })
            ->orderBy('codename')
            ->get();

        $entityNotifyEnabled = SystemSetting::check('entity_notify_enabled', true);

        $maintenanceMode = SystemSetting::check('maintenance_mode', false);

        // Tambahkan 'agents' ke view
        return view('admin.dashboard', compact('pendingApplicants', 'invites', 'agents', 'entityNotifyEnabled', 'maintenanceMode'));
    }

    // Method AJAX untuk toggle
    public function toggleSetting(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|boolean',
        ]);

        SystemSetting::updateOrCreate(
            ['key' => $request->key],
            ['value' => $request->value ? '1' : '0']
        );

        return response()->json(['status' => 'success']);
    }

    /**
     * Method untuk mengirim notifikasi broadcast (langsung/synchronous).
     */
    public function sendNotification(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,selected',
            'agents' => 'required_if:target,selected|array',
            'agents.*' => 'exists:users,id',
        ]);

        $subject = $data['subject'];
        $content = $data['message'];

        $targetAgents = collect();

        // Query dasar untuk agent (terkonfirmasi & BUKAN admin)
        $agentQuery = User::where('confirmed', true)
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            });

        if ($data['target'] === 'all') {
            $targetAgents = $agentQuery->get();
        } else {
            // Ambil hanya ID yang dipilih YANG JUGA memenuhi kriteria agentQuery
            $targetAgents = $agentQuery->whereIn('id', $data['agents'])->get();
        }

        if ($targetAgents->isEmpty()) {
            return back()->withErrors(['message' => 'No valid agents selected for notification.']);
        }

        // Kirim email secara langsung (synchronous)
        foreach ($targetAgents as $agent) {
            try {
                Mail::to($agent->email)->send(new AgentNotification($subject, $content));
            } catch (\Exception $e) {
                // Jika 1 email gagal, catat error dan lanjut ke email berikutnya
                // Anda bisa log errornya di sini jika perlu
                Log::error("Failed to send email to {$agent->email}: ".$e->getMessage());
                // Untuk saat ini, kita abaikan saja agar tidak menghentikan proses
            }
        }

        return back()->with('success', 'Notification broadcast has been sent to '.$targetAgents->count().' agents.');
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'value' => 'required|boolean', // true = mau maintenance, false = mau online
        ]);

        // 1. Simpan ke Database
        // Kita simpan sebagai '1' atau '0' sesuai struktur SystemSetting Anda
        $isMaintenance = $request->value;

        SystemSetting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => $isMaintenance ? '1' : '0']
        );

        // 2. Ambil list Agent untuk dinotifikasi
        // Menggunakan logic query yg sama dengan dashboard agar konsisten
        // Kecuali Admin/Director yg sedang login mungkin tidak perlu dikirim email, tapi dikirim semua juga tidak masalah.
        $agents = User::where('confirmed', true)
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            })->get();

        // 3. Kirim Email (Looping)
        // Note: Untuk production dgn user ribuan, ini sebaiknya pakai Queue (Job).
        // Tapi untuk tahap ini synchronous masih oke.
        foreach ($agents as $agent) {
            try {
                Mail::to($agent->email)->send(new MaintenanceAlert($isMaintenance));
            } catch (\Exception $e) {
                Log::error("Gagal kirim alert maintenance ke {$agent->email}: ".$e->getMessage());
            }
        }

        $statusText = $isMaintenance ? 'ACTIVATED (System Down)' : 'DEACTIVATED (System Online)';

        return response()->json([
            'status' => 'success',
            'message' => "Maintenance Protocol {$statusText}. Alerts sent.",
        ]);
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

        return redirect()->route('admin.dashboard')->with('success', "User '{$user->codename}' has been approved and is now an Allied Operative.");
    }

    /**
     * Menolak dan menghapus aplikasi user.
     */
    public function rejectUser(User $user)
    {
        $codename = $user->codename;
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', "Application for '{$codename}' has been rejected and deleted.");
    }

    /**
     * Membuat token undangan baru.
     */
    public function generateInvite()
    {
        Invite::create([
            'code' => 'inviteByAbsolute-'.Str::random(16),
            'created_by' => Auth::id(),
            'expires_at' => now()->addHour(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'New 1-hour invite token has been generated.');
    }

    public function getApplicantDetails(User $user)
    {
        // Pastikan hanya applicant yang bisa dilihat
        if ($user->confirmed || strtolower($user->role->name) !== 'applicant') {
            abort(404);
        }

        return response()->json($user);
    }
}
