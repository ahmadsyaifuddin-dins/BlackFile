<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

// Import traits
use App\Http\Traits\OsintChecker\SecurityChecks;
use App\Http\Traits\OsintChecker\RateLimiting;
use App\Http\Traits\OsintChecker\SignatureMatching;
use App\Http\Traits\OsintChecker\PlatformDetectors\InstagramDetector;
use App\Http\Traits\OsintChecker\PlatformDetectors\TwitterDetector;
use App\Http\Traits\OsintChecker\PlatformDetectors\GeneralDetector;
use App\Http\Traits\OsintChecker\PlatformDetectors\ThreadsDetector;

class UsernameTrackerController extends Controller
{
    use SecurityChecks,
        RateLimiting,
        SignatureMatching,
        InstagramDetector,
        TwitterDetector,
        GeneralDetector,
        ThreadsDetector;

    /**
     * Display the OSINT username tracker page
     */
    public function index()
    {
        return view('tools.username.index');
    }

    /**
     * Check username availability across platforms
     */
    public function check(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'username' => 'nullable|string|max:100'
        ]);

        $targetUrl = $request->input('url');
        $username = $request->input('username', '');

        // Security Check
        if (!$this->isDomainAllowed($targetUrl)) {
            return response()->json([
                'status' => 'ERROR', 
                'message' => 'Domain not allowed'
            ], 403);
        }

        // Cache Check
        $cacheKey = 'osint_check_' . md5($targetUrl);
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        // Rate Limiting
        if ($this->isRateLimited($request->ip())) {
            return response()->json([
                'status' => 'ERROR', 
                'message' => 'Rate limit exceeded'
            ], 429);
        }

        return $this->executeCheck($targetUrl, $username, $cacheKey);
    }

    /**
     * Execute the main check logic
     */
    private function executeCheck($url, $username, $cacheKey)
    {
        try {
            $response = Http::withHeaders($this->getDefaultHeaders())
                ->withOptions([
                    'verify' => true, 
                    'http_errors' => false, 
                    'allow_redirects' => ['max' => 3, 'protocols' => ['https']]
                ])
                ->timeout(8)
                ->get($url);

            $status = $response->status();
            $body = $response->body();
            $finalUrl = (string)$response->effectiveUri();

            // Debug log
            if (config('app.debug')) {
                Log::info('OSINT Check', [
                    'url' => $url,
                    'status' => $status,
                    'body_length' => strlen($body),
                    'first_1000_chars' => substr($body, 0, 1000),
                    'last_500_chars' => substr($body, -500),
                ]);
            }

            // Priority 1: Check for error signatures
            if ($this->matchesErrorSignature($url, $body)) {
                return $this->storeAndReturn($cacheKey, [
                    'status' => 'NOT_FOUND', 
                    'reason' => 'Account does not exist'
                ], 120);
            }

            // Priority 2: Platform-specific checks
            $platformResult = $this->checkPlatformSpecific($url, $body, $status, $cacheKey);
            if ($platformResult !== null) {
                return $platformResult;
            }

            // Priority 3: Status code checks
            return $this->checkByStatusCode($status, $url, $body, $finalUrl, $cacheKey);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->storeAndReturn($cacheKey, [
                'status' => 'MANUAL_CHECK', 
                'reason' => 'Connection timeout', 
                'suggestion' => 'Use Google Dork'
            ], 15);
        } catch (\Exception $e) {
            Log::error('OSINT Error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            
            return $this->storeAndReturn($cacheKey, [
                'status' => 'ERROR', 
                'message' => 'Request failed'
            ], 10);
        }
    }

    /**
     * Route to platform-specific checkers
     */
    private function checkPlatformSpecific($url, $body, $status, $cacheKey): mixed
    {
        // Instagram
        if (str_contains($url, 'instagram.com')) {
            return $this->checkInstagram($url, $body, $cacheKey);
        }

        // Twitter/X
        if (str_contains($url, 'twitter.com') || str_contains($url, 'x.com')) {
            return $this->checkTwitter($url, $body, $cacheKey);
        }

        if (str_contains($url, 'threads.net')) {
            return $this->checkThreads($url, $body, $cacheKey);
        }

        // Other platforms (Twitch, Steam, Spotify, etc)
        return $this->checkOtherPlatforms($url, $body, $cacheKey);
    }
}