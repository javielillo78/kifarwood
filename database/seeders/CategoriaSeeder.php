<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::updateOrCreate(['id' => 1], ['nombre' => 'Mesas a medida']);
        Categoria::updateOrCreate(['id' => 2], ['nombre' => 'Sillas']);
        Categoria::updateOrCreate(['id' => 3], ['nombre' => 'Decoración artesanal']);
        Categoria::updateOrCreate(['id' => 4], ['nombre' => 'Puertas y ventanas']);
        Categoria::updateOrCreate(['id' => 14], ['nombre' => 'Camper Nomade']);
    }
}