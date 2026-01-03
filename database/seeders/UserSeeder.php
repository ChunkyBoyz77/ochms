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
            'phone_number' => '0190000000',
            'role' => 'admin',
        ]);

        /* ================= LANDLORD USERS ================= */
        $landlords = [
            ['Ahmad Zainal', 'ahmad.landlord@gmail.com'],
            ['Siti Noraini', 'siti.landlord@gmail.com'],
            ['Lim Wei Ming', 'lim.landlord@gmail.com'],
        ];

        foreach ($landlords as $l) {
            User::create([
                'name' => $l[0],
                'email' => $l[1],
                'password' => Hash::make('password'),
                'phone_number' => fake()->phoneNumber(),
                'role' => 'landlord',
            ]);
        }

        /* ================= STUDENTS ================= */
        for ($i = 1; $i <= 12; $i++) {
            User::create([
                'name' => fake()->name(),
                'email' => "student{$i}@student.umpsa.edu.my",
                'password' => Hash::make('password'),
                'phone_number' => fake()->phoneNumber(),
                'role' => 'ocs',
            ]);
        }
    }
}
