@php
    $items = old($name, $items ?? []);
    if (! is_array($items)) {
        $items = [];
    }
    if ($items === []) {
        $items = [''];
    }
@endphp

<div class="admin-list-field" data-list-field="{{ $name }}">
    <label>{{ $label }}</label>
    <div class="admin-list-field__items">
        @foreach ($items as $item)
            <div class="admin-list-field__row">
                <input
                    type="text"
                    name="{{ $name }}[]"
                    value="{{ $item }}"
                    placeholder="{{ $placeholder ?? 'Enter item' }}"
                >
                <button type="button" class="admin-btn admin-btn--secondary admin-btn--sm admin-list-field__remove" aria-label="Remove line">
                    <i data-lucide="minus"></i>
                </button>
            </div>
        @endforeach
    </div>
    <button type="button" class="admin-btn admin-btn--secondary admin-btn--sm admin-list-field__add" data-add-label="{{ $addLabel ?? 'Add line' }}">
        <i data-lucide="plus"></i> {{ $addLabel ?? 'Add line' }}
    </button>
</div>
