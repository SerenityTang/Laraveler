<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\QuestionViewEvent' => [
            'App\Listeners\QuestionViewListener',
        ],
        'App\Events\HomepageViewEvent' => [
            'App\Listeners\HomepageViewListener',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\UserLoginListener',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\UserLogoutListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
