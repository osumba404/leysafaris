<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($enquiry) ? 'Edit Enquiry' : 'Create Enquiry' }}</h2>
        <a href="{{ route('admin.enquiries.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($enquiry) ? route('admin.enquiries.update', $enquiry) : route('admin.enquiries.store') }}" method="POST">
        @csrf @if(isset($enquiry)) @method('PUT') @endif
        <div class="admin-form__group"><label for="name">Name *</label><input type="text" id="name" name="name" value="{{ old('name', $enquiry->name ?? '') }}" required></div>
        <div class="admin-form__group"><label for="email">Email *</label><input type="email" id="email" name="email" value="{{ old('email', $enquiry->email ?? '') }}" required></div>
        <div class="admin-form__group"><label for="phone">Phone</label><input type="text" id="phone" name="phone" value="{{ old('phone', $enquiry->phone ?? '') }}"></div>
        <div class="admin-form__group"><label for="whatsapp">WhatsApp</label><input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $enquiry->whatsapp ?? '') }}"></div>
        <div class="admin-form__group"><label for="package_id">Package</label>
            <select id="package_id" name="package_id"><option value="">-</option>@foreach($packages as $p)<option value="{{ $p->id }}" @selected(old('package_id', $enquiry->package_id ?? '')==$p->id)>{{ $p->title }}</option>@endforeach</select>
        </div>
        <div class="admin-form__group"><label for="status">Status</label>
            <select id="status" name="status">@foreach(['new','contacted','quote_sent','negotiation','confirmed','lost'] as $s)<option value="{{ $s }}" @selected(old('status', $enquiry->status ?? 'new')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select>
        </div>
        <div class="admin-form__group"><label for="assigned_to">Assigned To</label>
            <select id="assigned_to" name="assigned_to"><option value="">-</option>@foreach($admins as $admin)<option value="{{ $admin->id }}" @selected(old('assigned_to', $enquiry->assigned_to ?? '')==$admin->id)>{{ $admin->name }}</option>@endforeach</select>
        </div>
        <div class="admin-form__group"><label for="travel_dates">Travel Dates</label><input type="text" id="travel_dates" name="travel_dates" value="{{ old('travel_dates', $enquiry->travel_dates ?? '') }}"></div>
        <div class="admin-form__group"><label for="group_size">Group Size</label><input type="number" id="group_size" name="group_size" min="1" value="{{ old('group_size', $enquiry->group_size ?? '') }}"></div>
        <div class="admin-form__group"><label for="budget_range">Budget Range</label><input type="text" id="budget_range" name="budget_range" value="{{ old('budget_range', $enquiry->budget_range ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="preferred_destinations">Preferred Destinations</label><input type="text" id="preferred_destinations" name="preferred_destinations" value="{{ old('preferred_destinations', $enquiry->preferred_destinations ?? '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="special_interests">Special Interests</label><textarea id="special_interests" name="special_interests">{{ old('special_interests', $enquiry->special_interests ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="message">Message</label><textarea id="message" name="message" rows="4">{{ old('message', $enquiry->message ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full"><label for="admin_notes">Admin Notes</label><textarea id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $enquiry->admin_notes ?? '') }}</textarea></div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($enquiry) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
