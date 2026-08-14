@props([
    'name' => 'image',
    'label' => 'Image',
    'value' => '',
    'folder' => 'uploads',
    'required' => true,
])

@php
    use App\Support\PublicImage;

    $oldKey = str_replace(['[', ']'], ['.', ''], $name);
    $oldKey = preg_replace('/\.+/', '.', trim($oldKey, '.'));
    $current = PublicImage::normalizeStoredPath(old($oldKey, $value)) ?? '';
    $fieldId = 'image-field-'.preg_replace('/[^a-z0-9_-]/i', '-', $name);
@endphp

<div class="admin-image-field" id="{{ $fieldId }}" data-image-field data-upload-url="{{ route('admin.uploads.image') }}" data-folder="{{ $folder }}">
    <label class="admin-image-field__label" for="{{ $fieldId }}-file">{{ $label }} @if($required)<span aria-hidden="true">*</span>@endif</label>

    <input type="hidden" name="{{ $name }}" value="{{ $current }}" data-image-path @if($required) required @endif>

    <div class="admin-image-field__preview" data-image-preview @if(! $current) hidden @endif>
        @if ($current)
            <img src="{{ PublicImage::url($current) }}" alt="Preview" data-image-preview-img>
        @else
            <img src="" alt="Preview" data-image-preview-img hidden>
        @endif
        <p class="admin-image-field__path" data-image-path-label>{{ $current }}</p>
    </div>

    <div class="admin-image-field__upload">
        <input type="file" id="{{ $fieldId }}-file" accept="image/jpeg,image/png,image/webp,image/gif" data-image-file>
        <p class="admin-image-field__hint">Upload JPG, PNG, WebP or GIF (max 5MB). Pasting paths is not allowed.</p>
        <p class="admin-image-field__status" data-image-status aria-live="polite"></p>
    </div>
</div>
