<?php

namespace App\Notifications;

use App\Models\HostVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerificationRejectedNotification extends Notification
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
        $reason = $this->verification->rejection_reason ? " Motivo: {$this->verification->rejection_reason}" : '';
        return [
            'type' => 'verification_rejected',
            'title' => 'Solicitud no aprobada',
            'message' => 'Tu solicitud para ser anfitrión no fue aprobada.' . $reason,
            'url' => route('become-host.show'),
            'host_verification_id' => $this->verification->id,
        ];
    }
}
