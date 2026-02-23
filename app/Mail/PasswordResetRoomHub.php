<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetRoomHub extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;
    public string $userName;

    public function __construct(string $resetUrl, string $userName)
    {
        $this->resetUrl = $resetUrl;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Restablece tu contraseña — RoomHub')
            ->view('emails.roomhub-notification')
            ->with([
                'title'      => 'Restablecer contraseña',
                'intro'      => "Hola {$this->userName}, recibimos una solicitud para cambiar tu contraseña en RoomHub.",
                'extraLines' => [
                    'Si tú no hiciste esta solicitud, puedes ignorar este mensaje y tu cuenta seguirá segura.',
                ],
                'buttonText' => 'Crear nueva contraseña',
                'buttonUrl'  => $this->resetUrl,
                'subcopy'    => 'Por seguridad, este enlace solo es válido durante un tiempo limitado.',
            ]);
    }
}
