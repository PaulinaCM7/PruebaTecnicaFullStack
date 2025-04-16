<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Mail\ProcessReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        Log::info("Procesando pago para reserva #{$this->reservation->id}...");
        sleep(5);

        $this->reservation->load('user', 'media');

        Mail::to($this->reservation->user->email)->send(new ProcessReservation($this->reservation));

        Log::info("Pago completado y correo enviado para reserva #{$this->reservation->id}.");
    }
}
