@php
    use App\Support\AssetUrl;
    use App\Support\SiteSettings;

    $settings = $settings ?? [];
    $siteName = SiteSettings::string($settings, 'site_name', 'Leyla Safari Tours');
    $phone = SiteSettings::string($settings, 'phone', '+254712345678');
    $whatsapp = SiteSettings::string($settings, 'whatsapp', preg_replace('/\D/', '', $phone));
    $whatsappDigits = preg_replace('/\D/', '', $whatsapp);
    $emails = SiteSettings::list($settings, 'emails', ['info@leylasafaritours.com']);
    $primaryEmail = $emails[0] ?? 'info@leylasafaritours.com';
    $address = SiteSettings::string($settings, 'address', 'Westlands, Nairobi, Kenya');
    $footerTagline = SiteSettings::string($settings, 'footer_tagline', 'Authentic Kenyan safaris, crafted with care from the heart of Nairobi.');
    $websiteUrl = SiteSettings::string($settings, 'website_url', parse_url(config('app.url'), PHP_URL_HOST) ?: 'leylasafaritours.com');
    $newsletterHeading = SiteSettings::string($settings, 'newsletter_heading', 'Newsletter');
    $newsletterText = SiteSettings::string($settings, 'newsletter_text', 'Safari inspiration in your inbox.');
    $paymentMethods = SiteSettings::list($settings, 'payment_methods', ['Visa', 'MC', 'M-Pesa', 'PayPal']);
    $faviconUrl = SiteSettings::faviconUrl($settings);
    $footerLinks = $footerLinks ?? collect();
@endphp
<!DOCTYPE html>
<html lang="en-KE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <script src="{{ AssetUrl::versionedRoute('assets.theme', 'js/theme.js') }}"></script>
    @include('partials.seo-head')

    <link rel="icon" href="{{ $faviconUrl }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="{{ AssetUrl::versionedRoute('assets.style', 'css/style.css') }}" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet"></noscript>

    <link rel="stylesheet" href="{{ AssetUrl::versionedRoute('assets.style', 'css/style.css') }}">
    @stack('styles')

    <style>
        .flash {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.85rem 1.25rem;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .flash svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px; }
        .flash--success { background: rgba(74, 103, 65, 0.15); color: var(--color-moss); }
        .flash--error { background: rgba(160, 82, 45, 0.12); color: var(--color-terracotta); }
        .wa-float {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #25D366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: transform var(--transition);
        }
        .wa-float:hover { transform: scale(1.08); color: #fff; }
        .wa-float svg { width: 28px; height: 28px; }
        .nav__link.is-active { color: var(--color-savanna); }
    </style>

    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js" defer></script>
    <script src="{{ AssetUrl::versionedRoute('assets.main', 'js/main.js') }}" defer></script>
    @stack('scripts')
</head>
<body>

    <header class="header" id="header">
        <div class="container header__inner">
            @include('partials.site-logo')

            <nav class="nav" id="nav" aria-label="Main navigation">
                <ul class="nav__list">
                    <li><a href="{{ route('packages.index') }}" class="nav__link @if(request()->routeIs('packages.*')) is-active @endif">Safaris</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="nav__link @if(request()->routeIs('destinations.*')) is-active @endif">Destinations</a></li>
                    <li><a href="{{ route('experiences.index') }}" class="nav__link @if(request()->routeIs('experiences.*')) is-active @endif">Experiences</a></li>
                    <li><a href="{{ route('about') }}" class="nav__link @if(request()->routeIs('about')) is-active @endif">About</a></li>
                    <li><a href="{{ route('blog.index') }}" class="nav__link @if(request()->routeIs('blog.*')) is-active @endif">Journal</a></li>
                    <li><a href="{{ route('faq.index') }}" class="nav__link @if(request()->routeIs('faq.*')) is-active @endif">FAQ</a></li>
                    <li><a href="{{ route('travel-quiz.show') }}" class="nav__link @if(request()->routeIs('travel-quiz.*')) is-active @endif">Travel Quiz</a></li>
                    <li class="nav__item--theme">@include('partials.theme-toggle')</li>
                    <li><a href="{{ route('contact') }}" class="nav__link nav__link--accent @if(request()->routeIs('contact')) is-active @endif">Contact</a></li>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="nav__link">Admin</a></li>
                        @else
                            <li><a href="{{ route('account.dashboard') }}" class="nav__link">My Account</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('login') }}" class="nav__link">Login</a></li>
                    @endauth
                </ul>
            </nav>

            <button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="nav">
                <i data-lucide="menu" aria-hidden="true"></i>
            </button>
        </div>
    </header>

    @include('partials.flash')

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div class="footer__brand">
                    @include('partials.site-logo', ['variant' => 'footer'])
                    <p class="footer__tagline">{{ $footerTagline }}</p>
                    @include('partials.social-links')
                </div>

                <div class="footer__col">
                    <h4 class="footer__heading">Contact</h4>
                    <address class="footer__address">
                        <p>
                            <i data-lucide="map-pin" aria-hidden="true"></i>
                            {!! nl2br(e($address)) !!}
                        </p>
                        <p>
                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">
                                <i data-lucide="phone" aria-hidden="true"></i>
                                {{ $phone }}
                            </a>
                        </p>
                        @foreach ($emails as $email)
                            <p>
                                <a href="mailto:{{ $email }}">
                                    <i data-lucide="mail" aria-hidden="true"></i>
                                    {{ $email }}
                                </a>
                            </p>
                        @endforeach
                    </address>
                </div>

                @foreach (['explore', 'travel_info'] as $group)
                    @if ($footerLinks->has($group) && $footerLinks[$group]->isNotEmpty())
                        <div class="footer__col">
                            <h4 class="footer__heading">{{ \App\Models\FooterLink::groupLabel($group) }}</h4>
                            <ul class="footer__links">
                                @foreach ($footerLinks[$group] as $link)
                                    <li><a href="{{ $link->href() }}">{{ $link->label }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach

                <div class="footer__col">
                    <h4 class="footer__heading">{{ $newsletterHeading }}</h4>
                    <p style="font-size: 0.85rem; color: var(--color-sage); margin-bottom: 0.75rem;">{{ $newsletterText }}</p>
                    <form action="{{ route('newsletter.store') }}" method="POST" class="newsletter-form">
                        @csrf
                        <input type="text" name="name" placeholder="Your name" aria-label="Name">
                        <input type="email" name="email" placeholder="Email address" required aria-label="Email">
                        <button type="submit" class="btn btn--primary btn--sm">Subscribe</button>
                    </form>
                </div>

                @if (count($paymentMethods) > 0)
                    <div class="footer__col">
                        <h4 class="footer__heading">Security</h4>
                        <div class="footer__security">
                            <div class="security-badge">
                                <i data-lucide="lock" aria-hidden="true"></i>
                                <span>HTTPS Secured</span>
                            </div>
                            <div class="payment-icons" aria-label="Accepted payment methods">
                                @foreach ($paymentMethods as $method)
                                    <span class="payment-icon" title="{{ $method }}">{{ $method }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="footer__bottom">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <p class="footer__domain">
                    <i data-lucide="globe" aria-hidden="true"></i>
                    {{ $websiteUrl }}
                </p>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/{{ $whatsappDigits }}" class="wa-float" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
        <i data-lucide="message-circle" aria-hidden="true"></i>
    </a>


</body>
</html>
