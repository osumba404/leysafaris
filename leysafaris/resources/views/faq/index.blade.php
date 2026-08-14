@extends('layouts.public')

@section('title', 'Safari FAQ | Leyla Safari Tours')
@section('meta_description', 'Answers to common Kenya safari questions - booking, payments, visas, best time to visit, what is included, and responsible travel with Leyla Safari Tours.')
@section('canonical', route('faq.index'))

@section('content')
    <section class="hero hero--compact" aria-labelledby="faq-heading">
        <div class="hero__media">
            <x-optimized-img src="images/pond_view.jpg" alt="Kenyan wilderness waterhole" :width="1920" :height="810" :priority="true" />
            <div class="hero__overlay"></div>
        </div>
        <div class="container hero__content">
            <p class="hero__eyebrow">Practical Information</p>
            <h1 id="faq-heading" class="hero__title">Frequently Asked<br><em>Questions</em></h1>
        </div>
    </section>

    <section class="section page-top">
        <div class="container">
            <div class="section-header">
                <p class="section-header__desc">Everything you need to know before your East Africa adventure. Still have questions? <a href="{{ route('contact') }}" style="color: var(--color-savanna); font-weight: 600;">Request a proposal</a>.</p>
            </div>

            @forelse ($faqs as $category => $items)
                <div style="margin-bottom: 3rem;">
                    <h2 class="section-header__title" style="font-size: 1.75rem; margin-bottom: 1.25rem;">{{ \App\Models\Faq::categoryLabel($category) }}</h2>
                    <div class="accordion" role="region">
                        @foreach ($items as $faq)
                            <div class="accordion__item">
                                <button class="accordion__trigger" aria-expanded="false" aria-controls="faq-{{ $faq->id }}">
                                    <span class="accordion__trigger-text">{{ $faq->question }}</span>
                                    <i data-lucide="chevron-down" class="accordion__icon" aria-hidden="true"></i>
                                </button>
                                <div class="accordion__panel" id="faq-{{ $faq->id }}" hidden>
                                    <p>{{ $faq->answer }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: var(--color-text-muted);">FAQ content coming soon.</p>
            @endforelse

            <div class="expert-cta" style="margin-top: 2rem;">
                <div class="expert-cta__copy">
                    <h2 class="section-header__title">Ready to plan your safari?</h2>
                    <p>Take our travel quiz or speak with a Nairobi-based specialist - no cost, no commitment.</p>
                </div>
                <div class="expert-cta__actions">
                    <a href="{{ route('travel-quiz.show') }}" class="btn btn--secondary">Travel Quiz</a>
                    <a href="{{ route('contact') }}" class="btn btn--primary">Request Proposal</a>
                </div>
            </div>
        </div>
    </section>
@endsection
