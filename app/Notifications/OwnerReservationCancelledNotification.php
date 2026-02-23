<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerReservationCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Reservation $reservation
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $reservation = $this->reservation;
        $apartment = $reservation->apartment;

        return [
            'type' => 'reservation_cancelled_owner',
            'title' => 'Reserva cancelada',
            'message' => "Una reserva para «{$apartment->title}» (entrada {$reservation->check_in?->format('d/m/Y')}) fue cancelada por el huésped.",
            'url' => route('owner.dashboard'),
            'reservation_id' => $reservation->id,
        ];
    }
}
