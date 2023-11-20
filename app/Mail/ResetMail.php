<?php

namespace App\Mail;

use App\Mail\BaseMail;

class ResetMail extends BaseMail {

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $content_emails;
    
    public function __construct($data) {
        $this->data = $data;
        $baseMail = new BaseMail();
        $this->content_emails = $baseMail->getContentMail();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->to($this->data['email'], config('settings.shop_name'))
          ->subject('Recuperación de contraseña en el admin de  '. config('settings.shop_name') . ' ['.date('d-m-Y H:i').']')
          ->view('emails.forgot');
    }
}
