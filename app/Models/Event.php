<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = [
        'title',
        'description',
        'venue',
        'event_date',
        'day_type',
        'availability',
        'fine_per_miss',
        'created_by',
    ];

    // How Laravel should treat these columns in PHP
    protected function casts(): array
    {
        return [
            'event_date'    => 'date',          // a date object, not plain text
            'fine_per_miss' => 'decimal:2',     // money with 2 decimals, such as 50.00
        ];
    }

    // An event has many sessions (morning, afternoon or half_day)
    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class);
    }

    // An event has many attendance rows, reached through its sessions
    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, EventSession::class);
    }

    // An event has many penalty rows
    public function penalties(): HasMany
    {
        return $this->hasMany(Penalty::class);
    }

    // The user who created the event (the column is created_by, not user_id)
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}