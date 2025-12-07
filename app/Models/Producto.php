<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockDisponibleMail;
use App\Models\Proveedor;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'proveedor_id'
    ];

    // 🔔 Avisos de stock cuando se actualiza el producto
    protected static function booted()
    {
        static::updated(function (Producto $producto) {
            // Antes estaba sin stock (0 o menos) y ahora tiene stock (>0)
            if ($producto->stock > 0 && $producto->getOriginal('stock') <= 0) {

                $alerts = $producto->stockAlerts()
                    ->whereNull('notified_at')
                    ->with('user')
                    ->get();

                foreach ($alerts as $alert) {
                    if (!$alert->user) {
                        continue;
                    }

                    Mail::to($alert->user->email)
                        ->send(new StockDisponibleMail($producto, $alert->user));

                    $alert->notified_at = now();
                    $alert->save();
                }
            }
        });
    }

    // relación: un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // relación imágenes producto
    public function imagenes()
    {
        return $this->hasMany(Imagen::class)->orderBy('orden')->orderBy('id');
    }

    // relación compras producto
    public function compras()
    {
        return $this->hasMany(\App\Models\Compra::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function stockAlerts()
    {
        return $this->hasMany(\App\Models\StockAlert::class);
    }
}