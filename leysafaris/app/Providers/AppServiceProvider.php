<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new MailMessage)
                ->subject('Reset Your Password — Leyla Safari Tours')
                ->greeting('Hello!')
                ->line('We received a request to reset the password for your Leyla Safari Tours account.')
                ->action('Reset Password', $url)
                ->line("This link expires in {$expire} minutes.")
                ->line('If you did not request a password reset, you can safely ignore this email.');
        });

        View::composer(['layouts.public', 'contact', 'about.index'], function ($view) {
            if (! array_key_exists('settings', $view->getData())) {
                $view->with('settings', Setting::allGrouped());
            }
        });
    }
}

