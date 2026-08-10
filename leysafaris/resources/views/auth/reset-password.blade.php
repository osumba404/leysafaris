@extends('layouts.public')

@section('title', 'Reset Password | Leyla Safari Tours')
@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="section page-top" style="min-height: 60vh;">
        <div class="container" style="max-width: 440px;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <h1 class="section-header__title">Reset Password</h1>
                <p class="section-header__desc">Choose a new password for your account.</p>
            </div>

            <form class="inquiry-form" style="grid-template-columns: 1fr; background: var(--color-white); padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);" action="{{ route('password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group form-group--full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus autocomplete="email" placeholder="you@example.com">
                </div>

                <div class="form-group form-group--full">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                </div>

                <div class="form-group form-group--full">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    <i data-lucide="key-round"></i> Reset Password
                </button>

                <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--color-text-muted);">
                    <a href="{{ route('login') }}" style="color: var(--color-savanna); font-weight: 500;">Back to sign in</a>
                </p>
            </form>
        </div>
    </section>
@endsection
