<?php

	namespace App\Shop;

	use App\Models\Cart as CartData;
	use App\Models\CartItem;
	use App\Models\Product;
	use App\Models\Variation;
	use App\Models\Coupon as ModelCoupon;
	use App\Shop\Repositories\ProductRepository;
	use Illuminate\Support\Arr;
	
class Cart
{
	protected $cart;
	protected $product;
	protected $cartItem;
	protected $variation;

	public function __construct(CartData $cart, CartItem $cartItem, Product $product, Variation $variation) {
        $this->cart 	= $cart;
        $this->cartItem = $cartItem;
        $this->product 	= $product;
        $this->variation 	= $variation;
    }

	public function add($data){
		// $this->removeCart();dd('remove');			
		$product_id = $data['product'];
		$cart = $this->getCart();
		if ($cart) {
			$ifExist = $this->checkIfItemExists($product_id, $data);
			if ($ifExist) {
				$this->cartItem->find($ifExist);				
				$result = $this->updateItem($ifExist, $data);
			}else{				
				$result = $this->createItem($product_id, $data);
			}				
		}else{
				$result = $this->createAll($product_id, $data);
		}
		return $result;
	}
		
	public function createAll($product_id, $data){
		$cart_data = [];
		$cart_data['ip'] = request()->ip();
		$cart_data['session_id'] = session()->getId();
		$cart_data['last_activity'] = time();
		$result =  $this->cart->create($cart_data);
		$this->putCart($result);
		if ($result) {
			if($item = $this->createItem($product_id, $data)){
				return $item;
			}else{
				return false;
			}
		}
	}

	public function createItem($product_id, $data){
		$product = $this->product->find($product_id);
		$variation = null;
		$variationId = null;
		$name_product = $product->name;

		if ($product->type_product == 'configurable') {
			if (!isset($data['variation']) || !$data['variation']) {
				return false;
			}
			$variation = $this->variation->where('values', $data['variation'])->where('product_id', $product_id)->first();
			$canAdd = $variation->haveSufficientStock($data['quantity']);
			if (!$canAdd) {
				session()->flash('shop.warning', 'No hay suficiente stock del producto');
				return false;
			}
			$variationId = $variation->values;
			$name_product = $product->name . ' - ' . $variation->variation;
		} else {
			$canAdd = $product->haveSufficientStock($data['quantity']);
			if (!$canAdd) {
				session()->flash('shop.warning', 'No hay suficiente stock del producto');
				return false;
			}
		}

		$quantity = $data['quantity'];			
		$product->variation = $variation;
		$product->quantity = $quantity;
		$product = (new ProductRepository)->prepareProduct($product);
		$price = $product->price;

		$data_item = [
			'quantity' 			=> $quantity,
			'cart_id'			=> $this->getCart()->id,
			'name'				=> $name_product,
			'sku'				=> $product->sku,
			'image' 			=> $product->image_main,
			'price'				=> $price,
			'total'				=> $price * $quantity,
			'tax_amount'        => $product->tax_amount * $quantity,
			'weight'			=> $product->weight,
			'total_weight'		=> $product->weight * $quantity,
			'type'				=> $product->type_product,
			'sku'				=> $product->sku,
			'product_id'		=> $product->id,
			'variation_id'		=> $variationId,
			'url_product'		=> route('product', $product->slug),
		];

		$item = $this->cartItem->create($data_item);
		$this->updateLastActivity();
		$this->updateShippingFree();

		return $item;
	}

	public function updateItem($item_id, $data, $action = 'product'){
		$item = $this->cartItem->find($item_id);
		$product = $this->product->where('id', $item->product_id)->first();
		if ($action == 'cart') {
			$quantity = $data['quantity'];
		}else{
			$quantity = $data['quantity'] + $item->quantity;
		}
		$variation = null;	
		if ($item->type == 'configurable') {
			$variation = $this->variation->where('values', $data['variation'])->where('product_id', $product->id)->first();
			if (!$variation->haveSufficientStock($quantity)) {
				return false;
			}
        } else {
            if (!$product->haveSufficientStock($quantity)) {
                return false;
            }
        }
    	// $this->updateStockProduct($product->codigo, $data['quantity']);
		$product->variation = $variation;
        $product = (new ProductRepository)->prepareProduct($product);
        $item->price = $product->price - $product->tax_amount;
        $item->discount_amount = $product->discount_amount;
		$item->update([
			'quantity'		    => $quantity,
			'total'			    => $item->price * $quantity,
			'base_total'		=> $item->price * $quantity,
			'tax_amount'		=> $product->tax_amount * $quantity,
			'total_weight'		=> $item->weight * $quantity,
		]);

		$this->collectTotals();

		return true;
	}

	public function removeItem($itemId) {
        if ($cart = $this->getCart()) {
            $item = $this->cartItem->find($itemId);
            $item->delete();
            //Eliminar el carrito sino hay mas items
            if ($cart->items()->get()->count() == 0) {
                $this->removeCart();
            }
            return true;
        }
        return false;
    }

	public function collectTotals(){
		if(!$cart = $this->getCart()){
			return false;
		}
		
		$cart->grand_total = 0;
		$cart->sub_total = 0;
		$cart->tax_total = 0;

		foreach ($cart->items as $item) {
			$cart->grand_total += ($item->total);
			$cart->sub_total += $item->total;
			$cart->tax_total += $item->tax_amount;
		}
		$discount = 0;
		if($coupon = $this->hasCoupon()){
			if ($coupon->type_discount == 'percent') {
				$discount += ($coupon->discount * $cart->sub_total) / 100;
			}else{
				$discount += $coupon->discount;
			}
		}
		$cart->discount = $discount; 
		
		if ($cart->shipping_rate) {
			$cart->grand_total = (float) $cart->grand_total + $cart->shipping_rate;
		}

		$cart->grand_total = $cart->grand_total - $discount;
		$quantities = 0;
		foreach ($cart->items as $item) {
			$quantities += $item->quantity;
		}

		$cart->items_count   = $cart->items->count();
		$cart->items_qty 	 = $quantities;
		$cart->last_activity = time();

		$cart->save();

		$this->updateShippingFree();
	}

	private function updateShippingFree(){			
		$limit_shipping_free= config('settings.limit_shipping_free');
		if($limit_shipping_free > 0){
			$cart = $this->getCart();
			$cart->shipping_free= 0;
			if($cart->sub_total >= $limit_shipping_free){
				$cart->shipping_free= 1;
				$cart->shipping_rate= 0;
				$cart->grand_total= $cart->sub_total - $cart->discount;
			}
			$cart->save();
		}
	}

	public function updateStockProduct($product_id, $quantity){
		$this->product->where('id', $product_id)->increment('stock', ($quantity-1));
		$this->product->where('id', $product_id)->decrement('stock', $quantity);

		
	}

	public function checkIfItemExists($product_id, $data){
		$items = $this->getCart()->items;
		foreach ($items as $item) {
			if ($product_id == $item->product_id) {
				if ($item->type == 'simple') {
			return $item->id;
		}else {
		// dd($data, $item->variation_id);
			if ($product_id == $item->product_id && $item->variation_id == $data['variation']) {
				return $item->id;
			}
		}
			}
		}
		return 0;
	}

		public function putCart($cart){
			session()->put('cart', $cart);
		}

		public function getCart(){
			$cart = false;
			if (session()->has('cart')) {
				$cart = $this->cart->with(['items', 'address'])->find(session()->get('cart')->id);
			}

			return $cart;
		}

		public function updateCart($data, $calculate = false){
			$cart = $this->cart->find(session()->get('cart')->id);
			$cart->fill($data);
			$cart->save();
			if ($calculate) {
				$this->collectTotals();
			}
		}

		public function totalValueBuy() {
			return $this->getCart()->items->sum('total');
		}
    
		public function removeCart(){
			$cart = $this->getCart();
			if ($cart != null) {
				if($cart->address){
					$cart->address()->delete();
				}

				if($cart->items){
					$cart->items()->delete();
				}
				$cart->delete();

				if (session()->has('cart')) {
					session()->forget('cart');					
				}
				if (session()->has('coupon')) {
					session()->forget('coupon');					
				}
			}
		}

		public function setAddress($data_address){
			$cart = $this->getCart();
			if(!$cart->address){
				$address = $cart->address()->create($data_address);
			}else{
				$cart->address()->update($data_address);
				$address = $cart->address;
			}
			return $address;
		}

        public function getAddress() {
            $fields = ['address', 'complement', 'code_dane', 'state_id', 'city_id'];
            return $this->cart->find(session()->get('cart')->id)->address()->select($fields)->first();
        }

		public function forgetUserData() {
        $cart = $this->getCart();
        if ($cart->address) {
            $cart->address()->delete();
        }
        $data = [
            'customer_name' => null,
            'customer_lastname' => null,
            'customer_mobile' => null,
            'customer_email' => null,
            'customer_type_document' => null,
            'customer_document' => null,
        ];        
        $cart->update(['step' => 'cart']);
        $this->updateCart($data);
    }

		public function count() {
	 	 	if (isset($this->getCart()->items_qty)) {
	 	 			$this->updateLastActivity();
          return $this->getCart()->items_qty;        
      }
      return 0;	 		
		}

	public function exists() {
        if (session()->has('cart') && $this->getCart()) {
            return true;
        }
        return false;
    }

    public function setCoupon($coupon){
    	$this->updateCart(['coupon_code' => $coupon->code, 'has_coupon' => 1]);
    	session()->put('coupon', $coupon);
    	$this->collectTotals();
    }

    public  function removeCoupon(){
    	if (session()->has('coupon')) {
    		$this->updateCart(['coupon_code' => null, 'has_coupon' => 0, 'discount' => 0.0]);
    		session()->forget('coupon');
    		$this->collectTotals();
    	}
    	return true;
    }

    public function hasCoupon(){
    	$coupon = null;
    	if (session()->has('coupon')) {
			$coupon = session()->get('coupon');
    	}
    	return $coupon;
    }

    public function setShipping($carrier){
    	$cart = Cart::getCart();
        $cart->shipping_method = $carrier->name;
    	$cart->shipping_description = $carrier->description;
        $cart->shipping_rate = $carrier->total;
		$cart->shipping_free= $carrier->shipping_free;
    	$cart->save();
    	$this->collectTotals();

    	return true;
    }

    public function getUser() {
        $fields = ['customer_email', 'customer_name', 'customer_lastname', 'customer_type_document', 'customer_document', 'customer_mobile'];
        return $this->cart->find(session()->get('cart')->id, $fields);
    }

    public function setCustomer($data) {
        $this->cart->find(session()->get('cart')->id)->update($data);
    }

    public function setPaymentMethod($gateway){
    	$cart = Cart::getCart();
    	$cart->payment_method = $gateway['name'];
    	$cart->save();
    	return true;
    }

    public function hasError(){
    	if (!$this->getCart()) {
    		return true;
    	}
    	if (!$this->isItemsHaveSufficientQuantity()) {
    		return true;
    	}

    	return false;
    }

    public function isItemsHaveSufficientQuantity(){
    	foreach ($this->getCart()->items as $item) { 
    		if(!$this->isItemHaveQuantity($item)){
    			return false;
    		}
    	}
    	return true;
    }

    public function isItemHaveQuantity($item){ 
    	if ($item->type == 'configurable') {
    		$variation = $this->variation->where('values', $item->variation_id)->where('product_id', $item->product->id)->first();
    		$product = $variation;
    	}else{
    		$product = $item->product;
    	}
    	if (!$product->haveSufficientStock($item->quantity)) {
    		return false;
    	}
    	return true;
    }

    public function prepareDataForOrder(){
    	$data = $this->toArray();
    	$finalData = [
			'customer_email'		=> $data['customer_email'],
			'customer_name'         => $data['customer_name'],
			'customer_lastname'	    => $data['customer_lastname'],
			'customer_document'	    => $data['customer_document'],
			'customer_type_document'=> $data['customer_type_document'],
			'customer_mobile'		=> $data['customer_mobile'],
			'coupon_code'			=> $data['coupon_code'],
			'shipping_method'       => $data['shipping_method'],
			'shipping_description'	=> $data['shipping_description'],
			'shipping_rate'			=> $data['shipping_rate'],
			'payment_method'	  => $data['payment_method'],
			'total_item_count'	  => $data['items_qty'],
			'total_qty_ordered'	  => $data['items_count'],
			'discount_amount'	  => $data['discount'],
			'sub_total'			  => $data['sub_total'],
			'tax_amount'		  => $data['tax_total'],
			'grand_total'		  => $data['grand_total'],
			'apply_tax'           => $data['apply_tax'],
			'shipping_free'       => $data['shipping_free'],
			'customer_id'         => $data['customer_id'],
			'address' 		      => Arr::except($data['address'], ['id', 'cart_id', 'name_address']),
			'cart_id' 			  => $data['id'],
    	];
    	foreach ($data['items'] as $item) {
    		$finalData['items'][] = $this->prepareDataForOrderItem($item);
    	}

    	return $finalData;
    }

    public function prepareDataForOrderItem($data) {
        $finalData = [
			'product'		=>$this->product->find($data['product_id']),
            'name' 		    => $data['name'],
            'quantity' 	    => $data['quantity'],
            'tax_amount' 	=> $data['tax_amount'],
            'price' 		=> $data['price'],
            'total' 		=> $data['total'],
            'url_product' 	=> $data['url_product'],
            'total_weight' 	=> $data['total_weight'],
            'variation_id' 	=> $data['variation_id'],
        ];

        return $finalData;
    }

    public function toArray(){
    	$cart = $this->getCart();
    	$data = $cart->toArray();
    	$data['address'] = $cart->address->toArray();
    	$data['items'] = $cart->items->toArray();
    	return $data;
    }

    public function updateLastActivity(){
    	if($cart = $this->getCart()){
				$cart->last_activity = time();
				$cart->save();
			}
    }

    public function getCartFormatted(){
    	$cart = json_encode([]);
    	if ($this->getCart()) {
			$cart = $this->getCart()->formatted();
    	}
    	return $cart;
    }
}