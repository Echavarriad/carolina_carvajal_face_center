<?php

namespace App\Mail;

use App\Mail\BaseMail;

class UserRegisteredMail extends BaseMail {

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $content_emails;
    
    public function __construct($user) {
        $this->user = $user;
        $baseMail = new BaseMail();
        $this->content_emails = $baseMail->getContentMail();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {  
        $message = $this->to($this->user->email)
           ->subject('Bienvenido a  '. config('settings.shop_name') . ' ['.date('d-m-Y H:i').']')
           ->view('emails.account.message_welcome_user_registered');
        return $message;
    }
}
