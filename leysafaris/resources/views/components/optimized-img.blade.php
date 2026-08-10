@props([
    'src',
    'alt',
    'width' => 800,
    'height' => 533,
    'priority' => false,
    'class' => '',
])

@php
    use App\Support\WebpImage;

    $srcUrl = WebpImage::resolve($src);
    $webpUrl = WebpImage::webpUrl($src);
@endphp

@if ($webpUrl)
    <picture>
        <source srcset="{{ $webpUrl }}" type="image/webp">
        <img
            src="{{ $srcUrl }}"
            alt="{{ $alt }}"
            width="{{ $width }}"
            height="{{ $height }}"
            class="{{ $class }}"
            decoding="async"
            @if ($priority) fetchpriority="high" @else loading="lazy" @endif
            {{ $attributes }}
        >
    </picture>
@else
    <img
        src="{{ $srcUrl }}"
        alt="{{ $alt }}"
        width="{{ $width }}"
        height="{{ $height }}"
        class="{{ $class }}"
        decoding="async"
        @if ($priority) fetchpriority="high" @else loading="lazy" @endif
        {{ $attributes }}
    >
@endif
