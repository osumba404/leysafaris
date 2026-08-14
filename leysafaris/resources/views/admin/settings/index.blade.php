@extends('layouts.admin')
@section('page_title', 'Settings')
@section('content')
<div class="admin-card" style="margin-bottom: 1.25rem;">
    <h2 class="admin-card__title" style="margin-bottom: 0.75rem;">Where to edit site content</h2>
    <ul style="margin: 0; padding-left: 1.25rem; color: var(--admin-muted); line-height: 1.8;">
        <li><strong>Hero slides</strong> (images + headline text) — <a href="{{ route('admin.hero-slides.index') }}">Admin → Hero Slides</a></li>
        <li><strong>Navigation menu</strong> — <a href="{{ route('admin.nav-items.index') }}">Admin → Navigation</a></li>
        <li><strong>Footer links</strong> — <a href="{{ route('admin.footer-links.index') }}">Admin → Footer Links</a></li>
        <li><strong>Site name, logo, contact, social</strong> — this page (edit each setting below)</li>
    </ul>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Site Settings</h2>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Group</th>
                    <th>Setting</th>
                    <th>Current value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($settings as $setting)
                    @php
                        $type = \App\Support\SettingDefinitions::type($setting->key);
                        $label = \App\Support\SettingDefinitions::label($setting->key);
                        $formValue = \App\Support\SettingDefinitions::formValue($setting);
                        $preview = \App\Support\SettingDefinitions::preview($setting);
                        $hint = \App\Support\SettingDefinitions::hint($setting->key);
                        $modalData = [
                            '_title' => 'Edit: '.$label,
                            '_action' => route('admin.settings.update', $setting),
                            '_method' => 'PUT',
                            '_type' => $type,
                            'value' => $formValue,
                        ];
                        if ($type === 'image' && is_string($setting->value) && $setting->value !== '') {
                            $modalData['image'] = $setting->value;
                            $modalData['_imageUrl'] = asset($setting->value);
                        }
                        if ($hint) {
                            $modalData['_hint'] = $hint;
                        }
                    @endphp
                    <tr>
                        <td style="text-transform: capitalize;">{{ $setting->group }}</td>
                        <td><strong>{{ $label }}</strong><br><code style="font-size:0.75rem;color:var(--admin-muted);">{{ $setting->key }}</code></td>
                        <td>
                            @if ($type === 'image' && is_string($setting->value) && $setting->value !== '')
                                <img src="{{ asset($setting->value) }}" alt="" style="width:72px;height:48px;object-fit:contain;border-radius:6px;border:1px solid var(--admin-border);">
                            @else
                                {{ $preview }}
                            @endif
                        </td>
                        <td class="admin-table__actions">
                            @include('admin.partials.table-actions', [
                                'editModal' => 'setting-modal',
                                'editModalData' => $modalData,
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No settings found. Run the database seeder to populate defaults.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="admin-modal" id="setting-modal" data-admin-modal hidden>
    <div class="admin-modal__backdrop" data-modal-close></div>
    <div class="admin-modal__panel" role="dialog" aria-modal="true" aria-labelledby="setting-modal-title">
        <div class="admin-modal__header">
            <h3 id="setting-modal-title" data-modal-title>Edit Setting</h3>
            <button type="button" class="admin-modal__close" data-modal-close aria-label="Close">&times;</button>
        </div>
        <form class="admin-form" data-crud-form action="#" method="POST">
            @csrf
            <div class="admin-form__group admin-form__group--full" data-setting-field="text">
                <label for="setting-value-text">Value</label>
                <input type="text" id="setting-value-text" name="value">
            </div>
            <div class="admin-form__group admin-form__group--full" data-setting-field="long" hidden>
                <label for="setting-value-long">Value</label>
                <textarea id="setting-value-long" name="value" rows="5"></textarea>
            </div>
            <div class="admin-form__group admin-form__group--full" data-setting-field="list" hidden>
                <label for="setting-value-list">Value</label>
                <textarea id="setting-value-list" name="value" rows="5"></textarea>
                <small style="color:var(--admin-muted);" data-setting-hint="list">One value per line</small>
            </div>
            <div class="admin-form__group admin-form__group--full" data-setting-field="social" hidden>
                <label for="setting-value-social">Social links</label>
                <textarea id="setting-value-social" name="value" rows="5" placeholder="facebook|https://facebook.com/yourpage"></textarea>
                <small style="color:var(--admin-muted);" data-setting-hint="social">One per line: platform|url</small>
            </div>
            <div class="admin-form__group admin-form__group--full" data-setting-field="image" hidden>
                @include('admin.partials.image-field', [
                    'name' => 'value',
                    'label' => 'Image',
                    'value' => '',
                    'folder' => 'logos',
                    'required' => false,
                ])
            </div>
            <div class="admin-form__actions">
                <button type="button" class="admin-btn admin-btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn--primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
