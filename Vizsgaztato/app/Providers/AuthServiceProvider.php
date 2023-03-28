<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\test' => 'App\Policies\testPolicy',
        'App\Models\group' => 'App\Policies\GroupPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Email cím megerősítése')
                ->line('Az Ön regisztrált fiókjához kiküldtük a szükséges e-mail cím megerősítő linket. Kérjük, kattintson a gombra a megerősítéshez!')
                ->action('Email cím megerősítése', $url)
                ->line('Amennyiben nem Ön regisztrált, hagyja figyelmen kívül a levelet!');
        });

        ResetPassword::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Új jelszó beállítása')
                ->greeting('Tisztelt felhasználó!')
                ->line('Az email címére kiküldjük a fiókjához igényelt jelszó helyreállítását bizotsító linket. Ehhez kattintson az alábbi gombra:')
                ->action('Jelszó módosítása', $url)
                ->line('Amennyiben nem Ön igényelte, hagyja figyelmen kívül a levelet!');
        });
    }
}
