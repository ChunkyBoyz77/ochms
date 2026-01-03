<?php

namespace Database\Seeders;

use App\Models\Landlord;
use App\Models\User;
use Illuminate\Database\Seeder;

class LandlordSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $landlordUsers = User::where('role', 'landlord')->get();

        foreach ($landlordUsers as $user) {
            Landlord::create([
                'user_id' => $user->id,
                'reviewed_by_admin_id' => $admin->id,
                'bank_account_num' => fake()->bankAccountNumber(),
                'bank_name' => fake()->randomElement([
                    'Maybank', 'CIMB', 'Bank Islam', 'Public Bank'
                ]),
                'has_criminal_record' => false,
                'agreement_accepted' => true,
                'screening_status' => 'approved',
                'screening_submitted_at' => now()->subDays(10),
                'screening_reviewed_at' => now()->subDays(7),
            ]);
        }
    }
}
