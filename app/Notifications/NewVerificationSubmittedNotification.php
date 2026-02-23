<?php

namespace App\Notifications;

use App\Models\HostVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewVerificationSubmittedNotification extends Notification
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
        $v = $this->verification;
        $userName = $v->user?->name ?? 'Un usuario';
        return [
            'type' => 'verification_pending',
            'title' => 'Solicitud de verificación',
            'message' => "{$userName} envió una solicitud para ser anfitrión. Revisa en verificaciones.",
            'url' => route('admin.verifications.show', $v),
            'host_verification_id' => $v->id,
        ];
    }
}
