<?php

namespace App\Notifications\DespachoCombustible;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use PDF;
class EnviarNotiChoferDespachoCombustible extends Notification
{
    use Queueable;
    public $dc;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dc)
    {
        $this->dc=$dc;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $despachoCombustible=$this->dc;

        $headerHtml = view()->make('empresa.pdfHeader',['titulo'=>'FORMULARIO AUTORIZACIÓN PARA EL DESPACHO DEL COMBUSTIBLE'])->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();
        $data = array('dc' => $despachoCombustible);

       $pdf = PDF::loadView('despachoCombustible.pdf',$data)
        // ->setOrientation('landscape')
        ->setOption('margin-top', '3cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);
        $pdf_data = $pdf->output();

        return (new MailMessage)
                    ->subject('DESPACHO DE COMBUSTIBLE '.$despachoCombustible->estado)
                    ->line('FORMULARIO DE AUTORIZACIÓN PARA EL DESPACHO DEL COMBUSTIBLE '.$despachoCombustible->estado)
                    ->LINE(new HtmlString('<strong>CÓDIGO: '.$despachoCombustible->codigo.'</strong>'))
                    ->LINE('N° ORDEN: '.$despachoCombustible->numero)
                    ->line('FECHA: '.$despachoCombustible->fecha)
                    ->line('N° MOVIL: '.$despachoCombustible->vehiculo->numero_movil)
                    ->line('CONCEPTO: '.$despachoCombustible->concepto)
                    ->line('CANTIDAD GALONES: '.$despachoCombustible->cantidad_galones)
                    ->line('VALOR: '.$despachoCombustible->valor)
                    ->attachData($pdf_data,'FORM-ADC- '.$despachoCombustible->numero.'.pdf');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
