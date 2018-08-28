<?php

namespace App\Listeners;

use App\Events\WelcomeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WelcomeEvent  $event
     * @return void
     */
    public function handle(WelcomeEvent $event)
    {
        //
    }
}
