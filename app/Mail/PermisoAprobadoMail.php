<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermisoAprobadoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($codigo, $correo)
    {
        $this->codigo = $codigo;
        $this->correo = $correo;
    }

    public function build()
    {
        return $this->subject('Permiso aprobado por Administrador')
                    ->view('emails.permiso_aprobado')
                    ->with([
                        'codigo' => $this->codigo,
                        'correo' => $this->correo
                    ]);
    }
}
