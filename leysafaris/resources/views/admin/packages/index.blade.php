@extends('layouts.admin')

@section('page_title', 'Packages')

@section('content')
    <div class="admin-card">
        <div class="admin-card__header">
            <h2 class="admin-card__title">Safari Packages</h2>
            <a href="{{ route('admin.packages.create') }}" class="admin-btn admin-btn--primary">
                <i data-lucide="plus"></i> New Package
            </a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
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
                                <a href="{{ route('admin.packages.show', $package) }}" class="admin-btn admin-btn--secondary admin-btn--sm">View</a>
                                <a href="{{ route('admin.packages.edit', $package) }}" class="admin-btn admin-btn--secondary admin-btn--sm">Edit</a>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this package?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No packages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">{{ $packages->links() }}</div>
    </div>
@endsection
