<?php

namespace App\Http\Controllers;

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
