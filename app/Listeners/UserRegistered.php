<?php



namespace App\Listeners;



use App\Mail\{UserRegisteredMail, SendNewsletter};
use App\Models\FormNewsletter;
use Mail;



class UserRegistered {

    

    public function __construct() {
    }

    public function registered($user) {
        Mail::send(new UserRegisteredMail($user));
    }

    public function newsletter($user) {
        $record = FormNewsletter::whereEmail($user->email)->first();
        if(!$record){
            $data['email'] = $user->email;
            $send = Mail::send(new SendNewsletter($data));
            if ($send) {
                FormNewsletter::create($data);
            }
        }
        
    }

}

