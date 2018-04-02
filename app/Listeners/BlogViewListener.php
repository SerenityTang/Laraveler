<?php

namespace App\Listeners;

use App\Events\BlogViewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BlogViewListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  BlogViewEvent  $event
     * @return void
     */
    public function handle(BlogViewEvent $event)
    {
        $blog = $event->blog;

        //博客浏览量+1
        $blog->increment('view_count');
    }
}
