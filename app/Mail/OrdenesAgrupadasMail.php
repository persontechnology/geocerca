<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdenesAgrupadasMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $pdf_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf_data)
    {
        $this->pdf_data = $pdf_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    

    public function build()
    {
        $email = $this->subject('Órdenes de Movilización Agrupadas')
            ->markdown('movilizacion.ordenes_agrupadas_html');

        foreach ($this->pdf_data as $file) {
            $email->attachData($file['data'], $file['name'], [
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }

}
