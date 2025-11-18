<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
    ];

    //relación: una categoría tiene muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}