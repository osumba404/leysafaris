@extends('layouts.admin')
@section('page_title', 'Hero Slides')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Homepage Hero Slides</h2>
        <button type="button" class="admin-btn admin-btn--primary admin-btn--sm" data-modal-open="hero-slide-modal" onclick="openAdminModal('hero-slide-modal', { _title: 'Add Hero Slide', _action: '{{ route('admin.hero-slides.store') }}' })">
            <i data-lucide="plus"></i> Add new
        </button>
    </div>
    <p class="admin-sort-status" data-sort-status></p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th aria-label="Reorder"></th><th>Preview</th><th>Title</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody data-sortable="{{ route('admin.reorder', 'hero-slides') }}">
                @forelse ($slides as $slide)
                    <tr data-sort-id="{{ $slide->id }}">
                        @include('admin.partials.sort-handle')
                        <td>
                            @if ($slide->image)
                                <img src="{{ asset($slide->image) }}" alt="" style="width:72px;height:48px;object-fit:cover;border-radius:6px;">
                            @endif
                        </td>
                        <td>{{ $slide->title }}</td>
                        <td>{{ $slide->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            @php
                                $modalData = [
                                    '_title' => 'Edit Hero Slide',
                                    '_action' => route('admin.hero-slides.update', $slide),
                                    '_method' => 'PUT',
                                    'image' => $slide->image,
                                    '_imageUrl' => $slide->image ? asset($slide->image) : '',
                                    'eyebrow' => $slide->eyebrow,
                                    'title' => $slide->title,
                                    'subtitle' => $slide->subtitle,
                                    'is_active' => $slide->is_active,
                                ];
                            @endphp
                            @include('admin.partials.table-actions', [
                                'editModal' => 'hero-slide-modal',
                                'editModalData' => $modalData,
                                'deleteUrl' => route('admin.hero-slides.destroy', $slide),
                                'deleteConfirm' => 'Delete slide?',
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No slides yet. Click <strong>Add new</strong> to create your homepage carousel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="admin-modal" id="hero-slide-modal" data-admin-modal hidden>
    <div class="admin-modal__backdrop" data-modal-close></div>
    <div class="admin-modal__panel" role="dialog" aria-modal="true" aria-labelledby="hero-slide-modal-title">
        <div class="admin-modal__header">
            <h3 id="hero-slide-modal-title" data-modal-title>Add Hero Slide</h3>
            <button type="button" class="admin-modal__close" data-modal-close aria-label="Close">&times;</button>
        </div>
        <form class="admin-form admin-form--grid" data-crud-form action="{{ route('admin.hero-slides.store') }}" method="POST">
            @csrf
            <div class="admin-form__group admin-form__group--full">
                @include('admin.partials.image-field', ['name' => 'image', 'label' => 'Slide image', 'folder' => 'heroes', 'required' => true])
            </div>
            <div class="admin-form__group"><label for="hero-eyebrow">Eyebrow</label><input type="text" id="hero-eyebrow" name="eyebrow"></div>
            <div class="admin-form__group admin-form__group--full"><label for="hero-title">Title *</label><input type="text" id="hero-title" name="title" required></div>
            <div class="admin-form__group admin-form__group--full"><label for="hero-subtitle">Subtitle</label><textarea id="hero-subtitle" name="subtitle" rows="3"></textarea></div>
            <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_active" value="0"><input type="checkbox" id="hero-active" name="is_active" value="1" checked><label for="hero-active">Active</label></div>
            <div class="admin-form__actions admin-form__group--full">
                <button type="button" class="admin-btn admin-btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn--primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
