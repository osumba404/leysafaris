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
            @foreach ($groupSettings as $index => $setting)
                @php
                    $value = old("settings.{$setting->key}.value", $setting->value);
                    if (is_array($value)) {
                        $displayValue = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        $isJson = true;
                    } else {
                        $displayValue = $value;
                        $isJson = false;
                    }
                @endphp
                <div class="admin-form__group" style="margin-bottom:1rem;">
                    <label for="setting_{{ $setting->key }}">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                    <input type="hidden" name="settings[{{ $setting->key }}][group]" value="{{ $setting->group }}">
                    @if ($isJson || in_array($setting->key, ['emails']))
                        <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" rows="3" placeholder='["email@example.com"]'>{{ is_array($value) ? implode("\n", $value) : $displayValue }}</textarea>
                        <small style="color:var(--admin-muted);">One value per line for lists, or JSON array</small>
                    @else
                        <input type="text" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}][value]" value="{{ $displayValue }}">
                    @endif
                </div>
            @endforeach
        @empty
            <p>No settings found. Run the database seeder to populate defaults.</p>
            @php
                $defaults = [
                    ['key' => 'site_name', 'value' => 'Leyla Safari Tours', 'group' => 'general'],
                    ['key' => 'phone', 'value' => '+254 712 345 678', 'group' => 'contact'],
                    ['key' => 'whatsapp', 'value' => '+254712345678', 'group' => 'contact'],
                    ['key' => 'emails', 'value' => "info@leylasafaritours.com\ninquiry@leylasafaritours.com", 'group' => 'contact'],
                    ['key' => 'address', 'value' => 'Ring Road Parklands, Westlands, Nairobi, Kenya', 'group' => 'contact'],
                ];
            @endphp
            @foreach ($defaults as $i => $default)
                <div class="admin-form__group" style="margin-bottom:1rem;">
                    <label>{{ str_replace('_', ' ', ucfirst($default['key'])) }}</label>
                    <input type="hidden" name="settings[{{ $i }}][key]" value="{{ $default['key'] }}">
                    <input type="hidden" name="settings[{{ $i }}][group]" value="{{ $default['group'] }}">
                    <textarea name="settings[{{ $i }}][value]" rows="{{ $default['key'] === 'emails' ? 2 : 1 }}">{{ $default['value'] }}</textarea>
                </div>
            @endforeach
        @endforelse

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection
