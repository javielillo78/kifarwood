<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    protected $fillable = ['titulo','slug','resumen','descripcion','activo'];
    protected $casts = [
        'activo' => 'boolean',
    ];
    public function imagenes(): HasMany
    {
        return $this->hasMany(ServicioImagen::class)->orderBy('orden');
    }
}