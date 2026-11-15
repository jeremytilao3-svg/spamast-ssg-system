<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Sample data for local lab use only.
     * Safe to run twice: nothing is duplicated.
     */
    public function run(): void
    {
        // Same demo password for every account (hashed, never stored as plain text)
        $password = Hash::make('Student@12345');

        // Role name => role id, for example ['Officer' => 3, 'Student' => 2]
        $roles = Role::pluck('id', 'name');

        // 1. Institutes and their programs (sample names)
        $catalog = [
            ['code' => 'ICS', 'name' => 'Institute of Computer Studies', 'programs' => [
                ['BSIT', 'Bachelor of Science in Information Technology'],
                ['BSCS', 'Bachelor of Science in Computer Science'],
            ]],
            ['code' => 'IBM', 'name' => 'Institute of Business and Management', 'programs' => [
                ['BSBA', 'Bachelor of Science in Business Administration'],
                ['BSOA', 'Bachelor of Science in Office Administration'],
            ]],
            ['code' => 'IAF', 'name' => 'Institute of Agriculture and Fisheries', 'programs' => [
                ['BSA', 'Bachelor of Science in Agriculture'],
                ['BSF', 'Bachelor of Science in Fisheries'],
            ]],
            ['code' => 'IED', 'name' => 'Institute of Education', 'programs' => [
                ['BEED', 'Bachelor of Elementary Education'],
                ['BSED', 'Bachelor of Secondary Education'],
            ]],
        ];

        $programIds = [];

        foreach ($catalog as $item) {
            // Find by code, or create it; update the name if it changed
            $institute = Institute::updateOrCreate(
                ['code' => $item['code']],
                ['name' => $item['name']]
            );

            foreach ($item['programs'] as [$code, $name]) {
                $program = Program::updateOrCreate(
                    ['code' => $code],
                    ['institute_id' => $institute->id, 'name' => $name]
                );

                $programIds[] = $program->id;
            }
        }

        // 2. One Officer and one Adviser
        User::firstOrCreate(
            ['email' => 'officer@spamast.edu.ph'],
            [
                'first_name' => 'Olivia',
                'last_name'  => 'Officer',
                'password'   => $password,
                'role_id'    => $roles['Officer'],
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'adviser@spamast.edu.ph'],
            [
                'first_name' => 'Adrian',
                'last_name'  => 'Adviser',
                'password'   => $password,
                'role_id'    => $roles['Adviser'],
                'is_active'  => true,
            ]
        );

        // 3. Twenty students (10 male, 10 female) spread across the programs
        $maleNames   = ['Juan', 'Pedro', 'Mark', 'Carlo', 'Jose', 'Miguel', 'Paolo', 'Rafael', 'Andres', 'Daniel'];
        $femaleNames = ['Maria', 'Ana', 'Grace', 'Liza', 'Rosa', 'Carla', 'Jenny', 'Clara', 'Sofia', 'Nina'];
        $lastNames   = ['Cruz', 'Reyes', 'Santos', 'Garcia', 'Mendoza', 'Torres', 'Flores', 'Ramos', 'Aquino', 'Bautista',
                        'Villanueva', 'Castillo', 'Navarro', 'Domingo', 'Salazar', 'Lim', 'Tan', 'Dela Cruz', 'Soriano', 'Fernandez'];

        for ($i = 1; $i <= 20; $i++) {
            $isMale = $i % 2 === 1;                               // odd = male, even = female
            $names  = $isMale ? $maleNames : $femaleNames;
            $first  = $names[intdiv($i - 1, 2)];

            // The login account of the student
            $user = User::firstOrCreate(
                ['email' => sprintf('student%02d@spamast.edu.ph', $i)],
                [
                    'first_name' => $first,
                    'last_name'  => $lastNames[$i - 1],
                    'password'   => $password,
                    'role_id'    => $roles['Student'],
                    'is_active'  => true,
                ]
            );

            // The student record linked to that account
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'program_id' => $programIds[($i - 1) % count($programIds)],
                    'student_no' => sprintf('2026-%04d', $i),
                    'sex'        => $isMale ? 'Male' : 'Female',
                    'year_level' => (($i - 1) % 4) + 1,
                ]
            );
        }
    }
}