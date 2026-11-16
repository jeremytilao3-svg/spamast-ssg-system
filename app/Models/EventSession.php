<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSession extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = [
        'event_id',
        'period',
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'check_in_open',
        'check_out_open',
    ];

    // How Laravel should treat these columns in PHP
    protected function casts(): array
    {
        return [
            'check_in_open'  => 'boolean',   // 1/0 in the database becomes true/false
            'check_out_open' => 'boolean',
        ];
    }

    // Each session belongs to one event
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    // A session has many attendance rows (one per student)
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Is check-in open right now?
    public function isCheckInOpen(): bool
    {
        return $this->isOpen('check_in');
    }

    // Is check-out open right now?
    public function isCheckOutOpen(): bool
    {
        return $this->isOpen('check_out');
    }

    // $type is 'check_in' or 'check_out'
    private function isOpen(string $type): bool
    {
        // Manual events: the Officer's switch decides
        if ($this->event->availability === 'manual') {
            return (bool) $this->{$type . '_open'};
        }

        // Scheduled events: only on the event date...
        if (! $this->event->event_date->isToday()) {
            return false;
        }

        // ...and only between the start and end time
        $start = $this->{$type . '_start'};
        $end = $this->{$type . '_end'};
        $now = now()->format('H:i:s');

        return $start && $end && $now >= $start && $now <= $end;
    }
}