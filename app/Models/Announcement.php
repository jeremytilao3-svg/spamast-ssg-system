<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['user_id', 'title', 'body', 'attachment'];
}