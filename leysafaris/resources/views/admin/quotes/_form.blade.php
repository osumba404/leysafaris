<div class="admin-card">
    <div class="admin-card__header">
        <h2 class="admin-card__title">{{ isset($quote) ? 'Edit Quote' : 'Create Quote' }}</h2>
        <a href="{{ route('admin.quotes.index') }}" class="admin-btn admin-btn--secondary admin-btn--sm">Back</a>
    </div>
    <form class="admin-form admin-form--grid" action="{{ isset($quote) ? route('admin.quotes.update', $quote) : route('admin.quotes.store') }}" method="POST">
        @csrf @if(isset($quote)) @method('PUT') @endif
        <div class="admin-form__group"><label for="enquiry_id">Enquiry *</label>
            <select id="enquiry_id" name="enquiry_id" required><option value="">Select enquiry</option>@foreach($enquiries as $e)<option value="{{ $e->id }}" @selected(old('enquiry_id', $quote->enquiry_id ?? $enquiry?->id ?? '')==$e->id)>{{ $e->name }} ({{ $e->email }})</option>@endforeach</select>
        </div>
        <div class="admin-form__group"><label for="package_id">Package</label>
            <select id="package_id" name="package_id"><option value="">-</option>@foreach($packages as $p)<option value="{{ $p->id }}" @selected(old('package_id', $quote->package_id ?? $enquiry?->package_id ?? '')==$p->id)>{{ $p->title }}</option>@endforeach</select>
        </div>
        <div class="admin-form__group"><label for="title">Title *</label><input type="text" id="title" name="title" value="{{ old('title', $quote->title ?? ($enquiry?->package?->title ? 'Quote: '.$enquiry->package->title : '')) }}" required></div>
        <div class="admin-form__group"><label for="total_amount">Total Amount *</label><input type="number" id="total_amount" name="total_amount" step="0.01" min="0" value="{{ old('total_amount', $quote->total_amount ?? '') }}" required></div>
        <div class="admin-form__group"><label for="currency">Currency</label><input type="text" id="currency" name="currency" maxlength="3" value="{{ old('currency', $quote->currency ?? 'USD') }}"></div>
        <div class="admin-form__group"><label for="status">Status</label>
            <select id="status" name="status">@foreach(['draft','sent','accepted','declined','expired'] as $s)<option value="{{ $s }}" @selected(old('status', $quote->status ?? 'draft')===$s)>{{ ucfirst($s) }}</option>@endforeach</select>
        </div>
        <div class="admin-form__group"><label for="valid_until">Valid Until</label><input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until', isset($quote) && $quote->valid_until ? $quote->valid_until->format('Y-m-d') : '') }}"></div>
        <div class="admin-form__group admin-form__group--full"><label for="notes">Notes</label><textarea id="notes" name="notes" rows="4">{{ old('notes', $quote->notes ?? '') }}</textarea></div>
        <div class="admin-form__group admin-form__group--full">
            <label>Line Items</label>
            @php $items = old('line_items', $quote->line_items ?? [['description' => '', 'amount' => '']]); @endphp
            @foreach($items as $i => $item)
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:0.5rem;margin-bottom:0.5rem;">
                <input type="text" name="line_items[{{ $i }}][description]" value="{{ $item['description'] ?? '' }}" placeholder="Description">
                <input type="number" name="line_items[{{ $i }}][amount]" step="0.01" min="0" value="{{ $item['amount'] ?? '' }}" placeholder="Amount">
            </div>
            @endforeach
        </div>
        <div class="admin-form__actions admin-form__group--full"><button type="submit" class="admin-btn admin-btn--primary">{{ isset($quote) ? 'Update' : 'Create' }}</button></div>
    </form>
</div>
