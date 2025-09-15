<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudEliminarRegistroMail extends Mailable
{
    use Queueable, SerializesModels;


    public $delete;

    /**
     * Create a new message instance.
     */
    public function __construct($delete)
    {
        $this->delete = $delete;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Solicitud para eliminar un registro')
                    ->view('emails.solicitud_eliminar');
    }
}
