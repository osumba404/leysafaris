<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $favicon = Setting::get('site_favicon');
        $logo = Setting::get('site_logo');

        if ($favicon && ! $logo) {
            Setting::set('site_logo', $favicon, 'general');
        }

        Setting::query()->where('key', 'site_favicon')->delete();
    }

    public function down(): void
    {
        $logo = Setting::get('site_logo');
        if ($logo) {
            Setting::set('site_favicon', $logo, 'general');
        }
    }
};
