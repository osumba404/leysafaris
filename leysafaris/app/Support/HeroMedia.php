<?php

namespace App\Support;

class HeroMedia
{
    public const TYPE_IMAGE = 'image';

    public const TYPE_VIDEO = 'video';

    public const TYPE_EMBED = 'embed';

    public static function normalizeVideoPath(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $path = (string) parse_url($path, PHP_URL_PATH);
        }

        $path = ltrim($path, '/');

        if (! str_starts_with($path, 'images/')) {
            return null;
        }

        if (! preg_match('/\.(mp4|webm|mov|m4v)$/i', $path)) {
            return null;
        }

        return $path;
    }

    public static function videoExists(?string $path): bool
    {
        $normalized = self::normalizeVideoPath($path);

        return $normalized !== null && is_file(public_path($normalized));
    }

    public static function videoUrl(?string $path): ?string
    {
        $normalized = self::normalizeVideoPath($path);

        return $normalized ? asset($normalized) : null;
    }

    public static function normalizeEmbedUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/)([a-zA-Z0-9_-]{11})#', $url, $matches)) {
            $id = $matches[1];

            return "https://www.youtube.com/embed/{$id}?autoplay=1&mute=1&loop=1&playlist={$id}&controls=0&rel=0&modestbranding=1&playsinline=1";
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}?autoplay=1&muted=1&loop=1&background=1";
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    public static function embedThumbnail(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/)([a-zA-Z0-9_-]{11})#', $url, $matches)) {
            return 'https://img.youtube.com/vi/'.$matches[1].'/hqdefault.jpg';
        }

        return null;
    }
}
