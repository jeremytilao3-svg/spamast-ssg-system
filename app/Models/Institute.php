<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['code', 'name'];
}