<?php

namespace App\Mail\Edemco;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Content;

class DeclinedOrderShopMail extends Mailable {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;
    public $content_emails;

    public function __construct($order) {
        $this->order = $order;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {   
        $this->content_emails = Content::where('section_id', 13)->select(['text_1', 'text_2', 'text_3'])->get();  
        $message = $this->to($this->getTo(config('settings.email_order')))
            ->subject('Orden de compra rechazada en '. config('settings.shop_name') . ' ['.date('d-m-Y H:i').']')
            ->view('emails.shop.declined_order');            
        return $message;
    }

    private function getTo($array_emails){
        $email=str_replace(' ', '', $array_emails);
        $array = explode(',', $email);
        return $array;
      }
}
