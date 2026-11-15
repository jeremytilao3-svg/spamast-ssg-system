<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // One student record per user account.
            // cascadeOnDelete: deleting the user also deletes this student record.
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // The program the student belongs to (cannot delete a program that has students)
            $table->foreignId('program_id')->constrained('programs')->restrictOnDelete();

            $table->string('student_no')->unique();          // student number; no duplicates
            $table->enum('sex', ['Male', 'Female']);
            $table->unsignedTinyInteger('year_level');       // 1, 2, 3, 4 ...
            $table->timestamps();
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};