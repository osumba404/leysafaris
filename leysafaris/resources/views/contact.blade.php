@extends('layouts.public')

@section('title', 'Plan Your Safari | Contact Leyla Safari Tours | Request a Quote')
@section('meta_description', 'Request a personalised Kenya safari quote from Leyla Safari Tours. Tell us your dates, group size and destinations — expert Nairobi team responds via email or WhatsApp.')
@section('canonical', route('contact'))

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact Leyla Safari Tours",
  "url": "{{ route('contact') }}",
  "description": "Request a safari quote or ask our Nairobi team about Kenya and East Africa tours."
}
</script>
@endpush

@section('content')
    @php
        $selectedPackageId = request('package_id', old('package_id'));
        $selectedPackage = $selectedPackageId ? \App\Models\Package::published()->find($selectedPackageId) : null;
    @endphp

    <section class="section inquiry page-top">
        <div class="container inquiry__inner">
            <div class="inquiry__intro">
                <span class="section-header__label">Get in Touch</span>
                <h1 class="section-header__title">Start Your Journey</h1>
                <p class="section-header__desc">
                    Tell us your dream safari and we will craft a personalised proposal —
                    no obligation, no pressure, just expert guidance.
                </p>

                @if ($selectedPackage)
                    <div class="feature-card" style="margin: 1.5rem 0;">
                        <p style="font-size: 0.85rem; color: var(--color-savanna); margin-bottom: 0.35rem;">Enquiring about:</p>
                        <strong>{{ $selectedPackage->title }}</strong>
                        <p style="font-size: 0.9rem; margin-top: 0.35rem;">{{ $selectedPackage->duration_days }} days · From {{ $selectedPackage->currency ?? 'USD' }} {{ number_format($selectedPackage->starting_price ?? 0, 0) }}</p>
                    </div>
                @endif

                <div class="inquiry__contacts">
                    <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '+254712345678') }}" class="inquiry__contact">
                        <i data-lucide="phone" aria-hidden="true"></i>
                        <span>{{ $settings['phone'] ?? '+254 712 345 678' }}</span>
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp'] ?? '254712345678') }}" class="inquiry__contact" target="_blank" rel="noopener noreferrer">
                        <i data-lucide="message-circle" aria-hidden="true"></i>
                        <span>Chat on WhatsApp</span>
                    </a>
                    @php
                        $emails = $settings['emails'] ?? ['info@leylasafaritours.com', 'inquiry@leylasafaritours.com'];
                        if (is_string($emails)) { $emails = json_decode($emails, true) ?? [$emails]; }
                        $contactEmail = is_array($emails) ? ($emails[1] ?? $emails[0]) : 'inquiry@leylasafaritours.com';
                    @endphp
                    <a href="mailto:{{ $contactEmail }}" class="inquiry__contact">
                        <i data-lucide="mail" aria-hidden="true"></i>
                        <span>{{ $contactEmail }}</span>
                    </a>
                </div>
            </div>

            <form class="inquiry-form" action="{{ route('enquiries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="contact_page">
                @if ($selectedPackageId)
                    <input type="hidden" name="package_id" value="{{ $selectedPackageId }}">
                @endif

                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com">
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+254...">
                </div>

                <div class="form-group">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="+254...">
                </div>

                @if (! $selectedPackageId)
                    <div class="form-group">
                        <label for="package_id">Safari Package</label>
                        <select id="package_id" name="package_id">
                            <option value="">Select a safari (optional)</option>
                            @foreach (\App\Models\Package::published()->orderBy('title')->get(['id', 'title']) as $pkg)
                                <option value="{{ $pkg->id }}" @selected(old('package_id') == $pkg->id)>{{ $pkg->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label for="preferred_destinations">Preferred Destinations</label>
                    <input type="text" id="preferred_destinations" name="preferred_destinations" value="{{ old('preferred_destinations') }}" placeholder="e.g. Maasai Mara, Amboseli">
                </div>

                <div class="form-group">
                    <label for="travel_dates">Travel Dates</label>
                    <input type="text" id="travel_dates" name="travel_dates" value="{{ old('travel_dates') }}" placeholder="e.g. July 15–22, 2026">
                </div>

                <div class="form-group">
                    <label for="group_size">Group Size</label>
                    <input type="number" id="group_size" name="group_size" min="1" max="100" value="{{ old('group_size') }}" placeholder="2">
                </div>

                <div class="form-group">
                    <label for="budget_range">Budget Range</label>
                    <select id="budget_range" name="budget_range">
                        <option value="">Select budget range</option>
                        <option value="Under $1,000" @selected(old('budget_range') === 'Under $1,000')>Under $1,000</option>
                        <option value="$1,000 – $2,500" @selected(old('budget_range') === '$1,000 – $2,500')>$1,000 – $2,500</option>
                        <option value="$2,500 – $5,000" @selected(old('budget_range') === '$2,500 – $5,000')>$2,500 – $5,000</option>
                        <option value="$5,000+" @selected(old('budget_range') === '$5,000+')>$5,000+</option>
                        <option value="Flexible" @selected(old('budget_range') === 'Flexible')>Flexible / Not sure</option>
                    </select>
                </div>

                <div class="form-group form-group--full">
                    <label for="special_interests">Special Interests</label>
                    <input type="text" id="special_interests" name="special_interests" value="{{ old('special_interests') }}" placeholder="Photography, honeymoon, family safari, birding...">
                </div>

                <div class="form-group form-group--full">
                    <label for="message">Tell us about your trip</label>
                    <textarea id="message" name="message" rows="4" placeholder="Dates, group size, special requests, dietary needs...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="send" aria-hidden="true"></i>
                    Send Inquiry
                </button>
            </form>
        </div>
    </section>
@endsection
