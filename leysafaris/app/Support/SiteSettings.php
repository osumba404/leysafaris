<?php

namespace App\Support;

class SiteSettings
{
    /**
     * @param  array<string, mixed>  $settings
     */
    public static function string(array $settings, string $key, string $default = ''): string
    {
        $value = $settings[$key] ?? $default;

        if (is_array($value)) {
            return (string) ($value[0] ?? $default);
        }

        return is_string($value) || is_numeric($value) ? (string) $value : $default;
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return list<string>
     */
    public static function list(array $settings, string $key, array $default = []): array
    {
        $value = $settings[$key] ?? $default;

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return array_values(array_filter(array_map('strval', $decoded)));
            }

            return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: [])));
        }

        if (! is_array($value)) {
            return $default;
        }

        return array_values(array_filter(array_map('strval', $value)));
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return list<array{platform: string, url: string}>
     */
    public static function socialLinks(array $settings): array
    {
        $value = $settings['social_links'] ?? [];

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($value)) {
            return [];
        }

        $links = [];

        foreach ($value as $item) {
            if (is_array($item) && ! empty($item['platform']) && ! empty($item['url'])) {
                $links[] = [
                    'platform' => strtolower((string) $item['platform']),
                    'url' => (string) $item['url'],
                ];

                continue;
            }

            if (is_string($item) && str_contains($item, '|')) {
                [$platform, $url] = array_map('trim', explode('|', $item, 2));
                if ($platform && $url) {
                    $links[] = ['platform' => strtolower($platform), 'url' => $url];
                }
            }
        }

        return $links;
    }

    public static function logoUrl(array $settings): ?string
    {
        $logo = self::string($settings, 'site_logo');

        return $logo !== '' ? asset($logo) : null;
    }

    public static function faviconUrl(array $settings): string
    {
        $favicon = self::string($settings, 'site_favicon');
        if ($favicon !== '') {
            return asset($favicon);
        }

        $logo = self::string($settings, 'site_logo');
        if ($logo !== '') {
            return asset($logo);
        }

        return asset('favicon.ico');
    }
}
