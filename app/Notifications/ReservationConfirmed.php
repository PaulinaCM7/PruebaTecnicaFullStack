<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('¡Reserva confirmada!')
            ->line('Tu reserva ha sido procesada exitosamente.')
            ->line('📍 Medio: ' . $this->reservation->media->name)
            ->line('📅 Desde: ' . $this->reservation->start_date)
            ->line('📅 Hasta: ' . $this->reservation->end_date)
            ->line('💰 Total: $' . number_format($this->reservation->total, 2))
            ->action('Ver tu historial', url('/dashboard/reservations'))
            ->line('Gracias por usar nuestra plataforma.');
    }
}
