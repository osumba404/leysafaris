@extends('layouts.public')

@section('title', 'Safari Travel Quiz | Leyla Safari Tours')
@section('meta_description', 'Take our quick safari travel quiz and get a personalised Kenya itinerary recommendation from Leyla Safari Tours - free, no obligation.')
@section('canonical', route('travel-quiz.show'))
@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="section page-top">
        <div class="container" style="max-width: 640px;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <span class="section-header__label">Travel Quiz</span>
                <h1 class="section-header__title">Find Your Perfect Safari</h1>
                <p class="section-header__desc">Answer a few quick questions and we will suggest a tailored route. No strings attached - we design your trip at no cost.</p>
            </div>

            <form class="inquiry-form quiz-form" style="grid-template-columns: 1fr; background: var(--color-white); padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);" action="{{ route('travel-quiz.submit') }}" method="POST">
                @csrf

                <div class="form-group form-group--full">
                    <label for="destination">Where would you like to explore?</label>
                    <select id="destination" name="destination" required>
                        <option value="">Select a region</option>
                        <option value="Kenya - Maasai Mara">Kenya - Maasai Mara</option>
                        <option value="Kenya - Amboseli">Kenya - Amboseli</option>
                        <option value="Kenya - Samburu & North">Kenya - Samburu & North</option>
                        <option value="Kenya - Bush & Beach">Kenya - Bush & Beach</option>
                        <option value="Uganda - Gorillas">Uganda - Gorillas</option>
                        <option value="Multi-country East Africa">Multi-country East Africa</option>
                    </select>
                </div>

                <div class="form-group form-group--full">
                    <label for="trip_style">What kind of trip are you planning?</label>
                    <select id="trip_style" name="trip_style" required>
                        <option value="">Select trip style</option>
                        <option value="Wildlife safari">Wildlife safari</option>
                        <option value="Honeymoon">Honeymoon</option>
                        <option value="Family-friendly">Family-friendly</option>
                        <option value="Luxury escape">Luxury escape</option>
                        <option value="Photography focused">Photography focused</option>
                        <option value="Safari and beach">Safari and beach</option>
                    </select>
                </div>

                <div class="form-group form-group--full">
                    <label for="travel_month">When would you like to travel?</label>
                    <input type="text" id="travel_month" name="travel_month" placeholder="e.g. August 2026 or Flexible">
                </div>

                <div class="form-group form-group--full" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label for="adults">Adults</label>
                        <input type="number" id="adults" name="adults" min="1" max="20" value="2">
                    </div>
                    <div>
                        <label for="children">Children</label>
                        <input type="number" id="children" name="children" min="0" max="20" value="0">
                    </div>
                </div>

                <div class="form-group form-group--full">
                    <label for="budget">Estimated budget (optional)</label>
                    <select id="budget" name="budget">
                        <option value="">Prefer not to say</option>
                        <option value="Under USD 3,000">Under USD 3,000</option>
                        <option value="USD 3,000 - 5,000">USD 3,000 - 5,000</option>
                        <option value="USD 5,000 - 8,000">USD 5,000 - 8,000</option>
                        <option value="USD 8,000+">USD 8,000+</option>
                    </select>
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="compass"></i> Get My Safari Recommendation
                </button>
            </form>
        </div>
    </section>
@endsection
