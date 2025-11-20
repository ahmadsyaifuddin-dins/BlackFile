<?php

namespace App\Http\Traits\OsintChecker;

use Illuminate\Support\Facades\Cache;

trait RateLimiting
{
    /**
     * Check if IP is rate limited
     */
    private function isRateLimited($ip): bool
    {
        $key = 'osint_rate_' . $ip;
        $count = Cache::get($key, 0);
        
        if ($count > 5) {
            return true;
        }
        
        Cache::put($key, $count + 1, now()->addSeconds(1));
        return false;
    }

    /**
     * Store result in cache and return JSON response
     */
    private function storeAndReturn($key, $data, $minutes)
    {
        Cache::put($key, $data, now()->addMinutes($minutes));
        return response()->json($data);
    }
}