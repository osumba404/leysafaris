@extends('layouts.admin')
@section('page_title', 'Footer Links')
@section('content')
<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">Footer Links</h2>
        <button type="button" class="admin-btn admin-btn--primary admin-btn--sm" onclick="openAdminModal('footer-link-modal', { _title: 'Add Footer Link', _action: '{{ route('admin.footer-links.store') }}' })">
            <i data-lucide="plus"></i> Add new
        </button>
    </div>
    <p class="admin-sort-status" data-sort-status></p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th aria-label="Reorder"></th><th>Group</th><th>Label</th><th>Route / URL</th><th>Actions</th></tr></thead>
            @forelse ($links->groupBy('group') as $group => $groupLinks)
                <tbody data-sortable="{{ route('admin.reorder', 'footer-links') }}" data-sort-group="{{ $group }}">
                    @foreach ($groupLinks as $link)
                        <tr data-sort-id="{{ $link->id }}" data-sort-group="{{ $link->group }}">
                            @include('admin.partials.sort-handle')
                            <td>{{ \App\Models\FooterLink::groupLabel($link->group) }}</td>
                            <td>{{ $link->label }}</td>
                            <td><code>{{ $link->route_name ?: $link->url }}</code></td>
                            <td>
                                @php
                                    $modalData = [
                                        '_title' => 'Edit Footer Link',
                                        '_action' => route('admin.footer-links.update', $link),
                                        '_method' => 'PUT',
                                        'group' => $link->group,
                                        'label' => $link->label,
                                        'route_name' => $link->route_name,
                                        'url' => $link->url,
                                        'is_active' => $link->is_active,
                                    ];
                                @endphp
                                @include('admin.partials.table-actions', [
                                    'editModal' => 'footer-link-modal',
                                    'editModalData' => $modalData,
                                    'deleteUrl' => route('admin.footer-links.destroy', $link),
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @empty
                <tbody>
                    <tr><td colspan="5">No footer links yet. Click <strong>Add new</strong>.</td></tr>
                </tbody>
            @endforelse
        </table>
    </div>
</div>

<div class="admin-modal" id="footer-link-modal" data-admin-modal hidden>
    <div class="admin-modal__backdrop" data-modal-close></div>
    <div class="admin-modal__panel" role="dialog" aria-modal="true">
        <div class="admin-modal__header">
            <h3 data-modal-title>Add Footer Link</h3>
            <button type="button" class="admin-modal__close" data-modal-close aria-label="Close">&times;</button>
        </div>
        <form class="admin-form admin-form--grid" data-crud-form action="{{ route('admin.footer-links.store') }}" method="POST">
            @csrf
            <div class="admin-form__group"><label for="footer-group">Group *</label><select id="footer-group" name="group" required><option value="explore">Explore</option><option value="travel_info">Travel Info</option></select></div>
            <div class="admin-form__group"><label for="footer-label">Label *</label><input type="text" id="footer-label" name="label" required></div>
            <div class="admin-form__group"><label for="footer-route">Route name</label><input type="text" id="footer-route" name="route_name"></div>
            <div class="admin-form__group admin-form__group--full"><label for="footer-url">URL (if no route)</label><input type="text" id="footer-url" name="url"></div>
            <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_active" value="0"><input type="checkbox" id="footer-active" name="is_active" value="1" checked><label for="footer-active">Active</label></div>
            <div class="admin-form__actions admin-form__group--full">
                <button type="button" class="admin-btn admin-btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="admin-btn admin-btn--primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
