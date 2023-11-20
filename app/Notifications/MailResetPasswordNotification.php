<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void 
     */
    private $token;
    public function __construct($token)
    {
        $this->token = $token;  
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
                    ->greeting('Hola!')
                    ->subject('Recuperación de contraseña en '.config('app.name'))
                    ->line('Estás recibiendo este email porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
                    ->action('Recuperar contraseña', route('password.reset' , $this->token))
                    ->line(\Lang::getFromJson('Este enlace expira en :count minutos.', ['count' => config('auth.passwords.users.expire')]))
                    ->salutation('Saludos.');
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
