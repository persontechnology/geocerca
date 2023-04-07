<?php

namespace App\Notifications;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistroUsuarioNoty extends Notification
{
    use Queueable;
    protected $user;
    protected $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,$password)
    {
        $this->password=$password;
        $this->user=$user;
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
        return (new MailMessage)
                    ->subject('Registro de usuario')
                    ->line('Se ha registrado como nuevo usuario en, '.Empresa::first()->nombre.'.')
                    ->line('Sus credenciales de acceso son:')
                    ->line('Email: '.$this->user->email)
                    ->line('Contraseña: '.$this->password)
                    ->action('Acceder al sistema', route('login'))
                    ->line('Gracias por preferirnos!');
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
