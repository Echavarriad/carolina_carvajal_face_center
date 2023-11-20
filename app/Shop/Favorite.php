<?php

namespace App\Shop;

use App\Models\Favorite as modelFavorite;

class Favorite{

    protected $customerId;
    protected $favorite;

    public function __construct(modelFavorite $favorite){
        $this->customerId = auth()->check() ? auth()->user()->id : null;
        $this->favorite = $favorite;
    }


    public function addProductToFavorite($productId){
        if(auth()->check()){
            $favorite = $this->favorite->where('product_id', $productId)->where('customer_id', $this->customerId)->first();
            if ($favorite) {
                $favorite->update(['product_id' => $productId]);
            }else{
                $this->favorite->create(['product_id' => $productId, 'customer_id' => $this->customerId]);
            } 
        }else{
            if (session()->has('favorites')) {
                $items = session()->get('favorites'); 
                $items[$productId] = $productId;
                session()->put('favorites', $items);
            }else{
                $items[$productId]= $productId;
                session()->put('favorites', $items);            
            }
        }
    
        return true;
    
    }

    public function removeProductOfFavorite($productId){  
        if(auth()->check()){
            $this->favorite->where('product_id', $productId)->where('customer_id', $this->customerId)->delete();
        }else{
            if (session()->has('favorites')) {
                $items = session()->get('favorites'); 
                unset($items[$productId]);
                session()->put('favorites', $items);
            }
        }   
        
        return true;
    }

    public function countFavorites() {
        if(auth()->check()){
            return $this->favorite->where('customer_id', $this->customerId)->count();
        }else{
            if (session()->has('favorites')) {
                return count(session()->get('favorites'));
            }
        }
        
        return 0;         
    }

    public function getArrayFavorites(){
      $favorites = [];
        if(auth()->check()){
            $favorites = $this->favorite->where('customer_id', $this->customerId)->pluck('product_id', 'product_id')->toArray();
        }else{
            if(session()->has('favorites')) {
                $favorites = session()->get('favorites');
            }
        }
        
        
        return $favorites;
    }

    public function putFavoritesToTableInBD() {
        if(session()->has('favorites')) {
            $favorites = session()->get('favorites');
            foreach($favorites as $value){
                if (!$this->favorite::where('product_id', $value)->where('customer_id', $this->customerId)->first()) {
                    $this->favorite->create(['product_id' => $value, 'customer_id' => $this->customerId]);
                }
            }
        }       
    }

    public function putFavoritesToSession() {
        $favorites = $this->favorite::where('customer_id', $this->customerId)->pluck('product_id', 'product_id')->toArray();
        if($favorites) {
            session()->put('favorites', $favorites);
        }  
    }

    public function forgetSesionFavorites() {
        if (session()->has('favorites')) {
            session()->forget('favorites');
        }               
    }

    public function isProductInFavorite($productId) {
        if(in_array($productId, $this->getArrayFavorites())){
            //dd($productId);
            return true;
        }

        return false;
    }
}