<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\PublicImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'folder' => ['nullable', 'string', 'max:40', 'regex:/^[a-z0-9_-]+$/'],
        ]);

        $folder = $validated['folder'] ?? 'uploads';
        $directory = public_path('images/'.$folder);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file('image');
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        $path = 'images/'.$folder.'/'.$filename;

        return response()->json([
            'path' => $path,
            'url' => PublicImage::url($path),
        ]);
    }
}
