<?php

namespace App\Http\Controllers;

use App\Support\PublicImage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController extends Controller
{
    public function styleCss(): BinaryFileResponse
    {
        return $this->serve('css/style.css', 'text/css');
    }

    public function themeJs(): BinaryFileResponse
    {
        return $this->serve('js/theme.js', 'application/javascript');
    }

    public function mainJs(): BinaryFileResponse
    {
        return $this->serve('js/main.js', 'application/javascript');
    }

    public function adminJs(): BinaryFileResponse
    {
        return $this->serve('js/admin.js', 'application/javascript');
    }

    public function image(string $path): BinaryFileResponse
    {
        $path = str_replace(['\\', '../'], ['/', ''], $path);
        $relative = 'images/'.$path;
        $full = PublicImage::filePath($relative);

        abort_unless($full !== null, 404);

        $real = realpath($full);
        $base = realpath(public_path('images'));
        abort_unless($real && $base && str_starts_with($real, $base), 404);

        $extension = strtolower(pathinfo($real, PATHINFO_EXTENSION));
        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };

        $version = (string) filemtime($real);

        return response()->file($real, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'ETag' => '"'.$version.'"',
        ]);
    }

    private function serve(string $relativePath, string $contentType): BinaryFileResponse
    {
        $path = public_path($relativePath);

        abort_unless(is_file($path), 404);

        $version = (string) filemtime($path);

        return response()->file($path, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=604800, must-revalidate',
            'ETag' => '"'.$version.'"',
        ]);
    }
}
