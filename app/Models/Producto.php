<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
    ];
    // relación: un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    //relacion imagenes producto
    public function imagenes()
    {
        return $this->hasMany(Imagen::class)->orderBy('orden')->orderBy('id');
    }
    //relacion compras producto
    public function compras()
    {
        return $this->hasMany(\App\Models\Compra::class);
    }
}
