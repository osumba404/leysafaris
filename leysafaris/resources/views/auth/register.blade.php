@extends('layouts.public')

@section('title', 'Register | Leyla Safari Tours')
@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="section page-top" style="min-height: 60vh;">
        <div class="container" style="max-width: 440px;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <h1 class="section-header__title">Create Account</h1>
                <p class="section-header__desc">Join to track enquiries, save wishlists, and plan your safari.</p>
            </div>

            <form class="inquiry-form" style="grid-template-columns: 1fr; background: var(--color-white); padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);" action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group form-group--full">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name">
                </div>

                <div class="form-group form-group--full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com">
                </div>

                <div class="form-group form-group--full">
                    <label for="phone">Phone (optional)</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+254...">
                </div>

                <div class="form-group form-group--full">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                </div>

                <div class="form-group form-group--full">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="user-plus"></i> Create Account
                </button>

                <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--color-text-muted);">
                    Already have an account? <a href="{{ route('login') }}" style="color: var(--color-savanna); font-weight: 500;">Sign in</a>
                </p>
            </form>
        </div>
    </section>
@endsection
