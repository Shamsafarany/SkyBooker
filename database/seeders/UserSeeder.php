<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'username' => 'sarah.support',
            'email' => 'support@skybooker.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Support@2024#'),
            'phone' => '+1-555-987-6543',
            'date_of_birth' => '1990-07-22',
            'role' => 'admin',
        ]);
        User::factory()->count(5)->admin()->create();
        User::factory()->count(100)->passenger()->create();
        }
}
