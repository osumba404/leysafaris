<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($destination) ? 'Edit: '.$destination->name : 'Create Destination' }}</h2>
        <a href="{{ route('admin.destinations.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($destination) ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}" method="POST">
        @csrf @if(isset($destination)) @method('PUT') @endif
        <div class="admin-form__group"><label for="name">Name *</label><input type="text" id="name" name="name" value="{{ old('name', $destination->name ?? '') }}" required></div>
        <div class="admin-form__group"><label for="slug">Slug</label><input type="text" id="slug" name="slug" value="{{ old('slug', $destination->slug ?? '') }}"></div>
        <div class="admin-form__group"><label for="country">Country</label><input type="text" id="country" name="country" value="{{ old('country', $destination->country ?? 'Kenya') }}"></div>
        <div class="admin-form__group"><label for="region">Region</label><input type="text" id="region" name="region" value="{{ old('region', $destination->region ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full">
            @include('admin.partials.image-field', [
                'name' => 'hero_image',
                'label' => 'Hero image',
                'value' => old('hero_image', $destination->hero_image ?? ''),
                'folder' => 'destinations',
                'required' => false,
            ])
        </div>
        <div class="admin-form__group"><label for="best_time">Best Time</label><input type="text" id="best_time" name="best_time" value="{{ old('best_time', $destination->best_time ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt">{{ old('excerpt', $destination->excerpt ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="description">Description</label><textarea id="description" name="description" rows="6">{{ old('description', $destination->description ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="signature_wildlife">Signature Wildlife</label><textarea id="signature_wildlife" name="signature_wildlife">{{ old('signature_wildlife', $destination->signature_wildlife ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_featured" value="0"><input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $destination->is_featured ?? false))><label for="is_featured">Featured</label></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_published" value="0"><input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $destination->is_published ?? true))><label for="is_published">Published</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($destination) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
