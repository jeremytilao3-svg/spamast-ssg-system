<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();

            // Each program belongs to one institute.
            // restrictOnDelete: an institute that still has programs cannot be deleted.
            $table->foreignId('institute_id')->constrained('institutes')->restrictOnDelete();

            $table->string('code')->unique();   // short code, such as "BSIT"; no duplicates
            $table->string('name');             // full name of the program
            $table->timestamps();
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};