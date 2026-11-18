<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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

    // A user who is a student has one student record
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    // A user can post many announcements
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    // True if the user's role is one of the given names, e.g. hasRole('Admin', 'Officer')
    public function hasRole(string ...$names): bool
    {
        return in_array($this->role?->name, $names, true);
    }

    // The name of the dashboard route for this user's role (Student is the default)
    public function dashboardRoute(): string
    {
        return match ($this->role?->name) {
            'Admin'   => 'admin.dashboard',
            'Officer' => 'officer.dashboard',
            'Adviser' => 'adviser.dashboard',
            default   => 'student.dashboard',
        };
    }

    // Accessor: $user->full_name (first + middle + last, extra spaces removed)
    public function getFullNameAttribute(): string
    {
        return Str::squish($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }
}