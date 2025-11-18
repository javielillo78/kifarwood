<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\User;

class PedidoPolicy
{
    /**
     * Puede ver/descargar la factura de este pedido si es suyo o si es admin.
     */
    public function view(User $user, Pedido $pedido): bool
    {
        return $pedido->user_id === $user->id || ($user->rol ?? null) === 'admin';
    }
}
