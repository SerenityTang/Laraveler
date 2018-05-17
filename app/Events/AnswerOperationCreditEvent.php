<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AnswerOperationCreditEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $type;
    public $bool;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $type, $bool = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->bool = $bool;
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
