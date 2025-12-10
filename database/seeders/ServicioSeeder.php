<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Servicio::updateOrCreate(
            ['slug' => 'dwada'],
            [
                'titulo' => 'Mueble Primor',
                'resumen' => 'mueble primor',
                'descripcion' => 'Mueble Primor',
                'activo' => true,
            ]
        );

        Servicio::updateOrCreate(
            ['slug' => 'camper-neo-s'],
            [
                'titulo' => 'Camper Neo S',
                'resumen' => 'camper neo s',
                'descripcion' => 'Camper Neo S Nomade',
                'activo' => true,
            ]
        );

        Servicio::updateOrCreate(
            ['slug' => 'camper-neo'],
            [
                'titulo' => 'Camper Neo',
                'resumen' => 'camper neo',
                'descripcion' => 'Camper Neo Nomade',
                'activo' => true,
            ]
        );
    }
}