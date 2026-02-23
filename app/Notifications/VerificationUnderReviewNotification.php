<?php

namespace App\Notifications;

use App\Models\HostVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerificationUnderReviewNotification extends Notification
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
            'type' => 'verification_under_review',
            'title' => 'Solicitud en revisión',
            'message' => 'Tu solicitud para ser anfitrión está siendo revisada por el equipo. Te avisaremos cuando tengamos una respuesta.',
            'url' => route('become-host.show'),
            'host_verification_id' => $this->verification->id,
        ];
    }
}
