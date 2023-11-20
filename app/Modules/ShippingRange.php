<?php

namespace App\Modules;

use Illuminate\Support\Str;
use App\Shop\Facades\Cart;
use App\Models\Shipping;

class ShippingRange
{		

    public static function calculate($module, $address){
        $codeDane = $address->code_dane;
        $valueBuy = Cart::totalValueBuy();
        $whereData = [['code_dane', $codeDane], ['value_min_buy', '<=', $valueBuy]];
        $resultShipping= Shipping::where($whereData)->orderBy('value_min_buy', 'DESC')->first();
        
        $data = [
            'success' => false,
            'total' => null,
            'name' => null,
            'description' => null,
            'shipping_free' => false
        ];
        if ($resultShipping) {
            $data['success'] = true;
            $data['total'] = $resultShipping->shipping_fee;
            $data['name'] = $module->name;
            $data['description'] = $module->description;
            $data['shipping_free'] = $resultShipping->shipping_fee == 0;

        }
       

        return (Object) $data;
    }
}