<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pedido $pedido;
    public string $pdfBytes;

    public function __construct(Pedido $pedido, string $pdfBytes)
    {
        $this->pedido   = $pedido->loadMissing('detalles.producto');
        $this->pdfBytes = $pdfBytes;
    }

    public function build()
    {
        $total = (float) $this->pedido->total;
        $base  = round($total / 1.21, 2);
        $iva   = round($total - $base, 2);

        return $this->subject('Confirmación de pedido #'.$this->pedido->id)
            ->view('emails.pedidos.confirmado', [
                'pedido' => $this->pedido,
                'total'  => $total,
                'base'   => $base,
                'iva'    => $iva,
            ])
            ->attachData(
                $this->pdfBytes,
                'factura-'.$this->pedido->id.'.pdf',
                ['mime' => 'application/pdf']
            );
    }
}