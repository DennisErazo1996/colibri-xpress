<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $lockerNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $lockerNumber)
    {
        $this->user = $user;
        $this->lockerNumber = $lockerNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.welcome-mail')
                    ->subject('Creación de casillero');
    }
}