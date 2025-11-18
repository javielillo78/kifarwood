<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = ['user_id','fecha_pedido','estado','total','revisado_admin'];

    protected $attributes = [
        'estado' => 'carrito',
        'total'  => 0,
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'revisado_admin' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

     public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function scopeCarritoDe($q, $userId)
    {
        return $q->where('user_id', $userId)->where('estado','carrito');
    }
}
