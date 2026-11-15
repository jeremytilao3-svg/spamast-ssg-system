<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['institute_id', 'code', 'name'];
}