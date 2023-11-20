<?php

namespace App\Mail\Customer;

use App\Mail\BaseMail;


class PaidOrderCustomerMail extends BaseMail {
    
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
        $subject= __('titles.subject_order_paid_customer');
        $template= 'customer';
        $message = $this->to($this->order->customer_email)
            ->subject($subject . ' ['.date('d-m-Y g:i A').']')
            ->view('emails.paid_order', compact('template'));

        return $message;
    }
}
