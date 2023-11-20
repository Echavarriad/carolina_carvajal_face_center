<?php 

namespace App\Shop\Repositories;

use Cart;

class UserRepository
{
	
	public static function fillUserCart($user){
		$data = [
			'customer_name' => $user->name,
			'customer_email' => $user->email,
			'customer_document' => $user->document,
			'customer_phone' => $user->phone,
			'customer_mobile' => $user->mobile,
		];
		Cart::updateCart($data);
		if(!empty($user)){
			 	$dataAddress = [
            'name_address' => $user->name_address,
            'address' => $user->address,
            'complement' => $user->complement,
            'state_id' => $user->state_id,
            'state' => $user->state->name,
            'city_id' => $user->city_id,
            'city' => $user->city->name,
            'name_person' => $user->name_person,
        ];
 		Cart::setAddress($dataAddress);
		}
	}
}
