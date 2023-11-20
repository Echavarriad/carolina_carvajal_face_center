<?php

namespace App\Listeners;

use App\Models\{Product, Category, ProductCategory};
use App\Shop\Helpers\Price;
use Illuminate\Support\Str;



class ProductListener {
    /**

     * Create the event listener.
     *
     * @return void
     */
    protected $helperPrice;

    public function __construct(Price $helperPrice) {

        $this->helperPrice = $helperPrice;

    }

	public function created($order) {

    }

    public function updated($product) {
        if ($product->type_product == 'configurable'  && $product->variations->count() > 0) {
            $price = $this->helperPrice->getMinimalPrice($product);
            $price_max = $this->helperPrice->getMaximalPrice($product);
            $data['price'] = $price;
            $data['price_special'] = $price;
            $data['price_max'] = $price_max;
            $data['price_min'] = $price;
           
        }else{
            $data['price_max'] = $product->price_special > 0 ? $product->price_special : $product->price;
            $data['price_min'] = $product->price_special > 0 ? $product->price_special : $product->price;;
        }
        Product::find($product->id)->update($data);
        

        //Contar los productos por categoría
        $categories= Category::where('parent_id', '>', 0)->get();
        foreach($categories as $category) {
            $count= ProductCategory::where('category_id', $category->id)->count();
            $category->update(['count_products' => $count]);
        }
    }

}

