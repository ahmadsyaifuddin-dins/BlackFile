<?php

namespace App\Http\Traits\OsintChecker;

trait SecurityChecks
{
    /**
     * Check if domain is allowed
     */
    private function isDomainAllowed($url): bool
    {
        $allowedDomains = [
            'github.com', 'instagram.com', 'facebook.com', 'twitter.com', 'x.com',
            'tiktok.com', 'youtube.com', 'pinterest.com', 'reddit.com', 'medium.com',
            'spotify.com', 'soundcloud.com', 'twitch.tv', 'steamcommunity.com',
            'vimeo.com', 'gitlab.com',
            // New platforms
            'linkedin.com', 'discord.com', 't.me', 'telegram.me', 'threads.net',
            'codepen.io', 'patreon.com', 'linktr.ee'
        ];
        
        $host = parse_url($url, PHP_URL_HOST) ?? '';
        
        foreach ($allowedDomains as $domain) {
            if (str_ends_with($host, $domain)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get default HTTP headers for requests
     */
    private function getDefaultHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.5',
            'DNT' => '1',
            'Upgrade-Insecure-Requests' => '1',
        ];
    }
}