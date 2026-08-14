@extends('layouts.admin')
@section('page_title', 'Navigation')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Navigation Menu</h2>
        <button type="button" class="admin-btn admin-btn--primary admin-btn--sm" onclick="openAdminModal('nav-item-modal', { _title: 'Add Nav Item', _action: '{{ route('admin.nav-items.store') }}' })">
            <i data-lucide="plus"></i> Add new
        </button>
    </div>
    <p class="admin-sort-status" data-sort-status></p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th aria-label="Reorder"></th><th>Label</th><th>Route / URL</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody data-sortable="{{ route('admin.reorder', 'nav-items') }}">
                @forelse ($items as $item)
                    <tr data-sort-id="{{ $item->id }}">
                        @include('admin.partials.sort-handle')
                        <td>{{ $item->label }}@if($item->is_highlight) <span class="admin-badge admin-badge--published">Accent</span>@endif</td>
                        <td><code>{{ $item->route_name ?: $item->url }}</code></td>
                        <td>{{ $item->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            @php
                                $modalData = [
                                    '_title' => 'Edit Nav Item',
                                    '_action' => route('admin.nav-items.update', $item),
                                    '_method' => 'PUT',
                                    'label' => $item->label,
                                    'route_name' => $item->route_name,
                                    'url' => $item->url,
                                    'is_active' => $item->is_active,
                                    'is_highlight' => $item->is_highlight,
                                ];
                            @endphp
                            @include('admin.partials.table-actions', [
                                'editModal' => 'nav-item-modal',
                                'editModalData' => $modalData,
                                'deleteUrl' => route('admin.nav-items.destroy', $item),
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No nav items yet. Click <strong>Add new</strong> to build your menu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="admin-modal" id="nav-item-modal" data-admin-modal hidden>
    <div class="admin-modal__backdrop" data-modal-close></div>
    <div class="admin-modal__panel" role="dialog" aria-modal="true">
        <div class="admin-modal__header">
            <h3 data-modal-title>Add Nav Item</h3>
            <button type="button" class="admin-modal__close" data-modal-close aria-label="Close">&times;</button>
        </div>
        <form class="admin-form admin-form--grid" data-crud-form action="{{ route('admin.nav-items.store') }}" method="POST">
            @csrf
            <div class="admin-form__group"><label for="nav-label">Label *</label><input type="text" id="nav-label" name="label" required></div>
            <div class="admin-form__group"><label for="nav-route">Route name</label><input type="text" id="nav-route" name="route_name" placeholder="packages.index"></div>
            <div class="admin-form__group admin-form__group--full"><label for="nav-url">URL (if no route)</label><input type="text" id="nav-url" name="url" placeholder="https://..."></div>
            <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_active" value="0"><input type="checkbox" id="nav-active" name="is_active" value="1" checked><label for="nav-active">Active</label></div>
            <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_highlight" value="0"><input type="checkbox" id="nav-highlight" name="is_highlight" value="1"><label for="nav-highlight">Highlight (accent style)</label></div>
            <div class="admin-form__actions admin-form__group--full">
                <button type="button" class="admin-btn admin-btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn--primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
