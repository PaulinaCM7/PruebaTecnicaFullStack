<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProcessReservationPayment;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'media_id' => 'required|exists:media,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $dates = collect(range(
            strtotime($validated['start_date']),
            strtotime($validated['end_date']),
            86400
        ))->map(fn($ts) => date('Y-m-d', $ts));

        $conflicts = Availability::where('media_id', $validated['media_id'])
            ->whereIn('date', $dates)
            ->exists();

        if ($conflicts) {
            return response()->json(['error' => 'Fechas no disponibles'], 422);
        }

        $days = $dates->count();

        $pricePerDay = \App\Models\Media::find($validated['media_id'])->price_per_day ?? 0;

        $total = $days * $pricePerDay;

        DB::transaction(function () use ($request, $validated, $dates, $total) {
            $reservation = Reservation::create([
                'user_id' => $request->user()->id,
                'media_id' => $validated['media_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total' => $total,
            ]);

            foreach ($dates as $date) {
                Availability::create([
                    'media_id' => $validated['media_id'],
                    'date' => $date,
                ]);
            }

            dispatch((new ProcessReservationPayment($reservation))->delay(now()->addSeconds(3)));
        });

        return response()->json(['message' => 'Reserva creada con éxito', 'total' => $total]);
    }

    public function history(Request $request)
    {
        $history = $request->user()->reservations()
            ->with('media')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($history);
    }
}
