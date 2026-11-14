<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Create the first Admin account (local lab use only).
     */
    public function run(): void
    {
        // The Admin role must already exist (RoleSeeder creates it)
        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        // Find this email, or create the account if it is missing (no duplicates)
        User::firstOrCreate(
            ['email' => 'admin@spamast.edu.ph'],
            [
                'first_name' => 'System',
                'last_name'  => 'Administrator',
                'password'   => Hash::make('Admin@12345'),
                'is_admin'   => true,
                'is_active'  => true,
                'role_id'    => $adminRole->id,
            ]
        );
    }
}