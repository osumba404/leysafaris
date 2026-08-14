<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Rules\PublicImagePath;
use App\Support\SettingDefinitions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** @var list<string> */
    private const LIST_KEYS = ['emails', 'press_mentions', 'payment_methods'];

    /** @var list<string> */
    private const SOCIAL_KEYS = ['social_links'];

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $settings = Setting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->reject(fn (Setting $setting) => SettingDefinitions::isHidden($setting->key));

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        if (SettingDefinitions::isHidden($setting->key)) {
            abort(404);
        }

        $rules = ['value' => ['nullable', 'string']];

        if (SettingDefinitions::type($setting->key) === 'image') {
            $rules['value'] = ['nullable', new PublicImagePath];
        }

        $validated = $request->validate($rules);

        $setting->update([
            'value' => $this->parseValue($setting->key, $validated['value'] ?? null),
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', SettingDefinitions::label($setting->key).' updated.');
    }

    private function parseValue(string $key, mixed $raw): mixed
    {
        if (! is_string($raw)) {
            return $raw;
        }

        $raw = trim($raw);

        if (in_array($key, self::SOCIAL_KEYS, true)) {
            return $this->parseSocialLinks($raw);
        }

        if (in_array($key, self::LIST_KEYS, true)) {
            return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $raw) ?: [])));
        }

        if ($raw !== '' && str_starts_with($raw, '[')) {
            $decoded = json_decode($raw, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $raw;
        }

        return $raw;
    }

    /**
     * @return list<array{platform: string, url: string}>
     */
    private function parseSocialLinks(string $raw): array
    {
        if (str_starts_with($raw, '[')) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $links = [];

        foreach (preg_split('/\r\n|\r|\n/', $raw) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || ! str_contains($line, '|')) {
                continue;
            }

            [$platform, $url] = array_map('trim', explode('|', $line, 2));
            if ($platform && $url) {
                $links[] = ['platform' => strtolower($platform), 'url' => $url];
            }
        }

        return $links;
    }
}
