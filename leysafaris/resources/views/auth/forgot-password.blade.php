@extends('layouts.public')

@section('title', 'Forgot Password | Leyla Safari Tours')
@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="section page-top" style="min-height: 60vh;">
        <div class="container" style="max-width: 440px;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <h1 class="section-header__title">Forgot Password</h1>
                <p class="section-header__desc">Enter your email and we will send you a link to choose a new password.</p>
            </div>

            <form class="inquiry-form" style="grid-template-columns: 1fr; background: var(--color-white); padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);" action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="form-group form-group--full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@example.com">
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="mail"></i> Send Reset Link
                </button>

                <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--color-text-muted);">
                    Remember your password? <a href="{{ route('login') }}" style="color: var(--color-savanna); font-weight: 500;">Sign in</a>
                </p>
            </form>
        </div>
    </section>
@endsection
