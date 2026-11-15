<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();   // short code, such as "CCS"; no duplicates
            $table->string('name');             // full name of the institute
            $table->timestamps();
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};