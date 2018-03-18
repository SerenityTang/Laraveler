<?php

namespace App\Listeners;

use App\Events\QuestionViewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuestionViewListener
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
     * @param  QuestionViewEvent  $event
     * @return void
     */
    public function handle(QuestionViewEvent $event)
    {
        $question = $event->question;

        //问答浏览量+1
        $question->increment('view_count');
    }
}
