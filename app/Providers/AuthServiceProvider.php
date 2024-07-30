<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();
        Gate::define(\WebDevEtc\BlogEtc\Gates\GateTypes::MANAGE_BLOG_ADMIN, static function (?Model $user) {
            return $user && $user->id == 1;
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(\Lang::get('Verify Email Address'))
                ->line(\Lang::get('Please click the button below to verify your email address.'))
                ->action(\Lang::get('Verify Email Address'), $url)
                ->markdown("notifications::email_test", ["imgSrc" => asset(implode(DIRECTORY_SEPARATOR, ["assets", "images", "emails", "verify_img.png"]))]);
        });
    }
}
