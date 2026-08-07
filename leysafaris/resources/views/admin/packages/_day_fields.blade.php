<div class="admin-day-block">
    <div class="admin-day-block__title">Day {{ ($day['day_number'] ?? $index + 1) }}</div>
    <div class="admin-form admin-form--grid">
        <div class="admin-form__group">
            <label>Day Number</label>
            <input type="number" name="days[{{ $index }}][day_number]" min="1" value="{{ old("days.{$index}.day_number", $day['day_number'] ?? $index + 1) }}">
        </div>
        <div class="admin-form__group">
            <label>Title *</label>
            <input type="text" name="days[{{ $index }}][title]" value="{{ old("days.{$index}.title", $day['title'] ?? '') }}" required>
        </div>
        <div class="admin-form__group">
            <label>Location</label>
            <input type="text" name="days[{{ $index }}][location]" value="{{ old("days.{$index}.location", $day['location'] ?? '') }}">
        </div>
        <div class="admin-form__group">
            <label>Accommodation</label>
            <input type="text" name="days[{{ $index }}][accommodation]" value="{{ old("days.{$index}.accommodation", $day['accommodation'] ?? '') }}">
        </div>
        <div class="admin-form__group admin-form__group--full">
            <label>Narrative</label>
            <textarea name="days[{{ $index }}][narrative]" rows="3">{{ old("days.{$index}.narrative", $day['narrative'] ?? '') }}</textarea>
        </div>
        <div class="admin-form__group">
            <label>Morning</label>
            <textarea name="days[{{ $index }}][morning]" rows="2">{{ old("days.{$index}.morning", $day['morning'] ?? '') }}</textarea>
        </div>
        <div class="admin-form__group">
            <label>Afternoon</label>
            <textarea name="days[{{ $index }}][afternoon]" rows="2">{{ old("days.{$index}.afternoon", $day['afternoon'] ?? '') }}</textarea>
        </div>
        <div class="admin-form__group">
            <label>Evening</label>
            <textarea name="days[{{ $index }}][evening]" rows="2">{{ old("days.{$index}.evening", $day['evening'] ?? '') }}</textarea>
        </div>
        <div class="admin-form__group">
            <label>Sort Order</label>
            <input type="number" name="days[{{ $index }}][sort_order]" min="0" value="{{ old("days.{$index}.sort_order", $day['sort_order'] ?? $index) }}">
        </div>
    </div>
</div>
