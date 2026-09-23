@props([
    'id',
    'name' => null,
    'label' => null,
    'required' => true,
    'autocomplete' => 'current-password',
    'placeholder' => '••••••••',
])

@php
    $inputName = $name ?? $id;
@endphp

<div {{ $attributes->class(['form-group', 'form-group--full']) }}>
    @if (isset($labelSlot))
        {{ $labelSlot }}
    @elseif ($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    <div class="password-field">
        <input
            type="password"
            id="{{ $id }}"
            name="{{ $inputName }}"
            @if($required) required @endif
            autocomplete="{{ $autocomplete }}"
            placeholder="{{ $placeholder }}"
        >
        <button
            type="button"
            class="password-field__toggle"
            aria-label="Show password"
            aria-pressed="false"
            aria-controls="{{ $id }}"
        >
            <i data-lucide="eye" aria-hidden="true"></i>
        </button>
    </div>
</div>
