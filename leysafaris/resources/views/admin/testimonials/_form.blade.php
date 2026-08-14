<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($testimonial) ? 'Edit Testimonial' : 'Create Testimonial' }}</h2>
        <a href="{{ route('admin.testimonials.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" method="POST">
        @csrf @if(isset($testimonial)) @method('PUT') @endif
        <div class="admin-form__group"><label for="author_name">Author Name *</label><input type="text" id="author_name" name="author_name" value="{{ old('author_name', $testimonial->author_name ?? '') }}" required></div>
        <div class="admin-form__group"><label for="author_location">Author Location</label><input type="text" id="author_location" name="author_location" value="{{ old('author_location', $testimonial->author_location ?? '') }}"></div>
        <div class="admin-form__group"><label for="rating">Rating *</label><select id="rating" name="rating" required>@for($i=1;$i<=5;$i++)<option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5)==$i)>{{ $i }} star{{ $i>1?'s':'' }}</option>@endfor</select></div>
        <div class="admin-form__group"><label for="package_id">Package</label><select id="package_id" name="package_id"><option value="">-</option>@foreach($packages as $p)<option value="{{ $p->id }}" @selected(old('package_id', $testimonial->package_id ?? '')==$p->id)>{{ $p->title }}</option>@endforeach</select></div>
        <div class="admin-form__group"><label for="source">Source</label><input type="text" id="source" name="source" value="{{ old('source', $testimonial->source ?? '') }}" placeholder="Google, TripAdvisor"></div>
        <div class="admin-form__group"><label for="source_url">Source URL</label><input type="url" id="source_url" name="source_url" value="{{ old('source_url', $testimonial->source_url ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="content">Content *</label><textarea id="content" name="content" rows="5" required>{{ old('content', $testimonial->content ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_approved" value="0"><input type="checkbox" id="is_approved" name="is_approved" value="1" @checked(old('is_approved', $testimonial->is_approved ?? true))><label for="is_approved">Approved</label></div>
        <div class="admin-form__group admin-form__checkbox"><input type="hidden" name="is_featured" value="0"><input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $testimonial->is_featured ?? false))><label for="is_featured">Featured</label></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($testimonial) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
