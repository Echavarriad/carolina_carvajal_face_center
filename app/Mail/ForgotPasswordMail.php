<?php

namespace App\Mail;

use App\Mail\BaseMail;

class ForgotPasswordMail extends BaseMail {

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $passwordTemporary;
    public $content_emails;

    public function __construct($email, $passwordTemporary) {
      $baseMail = new BaseMail();
      $this->content_emails = $baseMail->getContentMail();
      $this->email = $email;
      $this->passwordTemporary = $passwordTemporary;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build() { 
        $message = $this->to($this->email)
           ->subject('Recuperar contraseña:  '. config('settings.shop_name') . ' ['.date('d-m-Y H:i').']')
           ->view('emails.account.forgot_password'); 
        return $message;
     }
}
