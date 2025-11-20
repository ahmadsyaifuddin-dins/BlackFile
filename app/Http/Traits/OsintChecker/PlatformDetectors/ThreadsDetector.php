<?php

namespace App\Http\Traits\OsintChecker\PlatformDetectors;

trait ThreadsDetector
{
    /**
     * Check Threads platform specifically
     */
    private function checkThreads($url, $body, $cacheKey): mixed
    {
        // Check for the specific error message from Threads
        $errorSignatures = [
            'Not all who wander are lost, but this page is',
            'The link\'s not working or the page is gone',
            'Go back to keep exploring',
        ];
        
        foreach ($errorSignatures as $signature) {
            if (stripos($body, $signature) !== false) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND',
                    'reason' => 'Threads profile not found'
                ], 120);
            }
        }
        
        // Check for the specific span class that contains the error message
        if (preg_match('/span\.x1lliihq\.x1plvlek\.xryxfnj\.x1n2onr6\.xeuyipt\.x15dfinx\.x193iq5w\.xeuugli\.x1fj9vlw/i', $body) ||
            preg_match('/Not all who wander are lost/i', $body)) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Threads profile not found - error page detected'
            ], 120);
        }
        
        // Additional check: if body is too small (less than 10KB), likely not found
        if (strlen($body) < 10000) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'NOT_FOUND',
                'reason' => 'Profile page too small - likely not found'
            ], 120);
        }
        
        // If we reach here and status is 200, it's likely a real profile
        return null; // Continue to general checks
    }
}