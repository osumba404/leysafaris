@php
    use App\Support\SiteSettings;

    $socialLinks = SiteSettings::socialLinks($settings ?? []);
@endphp

@if (count($socialLinks) > 0)
    <div class="social-links" aria-label="Social media">
        @foreach ($socialLinks as $link)
            <a href="{{ $link['url'] }}" class="social-links__item social-links__item--{{ $link['platform'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($link['platform']) }}">
                @include('partials.social-icon', ['platform' => $link['platform']])
            </a>
        @endforeach
    </div>
@endif
