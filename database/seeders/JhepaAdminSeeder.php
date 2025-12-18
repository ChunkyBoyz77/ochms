<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JhepaAdmin;
use Illuminate\Support\Facades\Hash;

class JhepaAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'     => 'JHEPA Administrator',
            'email'    => 'jhepa@ochms.local', // dummy email (not used)
            'password' => Hash::make('Admin@123'),
            'role'     => 'admin',
        ]);

        JhepaAdmin::create([
            'user_id'  => $user->id,
            'staff_id' => 'JHEPA-001',
        ]);
    }
}
