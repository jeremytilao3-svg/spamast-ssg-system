<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    // Columns that may be filled in by create() or update()
    protected $fillable = ['name'];

    // One role has many users (for example, many users are Students)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}