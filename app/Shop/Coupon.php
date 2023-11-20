<?php

namespace App\Shop;

use App\Models\Coupon as CouponData;
use Cart;

class Coupon
{
	protected $coupon;
	private $message;

	function __construct(CouponData $coupon)
	{
		$this->coupon = $coupon;
	}

	public function redeem($code){
		$coupon = $this->coupon->whereCode($code)->first();

		if (!$coupon) {
			$this->message = __('content_vue.enter_coupon_valid');
			return false;
		}

		if (!$coupon->isActive()) {
			$this->message = __('content_vue.coupon_not_active');
			return false;
		}

		if($coupon->limited){
			if($coupon->quantity_redeem == $coupon->quantity){
				$this->message = __('content_vue.coupon_not_available');
				return false;
			}
		}

		if($coupon->customer_id){
			if(!auth()->check()){
				$this->message = __('content_vue.login_used_coupon');
				return false;
			}
			if (auth()->user()->id != $coupon->customer_id) {
				$this->message = __('content_vue.coupon_not_used');
				return false;
			}
		}

		$cart = Cart::getCart();

		if(($cart->grand_total + $cart->discount) < $coupon->min_amount){
			$this->message = __('content_vue.value_min_coupon') . ' ' . core()->currency($coupon->min_amount);
			return false;
		}

		if($cart->items_qty < $coupon->min_quantity){
			$this->message = __('content_vue.quantity_min_coupon') . ' ' .  $coupon->min_quantity;
			return false;
		}

		Cart::setCoupon($coupon);

		$this->message = __('content_vue.apply_coupon');
		return true;

	}

	public function getMessage(){
		return $this->message;
	}
}