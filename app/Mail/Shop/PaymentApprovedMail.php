<?php

namespace App\Mail\Edemco;

use App\Mail\BaseMail;


class PaymentApprovedMail extends BaseMail {
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;
    public $content_emails;
    
    public function __construct($order) {
        $baseMail = new BaseMail();
        $this->content_emails = $baseMail->getContentMail();
        $this->order = $order;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $emailTo = $this->getTo(config('settings.email_wompi'));
        $message = $this->to($emailTo)
            ->subject('Pago aprobado en Wompi ' . ' ['.date('d-m-Y g:i A').']')
            ->view('emails.payment_approved');
        
        return $message;
    }

    private function getTo($array_emails){
        $email=str_replace(' ', '', $array_emails);
        $array = explode(',', $email);
        return $array;
    }
}
