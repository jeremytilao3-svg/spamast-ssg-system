<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['user_id', 'title', 'body', 'attachment'];

    // The user who posted the announcement
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}