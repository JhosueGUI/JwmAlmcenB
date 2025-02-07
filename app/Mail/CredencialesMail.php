<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $credenciales;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($credenciales)
    {
        $this->credenciales = $credenciales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.credenciales')
                    ->with('credenciales', $this->credenciales);
    }
    
}