<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['event_id', 'student_id', 'missed_scans', 'amount', 'sanction', 'status'];

    // How Laravel should treat these columns in PHP
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',   // money with 2 decimals
        ];
    }

    // The event this penalty is for
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    // The student who has the penalty
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}