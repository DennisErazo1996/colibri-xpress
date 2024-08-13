<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PackageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $lockerNumber;
    public $envio;
    public $numeroTracking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $lockerNumber, $envio, $numeroTracking)
    {
        $this->nombre = $nombre;
        $this->lockerNumber = $lockerNumber;
        $this->envio = $envio;
        $this->numeroTracking = $numeroTracking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.package-mail')
                    ->subject('Recepción de paquete');
    }
}