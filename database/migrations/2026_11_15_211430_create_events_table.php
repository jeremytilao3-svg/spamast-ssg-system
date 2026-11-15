<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();      // optional long text
            $table->string('venue');
            $table->date('event_date');

            // whole_day = morning + afternoon sessions, half_day = one session
            $table->enum('day_type', ['whole_day', 'half_day']);

            // scheduled = opens by the clock, manual = an Officer opens and closes it
            $table->enum('availability', ['scheduled', 'manual'])->default('scheduled');

            // Money charged for each missed scan (8 digits in total, 2 after the point)
            $table->decimal('fine_per_miss', 8, 2)->default(0);

            // Who created the event; if that user is deleted, keep the event and set this to NULL
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};