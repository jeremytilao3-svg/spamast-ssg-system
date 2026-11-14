<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() builds the table when you run "php artisan migrate"
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();                       // auto-numbered primary key
            $table->string('name')->unique();   // role name, no duplicates
            $table->timestamps();               // created_at and updated_at
        });
    }

    // down() undoes it when you roll back
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};