<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'media'])->latest();

        if ($request->filled('cliente')) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%' . $request->cliente . '%')
                ->orWhere('email', 'like', '%' . $request->cliente . '%')
            );
        }

        if ($request->filled('medio')) {
            $query->whereHas('media', fn($q) =>
                $q->where('name', 'like', '%' . $request->medio . '%')
            );
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('start_date', '<=', $request->fecha_fin)
                    ->whereDate('end_date', '>=', $request->fecha_inicio);
            });
        }

        return response()->json($query->paginate(15));
    }
}
