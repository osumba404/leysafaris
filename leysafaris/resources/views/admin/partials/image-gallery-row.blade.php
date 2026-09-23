@php
    use App\Support\PublicImage;

    $normalized = PublicImage::normalizeStoredPath($path ?? '') ?? '';
    $rowId = 'gallery-row-' . preg_replace('/[^a-z0-9_-]/i', '-', $name) . '-' . $index;
@endphp

<div class="admin-image-gallery__row" data-image-gallery-row data-row-index="{{ $index }}">
    <input type="hidden" name="{{ $name }}[]" value="{{ $normalized }}" data-image-path>

    <div class="admin-image-gallery__preview" data-image-preview @if(! $normalized) hidden @endif>
        @if ($normalized)
            <img src="{{ PublicImage::url($normalized) }}" alt="Preview" data-image-preview-img>
        @else
            <img src="" alt="Preview" data-image-preview-img hidden>
        @endif
    </div>

    <div class="admin-image-gallery__upload">
        <input
            type="file"
            id="{{ $rowId }}-file"
            accept="image/jpeg,image/png,image/webp,image/gif"
            data-image-file
        >
        <p class="admin-image-gallery__path" data-image-path-label>{{ $normalized }}</p>
        <p class="admin-image-gallery__status" data-image-status aria-live="polite"></p>
    </div>

    <button type="button" class="admin-btn admin-btn--secondary admin-btn--sm admin-image-gallery__remove" data-gallery-remove aria-label="Remove image">
        <i data-lucide="minus"></i>
    </button>
</div>
