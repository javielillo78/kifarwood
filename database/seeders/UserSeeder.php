<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario admin que me has pedido
        User::updateOrCreate(
            ['email' => 'javireyescarmona24@gmail.com'],
            [
                'name'              => 'Javier Reyes',
                'password'          => Hash::make('admin'),
                'rol'               => 'admin',        // asumiendo que tu columna se llama 'rol'
                'email_verified_at' => now(),
            ]
        );

        // (Opcional) algún usuario normal de ejemplo
        User::updateOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name'              => 'Cliente Demo',
                'password'          => Hash::make('password'),
                'rol'               => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
