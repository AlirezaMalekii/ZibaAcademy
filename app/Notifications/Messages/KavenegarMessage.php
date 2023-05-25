<?php

namespace App\Notifications\Messages;


use App\Models\KavenegarTemplate;

class KavenegarMessage
{
    public $receptor;
    public $token;
    public $token2;
    public $token3;
    public $template;
    public $messageText;
    public $kavenegar_send_method;

    public function __construct($receptor , $template , $token ,  $token2 = null , $token3 = null , $kavenegar_send_method = "Lookup")
    {
        $this->receptor = $receptor;
        $this->token = $token;
        $this->token2 = $token2;
        $this->token3 = $token3;
        $this->template = $template;
        $this->kavenegar_send_method = $kavenegar_send_method;
        $this->messageText = $this->messageText();
    }

    public function messageText() : string
    {
        $kavenegar_template = KavenegarTemplate::where('name' , $this->template)->first();
        $message =  str_replace(array('%token' , '$token1' , '%token2'), array($this->token , $this->token2 , $this->token3), $kavenegar_template->message);
        return  $message;
    }



}
