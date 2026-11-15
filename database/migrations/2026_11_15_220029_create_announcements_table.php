<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Build the table
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            // Who posted it (deleting the user also deletes their announcements)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('body');                          // the long message
            $table->string('attachment')->nullable();      // optional file path

            $table->timestamps();
        });
    }

    // Undo (used by rollback)
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};