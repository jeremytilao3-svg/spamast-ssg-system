<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();

            // Which event, and which student (deleted together with their parents)
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            $table->unsignedTinyInteger('missed_scans')->default(0);   // number of scans missed
            $table->decimal('amount', 8, 2)->default(0);               // fine in money
            $table->string('sanction')->nullable();                    // optional non-money sanction

            // unpaid = fine not paid, paid = fine paid, served = sanction completed
            $table->enum('status', ['unpaid', 'paid', 'served'])->default('unpaid');

            $table->timestamps();

            // One penalty row per student per event
            $table->unique(['event_id', 'student_id']);
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};