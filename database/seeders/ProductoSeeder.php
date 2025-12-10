<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o recuperar categorías
        $categoriaPuertas = Categoria::firstOrCreate(['nombre' => 'Puertas y ventanas']);
        $categoriaDecoracion = Categoria::firstOrCreate(['nombre' => 'Decoración artesanal']);
        $categoriaSillas = Categoria::firstOrCreate(['nombre' => 'Sillas']);

        // Crear o recuperar proveedores
        $proveedorMueblam = Proveedor::firstOrCreate(['nombre' => 'Mueblam']);
        $proveedorFranco = Proveedor::firstOrCreate(['nombre' => 'Franco Furniture']);
        $proveedorJuan = Proveedor::firstOrCreate(['nombre' => 'Muebles Juan Aguilar']);
        $proveedorTorres = Proveedor::firstOrCreate(['nombre' => 'Torres y Gutiérrez']);

        // Productos
        Producto::updateOrCreate(
            ['nombre' => 'Puerta Entrada Madera Maciza'],
            [
                'categoria_id' => $categoriaPuertas->id,
                'proveedor_id' => $proveedorMueblam->id,
                'descripcion' => 'Puerta de entrada de madera maciza con detalle de lamas verticales.',
                'precio' => 200.00,
                'stock' => 1,
            ]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Cocina de Diseño Minimalista'],
            [
                'categoria_id' => $categoriaDecoracion->id,
                'proveedor_id' => $proveedorFranco->id,
                'descripcion' => 'Cocina de diseño minimalista y elegante.',
                'precio' => 3500.00,
                'stock' => 1,
            ]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Cierre Divisor'],
            [
                'categoria_id' => $categoriaPuertas->id,
                'proveedor_id' => $proveedorJuan->id,
                'descripcion' => 'Cierre Divisor de dos hojas correderas a juego.',
                'precio' => 320.00,
                'stock' => 4,
            ]
        );

        Producto::updateOrCreate(
            ['nombre' => 'Silla Comedor'],
            [
                'categoria_id' => $categoriaSillas->id,
                'proveedor_id' => $proveedorTorres->id,
                'descripcion' => 'Test carrusel Novedades',
                'precio' => 100.00,
                'stock' => 2,
            ]
        );
    }
}