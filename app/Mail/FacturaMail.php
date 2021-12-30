<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject ='Factura de appSia';
    public $ventas;
    public $detalles;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ventas,$detalles )
    {
        $this->ventas=$ventas;
        $this->detalles=$detalles;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('components.email');
    }
}
