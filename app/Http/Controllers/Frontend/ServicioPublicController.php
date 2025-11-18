<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Servicio;

class ServicioPublicController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('imagenes')
            ->where('activo', true)
            ->orderBy('id','asc')
            ->get();
        $camper = $servicios->firstWhere('slug', 'camper');
        $primor = $servicios->firstWhere('slug', 'muebles-de-primor');
        $medida = $servicios->firstWhere('slug', 'muebles-a-medida');

        return view('public.servicios.index', compact('camper','primor','medida','servicios'));
    }

    public function show(string $slug)
    {
        $servicio = Servicio::with('imagenes')
            ->where('slug', $slug)
            ->where('activo', true)
            ->firstOrFail();
        return view('public.servicios.show', compact('servicio'));
    }
}