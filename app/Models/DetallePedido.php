<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';

    protected $fillable = ['pedido_id','producto_id','cantidad','precio_unitario','subtotal'];

    protected $casts = [
        'cantidad'        => 'int',
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    protected $touches = ['pedido'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}