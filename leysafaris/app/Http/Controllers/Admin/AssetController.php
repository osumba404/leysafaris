<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController extends Controller
{
    public function adminCss(): BinaryFileResponse
    {
        return $this->serve('css/admin.css', 'text/css');
    }

    private function serve(string $relativePath, string $contentType): BinaryFileResponse
    {
        $path = public_path($relativePath);

        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
