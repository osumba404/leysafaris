<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\NavItem;
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
                ->subject('Reset Your Password - Leyla Safari Tours')
                ->greeting('Hello!')
                ->line('We received a request to reset the password for your Leyla Safari Tours account.')
                ->action('Reset Password', $url)
                ->line("This link expires in {$expire} minutes.")
                ->line('If you did not request a password reset, you can safely ignore this email.');
        });

        View::composer(['layouts.public'], function ($view) {
            if (! array_key_exists('settings', $view->getData())) {
                $view->with('settings', Setting::allGrouped());
            }

            if (! array_key_exists('navItems', $view->getData())) {
                $view->with('navItems', NavItem::active()->orderBy('sort_order')->get());
            }

            if (! array_key_exists('footerLinks', $view->getData())) {
                $view->with(
                    'footerLinks',
                    FooterLink::active()->orderBy('group')->orderBy('sort_order')->get()->groupBy('group')
                );
            }
        });

        View::composer(['layouts.admin'], function ($view) {
            $view->with('siteSettings', Setting::allGrouped());
        });
    }
}

