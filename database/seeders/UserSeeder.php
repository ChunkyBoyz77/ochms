<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /* ================= ADMIN ================= */
        User::create([
            'name' => 'JHEPA Admin',
            'email' => 'admin@umpsa.edu.my',
            'password' => Hash::make('password'),
            'phone_number' => '09-4246000',
            'role' => 'admin',
        ]);

        /* ================= LANDLORD USERS ================= */
        $landlords = [
            ['Ahmad Zainal bin Rahman', 'ahmad.landlord@gmail.com'],
            ['Siti Noraini binti Hamzah', 'siti.landlord@gmail.com'],
            ['Lim Wei Ming', 'lim.landlord@gmail.com'],
            ['Mohd Firdaus bin Salleh', 'firdaus.landlord@gmail.com'],
            ['Nur Aisyah binti Zulkifli', 'aisyah.landlord@gmail.com'],
        ];

        foreach ($landlords as $l) {
            User::create([
                'name' => $l[0],
                'email' => $l[1],
                'password' => Hash::make('password'),
                'phone_number' => fake()->numerify('01#-#######'),
                'role' => 'landlord',
            ]);
        }

        /* ================= OCS (STUDENTS) ================= */
        $studentNames = [
            'Muhammad Arif Hakimi',
            'Nur Izzati binti Ahmad',
            'Aiman Hakim bin Rosli',
            'Siti Khadijah binti Ali',
            'Afiq Danial bin Hassan',
            'Nabila Syuhada binti Razak',
            'Daniel Amir bin Shamsul',
            'Aisyah Najwa binti Farhan',
            'Muhammad Aiman Zulkarnain',
            'Nur Amirah binti Khairul',
            'Pravin Kumar',
            'Nurul Huda binti Azman',
        ];

        foreach ($studentNames as $i => $name) {
            User::create([
                'name' => $name,
                'email' => 'student' . ($i + 1) . '@student.umpsa.edu.my',
                'password' => Hash::make('password'),
                'phone_number' => fake()->numerify('01#-#######'),
                'role' => 'ocs',
            ]);
        }
    }
}
