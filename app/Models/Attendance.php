<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    // The table name, written out so Laravel never guesses it wrongly
    protected $table = 'attendances';

    // Columns that may be filled in by create() or update()
    protected $fillable = ['event_session_id', 'student_id', 'check_in_at', 'check_out_at'];

    // How Laravel should treat these columns in PHP
    protected function casts(): array
    {
        return [
            'check_in_at'  => 'datetime',   // a date-and-time object
            'check_out_at' => 'datetime',
        ];
    }

    // The session this row belongs to (the method is "session", but the model is EventSession)
    public function session(): BelongsTo
    {
        return $this->belongsTo(EventSession::class, 'event_session_id');
    }

    // The student who scanned
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}