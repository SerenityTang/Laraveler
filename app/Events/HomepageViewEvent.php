<?php

namespace App\Events;

use App\Models\UserData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HomepageViewEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $UserData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserData $UserData)
    {
        $this->UserData = $UserData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
