<?php

namespace App\Mail;

use App\Mail\BaseMail;

class ContactMail extends BaseMail {

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
        if(isset($this->data['service'])){
            $to = config('settings.' . $this->data['_email_to']);      
            $subject = 'Formulario de servicios '; 
        }else{
            $to = config('settings.email_contact'); 
            $subject = 'Formulario de contacto '; 
        }
              
        $message = $this->to($this->getTo($to))
            ->subject($subject . config('settings.shop_name') . ' ['.date('d-m-Y H:i').']')
            ->view('emails.contact');
        return $message;
    }

    private function getTo($array_emails){
        $email=str_replace(' ', '', $array_emails);
        $array = explode(',', $email);
        return $array;
     }
}
