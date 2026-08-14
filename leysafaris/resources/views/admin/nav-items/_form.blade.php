<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($navItem) ? 'Edit Nav Item' : 'New Nav Item' }}</h2>
        <a href="{{ route('admin.nav-items.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($navItem) ? route('admin.nav-items.update', $navItem) : route('admin.nav-items.store') }}" method="POST">
        @csrf
        @if(isset($navItem)) @method('PUT') @endif
        <div class="admin-form__group"><label for="label">Label *</label><input type="text" id="label" name="label" value="{{ old('label', $navItem->label ?? '') }}" required></div>
        <div class="admin-form__group"><label for="route_name">Route name</label><input type="text" id="route_name" name="route_name" value="{{ old('route_name', $navItem->route_name ?? '') }}" placeholder="packages.index"></div>
        <div class="admin-form__group admin-form__group--full"><label for="url">URL (if no route)</label><input type="text" id="url" name="url" value="{{ old('url', $navItem->url ?? '') }}" placeholder="https://..."></div>
        <div class="admin-form__group"><label for="sort_order">Sort order</label><input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $navItem->sort_order ?? 0) }}"></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_active" value="0"><input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $navItem->is_active ?? true))><label for="is_active">Active</label></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_highlight" value="0"><input type="checkbox" id="is_highlight" name="is_highlight" value="1" @checked(old('is_highlight', $navItem->is_highlight ?? false))><label for="is_highlight">Highlight (accent style)</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($navItem) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
