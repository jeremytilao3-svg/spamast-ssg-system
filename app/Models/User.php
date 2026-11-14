<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Columns that may be filled in by create() or update()
#[Fillable(['first_name', 'last_name', 'middle_name', 'email', 'password', 'role_id', 'is_active', 'is_admin'])]
// Columns hidden when the user is turned into an array or JSON
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // 1/0 in the database becomes true/false in PHP
            'is_admin' => 'boolean',
        ];
    }

    // Each user belongs to one role (users.role_id points to roles.id)
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}