@php
    $mediaType = old('media_type', $mediaType ?? 'image');
@endphp

<div class="hero-media-field" data-hero-media-field>
    <div class="admin-form__group admin-form__group--full">
        <label for="hero-media-type">Slide media *</label>
        <select id="hero-media-type" name="media_type" data-hero-media-type>
            <option value="image" @selected($mediaType === 'image')>Image</option>
            <option value="video" @selected($mediaType === 'video')>Uploaded video</option>
            <option value="embed" @selected($mediaType === 'embed')>YouTube / external link</option>
        </select>
    </div>

    <div class="hero-media-field__panel" data-hero-media-panel="image" @if($mediaType !== 'image') hidden @endif>
        @include('admin.partials.image-field', [
            'name' => 'image',
            'label' => 'Slide image',
            'value' => $image ?? '',
            'folder' => 'heroes',
            'required' => false,
        ])
    </div>

    <div class="hero-media-field__panel" data-hero-media-panel="video" @if($mediaType !== 'video') hidden @endif>
        @include('admin.partials.video-field', [
            'name' => 'video_path',
            'label' => 'Slide video',
            'value' => $videoPath ?? '',
            'folder' => 'heroes',
            'required' => false,
        ])
        @include('admin.partials.image-field', [
            'name' => 'image',
            'label' => 'Poster image (optional)',
            'value' => $image ?? '',
            'folder' => 'heroes',
            'required' => false,
        ])
    </div>

    <div class="hero-media-field__panel" data-hero-media-panel="embed" @if($mediaType !== 'embed') hidden @endif>
        <div class="admin-form__group admin-form__group--full">
            <label for="hero-video-url">Video URL *</label>
            <input
                type="url"
                id="hero-video-url"
                name="video_url"
                value="{{ old('video_url', $videoUrl ?? '') }}"
                placeholder="https://www.youtube.com/watch?v=..."
                data-hero-video-url
            >
            <p class="admin-video-field__hint">Paste a YouTube, YouTube Shorts, or Vimeo link.</p>
        </div>
        @include('admin.partials.image-field', [
            'name' => 'image',
            'label' => 'Fallback poster image (optional)',
            'value' => $image ?? '',
            'folder' => 'heroes',
            'required' => false,
        ])
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-hero-media-field]').forEach(function (field) {
        const typeSelect = field.querySelector('[data-hero-media-type]');

        function syncPanels() {
            const type = typeSelect?.value || 'image';
            field.querySelectorAll('[data-hero-media-panel]').forEach(function (panel) {
                const active = panel.dataset.heroMediaPanel === type;
                panel.hidden = !active;
                panel.querySelectorAll('input, textarea, select').forEach(function (input) {
                    if (input === typeSelect) return;
                    input.disabled = !active;
                });
            });

            const imagePanel = field.querySelector('[data-hero-media-panel="image"] [data-image-path]');
            const videoPanel = field.querySelector('[data-hero-media-panel="video"] [data-video-path]');
            const urlInput = field.querySelector('[data-hero-video-url]');

            if (imagePanel) imagePanel.required = type === 'image';
            if (videoPanel) videoPanel.required = type === 'video';
            if (urlInput) urlInput.required = type === 'embed';
        }

        typeSelect?.addEventListener('change', syncPanels);
        syncPanels();
    });
});
</script>
@endpush
