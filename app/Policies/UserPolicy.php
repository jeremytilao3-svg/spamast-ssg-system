<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Can $user delete the account $model?
    // Yes, but only an Admin, and never their own account
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Admin') && $user->id !== $model->id;
    }
}