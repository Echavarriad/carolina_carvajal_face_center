<?php

namespace App\Mail\Shop;

use App\Mail\BaseMail;


class NewOrderShopMail extends BaseMail {
    
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
        $template= 'shop';
        $subject= __('titles.subject_order_shop');
        $emailTo = $this->getTo(config('settings.email_order'));
        $message = $this->to($emailTo)
            ->subject($subject . ' ['.date('d-m-Y g:i A').']')
            ->view('emails.new_order', compact('template'));

        return $message;
    }

    private function getTo($array_emails){
        $email=str_replace(' ', '', $array_emails);
        $array = explode(',', $email);
        return $array;
    }
}
