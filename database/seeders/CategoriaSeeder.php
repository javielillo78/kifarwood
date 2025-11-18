<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Categoria;
// use Illuminate\Support\Str;

// class CategoriaSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $categorias = [
//             'Sillas',
//             'Mesas',
//             'Estanterías',
//             'Decoración',
//             'Armarios',
//         ];

//         foreach ($categorias as $nombre) {
//             $slug = Str::slug($nombre);

//             Categoria::updateOrCreate(
//                 ['slug' => $slug],
//                 [
//                     'nombre' => $nombre,
//                 ]
//             );
//         }
//     }
// }