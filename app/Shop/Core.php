<?php

namespace App\Shop;

class Core{

    public function currency($amount = 0, $decimal= 0) {        
        if($decimal > 0){
            $number_without_decimals= substr($amount, 0, -2);
            $last_digits= substr($amount, -2);
            return '$' . number_format($number_without_decimals, 0, ',', '.') . ',' .  $last_digits;
        }else{
            $amount = round($amount);
        }

        return '$' . number_format($amount, $decimal, ',', '.');
    }

    public function consecutiveOrder($data, $max) {
        if ($max > 0) {
            $consecutive_cod = $max + 1;
        }else{
            $consecutive_cod = 1;
        }
        $data['consecutive_cod'] = $consecutive_cod;
        $data['reference'] = $this->getConsecutive($consecutive_cod);

        return $data;
    } 

    public function getMonthsLarge(){
        return array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    }

    private function getConsecutive($consecutive){
        $prefix = config('settings.prefix_reference');
        $consecutive = $prefix . str_pad($consecutive, 8, "0", STR_PAD_LEFT);

        return $consecutive;
    }

    public function parseUrlFilter($category, $subcategory, $attr, $option) {
        $params = request()->input();
        if($subcategory){
            $fields = array_merge($params , [$category->slug, $subcategory->slug, $attr->slug => $option['name']]);
            $route = route('products.subcat', $fields);
        }elseif($category){
            $fields = array_merge($params , [$category->slug, $attr->slug => $option['id']]);
            $route = route('products.cat', $fields);
        }else{
            $fields = array_merge($params , [$attr->slug => $option['id']]);
            $route = route('products', $fields);
        }

        

        return $route;
    }
}