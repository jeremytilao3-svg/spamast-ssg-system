<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();

            // Deleting an event also deletes its sessions
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            // morning + afternoon for a whole-day event, half_day for a half-day event
            $table->enum('period', ['morning', 'afternoon', 'half_day']);

            // Time windows for scanning in and out (empty for manual events)
            $table->time('check_in_start')->nullable();
            $table->time('check_in_end')->nullable();
            $table->time('check_out_start')->nullable();
            $table->time('check_out_end')->nullable();

            // Used by manual events: an Officer opens and closes the scan
            $table->boolean('check_in_open')->default(false);
            $table->boolean('check_out_open')->default(false);

            $table->timestamps();

            // An event can have each period only once
            $table->unique(['event_id', 'period']);
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};