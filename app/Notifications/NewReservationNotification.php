<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReservationNotification extends Notification
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
            'type' => 'new_reservation',
            'title' => 'Nueva reserva',
            'message' => "Reserva #{$reservation->id} para «{$apartment->title}» (entrada: {$reservation->check_in->format('d/m/Y')}).",
            'url' => route('admin.finances.index'),
            'reservation_id' => $reservation->id,
        ];
    }
}
