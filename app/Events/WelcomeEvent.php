<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Symfony\Component\EventDispatcher\Event;

class WelcomeEvent extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * 获取事件广播的频道
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['welcome'];
    }

    /**
     * 事件的广播名称.
     *
     * @return string
     */
    /*public function broadcastAs()
    {
        return 'welcome';
    }*/

    /**
     * 获取广播数据
     *
     * @return array
     */
    /*public function broadcastWith(){
        return ['data' => '欢迎回来(⊙o⊙)哦'];
    }*/
}
