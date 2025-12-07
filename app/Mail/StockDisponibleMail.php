<?php

namespace App\Mail;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockDisponibleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $producto;
    public $user;

    public function __construct(Producto $producto, User $user)
    {
        $this->producto = $producto;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Ya hay stock disponible: '.$this->producto->nombre)
                    ->view('emails.stock-disponible');
    }
}