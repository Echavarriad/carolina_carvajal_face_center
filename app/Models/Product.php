<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    public $timestamps = false;
    protected $guarded = [];

    public function scopeOrder($query){
        return $query->orderBy('order');
    }

    public function scopePublished($query){
          return $query->where('products.published', 1)->order();
    }

    public function scopeFeatured($query){
          return $query->published()->where('featured', 1)->order();
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    public function relateds() {
        return $this->hasMany(ProductRelated::class)->orderBy('order');
    }

    public function variations() {
        return $this->hasMany(Variation::class)->orderBy('order');
    }

    public function attributes() {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->with('options')->orderBy('order');
   }

    public function gallery() {
       return $this->hasMany(ProductGallery::class)->orderBy('order');
    }

    public function relateds_published() {
        $data = $this->relateds()->with('product')->whereHas('product', function($query) {
                $query->where('published', 1);
            })->get();
        $collect = collect();
        foreach ($data as $item) {
            $collect->push($item->product);
        }
        return $collect;
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'product_categories')->orderBy('_lft');
    }

    public function priceProductSimple() {
        $html= '';
        if ($this->price_special > 0) {
            $html .= '<h5>' . core()->currency($this->price_special) . '</h5>';
            $html .= '<h6 class="previous_price">' . core()->currency($this->price) . '</h6>';
        }else{
            $html .= '<h5>' . core()->currency($this->price) . '</h5>';
        }
        
        return $html ;
    }

    public function price() {
        $price = $this->price_max;
        if ($this->price_min <  $this->price_max ) {
            return core()->currency($this->price_min);
        }

        

        return core()->currency($price);
    }

    public function category_main(){
        $category = false;
        if ($this->categories()->count() > 0) {
            $category = $this->categories()->where('parent_id', null)->first();
        }
        
        return $category;
    }

    public function subcategory(){
        $category = false;
        if ($this->categories()->count() > 0) {
            $category = $this->categories()->where('parent_id', '!=', null)->first();
        }
        return $category;
    }

    public function create_route(){
        if($this->category_main() && $this->subcategory()){
            return route('product.subcat', [$this->category_main()->slug, $this->subcategory()->slug, $this->slug]);
        }

        return route('product', $this->slug);
    }

    public function haveSufficientStock($quantity){
        if ($quantity > $this->stock) {
           return false;
        }
        return true; 
    }

    public function getImageMainAttribute() {
        return asset('uploads/' . $this->image);
    }

    public function scopeQueryInitial($query){
        $query->published()->where('products.published', 1)->selectRaw('products.*');
        $query->leftJoin('variations', 'variations.product_id', '=', 'products.id');
        $query->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id');
        $query->leftJoin('product_attributes', 'products.id', '=', 'product_attributes.product_id');
        $query->leftJoin('categories', 'categories.id', '=', 'product_categories.category_id');

        return $query;
    }

    public function scopeFilterPrice($query, $min_price, $max_price){
        $query->where(function ($q) use ($min_price, $max_price) {   
            $q->where('products.price_min', '>=', $min_price)
            ->where('products.price_min', '<=', $max_price);            
        });
        return $query;
    }

    public function scopeFilterAttributes($query, $attrs){
        $query->where('variations.is_hidden',0); 
        foreach ($attrs as $value) {
          $query->where(function ($q) use ($value) {   
            $q->where('variations.values', 'LIKE', "%$value%");            
          });
        }
        return $query;
    }
    
}