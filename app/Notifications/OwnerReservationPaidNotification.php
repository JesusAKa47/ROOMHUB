<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerReservationPaidNotification extends Notification
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
        $guest = $reservation->user;
        $guestName = $guest?->name ?? 'Un huésped';
        $entrada = $reservation->check_in?->format('d/m/Y') ?? '—';

        return [
            'type' => 'reservation_paid_owner',
            'title' => 'Tu alojamiento fue reservado',
            'message' => "{$guestName} pagó la reserva de «{$apartment->title}». Entrada: {$entrada}. Revisa Mensajes para coordinar.",
            'url' => route('messages.index'),
            'reservation_id' => $reservation->id,
            'apartment_id' => $apartment->id,
        ];
    }
}
