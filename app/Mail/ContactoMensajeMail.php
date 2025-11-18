<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactoMensajeMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->subject('Contacto: '.$data['asunto']);
    }

    public function build()
    {
        return $this->view('emails.contacto');
    }
}