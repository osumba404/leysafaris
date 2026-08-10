@php
    $seoTitle = trim($__env->yieldContent('title')) ?: ($siteName ?? 'Leyla Safari Tours');
    $seoDescription = trim($__env->yieldContent('meta_description')) ?: 'Leyla Safari Tours — premium Kenya safaris, Maasai Mara migration tours, Amboseli, Samburu & East Africa journeys. Request a quote from Nairobi experts.';
    $seoKeywords = trim($__env->yieldContent('meta_keywords')) ?: 'Kenya safari, Maasai Mara tours, safari packages Kenya, Leyla Safari Tours, East Africa travel, Nairobi safari company, wildlife tours';
    $seoRobots = trim($__env->yieldContent('meta_robots')) ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $seoCanonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $seoOgType = trim($__env->yieldContent('og_type')) ?: 'website';
    $seoOgImage = trim($__env->yieldContent('og_image')) ?: asset('images/savannah_sunset_tree.jpg');
    $seoLocale = 'en_KE';
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="author" content="Leyla Safari Tours">
<meta name="geo.region" content="KE-30">
<meta name="geo.placename" content="Nairobi, Kenya">
<link rel="canonical" href="{{ $seoCanonical }}">

<meta property="og:site_name" content="{{ $siteName ?? 'Leyla Safari Tours' }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:type" content="{{ $seoOgType }}">
<meta property="og:image" content="{{ $seoOgImage }}">
<meta property="og:image:alt" content="{{ $seoDescription }}">
<meta property="og:locale" content="{{ $seoLocale }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoOgImage }}">

<link rel="alternate" hreflang="en-ke" href="{{ $seoCanonical }}">
<link rel="alternate" hreflang="x-default" href="{{ $seoCanonical }}">

@stack('structured_data')
