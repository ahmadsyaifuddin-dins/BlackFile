<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        /* *
         * Disk ini fleksibel. Di Local dia pakai default,
         * di Hosting dia baca path dari .env
         */
        'main_uploads' => [
            'driver' => 'local',

            // LOGIKA: "Coba cari variabel GLOBAL_UPLOAD_PATH di .env,
            // kalau tidak ada (di local), pakai public_path('uploads')"
            'root' => env('GLOBAL_UPLOAD_PATH', public_path('uploads')),

            // LOGIKA: "Coba cari variabel GLOBAL_UPLOAD_URL di .env,
            // kalau tidak ada (di local), pakai APP_URL + /uploads"
            'url' => env('GLOBAL_UPLOAD_URL', env('APP_URL').'/uploads'),

            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
