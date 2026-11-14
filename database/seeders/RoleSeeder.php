<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Fill the roles table with the four roles of the system.
     */
    public function run(): void
    {
        // The role names must be exactly these four
        $roles = ['Admin', 'Student', 'Officer', 'Adviser'];

        foreach ($roles as $name) {
            // firstOrCreate adds the role only if it is not there yet,
            // so running the seeder twice creates no duplicates
            Role::firstOrCreate(['name' => $name]);
        }
    }
}