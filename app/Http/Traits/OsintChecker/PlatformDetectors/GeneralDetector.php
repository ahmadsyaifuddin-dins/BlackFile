<?php

namespace App\Http\Traits\OsintChecker\PlatformDetectors;

trait GeneralDetector
{
    /**
     * Check other platforms (Twitch, Steam, Spotify, etc)
     */
    private function checkOtherPlatforms($url, $body, $cacheKey): mixed
    {
        // Twitch: Cek channel data
        if (str_contains($url, 'twitch.tv')) {
            if (!preg_match('/"channelID":"[^"]*"/', $body) && 
                !preg_match('/"displayName":"[^"]*"/', $body)) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'No channel data in response'
                ], 120);
            }
            
            if (strlen($body) < 15000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Profile page too small - likely not found'
                ], 120);
            }
        }

        // Steam: Cek dari title tag
        if (str_contains($url, 'steamcommunity.com')) {
            if (stripos($body, '<title>Steam Community :: Error</title>') !== false) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Steam profile not found'
                ], 120);
            }
        }

        // Spotify: Cek dari body size
        if (str_contains($url, 'spotify.com') && strlen($body) < 10000) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Profile page too small - likely not found'
            ], 120);
        }
        
        // Pinterest: Body size check
        if (str_contains($url, 'pinterest.com') && strlen($body) < 10000) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Profile page too small - likely not found'
            ], 120);
        }
        
        // TikTok: Body size check
        if (str_contains($url, 'tiktok.com') && strlen($body) < 20000) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Profile page too small - likely not found'
            ], 120);
        }
        
        // LinkedIn: Check for profile data
        if (str_contains($url, 'linkedin.com')) {
            if (stripos($body, 'profile-unavailable') !== false ||
                stripos($body, 'page-not-found') !== false) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'LinkedIn profile not found'
                ], 120);
            }
            
            if (strlen($body) < 15000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Profile page too small - likely not found'
                ], 120);
            }
        }
        
        // Discord: Check invite validity
        if (str_contains($url, 'discord.com') || str_contains($url, 'discord.gg')) {
            if (stripos($body, 'invite-invalid') !== false ||
                stripos($body, 'Invite Invalid') !== false ||
                strlen($body) < 5000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Discord invite invalid or expired'
                ], 120);
            }
        }
        
        // ===== IMPROVED TELEGRAM DETECTION =====
        if (str_contains($url, 't.me') || str_contains($url, 'telegram.me')) {
            // Extract username from URL for better checking
            preg_match('/t\.me\/([^\/\?]+)/', $url, $matches);
            $username = $matches[1] ?? '';
            
            // === PRIORITY 1: Check for definitive NOT FOUND indicators ===
            $notFoundPatterns = [
                'If you have <strong>Telegram</strong>, you can contact', // Without @ means suggesting contact
                'This username is not registered',
                'User not found',
                'Channel not found',
                'preview not available',
            ];
            
            // BUT exclude cases where it says "You can contact @username" (this means user exists)
            $hasContactUsername = stripos($body, "contact @{$username}") !== false;
            
            if (!$hasContactUsername) {
                foreach ($notFoundPatterns as $pattern) {
                    if (stripos($body, $pattern) !== false) {
                        return $this->storeAndReturn($cacheKey, [
                            'status' => 'NOT_FOUND',
                            'reason' => 'Telegram user does not exist'
                        ], 120);
                    }
                }
            }
            
            // === PRIORITY 2: Check for "Contact @username" pattern (FOUND) ===
            if ($hasContactUsername) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'FOUND',
                    'reason' => 'Valid Telegram user (contact page)',
                    'note' => 'User exists but may not have public bio/channel'
                ], 360);
            }
            
            // === PRIORITY 3: Check for public profile/channel indicators ===
            $publicProfileIndicators = [
                'tgme_page_photo',        // Has profile photo
                'tgme_page_description',   // Has bio/description
                'tgme_page_context_action', // Has join button
                'tgme_channel_info',       // Channel info
                'class="tgme_page"',       // Standard page wrapper
            ];
            
            $foundIndicators = 0;
            foreach ($publicProfileIndicators as $indicator) {
                if (stripos($body, $indicator) !== false) {
                    $foundIndicators++;
                }
            }
            
            // If found at least 2 indicators, it's a public profile
            if ($foundIndicators >= 2) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'FOUND',
                    'reason' => 'Valid Telegram public profile/channel'
                ], 360);
            }
            
            // === PRIORITY 4: Title check as fallback ===
            if (preg_match('/Telegram:\s*Contact\s*@/i', $body)) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'FOUND',
                    'reason' => 'Valid Telegram user (detected from title)'
                ], 360);
            }
            
            // === PRIORITY 5: Body size check (very small = likely not found) ===
            if (strlen($body) < 2000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Response too small - likely not found'
                ], 120);
            }
        }
        
        // CodePen: Check for user profile data
        if (str_contains($url, 'codepen.io')) {
            if (stripos($body, 'profile-name') === false &&
                stripos($body, 'ProfileHeader') === false) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'CodePen profile not found'
                ], 120);
            }
            
            if (strlen($body) < 8000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Profile page too small - likely not found'
                ], 120);
            }
        }
        
        // Patreon: Check for creator data
        if (str_contains($url, 'patreon.com')) {
            if (stripos($body, 'creator-info') === false &&
                stripos($body, 'campaign-info') === false &&
                strlen($body) < 10000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Patreon page not found'
                ], 120);
            }
        }
        
        // Linktree: Check for profile data
        if (str_contains($url, 'linktr.ee')) {
            if (stripos($body, 'profile-info') === false &&
                stripos($body, 'link-button') === false &&
                strlen($body) < 5000) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Linktree profile not found'
                ], 120);
            }
        }
        
        return null; // No specific check matched
    }

    /**
     * Check based on HTTP status code
     */
    private function checkByStatusCode($status, $url, $body, $finalUrl, $cacheKey): mixed
    {
        // 404 - Not Found
        if ($status === 404) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND', 
                'code' => 404
            ], 120);
        }

        // Cloudflare/Rate Limit
        if (in_array($status, [403, 503, 429])) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'MANUAL_CHECK', 
                'reason' => 'Anti-bot protection/Rate limited', 
                'code' => $status
            ], 30);
        }

        // Login Redirect
        if (str_contains($finalUrl, 'login') || str_contains($finalUrl, 'signin')) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND', 
                'reason' => 'Login required (likely not found)'
            ], 60);
        }

        // GitHub special handling
        if (str_contains($url, 'github.com') && $status === 200) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'FOUND', 
                'code' => $status
            ], 360);
        }

        // General 2xx success
        if ($status >= 200 && $status < 300) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'FOUND', 
                'code' => $status
            ], 360);
        }

        // Default: Manual Check
        return $this->storeAndReturn($cacheKey, [
            'status' => 'MANUAL_CHECK', 
            'code' => $status,
            'reason' => 'Unclear response'
        ], 20);
    }
}