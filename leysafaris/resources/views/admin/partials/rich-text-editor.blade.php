@props([
    'name' => 'content',
    'label' => 'Content',
    'value' => '',
    'folder' => 'blog',
    'rows' => 16,
    'required' => false,
])

@php
    $fieldId = 'rich-editor-' . preg_replace('/[^a-z0-9_-]/i', '-', $name);
    $oldKey = str_replace(['[', ']'], ['.', ''], $name);
    $oldKey = preg_replace('/\.+/', '.', trim($oldKey, '.'));
    $current = old($oldKey, $value);
@endphp

<div
    class="admin-rich-editor-wrap admin-form__group admin-form__group--full"
    data-rich-editor-wrap
    data-upload-url="{{ route('admin.uploads.image') }}"
    data-upload-folder="{{ $folder }}"
>
    <div class="admin-rich-editor__header">
        <label for="{{ $fieldId }}" class="admin-rich-editor__label">{{ $label }} @if($required)<span aria-hidden="true">*</span>@endif</label>
        <p class="admin-rich-editor__stats" data-rich-editor-stats="{{ $fieldId }}" aria-live="polite">0 words · 0 characters</p>
    </div>

    <div class="admin-rich-editor" data-rich-editor data-editor-for="{{ $fieldId }}">
        <textarea
            id="{{ $fieldId }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            class="admin-rich-editor__source"
            @if($required) required @endif
        >{{ $current }}</textarea>
    </div>

    <p class="admin-rich-editor__hint">
        Format headings, lists, quotes, tables, links, and inline images. Use <strong>Source</strong> in the toolbar for HTML view.
        Images upload to your media library automatically.
    </p>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.css">
    @endpush
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>
        <script src="{{ asset('js/admin-rich-editor.js') }}?v={{ filemtime(public_path('js/admin-rich-editor.js')) }}"></script>
    @endpush
@endonce
