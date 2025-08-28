<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // NOTE: The logic for adding 'role' and 'slug' to the 'users' table has been removed,
        // as the provided database schema already handles user roles via a dedicated 'roles' table.

        // Create the credits table
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // This 'role' column is for the specific title in the end credits,
            // e.g., "Lead Analyst", "Field Operator", "Project Director".
            // It is intentionally a string and not a foreign key to the main 'roles' table.
            $table->string('role'); 
            
            $table->string('name'); // e.g., "John Doe"
            $table->json('logos')->nullable(); // Store multiple logo paths as a JSON array
            $table->string('music_path')->nullable(); // Path to the background music for this user's credits
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
