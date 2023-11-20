<?php

namespace App\Shop\Helpers;

class Price {

    public function getMinimalPrice($product) {
        if ($product->type_product == 'configurable') {
            if ($product->variations()->count() == 0) {
                return 0;
            }
            return $this->getVariantMinPrice($product->variations->where('is_hidden', 0));
        } else {
            if ($this->haveSpecialPrice($product)) {
                return $product->price_special;
            } else {
                return $product->price;
            }
        }
    }

    public function getMaximalPrice($product) {
        if ($product->type_product == 'configurable') {
            if ($product->variations()->count() == 0) {
                return 0;
            }
            return $this->getVariantMaxPrice($product->variations->where('is_hidden', 0));
        } else {
            return $product->price;
        }

    }

    public function haveSpecialPrice($product) {
        if (is_null($product->price_special) || !(float) $product->price_special) {
            return false;
        }
        return true;
    }

    private function getVariantMaxPrice($variations) {
        // dd($variations);
        $max_price= 0;
        foreach($variations as $item){
            if($item->price_special == 0 && $item->price > $max_price){
                $max_price= $item->price;
            }elseif($item->price_special > $max_price){
                $max_price= $item->price_special;
            }
        }

        return $max_price;
    }

    private function getVariantMinPrice($variations) { 
        //dd($variations)       ;
        $min_price= 100000000000000000;
        foreach($variations as $item){
            // if($item->id == 44){
            //     dd($item->price < $min_price);
            // }
            if($item->price_special > 0 && $item->price_special < $item->price && $item->price_special < $min_price){
                $min_price= $item->price_special;
            }elseif($item->price < $min_price){
                $min_price= $item->price;
            }
        }
        //dd($min_price);

        return $min_price;
    }
}