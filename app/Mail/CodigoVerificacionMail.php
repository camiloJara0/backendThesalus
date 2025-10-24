<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoVerificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $correo;
    public $codigo;

    public function __construct($correo, $codigo)
    {
        $this->correo = $correo;
        $this->codigo = $codigo;
    }

    public function build()
    {
        return $this->subject('Verifica tu cuenta')
                    ->view('emails.codigo_verificacion')
                    ->with([
                        'correo' => $this->correo,
                        'codigo' => $this->codigo
                    ]);
    }
}

