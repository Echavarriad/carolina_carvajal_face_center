<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\Customer\{NewOrderCustomerMail, PaidOrderCustomerMail, DeclinedOrderCustomerMail, SendOrderCustomerMail};
use App\Mail\Shop\{NewOrderShopMail, PaidOrderShopMail};
use App\Mail\DeclinedOrderShopMail;
use App\Models\{Product, Variation, SiigoUpdateStock};
use App\Api\Facades\Siigo;


class Order {

	public function __construct() {
        
    }

    public function created($order) {
        //Mail::send(new NewOrderCustomerMail($order));
        Mail::send(new NewOrderShopMail($order));

    }

    public function paid($order) {
        Mail::send(new PaidOrderCustomerMail($order));
        Mail::send(new PaidOrderShopMail($order));
        $selfIdsProducts= [];
        foreach ($order->items as  $item) {
            if($item->type_product == 'simple'){
                Product::find($item->product_id)->decrement('reserved_stock', $item->quantity);
                Product::find($item->product_id)->decrement('stock', $item->quantity);   
                SiigoUpdateStock::create([
                    'code' => $item->sku,
                    'quantity' =>  $item->quantity
                ]);       
            }else{
                Variation::where('product_id', $item->product_id)->where('values', $item->variation_id)->decrement('reserved_stock', $item->quantity);
                Variation::where('product_id', $item->product_id)->where('values', $item->variation_id)->decrement('stock', $item->quantity);
                if(!in_array($item->product_id, $selfIdsProducts)){
                    $selfIdsProducts[$item->product_id]= $item->product_id;
                    $sumQuantity= $order->items()->where('product_id', $item->product_id)->sum('quantity');
                    SiigoUpdateStock::create([
                        'code' => $item->sku,
                        'quantity' =>  $sumQuantity
                    ]); 
                } 
            }
        }
    }

    public function declined($order) {
        Mail::send(new DeclinedOrderCustomerMail($order));
        foreach($order->items as $item){
            if($item->type_product == 'simple'){
                Product::find($item->product_id)->decrement('reserved_stock', $item->quantity);
            }else{
                Variation::where('product_id', $item->product_id)->where('values', $item->variation_id)->decrement('reserved_stock', $item->quantity);
            }
        }
        // Mail::send(new DeclinedOrderShopMail($order));

    }

    public function send($order) {
        Mail::send(new SendOrderCustomerMail($order));
    }
}