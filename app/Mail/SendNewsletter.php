<?php

namespace App\Mail;

use App\Mail\BaseMail;

class SendNewsletter extends BaseMail {
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $content_emails;
    
    public function __construct($data) {
        $baseMail = new BaseMail();
        $this->content_emails = $baseMail->getContentMail();
        $this->data = $data;        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {        
        $message = $this->to($this->getTo(config('settings.email_newsletter')))
            ->subject('Suscripción al boletín '. config('settings.shop_name') . ' ['.date('d-m-Y H:i').']')
            ->view('emails.newsletter');
        return $message;
    }

    private function getTo($array_emails){
        $email=str_replace(' ', '', $array_emails);
        $array = explode(',', $email);
        return $array;
    }
}
