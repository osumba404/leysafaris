<?php

namespace App\Support;

class WebpImage
{
    public static function resolve(?string $path, ?string $fallback = null): string
    {
        $normalized = PublicImage::normalizeStoredPath($path);

        if ($normalized === null) {
            $fallback = PublicImage::normalizeStoredPath($fallback) ?? 'images/savannah_sunset_tree.jpg';

            return asset($fallback);
        }

        return asset($normalized);
    }

    public static function webpPath(?string $path): ?string
    {
        $normalized = PublicImage::normalizeStoredPath($path);

        if ($normalized === null) {
            return null;
        }

        if (! preg_match('/\.(jpe?g|png)$/i', $normalized)) {
            return null;
        }

        return preg_replace('/\.(jpe?g|png)$/i', '.webp', $normalized);
    }

    public static function hasWebp(?string $path): bool
    {
        $webp = self::webpPath($path);

        return $webp !== null && PublicImage::exists($webp);
    }

    public static function webpUrl(?string $path): ?string
    {
        return self::hasWebp($path) ? asset((string) self::webpPath($path)) : null;
    }
}
