<?php

namespace Database\Seeders;

use App\Models\Ocs;
use App\Models\User;
use Illuminate\Database\Seeder;

class OcsSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'ocs')->get();

        foreach ($students as $index => $user) {
            Ocs::create([
                'user_id' => $user->id,
                'matric_id' => 'CB' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'faculty' => fake()->randomElement([
                    'Faculty of Computing', 'Faculty of Mechanical and Automotive Engineering Technology', 'Faculty of Manufacturing and Mechatronic Engineering Technology', 'Faculty of Electrical and Electronics Engineering Technology'
                ]),
                'course' => fake()->randomElement([
                    'Software Engineering',
                    'Mechanical Engineering',
                    'Civil Engineering',
                    'Electrical Engineering',
                ]),
                'study_year' => fake()->numberBetween(1, 4),
                'current_semester' => fake()->numberBetween(1, 8),
            ]);
        }
    }
}
