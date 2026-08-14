@extends('layouts.admin')
@section('page_title', 'Settings')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Site Settings</h2>
    </div>

    <form class="admin-form" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @forelse ($grouped as $group => $groupSettings)
            <h3 style="font-family:'Cormorant Garamond',serif;margin:1.5rem 0 1rem;text-transform:capitalize;color:var(--admin-sidebar);">{{ $group }}</h3>
            @foreach ($groupSettings as $setting)
                @php
                    $value = old("settings.{$setting->key}.value", $setting->value);
                    $isList = in_array($setting->key, ['emails', 'press_mentions', 'payment_methods'], true);
                    $isSocial = $setting->key === 'social_links';
                    $isLong = in_array($setting->key, ['footer_tagline', 'lead_guide_bio', 'address'], true);
                    $displayValue = $value;
                    if ($isList && is_array($value)) {
                        $displayValue = implode("\n", $value);
                    }
                    if ($isSocial && is_array($value)) {
                        $displayValue = collect($value)->map(fn ($item) => ($item['platform'] ?? '').'|'.($item['url'] ?? ''))->implode("\n");
                    }
                @endphp
                <div class="admin-form__group" style="margin-bottom:1rem;">
                    <label for="setting_{{ $setting->key }}">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                    <input type="hidden" name="settings[{{ $setting->key }}][group]" value="{{ $setting->group }}">
                    @if ($isSocial)
                        <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" rows="4" placeholder="facebook|https://facebook.com/yourpage">{{ $displayValue }}</textarea>
                        <small style="color:var(--admin-muted);">One per line: platform|url (facebook, instagram, x, youtube, linkedin, tripadvisor, tiktok, whatsapp)</small>
                    @elseif ($isList)
                        <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" rows="3">{{ $displayValue }}</textarea>
                        <small style="color:var(--admin-muted);">One value per line</small>
                    @elseif ($isLong)
                        <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" rows="3">{{ is_string($displayValue) ? $displayValue : '' }}</textarea>
                    @elseif (in_array($setting->key, ['site_logo', 'site_favicon'], true))
                        <input type="text" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" value="{{ is_string($displayValue) ? $displayValue : '' }}" placeholder="images/logo.png">
                        <small style="color:var(--admin-muted);">Path under public/ — logo also used as favicon if favicon is empty</small>
                    @else
                        <input type="text" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" value="{{ is_string($displayValue) || is_numeric($displayValue) ? $displayValue : '' }}">
                    @endif
                </div>
            @endforeach
        @empty
            <p>No settings found. Run the database seeder to populate defaults.</p>
        @endforelse

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection
