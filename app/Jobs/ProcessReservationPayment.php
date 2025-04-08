<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ReservationConfirmed;
use Illuminate\Foundation\Bus\Dispatchable;


class ProcessReservationPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function handle(): void
    {
        sleep(5);

        $this->reservation->load('user', 'media');
        $this->reservation->user->notify(new ReservationConfirmed($this->reservation));

        Log::info("Reserva #{$this->reservation->id} confirmada y notificada.");
    }
}

