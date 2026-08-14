@props(['variant' => 'header', 'class' => ''])

@php
    use App\Support\SiteSettings;

    $settings = $settings ?? [];
    $siteName = SiteSettings::string($settings, 'site_name', 'Leyla Safari Tours');
    $logoName = SiteSettings::string($settings, 'logo_name', 'Leyla Safari');
    $logoTag = SiteSettings::string($settings, 'logo_tag', 'Tours');
    $logoUrl = SiteSettings::logoUrl($settings);
@endphp

<a href="{{ route('home') }}" class="logo {{ $variant === 'footer' ? 'logo--footer' : '' }} {{ $class }}" aria-label="{{ $siteName }} - Home">
    <span class="logo__mark" aria-hidden="true">
        @if ($logoUrl)
            <img src="{{ $logoUrl }}" alt="" class="logo__img" width="40" height="40">
        @else
            <i data-lucide="compass"></i>
        @endif
    </span>
    <span class="logo__text">
        <span class="logo__name">{{ $logoName }}</span>
        @if ($logoTag !== '')
            <span class="logo__tag">{{ $logoTag }}</span>
        @endif
    </span>
</a>
