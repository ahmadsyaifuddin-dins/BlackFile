<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && isset(Auth::user()->settings['locale'])) {
            App::setLocale(Auth::user()->settings['locale']);
        }
        return $next($request);
    }
}