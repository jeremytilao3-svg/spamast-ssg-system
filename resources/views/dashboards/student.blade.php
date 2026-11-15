<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['user_id', 'program_id', 'student_no', 'sex', 'year_level'];
}