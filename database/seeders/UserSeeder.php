<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'javireyescarmona24@gmail.com'],
            [
                'name' => 'Javier Reyes',
                'password' => Hash::make('admin'),
                'rol' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
    }
}