<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;

class KavenegarChannel
{

    protected $kavenegar_api_key = "4E6B4E466855477469583479354A6F356335315451706E734130575A627164766C63544F52476F567775673D";
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
