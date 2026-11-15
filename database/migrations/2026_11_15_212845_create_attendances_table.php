<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Which session, and which student (deleted together with their parents)
            $table->foreignId('event_session_id')->constrained('event_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            // When the student scanned in and out (empty until they do)
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();

            $table->timestamps();

            // One attendance row per student per session
            $table->unique(['event_session_id', 'student_id']);
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};