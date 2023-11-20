<?php

namespace App\Shop\Repositories;

use App\Models\{Order, OrderItem, Coupon, User, Variation, Product};
use Illuminate\Support\Facades\DB;

class OrderRepository{

	protected $modelOrder;
	protected $orderItem;
	protected $user;
	protected $coupon;
	protected $product;
	protected $variation;

	public function __construct(Order $order, OrderItem $order_item, User $user, Coupon $coupon, Product $product, Variation $variation){
		$this->modelOrder = $order;
		$this->orderItem = $order_item;
		$this->user = $user;
		$this->coupon = $coupon;
		$this->product = $product;
		$this->variation = $variation;
	}

	public function create(array $data){
		DB::beginTransaction();
		try {
			$data['status_id'] = 1;
			$data['order_date'] = date('Y-m-d H:i:s');
			$data['ip'] = request()->ip();
			if (auth()->user()) {
				$data['customer_id'] = auth()->user()->id;
			}
			$max = Order::max('consecutive_cod');			
			$data = core()->consecutiveOrder($data, $max);

			if (!empty($data['coupon_code'])) {
				$coupon = $this->coupon->whereCode($data['coupon_code'])->first();
				$data['discount_percent'] = $coupon->type_discount == 'percent' ? $coupon->discount : 0.0;
				$coupon->quantity_redeem++;
				$coupon->save();
			}

			$order = $this->modelOrder->create($data);
			$order->address()->create($data['address']);
			foreach ($data['items'] as $item) {
				$this->createItem(array_merge($item, ['order_id' => $order->id]));
			}
		}catch (Exception $e) {
			 	DB::rollBack();
        throw $e;
		}
		DB::commit();
		return $order;
	}

	public function createItem(array $data){
		if (isset($data['product']) && $data['product']) {
			$data['product_id'] = $data['product']->id;
			$data['type_product'] = $data['product']->type_product;
			$data['sku'] = $data['product']->sku;
			$data['image'] = $data['product']->image;
			if($data['type_product'] == 'simple'){
				$this->product::find($data['product']->id)->increment('reserved_stock', $data['quantity']);
			}else{
				$this->variation::where('product_id', $data['product']->id)->where('values', $data['variation_id'])->increment('reserved_stock', $data['quantity']);
			}
			unset($data['product']);
		}

     	return $this->orderItem->create($data);
	}
}