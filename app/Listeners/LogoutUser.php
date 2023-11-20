<?php

namespace App\Listeners;

use Cart;

class LogoutUser {

    public function handle($user) {
    	$cart = Cart::getCart();
			if ($cart) {
    		$cart->update(['shipping_method' => null, 'shipping_rate' => 0]);
    		Cart::forgetUserData();
    	}
    }
}