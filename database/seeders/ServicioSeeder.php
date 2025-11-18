<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Servicio;
// use Illuminate\Support\Str;

// class ServicioSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $servicios = [
//             [
//                 'titulo'      => 'Camperización de furgonetas',
//                 'slug'        => 'camper',
//                 'resumen'     => 'Diseño y fabricación de interiores a medida para furgonetas camper.',
//                 'descripcion' => "Te ayudamos a transformar tu furgoneta en una camper totalmente personalizada.\nMobiliario a medida, instalaciones básicas y acabados de calidad.",
//             ],
//             [
//                 'titulo'      => 'Muebles de Primor',
//                 'slug'        => 'muebles-de-primor',
//                 'resumen'     => 'Mobiliario para tiendas, expositores y mostradores.',
//                 'descripcion' => "Diseño y fabricación de muebles para tiendas tipo Primor.\nMostradores, estanterías y expositores adaptados a tu espacio.",
//             ],
//             [
//                 'titulo'      => 'Muebles a medida',
//                 'slug'        => 'muebles-a-medida',
//                 'resumen'     => 'Muebles únicos adaptados a tu espacio y estilo.',
//                 'descripcion' => "Creamos muebles personalizados: mesas, armarios, estanterías, etc.\nNos adaptamos a las medidas y necesidades de tu proyecto.",
//             ],
//         ];

//         foreach ($servicios as $s) {
//             $slug = $s['slug'] ?: Str::slug($s['titulo']);

//             Servicio::updateOrCreate(
//                 ['slug' => $slug],
//                 [
//                     'titulo'      => $s['titulo'],
//                     'resumen'     => $s['resumen'],
//                     'descripcion' => $s['descripcion'],
//                     'activo'      => true,
//                 ]
//             );
//         }
//     }
// }
