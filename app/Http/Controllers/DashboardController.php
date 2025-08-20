<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Prototype;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- PENTING: Import Auth facade
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menghitung metrik utama (tidak berubah)
        $totalAgents = User::count();
        $totalEntities = Entity::count();
        $totalPrototypes = Prototype::count();

        // Mengambil 5 entitas yang terakhir diperbarui (tidak berubah)
        $recentActivities = Entity::orderBy('updated_at', 'desc')->take(5)->get();

        // Mengambil satu entitas berbahaya secara acak (tidak berubah)
        $highThreatRanks = ['Keter', 'Apollyon', 'Omega (Global Threat)', 'Cosmic (Existential Threat)', 'Outer God'];
        $systemAlert = Entity::whereIn('rank', $highThreatRanks)->inRandomOrder()->first();

        // --- LOGIKA BARU: Mengambil status jaringan agen ---
        $activeAgents = User::whereNotNull('last_active_at')      // Hanya ambil yang pernah aktif
            ->where('id', '!=', Auth::id())  // Kecualikan diri sendiri
            ->orderBy('last_active_at', 'desc') // Urutkan dari yang terbaru
            ->take(4)                        // Ambil 4 agen teratas
            ->get();

        // --- LOGIKA BARU: Menghitung status proyek ---
        $projectStatuses = Prototype::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Mengirim semua data ke view, termasuk data baru
        return view('dashboard', [
            'totalAgents' => $totalAgents,
            'totalEntities' => $totalEntities,
            'totalPrototypes' => $totalPrototypes,
            'recentActivities' => $recentActivities,
            'systemAlert' => $systemAlert,
            'activeAgents' => $activeAgents,
            'projectStatuses' => $projectStatuses,
        ]);
    }
}
