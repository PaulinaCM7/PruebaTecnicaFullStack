<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaAdminController extends Controller
{
    public function index()
    {
        return Media::paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'type' => 'required|string|in:espectacular,pantalla,valla,otro',
            'image' => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
        ]);

        $media = Media::create($data);

        return response()->json($media, 201);
    }

    public function show(Media $media)
    {
        return $media;
    }

    public function update(Request $request, Media $media)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'location' => 'sometimes|string',
            'type' => 'sometimes|string|in:espectacular,pantalla,valla,otro',
            'image' => 'nullable|string',
            'price_per_day' => 'sometimes|numeric|min:0',
        ]);

        $media->update($data);

        return $media;
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json(['message' => 'Medio eliminado']);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('media', 'public');

        return response()->json([
            'url' => Storage::url($path),
            'path' => $path,
        ]);
    }
}
