<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;

class KavenegarChannel
{

    protected $kavenegar_api_key = "346361575058496935676D69763963725877737650395769483339784954644653395A7056474B3936484D3D";
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $kavenegarMessage = $notification->toKavenegar($notifiable);
        $api = new KavenegarApi($this->kavenegar_api_key);
        if ($kavenegarMessage->kavenegar_send_method === "Lookup"){
            $api->VerifyLookup($kavenegarMessage->receptor, replace_space_with_underline($kavenegarMessage->token), replace_space_with_underline($kavenegarMessage->token2), replace_space_with_underline($kavenegarMessage->token3), $kavenegarMessage->template, $type = null);
        }elseif ($kavenegarMessage->kavenegar_send_method === "Send"){
            $api->Send("1000300030020" , $kavenegarMessage->receptor , $kavenegarMessage->messageText);
        }

    }
}
