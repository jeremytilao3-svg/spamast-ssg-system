<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['user_id', 'program_id', 'student_no', 'sex', 'year_level'];

    // Each student record belongs to one login account
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Each student belongs to one program
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}