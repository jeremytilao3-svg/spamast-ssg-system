<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Institute extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['code', 'name'];

    // An institute has many programs
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    // An institute has many students, reached through its programs
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Program::class);
    }
}