<?php

namespace App\Support;

class WebpImage
{
    public static function resolve(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        return asset($normalized);
    }

    public static function webpPath(string $path): ?string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        $normalized = ltrim($path, '/');

        if (! preg_match('/\.(jpe?g|png)$/i', $normalized)) {
            return null;
        }

        return preg_replace('/\.(jpe?g|png)$/i', '.webp', $normalized);
    }

    public static function hasWebp(string $path): bool
    {
        $webp = self::webpPath($path);

        return $webp !== null && is_file(public_path($webp));
    }

    public static function webpUrl(string $path): ?string
    {
        return self::hasWebp($path) ? asset(self::webpPath($path)) : null;
    }
}
