<?php


namespace App\Listeners;


use App\Mail\ChangeStatusDevolutionMail;
use Illuminate\Support\Facades\Mail;

class ChangeStatusDevolution {


    public function __construct() {

        //

    }

    public function handle($event) {
        try {
            Mail::queue(new ChangeStatusDevolutionMail($event->data));
        } catch (\Exception $event) {
        	
        }

    }

}

