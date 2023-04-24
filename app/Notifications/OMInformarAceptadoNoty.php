<?php

namespace App\Notifications;

use App\Models\OrdenMovilizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PDF;
class OMInformarAceptadoNoty extends Notification
{
    use Queueable;

    protected $orden;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(OrdenMovilizacion $om)
    {
        $this->orden=$om;
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

        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $orden=$this->orden;
        $data = array('orden' => $orden);

       $pdf = PDF::loadView('movilizacion.pdf',$data)
        ->setOrientation('landscape')
        ->setOption('margin-top', '2.5cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);
        $pdf_data = $pdf->output();

        return (new MailMessage)
        ->subject('Orden de Movilización '.$this->orden->estado)
        ->line('Orden de movilización '.$this->orden->numero)
        ->line('# '.$this->orden->estado)
        // ->action('Ver', route('controlOdernMovilizacionPdf',$this->orden->id))
        ->line('Gracias por usar nuestra aplicación!')
        ->attachData($pdf_data,'Orden Movilización '.$orden->numero.'.pdf')
        ;
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
