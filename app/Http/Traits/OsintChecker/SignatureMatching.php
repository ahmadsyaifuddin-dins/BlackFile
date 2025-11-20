<?php

namespace App\Http\Traits\OsintChecker;

trait SignatureMatching
{
    /**
     * Get error signatures for different platforms
     */
    private function getErrorSignatures(): array
    {
        return [
            'github.com' => [
                'This is not the web page you are looking for',
                '<title>Page not found · GitHub</title>',
                'could not be found on GitHub',
            ],
            'instagram.com' => [
                'Profile isn\'t available',
                'Sorry, this page isn\'t available',
                'The link may be broken, or the profile may have been removed.',
                'page not found',
                'Sorry. It looks like you may have taken a wrong turn.',
            ],
            'facebook.com' => [
                'This Page Isn\'t Available',
                'The link you followed may be broken',
                'Content Not Found',
            ],
            'twitter.com' => [
                'This account doesn\'t exist',
                'Account suspended',
                'page doesn\'t exist',
            ],
            'x.com' => [
                'This account doesn\'t exist',
                'Account suspended',
                'page doesn\'t exist',
            ],
            'tiktok.com' => [
                'Couldn\'t find this account',
                'page not available',
                'This account cannot be found',
                'Looking for videos? Try browsing',
            ],
            'youtube.com' => [
                'This page isn\'t available',
                'This channel doesn\'t exist',
                'Halaman ini tidak tersedia',
            ],
            'pinterest.com' => [
                'Sorry, we couldn\'t find that page',
                'Board not found',
                'Profile not found',
            ],
            'reddit.com' => [
                'Sorry, nobody on Reddit goes by that name', 
                'Sorry, there aren\'t any communities',
                'page not found'
            ],
            'medium.com' => [
                'PAGE NOT FOUND',
                'Page not found'
            ],
            'soundcloud.com' => [
                'We can\'t find that user',
                'The page you were looking for doesn\'t exist'
            ],
            'steamcommunity.com' => [
                'The specified profile could not be found',
                'This user has not yet set up'
            ],
            'vimeo.com' => [
                '404 Page Not Found',
                'Oops! We couldn\'t find that page'
            ],
            'gitlab.com' => [
                'Page Not Found',
                'The page you\'re looking for could not be found'
            ],
            'twitch.tv' => [
                'Sorry. Unless you\'ve got a time machine',
                'content is unavailable',
            ],
            'spotify.com' => [
                'Page not found',
                'We can\'t seem to find the page you are looking for'
            ],
            'linkedin.com' => [
                'This page doesn\'t exist',
                'We couldn\'t find that page',
                'Page not found',
                'The page you\'re trying to reach',
            ],
            'discord.com' => [
                'Invalid Invite',
                'Invite Invalid',
                'This invite may be expired',
                'No Text Channels',
            ],
            // TELEGRAM - REMOVED FROM SIGNATURE MATCHING
            // Telegram detection is now handled entirely in GeneralDetector
            // because it requires more complex logic (contact pages vs not found)
            't.me' => [],
            'telegram.me' => [],
            'threads.net' => [
                'Sorry, this page isn\'t available',
                'The link you followed may be broken',
                'Page Not Found',
            ],
            'codepen.io' => [
                'Page Not Found',
                '404 - This is not the page you\'re looking for',
                'Whoops, that page is gone.',
            ],
            'patreon.com' => [
                'Page not found',
                'Sorry, we can\'t find the page you\'re looking for',
                'This page is unavailable',
            ],
            'linktr.ee' => [
                'The page you\'re looking for doesn\'t exist',
                'This Linktree does not exist',
                'Page not found',
            ],
        ];
    }

    /**
     * Check if body contains error signatures
     */
    private function matchesErrorSignature($url, $body): bool
    {
        $signatures = $this->getErrorSignatures();
        
        foreach ($signatures as $domain => $sigs) {
            if (str_contains($url, $domain)) {
                // Skip if no signatures defined (like Telegram)
                if (empty($sigs)) {
                    continue;
                }
                
                foreach ($sigs as $sig) {
                    $normalizedBody = preg_replace('/\s+/', ' ', $body);
                    $normalizedSig = preg_replace('/\s+/', ' ', $sig);
                    
                    if (stripos($normalizedBody, $normalizedSig) !== false) {
                        return true;
                    }
                    
                    if (stripos(str_replace(' ', '', $normalizedBody), str_replace(' ', '', $normalizedSig)) !== false) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}