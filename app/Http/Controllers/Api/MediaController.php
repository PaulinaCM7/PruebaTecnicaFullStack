<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();

        if ($request->has('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->paginate(10));
    }

    public function show($id)
    {
        $media = Media::with(['availabilities', 'reservations.user'])
            ->findOrFail($id);

        return response()->json($media);
    }

    public function tipos()
    {
        $tipos = Media::select('type')
            ->distinct()
            ->pluck('type');

        return response()->json($tipos);
    }

}
