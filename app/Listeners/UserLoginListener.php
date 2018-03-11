<?php
/**
 * Created by PhpStorm.
 * User: dengzhihao
 * Date: 2018/3/11
 * Time: 下午9:50
 */

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoginListener
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
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->event = $event;

        $this->_updateLastLoggedIn();
    }

    /**
     * 更新最后登录时间
     */
    private function _updateLastLoggedIn()
    {
        $user = $this->event->user;
        session(['lastLoggedIn' => $user->last_login_at]);
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}