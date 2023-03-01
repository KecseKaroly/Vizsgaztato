<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TestEnded;
use App\Events\TestUpdated;
use App\Events\GroupCreated;
use App\Listeners\CalculateResults;
use App\Listeners\AddFirstUserToGroup;
use App\Listeners\ReCalculateResults;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TestEnded::class => [
            CalculateResults::class,
        ],
        TestUpdated::class => [
            CalculateResults::class,
        ],
        GroupCreated::class => [
            AddFirstUserToGroup::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
