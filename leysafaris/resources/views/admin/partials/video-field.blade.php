@props([
    'name' => 'video_path',
    'label' => 'Video file',
    'value' => '',
    'folder' => 'heroes',
    'required' => false,
])

@php
    use App\Support\HeroMedia;

    $oldKey = str_replace(['[', ']'], ['.', ''], $name);
    $oldKey = preg_replace('/\.+/', '.', trim($oldKey, '.'));
    $current = HeroMedia::normalizeVideoPath(old($oldKey, $value)) ?? '';
    $fieldId = 'video-field-'.preg_replace('/[^a-z0-9_-]/i', '-', $name);
@endphp

<div class="admin-video-field" id="{{ $fieldId }}" data-video-field data-upload-url="{{ route('admin.uploads.video') }}" data-folder="{{ $folder }}">
    <label class="admin-video-field__label" for="{{ $fieldId }}-file">{{ $label }} @if($required)<span aria-hidden="true">*</span>@endif</label>

    <input type="hidden" name="{{ $name }}" value="{{ $current }}" data-video-path @if($required) required @endif>

    <div class="admin-video-field__preview" data-video-preview @if(! $current) hidden @endif>
        @if ($current)
            <video src="{{ HeroMedia::videoUrl($current) }}" controls muted playsinline data-video-preview-player></video>
        @else
            <video src="" controls muted playsinline data-video-preview-player hidden></video>
        @endif
        <p class="admin-video-field__path" data-video-path-label>{{ $current }}</p>
    </div>

    <div class="admin-video-field__upload">
        <input type="file" id="{{ $fieldId }}-file" accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov,.m4v" data-video-file>
        <p class="admin-video-field__hint">Upload MP4, WebM, or MOV (max 50MB).</p>
        <p class="admin-video-field__status" data-video-status aria-live="polite"></p>
    </div>
</div>
