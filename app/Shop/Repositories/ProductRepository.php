<?php

namespace App\Shop\Repositories;

use App\Models\{Product};
use App\Shop\Helpers\Price;
use Illuminate\Support\Str;

class ProductRepository{

    protected $priceHelper;

	public function __construct() {
        $this->priceHelper = new Price;

    }

	public function prepareProducts($products){
			foreach ($products as $key => $product) {
					$product = $this->prepareProduct($product);
			}
			return $products;
	}

	public function prepareProduct($product) {
        $tax = config('settings.tax') ?: 0;
        $product->category_main = false;
        if (!$product->categories->isEmpty()){
               $product->category_main = $product->categories()->first()->title;
               $product->category_slug = $product->categories()->first()->slug;          
        }
        $product->price = $this->priceHelper->getMinimalPrice($product);
        $product->tax_amount = $product->price * ($tax/100);
        return $product;
    }

	private function setPrices($product) {
		if (!empty($product->variation)) {
            $product->price = $product->variation->price;
        }
	}

	
}