<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdenesMovilizacionPdfVariasCorreos extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;

    public function __construct($pdfContent)
    {
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->view('movilizacion.ordenes_pdf_varios_correos')
                    ->subject('Órdenes de Movilización')
                    ->attachData($this->pdfContent, 'ordenes.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
