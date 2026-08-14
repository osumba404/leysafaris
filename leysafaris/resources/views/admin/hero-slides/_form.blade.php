<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($heroSlide) ? 'Edit Slide' : 'New Slide' }}</h2>
        <a href="{{ route('admin.hero-slides.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($heroSlide) ? route('admin.hero-slides.update', $heroSlide) : route('admin.hero-slides.store') }}" method="POST">
        @csrf
        @if(isset($heroSlide)) @method('PUT') @endif
        <div class="admin-form__group admin-form__group--full"><label for="image">Image path *</label><input type="text" id="image" name="image" value="{{ old('image', $heroSlide->image ?? 'images/savannah_sunset_tree.jpg') }}" required placeholder="images/savannah_sunset_tree.jpg"><small style="color:var(--admin-muted);">Path relative to public/ folder</small></div>
        <div class="admin-form__group"><label for="eyebrow">Eyebrow</label><input type="text" id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $heroSlide->eyebrow ?? '') }}"></div>
        <div class="admin-form__group"><label for="sort_order">Sort order</label><input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $heroSlide->sort_order ?? 0) }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="title">Title *</label><input type="text" id="title" name="title" value="{{ old('title', $heroSlide->title ?? '') }}" required></div>
        <div class="admin-form__group admin-form__group--full"><label for="subtitle">Subtitle</label><textarea id="subtitle" name="subtitle" rows="3">{{ old('subtitle', $heroSlide->subtitle ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_active" value="0"><input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $heroSlide->is_active ?? true))><label for="is_active">Active</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($heroSlide) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
