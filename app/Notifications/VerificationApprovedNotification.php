<?php

namespace App\Notifications;

use App\Models\HostVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerificationApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public HostVerification $verification
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'verification_approved',
            'title' => '¡Felicidades, ya eres anfitrión!',
            'message' => 'Tu solicitud fue aprobada. Completa tu perfil de anfitrión para publicar tu primer alojamiento.',
            'url' => route('become-host.complete'),
            'host_verification_id' => $this->verification->id,
        ];
    }
}
