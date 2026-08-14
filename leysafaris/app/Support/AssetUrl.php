<?php

namespace App\Support;

class AssetUrl
{
    /**
     * Laravel route for css/js — always reads from public/ in git (never a stale docroot copy).
     */
    public static function versionedRoute(string $routeName, string $path): string
    {
        $path = ltrim($path, '/');
        $version = config('app.asset_version');

        if (! is_string($version) || $version === '') {
            $file = public_path($path);
            $version = is_file($file) ? (string) filemtime($file) : null;
        }

        $url = route($routeName);

        return $version !== null && $version !== '' ? $url.'?v='.$version : $url;
    }
}
