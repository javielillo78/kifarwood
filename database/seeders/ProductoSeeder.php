<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Schema;
// use App\Models\Producto;
// use App\Models\Categoria;

// class ProductoSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $tieneSlug = Schema::hasColumn('productos', 'slug');

//         $productos = [
//             [
//                 'nombre'       => 'Silla de madera rústica',
//                 'categoria'    => 'Sillas',
//                 'precio'       => 120.00,
//                 'stock'        => 10,
//                 'descripcion'  => 'Silla de madera maciza, acabado rústico, ideal para comedor.',
//             ],
//             [
//                 'nombre'       => 'Mesa de comedor industrial',
//                 'categoria'    => 'Mesas',
//                 'precio'       => 450.00,
//                 'stock'        => 5,
//                 'descripcion'  => 'Mesa con patas metálicas y sobre de madera maciza.',
//             ],
//             [
//                 'nombre'       => 'Estantería flotante',
//                 'categoria'    => 'Estanterías',
//                 'precio'       => 80.00,
//                 'stock'        => 15,
//                 'descripcion'  => 'Estantería de pared, minimalista y funcional.',
//             ],
//         ];

//         foreach ($productos as $p) {
//             $categoria = Categoria::where('nombre', $p['categoria'])->first();

//             if (! $categoria) {
//                 continue;
//             }

//             $data = [
//                 'nombre'       => $p['nombre'],
//                 'descripcion'  => $p['descripcion'],
//                 'precio'       => $p['precio'],
//                 'stock'        => $p['stock'],
//                 'categoria_id' => $categoria->id,
//             ];

//             if ($tieneSlug) {
//                 $data['slug'] = Str::slug($p['nombre']);
//             }

//             Producto::updateOrCreate(
//                 ['nombre' => $p['nombre']],
//                 $data
//             );
//         }
//     }
// }
