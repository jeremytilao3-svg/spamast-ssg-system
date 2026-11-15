<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}