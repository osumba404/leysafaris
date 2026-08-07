@php
    $settings = $settings ?? [];
    $siteName = is_string($settings['site_name'] ?? null) ? $settings['site_name'] : 'Leyla Safari Tours';
    $phone = is_string($settings['phone'] ?? null) ? $settings['phone'] : '+254712345678';
    $whatsapp = is_string($settings['whatsapp'] ?? null) ? $settings['whatsapp'] : preg_replace('/\D/', '', $phone);
    $whatsappDigits = preg_replace('/\D/', '', $whatsapp);
    $emails = $settings['emails'] ?? ['info@leylasafaritours.com'];
    if (is_string($emails)) {
        $decoded = json_decode($emails, true);
        $emails = is_array($decoded) ? $decoded : [$emails];
    }
    if (! is_array($emails)) {
        $emails = ['info@leylasafaritours.com'];
    }
    $primaryEmail = $emails[0] ?? 'info@leylasafaritours.com';
    $address = is_string($settings['address'] ?? null) ? $settings['address'] : 'Westlands, Nairobi, Kenya';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Leyla Safari Tours — Authentic Kenyan safari experiences from Nairobi.')">
    <title>@yield('title', $siteName)</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    @stack('scripts')
</head>
<body>

    <div class="trust-bar" role="complementary" aria-label="Contact information">
        <div class="container trust-bar__inner">
            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="trust-bar__item">
                <i data-lucide="phone" aria-hidden="true"></i>
                <span>{{ $phone }}</span>
            </a>
            <a href="https://wa.me/{{ $whatsappDigits }}" class="trust-bar__item" target="_blank" rel="noopener noreferrer">
                <i data-lucide="message-circle" aria-hidden="true"></i>
                <span>WhatsApp</span>
            </a>
            <a href="mailto:{{ $primaryEmail }}" class="trust-bar__item">
                <i data-lucide="mail" aria-hidden="true"></i>
                <span>{{ $primaryEmail }}</span>
            </a>
            <span class="trust-bar__item trust-bar__item--address">
                <i data-lucide="map-pin" aria-hidden="true"></i>
                <span>{{ $address }}</span>
            </span>
        </div>
    </div>

    <header class="header" id="header">
        <div class="container header__inner">
            <a href="{{ route('home') }}" class="logo" aria-label="{{ $siteName }} — Home">
                <span class="logo__mark" aria-hidden="true">
                    <i data-lucide="compass"></i>
                </span>
                <span class="logo__text">
                    <span class="logo__name">Leyla Safari</span>
                    <span class="logo__tag">Tours</span>
                </span>
            </a>

            <nav class="nav" id="nav" aria-label="Main navigation">
                <ul class="nav__list">
                    <li><a href="{{ route('packages.index') }}" class="nav__link @if(request()->routeIs('packages.*')) is-active @endif">Safaris</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="nav__link @if(request()->routeIs('destinations.*')) is-active @endif">Destinations</a></li>
                    <li><a href="{{ route('experiences.index') }}" class="nav__link @if(request()->routeIs('experiences.*')) is-active @endif">Experiences</a></li>
                    <li><a href="{{ route('about') }}" class="nav__link @if(request()->routeIs('about')) is-active @endif">About</a></li>
                    <li><a href="{{ route('blog.index') }}" class="nav__link @if(request()->routeIs('blog.*')) is-active @endif">Journal</a></li>
                    <li><a href="{{ route('contact') }}" class="nav__link nav__link--accent @if(request()->routeIs('contact')) is-active @endif">Contact</a></li>
                    @auth
                        <li><a href="{{ route('account.dashboard') }}" class="nav__link">My Account</a></li>
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

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div class="footer__brand">
                    <a href="{{ route('home') }}" class="logo logo--footer">
                        <span class="logo__mark" aria-hidden="true">
                            <i data-lucide="compass"></i>
                        </span>
                        <span class="logo__text">
                            <span class="logo__name">Leyla Safari</span>
                            <span class="logo__tag">Tours</span>
                        </span>
                    </a>
                    <p class="footer__tagline">
                        Authentic Kenyan safaris, crafted with care from the heart of Nairobi.
                    </p>
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

                <div class="footer__col">
                    <h4 class="footer__heading">Explore</h4>
                    <ul class="footer__links">
                        <li><a href="{{ route('packages.index') }}">Our Safaris</a></li>
                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                        <li><a href="{{ route('experiences.index') }}">Experiences</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('blog.index') }}">Journal</a></li>
                        <li><a href="{{ route('contact') }}">Inquire</a></li>
                    </ul>
                </div>

                <div class="footer__col">
                    <h4 class="footer__heading">Security</h4>
                    <div class="footer__security">
                        <div class="security-badge">
                            <i data-lucide="lock" aria-hidden="true"></i>
                            <span>HTTPS Secured</span>
                        </div>
                        <div class="payment-icons" aria-label="Accepted payment methods">
                            <span class="payment-icon" title="Visa">Visa</span>
                            <span class="payment-icon" title="Mastercard">MC</span>
                            <span class="payment-icon" title="M-Pesa">M-Pesa</span>
                            <span class="payment-icon" title="PayPal">PayPal</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer__bottom">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <p class="footer__domain">
                    <i data-lucide="globe" aria-hidden="true"></i>
                    leylasafaritours.com
                </p>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/{{ $whatsappDigits }}" class="wa-float" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
        <i data-lucide="message-circle" aria-hidden="true"></i>
    </a>

</body>
</html>
