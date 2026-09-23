@props([
    'name' => 'gallery',
    'label' => 'Images',
    'folder' => 'uploads',
    'values' => [],
])

@php
    use App\Support\PublicImage;

    $items = old($name, $values);
    if (! is_array($items)) {
        $items = [];
    }

    $items = array_values(array_filter(array_map(
        fn ($item) => PublicImage::normalizeStoredPath(is_string($item) ? $item : null),
        $items
    )));

    if ($items === []) {
        $items = [''];
    }

    $fieldId = 'image-gallery-' . preg_replace('/[^a-z0-9_-]/i', '-', $name);
@endphp

<div
    class="admin-image-gallery"
    id="{{ $fieldId }}"
    data-image-gallery
    data-upload-url="{{ route('admin.uploads.image') }}"
    data-folder="{{ $folder }}"
    data-input-name="{{ $name }}"
>
    <label class="admin-image-gallery__label">{{ $label }}</label>
    <p class="admin-image-gallery__hint">Upload as many images as you need. The first image is used as the hero image on listing pages.</p>

    <div class="admin-image-gallery__items" data-gallery-items>
        @foreach ($items as $index => $path)
            @include('admin.partials.image-gallery-row', [
                'name' => $name,
                'path' => $path,
                'folder' => $folder,
                'index' => $index,
            ])
        @endforeach
    </div>

    <button type="button" class="admin-btn admin-btn--secondary admin-btn--sm admin-image-gallery__add" data-gallery-add>
        <i data-lucide="plus"></i> Add image
    </button>

    <template data-gallery-row-template>
        @include('admin.partials.image-gallery-row', [
            'name' => $name,
            'path' => '',
            'folder' => $folder,
            'index' => '__INDEX__',
        ])
    </template>
</div>
