@php
    use App\Support\SiteSettings;

    $settings = $settings ?? [];
    $adminSiteName = SiteSettings::string($settings, 'site_name', 'Leyla Safari Tours');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ $adminSiteName }}</title>

    @if ($favicon = SiteSettings::faviconUrl($settings))
        <link rel="icon" href="{{ $favicon }}" sizes="any">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ route('admin.css') }}">
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js" defer></script>
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar__brand">
                <a href="{{ route('admin.dashboard') }}">
                    @if ($logoUrl = SiteSettings::logoUrl($settings))
                        <img src="{{ $logoUrl }}" alt="" class="admin-sidebar__logo" width="28" height="28">
                    @else
                        <i data-lucide="compass"></i>
                    @endif
                    {{ SiteSettings::string($settings, 'logo_name', 'Leyla Safari') }} <span>Admin</span>
                </a>
            </div>

            <nav class="admin-nav" aria-label="Admin navigation">
                <div class="admin-nav__section">Overview</div>
                <a href="{{ route('admin.dashboard') }}" class="admin-nav__link @if(request()->routeIs('admin.dashboard')) is-active @endif">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>

                <div class="admin-nav__section">Content</div>
                <a href="{{ route('admin.packages.index') }}" class="admin-nav__link @if(request()->routeIs('admin.packages.*')) is-active @endif">
                    <i data-lucide="map"></i> Packages
                </a>
                <a href="{{ route('admin.destinations.index') }}" class="admin-nav__link @if(request()->routeIs('admin.destinations.*')) is-active @endif">
                    <i data-lucide="map-pin"></i> Destinations
                </a>
                <a href="{{ route('admin.experiences.index') }}" class="admin-nav__link @if(request()->routeIs('admin.experiences.*')) is-active @endif">
                    <i data-lucide="binoculars"></i> Experiences
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="admin-nav__link @if(request()->routeIs('admin.testimonials.*')) is-active @endif">
                    <i data-lucide="star"></i> Testimonials
                </a>
                <a href="{{ route('admin.blog-posts.index') }}" class="admin-nav__link @if(request()->routeIs('admin.blog-posts.*')) is-active @endif">
                    <i data-lucide="file-text"></i> Blog Posts
                </a>
                <a href="{{ route('admin.annual-events.index') }}" class="admin-nav__link @if(request()->routeIs('admin.annual-events.*')) is-active @endif">
                    <i data-lucide="calendar"></i> Annual Events
                </a>
                <a href="{{ route('admin.hero-slides.index') }}" class="admin-nav__link @if(request()->routeIs('admin.hero-slides.*')) is-active @endif">
                    <i data-lucide="images"></i> Hero Slides
                </a>
                <a href="{{ route('admin.nav-items.index') }}" class="admin-nav__link @if(request()->routeIs('admin.nav-items.*')) is-active @endif">
                    <i data-lucide="menu"></i> Navigation
                </a>
                <a href="{{ route('admin.footer-links.index') }}" class="admin-nav__link @if(request()->routeIs('admin.footer-links.*')) is-active @endif">
                    <i data-lucide="link"></i> Footer Links
                </a>

                <div class="admin-nav__section">Sales</div>
                <a href="{{ route('admin.enquiries.index') }}" class="admin-nav__link @if(request()->routeIs('admin.enquiries.*')) is-active @endif">
                    <i data-lucide="inbox"></i> Enquiries
                </a>
                <a href="{{ route('admin.quotes.index') }}" class="admin-nav__link @if(request()->routeIs('admin.quotes.*')) is-active @endif">
                    <i data-lucide="receipt"></i> Quotes
                </a>

                <div class="admin-nav__section">System</div>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav__link @if(request()->routeIs('admin.settings.*')) is-active @endif">
                    <i data-lucide="settings"></i> Settings
                </a>
                <a href="{{ route('home') }}" class="admin-nav__link" target="_blank">
                    <i data-lucide="external-link"></i> View Site
                </a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <h1 class="admin-topbar__title">@yield('page_title', 'Dashboard')</h1>
                <div class="admin-topbar__actions">
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn--secondary admin-btn--sm">
                            <i data-lucide="log-out"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <div class="admin-content">
                @if (session('success'))
                    <div class="admin-flash admin-flash--success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="admin-flash admin-flash--error">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="admin-flash admin-flash--error">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
