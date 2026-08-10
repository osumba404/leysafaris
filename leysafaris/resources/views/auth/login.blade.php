@extends('layouts.public')

@section('title', 'Login | Leyla Safari Tours')
@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="section page-top" style="min-height: 60vh;">
        <div class="container" style="max-width: 440px;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <h1 class="section-header__title">Welcome Back</h1>
                <p class="section-header__desc">Sign in to manage your enquiries and wishlist.</p>
            </div>

            <form class="inquiry-form" style="grid-template-columns: 1fr; background: var(--color-white); padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group form-group--full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@example.com">
                </div>

                <div class="form-group form-group--full">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.35rem;">
                        <label for="password" style="margin: 0;">Password</label>
                        <a href="{{ route('password.request') }}" style="font-size: 0.85rem; color: var(--color-savanna); font-weight: 500;">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>

                <div class="form-group form-group--full" style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                    <label for="remember" style="margin: 0;">Remember me</label>
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="log-in"></i> Sign In
                </button>

                <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--color-text-muted);">
                    Don't have an account? <a href="{{ route('register') }}" style="color: var(--color-savanna); font-weight: 500;">Register</a>
                </p>
            </form>
        </div>
    </section>
@endsection
