<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($annualEvent) ? 'Edit: '.$annualEvent->title : 'Create Annual Event' }}</h2>
        <a href="{{ route('admin.annual-events.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($annualEvent) ? route('admin.annual-events.update', $annualEvent) : route('admin.annual-events.store') }}" method="POST">
        @csrf @if(isset($annualEvent)) @method('PUT') @endif
        <div class="admin-form__group"><label for="title">Title *</label><input type="text" id="title" name="title" value="{{ old('title', $annualEvent->title ?? '') }}" required></div>
        <div class="admin-form__group"><label for="slug">Slug</label><input type="text" id="slug" name="slug" value="{{ old('slug', $annualEvent->slug ?? '') }}"></div>
        <div class="admin-form__group"><label for="event_date">Event Date *</label><input type="date" id="event_date" name="event_date" value="{{ old('event_date', isset($annualEvent) ? $annualEvent->event_date->format('Y-m-d') : '') }}" required></div>
        <div class="admin-form__group"><label for="package_id">Linked Package</label><select id="package_id" name="package_id"><option value="">—</option>@foreach($packages as $p)<option value="{{ $p->id }}" @selected(old('package_id', $annualEvent->package_id ?? '')==$p->id)>{{ $p->title }}</option>@endforeach</select></div>
        <div class="admin-form__group"><label for="early_bird_deadline">Early Bird Deadline</label><input type="date" id="early_bird_deadline" name="early_bird_deadline" value="{{ old('early_bird_deadline', isset($annualEvent) && $annualEvent->early_bird_deadline ? $annualEvent->early_bird_deadline->format('Y-m-d') : '') }}"></div>
        <div class="admin-form__group"><label for="early_bird_price">Early Bird Price</label><input type="number" id="early_bird_price" name="early_bird_price" step="0.01" min="0" value="{{ old('early_bird_price', $annualEvent->early_bird_price ?? '') }}"></div>
        <div class="admin-form__group"><label for="regular_price">Regular Price</label><input type="number" id="regular_price" name="regular_price" step="0.01" min="0" value="{{ old('regular_price', $annualEvent->regular_price ?? '') }}"></div>
        <div class="admin-form__group"><label for="currency">Currency</label><input type="text" id="currency" name="currency" maxlength="3" value="{{ old('currency', $annualEvent->currency ?? 'USD') }}"></div>
        <div class="admin-form__group"><label for="hero_image">Hero Image</label><input type="text" id="hero_image" name="hero_image" value="{{ old('hero_image', $annualEvent->hero_image ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt">{{ old('excerpt', $annualEvent->excerpt ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="description">Description</label><textarea id="description" name="description" rows="5">{{ old('description', $annualEvent->description ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_published" value="0"><input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $annualEvent->is_published ?? true))><label for="is_published">Published</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($annualEvent) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
