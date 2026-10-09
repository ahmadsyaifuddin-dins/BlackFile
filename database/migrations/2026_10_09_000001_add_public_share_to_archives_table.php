<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            // Mode monetisasi: link publik melewati gerbang iklan sebelum data dibuka.
            $table->boolean('has_ad')->default(false)->after('is_public');

            // Token acak untuk link publik ("/s/{token}") supaya tidak bisa ditebak.
            $table->string('public_token', 64)->nullable()->unique()->after('has_ad');
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn(['has_ad', 'public_token']);
        });
    }
};