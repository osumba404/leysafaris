<?php

namespace App\Support;

class RichContent
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><del><h2><h3><h4><ul><ol><li><blockquote><a><img><figure><figcaption><table><thead><tbody><tr><th><td><hr><pre><code><span><div><sup><sub>';

    public static function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);
        if ($html === '') {
            return null;
        }

        $html = strip_tags($html, self::ALLOWED_TAGS);
        $html = preg_replace('/\s(on\w+|style|class)\s*=\s*"[^"]*"/iu', '', $html) ?? $html;
        $html = preg_replace("/\s(on\w+|style|class)\s*=\s*'[^']*'/iu", '', $html) ?? $html;
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript:[^"\'>\s]*/iu', 'href="#"', $html) ?? $html;
        $html = preg_replace('/src\s*=\s*["\']?\s*javascript:[^"\'>\s]*/iu', '', $html) ?? $html;

        return $html !== '' ? $html : null;
    }

    public static function render(?string $content): string
    {
        if ($content === null || trim($content) === '') {
            return '';
        }

        if (! str_contains($content, '<')) {
            return nl2br(e($content));
        }

        return self::sanitize($content) ?? '';
    }
}
