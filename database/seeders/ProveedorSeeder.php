<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proveedor::updateOrCreate(['id' => 1], ['nombre' => 'Franco Furniture']);
        Proveedor::updateOrCreate(['id' => 4], ['nombre' => 'Mueblam']);
        Proveedor::updateOrCreate(['id' => 3], ['nombre' => 'Muebles Aparicio']);
        Proveedor::updateOrCreate(['id' => 5], ['nombre' => 'Muebles Juan Aguilar']);
        Proveedor::updateOrCreate(['id' => 6], ['nombre' => 'Muebles Río']);
        Proveedor::updateOrCreate(['id' => 2], ['nombre' => 'Torres y Gutiérrez']);
    }
}