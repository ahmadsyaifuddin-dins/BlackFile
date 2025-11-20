<?php

namespace App\Http\Traits\OsintChecker\PlatformDetectors;

use Illuminate\Support\Facades\Log;

trait TwitterDetector
{
    /**
     * Check Twitter/X account
     */
    private function checkTwitter($url, $body, $cacheKey): mixed
    {
        $body_size = strlen($body);
        $username_from_url = preg_match('/(?:twitter|x)\.com\/([^\/\?]+)/', $url, $m) ? $m[1] : null;
        
        // Pattern 1: Cek error messages
        $has_suspended = stripos($body, 'Account suspended') !== false ||
                         stripos($body, 'This account doesn\'t exist') !== false ||
                         stripos($body, 'page doesn\'t exist') !== false ||
                         stripos($body, 'This account owner limits who can view') !== false;
        
        // Pattern 2: Cek script bundles
        $has_main_bundle = preg_match('/client-web\/main\.[a-f0-9]+\.js/', $body);
        $has_vendor_bundle = preg_match('/client-web\/vendor\.[a-f0-9]+\.js/', $body);
        $has_critical_scripts = $has_main_bundle && $has_vendor_bundle;
        
        // Pattern 3: Cek karakteristik page
        $has_placeholder_div = preg_match('/<div id="placeholder">/', $body);
        $has_react_root = preg_match('/<div id="react-root">/', $body);
        
        // Pattern 4: Cek meta tags
        $has_twitter_card = preg_match('/<meta name="twitter:card"/', $body);
        $has_og_type = preg_match('/<meta property="og:type" content="profile"/', $body);
        
        // Pattern 5: Extract page title
        $page_title = preg_match('/<title>([^<]*)<\/title>/i', $body, $title_match) ? trim($title_match[1]) : null;
        
        Log::info('Twitter/X Debug', [
            'url' => $url,
            'username_from_url' => $username_from_url,
            'body_size' => $body_size,
            'has_suspended' => $has_suspended,
            'has_critical_scripts' => $has_critical_scripts,
            'has_og_type' => $has_og_type,
            'page_title' => $page_title,
        ]);
        
        // DECISION LOGIC
        
        // 1. Suspended/doesn't exist -> NOT FOUND
        if ($has_suspended) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Account suspended or does not exist'
            ], 120);
        }
        
        // 2. Body size > 180KB + critical scripts -> FOUND
        if ($body_size > 180000 && $has_critical_scripts) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'FOUND',
                'code' => 200,
                'username' => $username_from_url,
                'detected_via' => 'body_size_and_scripts'
            ], 360);
        }
        
        // 3. Has og:type="profile" -> FOUND
        if ($has_og_type) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'FOUND',
                'code' => 200,
                'username' => $username_from_url,
                'detected_via' => 'og_meta_tag'
            ], 360);
        }
        
        // 4. Page title has @username -> FOUND
        if ($page_title && preg_match('/@([a-zA-Z0-9_]+)/', $page_title, $username_match)) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'FOUND',
                'code' => 200,
                'username' => $username_match[1],
                'detected_via' => 'page_title'
            ], 360);
        }
        
        // 5. Body < 50KB -> NOT FOUND
        if ($body_size < 50000) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Response too small - likely error page',
                'body_size' => $body_size
            ], 120);
        }
        
        // 6. Body 50-180KB without critical scripts -> NOT FOUND
        if ($body_size < 180000 && !$has_critical_scripts) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Missing critical scripts - likely error page',
                'body_size' => $body_size
            ], 120);
        }
        
        // 7. Default: MANUAL CHECK
        return $this->storeAndReturn($cacheKey, [
            'status' => 'MANUAL_CHECK',
            'reason' => 'Ambiguous response - please verify manually',
            'code' => 200,
            'body_size' => $body_size
        ], 30);
    }
}