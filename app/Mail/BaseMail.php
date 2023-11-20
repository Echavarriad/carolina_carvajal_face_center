<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\Content;

class BaseMail extends Mailable {
    use Queueable, SerializesModels;
    


    protected $content_emails;
    
    public function __construct() {
        $this->content_emails = Content::where('section_id', 6)->select(['text_1', 'text_2', 'text_3', 'text_4'])->get();
    }

    public function getContentMail(){
        return $this->content_emails;
    }
}
