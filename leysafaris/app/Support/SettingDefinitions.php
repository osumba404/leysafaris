<?php

namespace App\Support;

use App\Models\Setting;

class SettingDefinitions
{
    /** @var list<string> */
    private const HIDDEN_KEYS = ['site_favicon'];

    /** @var list<string> */
    private const LIST_KEYS = ['emails', 'press_mentions', 'payment_methods'];

    /** @var list<string> */
    private const SOCIAL_KEYS = ['social_links'];

    /** @var list<string> */
    private const LONG_KEYS = ['footer_tagline', 'lead_guide_bio', 'address'];

    /** @var list<string> */
    private const IMAGE_KEYS = ['site_logo'];

    public static function isHidden(string $key): bool
    {
        return in_array($key, self::HIDDEN_KEYS, true);
    }

    public static function type(string $key): string
    {
        if (in_array($key, self::IMAGE_KEYS, true)) {
            return 'image';
        }

        if (in_array($key, self::SOCIAL_KEYS, true)) {
            return 'social';
        }

        if (in_array($key, self::LIST_KEYS, true)) {
            return 'list';
        }

        if (in_array($key, self::LONG_KEYS, true)) {
            return 'long';
        }

        return 'text';
    }

    public static function label(string $key): string
    {
        return match ($key) {
            'site_logo' => 'Site logo (also used as favicon)',
            'logo_name' => 'Logo text — primary line',
            'logo_tag' => 'Logo text — secondary line',
            'site_name' => 'Site name (copyright, SEO)',
            default => str_replace('_', ' ', ucfirst($key)),
        };
    }

    public static function formValue(Setting $setting): string
    {
        $value = $setting->value;
        $type = self::type($setting->key);

        if ($type === 'list' && is_array($value)) {
            return implode("\n", $value);
        }

        if ($type === 'social' && is_array($value)) {
            return collect($value)->map(fn ($item) => ($item['platform'] ?? '').'|'.($item['url'] ?? ''))->implode("\n");
        }

        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        return '';
    }

    public static function preview(Setting $setting): string
    {
        $value = $setting->value;
        $type = self::type($setting->key);

        if ($type === 'image') {
            return is_string($value) && $value !== '' ? $value : '—';
        }

        if ($type === 'list' && is_array($value)) {
            if ($value === []) {
                return '—';
            }

            $preview = implode(', ', array_slice($value, 0, 2));

            return count($value) > 2 ? $preview.'…' : $preview;
        }

        if ($type === 'social' && is_array($value)) {
            return $value === [] ? '—' : count($value).' link(s)';
        }

        if (is_string($value) || is_numeric($value)) {
            $text = (string) $value;

            return strlen($text) > 80 ? substr($text, 0, 77).'…' : ($text !== '' ? $text : '—');
        }

        return '—';
    }

    public static function hint(string $key): ?string
    {
        return match ($key) {
            'social_links' => 'One per line: platform|url (facebook, instagram, x, youtube, linkedin, tripadvisor, tiktok, whatsapp)',
            'emails', 'press_mentions', 'payment_methods' => 'One value per line',
            default => null,
        };
    }
}
