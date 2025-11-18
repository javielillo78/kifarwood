<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = ['pedido_id', 'importe', 'fecha_pago'];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'importe'    => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
