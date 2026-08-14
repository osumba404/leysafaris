<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($experience) ? 'Edit: '.$experience->name : 'Create Experience' }}</h2>
        <a href="{{ route('admin.experiences.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($experience) ? route('admin.experiences.update', $experience) : route('admin.experiences.store') }}" method="POST">
        @csrf @if(isset($experience)) @method('PUT') @endif
        <div class="admin-form__group"><label for="name">Name *</label><input type="text" id="name" name="name" value="{{ old('name', $experience->name ?? '') }}" required></div>
        <div class="admin-form__group"><label for="slug">Slug</label><input type="text" id="slug" name="slug" value="{{ old('slug', $experience->slug ?? '') }}"></div>
        <div class="admin-form__group"><label for="type">Type</label><input type="text" id="type" name="type" value="{{ old('type', $experience->type ?? '') }}" placeholder="balloon, walking, cultural"></div>
        <div class="admin-form__group"><label for="duration_hours">Duration (hours)</label><input type="number" id="duration_hours" name="duration_hours" min="1" value="{{ old('duration_hours', $experience->duration_hours ?? '') }}"></div>
        <div class="admin-form__group"><label for="starting_price">Starting Price</label><input type="number" id="starting_price" name="starting_price" step="0.01" min="0" value="{{ old('starting_price', $experience->starting_price ?? '') }}"></div>
        <div class="admin-form__group"><label for="currency">Currency</label><input type="text" id="currency" name="currency" maxlength="3" value="{{ old('currency', $experience->currency ?? 'USD') }}"></div>
        <div class="admin-form__group admin-form__group--full">
            @include('admin.partials.image-field', [
                'name' => 'image',
                'label' => 'Image',
                'value' => old('image', $experience->image ?? ''),
                'folder' => 'experiences',
                'required' => false,
            ])
        </div>
        <div class="admin-form__group admin-form__group--full"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt">{{ old('excerpt', $experience->excerpt ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="description">Description</label><textarea id="description" name="description" rows="5">{{ old('description', $experience->description ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_published" value="0"><input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $experience->is_published ?? true))><label for="is_published">Published</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($experience) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
