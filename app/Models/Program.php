<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['institute_id', 'code', 'name'];

    // Each program belongs to one institute
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    // A program has many students
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}