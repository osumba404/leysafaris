@extends('layouts.admin')

@section('page_title', 'Packages')

@section('content')
    <div class="admin-card">
        <div class="admin-card__header">
            <h2 class="admin-card__title">Safari Packages</h2>
            <a href="{{ route('admin.packages.create') }}" class="admin-btn admin-btn--primary admin-btn--sm">
                <i data-lucide="plus"></i> Add new
            </a>
        </div>
        <p class="admin-sort-status" data-sort-status></p>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th aria-label="Reorder"></th>
                        <th>Title</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody data-sortable="{{ route('admin.reorder', 'packages') }}">
                    @forelse ($packages as $package)
                        <tr data-sort-id="{{ $package->id }}">
                            @include('admin.partials.sort-handle')
                            <td>
                                <strong>{{ $package->title }}</strong>
                                @if ($package->destinations->isNotEmpty())
                                    <br><small style="color: var(--admin-muted);">{{ $package->destinations->pluck('name')->join(', ') }}</small>
                                @endif
                            </td>
                            <td>{{ $package->duration_days }} days</td>
                            <td>{{ $package->starting_price ? ($package->currency ?? 'USD') . ' ' . number_format($package->starting_price, 0) : '-' }}</td>
                            <td><span class="admin-badge admin-badge--{{ $package->status }}">{{ $package->status }}</span></td>
                            <td>{{ $package->is_featured ? 'Yes' : 'No' }}</td>
                            <td>
                                @include('admin.partials.table-actions', [
                                    'viewUrl' => route('admin.packages.show', $package),
                                    'editUrl' => route('admin.packages.edit', $package),
                                    'deleteUrl' => route('admin.packages.destroy', $package),
                                    'deleteConfirm' => 'Delete this package?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No packages yet. Click <strong>Add new</strong> to create one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
