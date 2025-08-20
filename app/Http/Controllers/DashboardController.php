<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Prototype;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menghitung metrik utama
        $totalAgents = User::count();
        $totalEntities = Entity::count();
        $totalPrototypes = Prototype::count();

        // Mengambil 5 entitas yang terakhir diperbarui untuk log aktivitas
        $recentActivities = Entity::orderBy('updated_at', 'desc')->take(5)->get();
        
        // Mengambil satu entitas berbahaya secara acak untuk system alert
        $highThreatRanks = ['Keter', 'Apollyon', 'Omega (Global Threat)', 'Cosmic (Existential Threat)', 'Outer God'];
        $systemAlert = Entity::whereIn('rank', $highThreatRanks)->inRandomOrder()->first();

        // Mengirim semua data ke view
        return view('dashboard', [
            'totalAgents' => $totalAgents,
            'totalEntities' => $totalEntities,
            'totalPrototypes' => $totalPrototypes,
            'recentActivities' => $recentActivities,
            'systemAlert' => $systemAlert,
        ]);
    }
}
