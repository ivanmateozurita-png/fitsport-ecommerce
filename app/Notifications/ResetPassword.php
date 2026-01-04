<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablecer Contraseña - FitSport')
            ->greeting('¡Hola!')
            ->line('Estás recibiendo este email porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de restablecimiento de contraseña expirará en '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' minutos.')
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Saludos, FitSport');
    }
}
