<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/3/11
 * Time: 下午10:07
 */

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLogoutListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $event;

    /**
     * Handle the event.
     *
     * @param  Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $this->event = $event;

        $this->_updateLastActive();
    }

    /**
     * 更新最后活跃时间
     */
    private function _updateLastActive()
    {
        $user = $this->event->user;
        session(['lastActive' => $user->last_active_at]);
        $user->last_active_at = Carbon::now();
        $user->save();
    }
}