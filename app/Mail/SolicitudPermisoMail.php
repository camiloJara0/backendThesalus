<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudPermisoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $profesional, $seccion)
    {
        $this->link = $link;
        $this->profesional = $profesional;
        $this->seccion = $seccion;
    }

    public function build()
    {
        return $this->subject('Solicitud de permiso')
                    ->view('emails.solicitud_permiso')
                    ->with([
                        'link' => $this->link,
                        'profesional' => $this->profesional,
                        'seccion' => $this->seccion
                    ]);
    }


}
