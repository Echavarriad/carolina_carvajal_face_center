<?php

namespace App\Mail\Customer;

use App\Mail\BaseMail;


class DeclinedOrderCustomerMail extends BaseMail {
    
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
        $subject= $this->order->status_id == 5 ? __('titles.subject_order_declined_customer') : __('titles.subject_order_canceled_customer');
        $template= 'customer';
        $message = $this->to($this->order->customer_email)
            ->subject($subject . ' ['.date('d-m-Y g:i A').']')
            ->view('emails.declined_order', compact('template'));
        return $message;
    }
}
