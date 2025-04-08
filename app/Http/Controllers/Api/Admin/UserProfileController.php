<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with([
            'reservations' => fn($q) => $q->with('media')->latest()
        ])->findOrFail($id);

        return response()->json($user);
    }
}
