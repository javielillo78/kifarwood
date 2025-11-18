<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMensajeMail;

class ContactoController extends Controller
{
    public function index()
    {
        return view('public.contacto');
    }

    public function send(Request $request)
    {
        $rules = [
            'asunto'   => ['required','string','max:150'],
            'mensaje'  => ['required','string','min:10','max:4000'],
            'telefono' => ['nullable','string','max:30'],
        ];
        if ($request->user() === null) {
            $rules['nombre'] = ['required','string','min:2','max:80'];
            $rules['email']  = ['required','email','max:120'];
        }
        $messages = [
            'required' => 'Este campo es obligatorio.',
            'email'    => 'Introduce un correo electrónico válido.',
            'min'      => ['string' => 'Debe tener al menos :min caracteres.'],
            'max'      => ['string' => 'No puede tener más de :max caracteres.'],
        ];
        $data = $request->validate($rules, $messages);
        $nombre = $request->user()->name  ?? $data['nombre'];
        $email  = $request->user()->email ?? $data['email'];
        $payload = [
            'asunto'      => $data['asunto'],
            'mensaje'     => $data['mensaje'],
            'telefono'    => $data['telefono'] ?? null,
            'user_name'   => $nombre,
            'user_email'  => $email,
        ];
        $to = config('mail.contact_to', config('mail.from.address'));
        Mail::to($to)->send(new ContactoMensajeMail($payload));
        Mail::to($email)->send(new \App\Mail\ContactoConfirmacionMail($payload));
        return back()->with(
            'success',
            '¡Mensaje enviado! Revisa tu bandeja de entrada. Te hemos enviado un correo de confirmación.'
        );
    }

}