<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App; 
use Illuminate\Support\Carbon;    

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // [BARU] Mengatur locale untuk Carbon (library tanggal Laravel)
        // agar mengikuti locale aplikasi yang sedang aktif.
        Carbon::setLocale(App::getLocale());
    }
}
