<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah Maintenance Mode Aktif
        $isMaintenance = SystemSetting::check('maintenance_mode', false);

        if ($isMaintenance) {
            $user = Auth::user();

            // 2. Logic Pengecualian
            // Jika user belum login, biarkan (nanti kena auth middleware biasa)
            // ATAU Jika user adalah 'Director' (Admin), biarkan lewat.
            if ($user && $user->role->name === 'Director') {
                return $next($request);
            }

            // 3. Pengecualian Route Logout
            // Kita harus membiarkan mereka logout meskipun sedang maintenance
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // 4. Blokir User Lain (Agent, Technician, dll)
            // Return view maintenance yang baru kita buat
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
