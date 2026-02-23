<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservationCancelledNotification extends Notification
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
            'type' => 'reservation_cancelled',
            'title' => 'Reserva cancelada',
            'message' => "Reserva #{$reservation->id} («{$apartment->title}») fue cancelada por el usuario.",
            'url' => route('admin.finances.index'),
            'reservation_id' => $reservation->id,
        ];
    }
}
