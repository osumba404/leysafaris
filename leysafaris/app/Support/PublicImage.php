<?php

namespace App\Support;

class PublicImage
{
    /**
     * Normalize a stored image value to a relative public path (images/...).
     */
    public static function normalizeStoredPath(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $path = (string) parse_url($path, PHP_URL_PATH);
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (str_starts_with($path, 'storage/')) {
            return null;
        }

        if (! str_starts_with($path, 'images/')) {
            return null;
        }

        return $path;
    }

    public static function filePath(?string $path): ?string
    {
        $normalized = self::normalizeStoredPath($path);

        if ($normalized === null) {
            return null;
        }

        $full = public_path($normalized);

        return is_file($full) ? $full : null;
    }

    public static function exists(?string $path): bool
    {
        return self::filePath($path) !== null;
    }

    public static function url(?string $path, ?string $fallback = null): ?string
    {
        $normalized = self::normalizeStoredPath($path);

        if ($normalized === null) {
            return $fallback !== null ? asset(ltrim($fallback, '/')) : null;
        }

        return asset($normalized);
    }
}
