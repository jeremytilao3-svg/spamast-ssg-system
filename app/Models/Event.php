<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}