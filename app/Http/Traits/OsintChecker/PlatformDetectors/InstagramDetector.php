<?php

namespace App\Http\Traits\OsintChecker\PlatformDetectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait InstagramDetector
{
    /**
     * Check Instagram account
     */
    private function checkInstagram($url, $body, $cacheKey): mixed
    {
        // Extract username dari URL
        $username = preg_match('/instagram\.com\/([^\/\?]+)/', $url, $m) ? $m[1] : null;
        
        if (!$username) {
            return null;
        }
        
        // Strategy 1: Cek Instagram API langsung
        try {
            $api_url = "https://www.instagram.com/api/v1/users/web_profile_info/?username=" . urlencode($username);
            $api_response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'X-IG-App-ID' => '936619743392459',
                'X-Requested-With' => 'XMLHttpRequest'
            ])
            ->timeout(5)
            ->get($api_url);
            
            if ($api_response->successful()) {
                $api_data = $api_response->json();
                
                if (isset($api_data['data']['user']) && $api_data['data']['user']) {
                    Log::info('Instagram API Success', ['username' => $username, 'found' => true]);
                    
                    return $this->storeAndReturn($cacheKey, [
                        'status' => 'FOUND',
                        'code' => 200,
                        'username' => $username
                    ], 360);
                }
            }
            
            if ($api_response->status() === 404 || !isset($api_data['data']['user'])) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Account does not exist (API verification)'
                ], 120);
            }
            
        } catch (\Exception $e) {
            Log::warning('Instagram API Exception', [
                'username' => $username,
                'error' => $e->getMessage()
            ]);
        }
        
        // Strategy 2: Fallback HTML analysis
        $has_error_text = stripos($body, 'Sorry, this page isn\'t available') !== false || 
                          stripos($body, 'The link may be broken') !== false ||
                          stripos($body, 'Profile isn\'t available') !== false;
        
        if ($has_error_text) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Account does not exist (error message)'
            ], 120);
        }
        
        // Strategy 3: Body size check
        if (strlen($body) < 15000) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND', 
                'reason' => 'Profile page too small - likely not found'
            ], 120);
        }
        
        // Default: Manual check
        return $this->storeAndReturn($cacheKey, [
            'status' => 'MANUAL_CHECK',
            'reason' => 'Unable to verify via API or HTML',
            'code' => 200
        ], 30);
    }
}