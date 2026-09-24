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

    public static function extractYouTubeId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        if (preg_match('#(?:youtube-nocookie\.com|youtube\.com)/embed/([a-zA-Z0-9_-]{11})#', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('#(?:youtube\.com/watch\?(?:.*&)?v=|youtube\.com/watch\?v=|youtu\.be/|youtube\.com/shorts/|youtube\.com/live/)([a-zA-Z0-9_-]{11})#', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('#^[a-zA-Z0-9_-]{11}$#', $url)) {
            return $url;
        }

        return null;
    }

    public static function extractVimeoId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', trim($url), $matches)) {
            return $matches[1];
        }

        return null;
    }

    public static function embedOrigin(): string
    {
        if (function_exists('request') && request()->getSchemeAndHttpHost()) {
            return request()->getSchemeAndHttpHost();
        }

        return rtrim((string) config('app.url'), '/');
    }

    public static function youtubeEmbedUrl(string $id, ?string $origin = null): string
    {
        $origin = $origin ?? self::embedOrigin();

        $params = http_build_query([
            'autoplay' => '1',
            'mute' => '1',
            'loop' => '1',
            'playlist' => $id,
            'controls' => '0',
            'fs' => '0',
            'rel' => '0',
            'modestbranding' => '1',
            'playsinline' => '1',
            'iv_load_policy' => '3',
            'cc_load_policy' => '3',
            'disablekb' => '1',
            'enablejsapi' => '1',
            'origin' => $origin,
        ], '', '&', PHP_QUERY_RFC3986);

        return "https://www.youtube-nocookie.com/embed/{$id}?{$params}";
    }

    public static function vimeoEmbedUrl(string $id): string
    {
        $params = http_build_query([
            'autoplay' => '1',
            'muted' => '1',
            'loop' => '1',
            'background' => '1',
            'texttrack' => '0',
        ], '', '&', PHP_QUERY_RFC3986);

        return "https://player.vimeo.com/video/{$id}?{$params}";
    }

    public static function normalizeEmbedUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        $youtubeId = self::extractYouTubeId($url);
        if ($youtubeId !== null) {
            return self::youtubeEmbedUrl($youtubeId);
        }

        $vimeoId = self::extractVimeoId($url);
        if ($vimeoId !== null) {
            return self::vimeoEmbedUrl($vimeoId);
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    public static function normalizeStoredVideoUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $youtubeId = self::extractYouTubeId($url);
        if ($youtubeId !== null) {
            return "https://www.youtube.com/watch?v={$youtubeId}";
        }

        $vimeoId = self::extractVimeoId($url);
        if ($vimeoId !== null) {
            return "https://vimeo.com/{$vimeoId}";
        }

        return trim($url);
    }

    public static function embedThumbnail(?string $url): ?string
    {
        $youtubeId = self::extractYouTubeId($url);
        if ($youtubeId !== null) {
            return 'https://img.youtube.com/vi/'.$youtubeId.'/hqdefault.jpg';
        }

        return null;
    }
}
