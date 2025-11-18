<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Sillas',
            'Mesas',
            'Estanterías',
            'Decoración',
            'Armarios',
        ];

        $tieneSlug = Schema::hasColumn('categorias', 'slug');

        foreach ($categorias as $nombre) {

            $data = [
                'nombre' => $nombre,
            ];

            if ($tieneSlug) {
                $data['slug'] = Str::slug($nombre);
            }

            Categoria::updateOrCreate(
                ['nombre' => $nombre], // usamos nombre como clave
                $data
            );
        }
    }
}
