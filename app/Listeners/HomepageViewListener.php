<?php

namespace App\Listeners;

use App\Events\HomepageViewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HomepageViewListener
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
     * @param  HomepageViewEvent  $event
     * @return void
     */
    public function handle(HomepageViewEvent $event)
    {
        $user_data = $event->user_data;

        //主页访问量+1
        $user_data->increment('view_count');
    }
}
