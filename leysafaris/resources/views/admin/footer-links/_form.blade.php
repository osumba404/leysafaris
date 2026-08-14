<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($footerLink) ? 'Edit Footer Link' : 'New Footer Link' }}</h2>
        <a href="{{ route('admin.footer-links.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($footerLink) ? route('admin.footer-links.update', $footerLink) : route('admin.footer-links.store') }}" method="POST">
        @csrf
        @if(isset($footerLink)) @method('PUT') @endif
        <div class="admin-form__group"><label for="group">Group *</label><select id="group" name="group" required><option value="explore" @selected(old('group', $footerLink->group ?? 'explore') === 'explore')>Explore</option><option value="travel_info" @selected(old('group', $footerLink->group ?? '') === 'travel_info')>Travel Info</option></select></div>
        <div class="admin-form__group"><label for="label">Label *</label><input type="text" id="label" name="label" value="{{ old('label', $footerLink->label ?? '') }}" required></div>
        <div class="admin-form__group"><label for="route_name">Route name</label><input type="text" id="route_name" name="route_name" value="{{ old('route_name', $footerLink->route_name ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="url">URL (if no route)</label><input type="text" id="url" name="url" value="{{ old('url', $footerLink->url ?? '') }}"></div>
        <div class="admin-form__group"><label for="sort_order">Sort order</label><input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $footerLink->sort_order ?? 0) }}"></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_active" value="0"><input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $footerLink->is_active ?? true))><label for="is_active">Active</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($footerLink) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
