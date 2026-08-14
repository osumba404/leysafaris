<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($package) ? 'Edit: ' . $package->title : 'Create Package' }}</h2>
        <a href="{{ route('admin.packages.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back to List</a>
    </div>

    <form class="admin-form admin-form--grid" id="package-form" action="{{ isset($package) ? route('admin.packages.update', $package) : route('admin.packages.store') }}" method="POST">
        @csrf
        @if (isset($package)) @method('PUT') @endif

        <div class="admin-form__group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $package->title ?? '') }}" required>
        </div>
        <div class="admin-form__group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $package->slug ?? '') }}" placeholder="Auto-generated if empty">
        </div>
        <div class="admin-form__group">
            <label for="tagline">Tagline</label>
            <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $package->tagline ?? '') }}">
        </div>
        <div class="admin-form__group">
            <label for="duration_days">Duration (days) *</label>
            <input type="number" id="duration_days" name="duration_days" min="1" value="{{ old('duration_days', $package->duration_days ?? 7) }}" required>
        </div>
        <div class="admin-form__group">
            <label for="starting_price">Starting Price</label>
            <input type="number" id="starting_price" name="starting_price" step="0.01" min="0" value="{{ old('starting_price', $package->starting_price ?? '') }}">
        </div>
        <div class="admin-form__group">
            <label for="currency">Currency</label>
            <input type="text" id="currency" name="currency" maxlength="3" value="{{ old('currency', $package->currency ?? 'USD') }}">
        </div>
        <div class="admin-form__group">
            <label for="price_note">Price Note</label>
            <input type="text" id="price_note" name="price_note" value="{{ old('price_note', $package->price_note ?? '') }}">
        </div>
        <div class="admin-form__group">
            <label for="departure_style">Departure Style</label>
            <select id="departure_style" name="departure_style">
                <option value="">-</option>
                @foreach (['private', 'fixed', 'custom', 'group'] as $style)
                    <option value="{{ $style }}" @selected(old('departure_style', $package->departure_style ?? '') === $style)>{{ ucfirst($style) }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-form__group">
            <label for="status">Status *</label>
            <select id="status" name="status" required>
                @foreach (['draft', 'published', 'archived'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $package->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="admin-form__group admin-form__group--full">
            @include('admin.partials.image-field', [
                'name' => 'hero_image',
                'label' => 'Hero image',
                'value' => old('hero_image', $package->hero_image ?? ''),
                'folder' => 'packages',
                'required' => false,
            ])
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label for="short_description">Short Description</label>
            <textarea id="short_description" name="short_description">{{ old('short_description', $package->short_description ?? '') }}</textarea>
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label for="long_description">Long Description</label>
            <textarea id="long_description" name="long_description" rows="6">{{ old('long_description', $package->long_description ?? '') }}</textarea>
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label for="highlights_text">Highlights (one per line)</label>
            <textarea id="highlights_text" rows="4" placeholder="Hot air balloon safari">{{ old('highlights_text', isset($package) && $package->highlights ? implode("\n", $package->highlights) : '') }}</textarea>
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label for="inclusions_text">Inclusions (one per line)</label>
            <textarea id="inclusions_text" rows="4">{{ old('inclusions_text', isset($package) && $package->inclusions ? implode("\n", $package->inclusions) : '') }}</textarea>
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label for="exclusions_text">Exclusions (one per line)</label>
            <textarea id="exclusions_text" rows="4">{{ old('exclusions_text', isset($package) && $package->exclusions ? implode("\n", $package->exclusions) : '') }}</textarea>
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label>Destinations</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.5rem;">
                @foreach ($destinations as $dest)
                    <label class="admin-form__checkbox">
                        <input type="checkbox" name="destination_ids[]" value="{{ $dest->id }}" @checked(in_array($dest->id, old('destination_ids', isset($package) ? $package->destinations->pluck('id')->all() : [])))>
                        {{ $dest->name }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label>Experiences</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.5rem;">
                @foreach ($experiences as $exp)
                    <label class="admin-form__checkbox">
                        <input type="checkbox" name="experience_ids[]" value="{{ $exp->id }}" @checked(in_array($exp->id, old('experience_ids', isset($package) ? $package->experiences->pluck('id')->all() : [])))>
                        {{ $exp->name }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="admin-form__group admin-form__checkbox">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured ?? false))>
            <label for="is_featured">Featured</label>
        </div>
        <div class="admin-form__group admin-form__checkbox">
            <input type="hidden" name="is_template" value="0">
            <input type="checkbox" id="is_template" name="is_template" value="1" @checked(old('is_template', $package->is_template ?? false))>
            <label for="is_template">Template</label>
        </div>

        @php $days = old('days', isset($package) ? $package->packageDays->toArray() : []); @endphp
        <div class="admin-form__group admin-form__group--full">
            <h3 style="margin-bottom: 0.75rem;">Itinerary Days</h3>
            <div id="days-container">
                @forelse ($days as $i => $day)
                    @include('admin.packages._day_fields', ['index' => $i, 'day' => $day])
                @empty
                    @include('admin.packages._day_fields', ['index' => 0, 'day' => []])
                @endforelse
            </div>
            <button type="button" class="admin-btn admin-btn--secondary admin-btn--sm" id="add-day" style="margin-top: 0.75rem;">+ Add Day</button>
        </div>

        <div class="admin-form__actions admin-form__group--full">
            <button type="submit" class="admin-btn admin-btn--primary">{{ isset($package) ? 'Update Package' : 'Create Package' }}</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let dayIndex = {{ count($days) ?: 1 }};
    document.getElementById('add-day')?.addEventListener('click', function () {
        const container = document.getElementById('days-container');
        const template = container.querySelector('.admin-day-block').cloneNode(true);
        template.querySelectorAll('input, textarea').forEach(el => {
            el.name = el.name.replace(/days\[\d+\]/, 'days[' + dayIndex + ']');
            el.value = '';
        });
        template.querySelector('.admin-day-block__title').textContent = 'Day ' + (dayIndex + 1);
        container.appendChild(template);
        dayIndex++;
    });

    const form = document.getElementById('package-form');
    form?.addEventListener('submit', function () {
        ['highlights', 'inclusions', 'exclusions'].forEach(function (field) {
            const el = document.getElementById(field + '_text');
            if (!el) return;
            el.value.split('\n').map(s => s.trim()).filter(Boolean).forEach(function (line) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = field + '[]';
                input.value = line;
                form.appendChild(input);
            });
            el.disabled = true;
        });
    });
});
</script>
@endpush
