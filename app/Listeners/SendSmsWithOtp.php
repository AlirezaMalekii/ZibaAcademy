<?php

namespace App\Listeners;

use App\Events\UserLoginOtp;
use App\Http\Controllers\AdminController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsWithOtp extends AdminController
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserLoginOtp $event)
    {
       $this->send_sms_lookup([
            'receptor' => $event->user->phone,
            'template' => "fortest",
            'token' => $event->otpCode
        ]);
    }
}
