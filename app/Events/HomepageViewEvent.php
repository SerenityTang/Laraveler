<?php

namespace App\Events;

use App\Models\User_data;
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

    public $user_data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User_data $user_data)
    {
        $this->user_data = $user_data;
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
