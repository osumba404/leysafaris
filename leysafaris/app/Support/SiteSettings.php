<?php

namespace App\Support;

class SiteSettings
{
    /**
     * @return array<string, mixed>
     */
    public static function normalize(mixed $settings): array
    {
        if (is_array($settings)) {
            return $settings;
        }

        if ($settings instanceof \Illuminate\Support\Collection) {
            return $settings
                ->mapWithKeys(fn ($item) => [
                    is_object($item) && isset($item->key)
                        ? $item->key
                        : (string) $item => is_object($item) && isset($item->value) ? $item->value : $item,
                ])
                ->all();
        }

        return [];
    }

    /**
     * @param  array<string, mixed>|mixed  $settings
     */
    public static function string(mixed $settings, string $key, string $default = ''): string
    {
        $settings = self::normalize($settings);
        $value = $settings[$key] ?? $default;

        if (is_array($value)) {
            return (string) ($value[0] ?? $default);
        }

        return is_string($value) || is_numeric($value) ? (string) $value : $default;
    }

    /**
     * @param  array<string, mixed>|mixed  $settings
     * @return list<string>
     */
    public static function list(mixed $settings, string $key, array $default = []): array
    {
        $settings = self::normalize($settings);
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
     * @param  array<string, mixed>|mixed  $settings
     * @return list<array{platform: string, url: string}>
     */
    public static function socialLinks(mixed $settings): array
    {
        $settings = self::normalize($settings);
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

    public static function logoUrl(mixed $settings): ?string
    {
        $logo = self::string($settings, 'site_logo');

        return $logo !== '' ? asset($logo) : null;
    }

    public static function faviconUrl(mixed $settings): string
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
