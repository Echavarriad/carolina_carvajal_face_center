<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use App\Models\Attribute;
use App\Models\Variation;
use App\Models\ProductAttribute;
use App\Models\Product;
use App\Shop\Helpers\Variations;
use Illuminate\Support\Facades\Storage;

class VariationController extends Controller {

    protected $variation;
    protected $productId;

    public function __construct(Variations $variation) {
        $this->variation= $variation;
    }

    public function get_settings_variations(){
      $this->productId= request()->product_id;
      $product = Product::with(['attributes','variations'])->findOrFail($this->productId);
      // $results =  $this->variation->getAttributes($product);
      $attributes = Attribute::with('options')->order()->get();
      $attributes= $this->getFormattedAttributes($product, $attributes);
      $countAttributesChecked = 1;

      return response()->json(['attributes' => $attributes, 'countAttributesChecked' => $countAttributesChecked]);
    }

    public function update_variation(){
        $data = request()->all();
        $this->productId = $data['product_id'];
        $product = [];
        if ($data['action'] == 'delete') {

            $message= $this->actionIsDeleteVariation($data);

        }elseif($data['action'] == 'add'){

          $message= $this->actionIsAddVariation($data);

        }elseif($data['action'] == 'delete-all'){

          $message= $this->actionIsDeleteAllVariations($data);

        }elseif($data['action'] == 'update'){
          $message= $this->actionIsUpdateVarition($data);
        }else{
          $message= $this->actionIsUpdateAllVariations($data);
          $product = Product::with(['attributes','variations'])->findOrFail($this->productId);
        }

        $record = Product::findOrFail($this->productId);
        $record->update(['type_product' => 'configurable']);
  
        return response()->json(['status' => true, 'message' => $message, 'product' => $product]);
      }
  
      public function  delete_create_variation(){
        $data = request()->all();
        $this->variation->getVariations($data);
        $product = Product::with(['attributes','variations'])->findOrFail($data['product_id']);
        $product->update(['type_product' => 'configurable']);
        foreach ($product->variations as $variation) {
            $variation->update = false;
        }
        $attributes = Attribute::with('options')->order()->get();
        $attributes= $this->getFormattedAttributes($product, $attributes);
      
        return response()->json(['status' => true, 'message' => 'Actualización exitosa', 'attributes' => $attributes, 'product' => $product]);
    }
    
  //Save prices desde componente variaciones
    public function save_product_simple(){
      $data = request()->all();
      $object = Product::find($data['id']);
      $object->fill($data);
      $object->save();
      $variations= Variation::where('product_id', $data['id'])->get();
      foreach($variations as $variation){
        if(!empty($variation->image) && $variation->image != 'null' && Storage::disk('uploads')->exists($variation->image)){
          unlink('uploads/' . $variation->image);
        }
        $variation->delete();
      }
      ProductAttribute::where('product_id', $data['id'])->delete();
      Event::dispatch('product.updated', $object);
      $product = Product::with(['attributes','variations'])->findOrFail($data['id']);

      return response()->json(['status' => true, 'message' => 'Datos actualizados exitósamente', 'product' => $product]);
    }

    private function actionIsDeleteVariation($data){
        $ids = explode('_', $data['data']['values']); 
        Variation::where('values', $data['data']['values'])->where('product_id', $this->productId)->update($data['data']);
        $this->updateIsHiddenAttributtes($ids, true);

        return 'Variación eliminada exitósamente';
    }

    private function actionIsAddVariation($data){
      foreach ($data['data'] as $value) {
        $ids = explode('_', $value['values']);
        Variation::where('values', $value['values'])->where('product_id', $this->productId)->update(['is_hidden' => 0]);
        $this->updateIsHiddenAttributtes($ids, false);
      }  

      return 'Variaciones agregadas exitósamente';
    }

    private function actionIsDeleteAllVariations($data){
      foreach ($data['data'] as $value) {
        $ids = explode('_', $value['values']);
        Variation::where('values', $value['values'])->where('product_id', $this->productId)->update(['is_hidden' => 1]);
        $this->updateIsHiddenAttributtes($ids, true);
      }        
      return 'Variaciones eliminadas exitósamente';
    }

    private function actionIsUpdateVarition($data){
      unset($data['data']['update']);
      Variation::where('values', $data['data']['values'])->where('product_id', $this->productId)->update($data['data']);

      return 'Variación actualizada exitósamente';
    }

    private function actionIsUpdateAllVariations($data){
        $price = $data['data']['price'];
        $price_special = $data['data']['price_special'];
        $stock = $data['data']['stock'];
        if($price != null && $price >= 0){
          $data_update['price'] = $price;
        }
        if($price_special != null){
          $data_update['price_special'] = $price_special;
        }
        if($stock != null && $stock >= 0){
            $data_update['stock'] = $stock;
        }
        Variation::where('product_id', $this->productId)->where('is_hidden', 0)->update($data_update);

        return 'Se actualizaron todas las variaciones';
    }

    private function updateIsHiddenAttributtes($ids, $bool){
        ProductAttribute::where('product_id', $this->productId)->whereIn('attribute_option_id', $ids)->update(['is_hidden' => $bool]);
    }

    private function getFormattedAttributes($product, $attributes){
      foreach($attributes as $attr){
          $count = $product->attributes()->where('attribute_id', $attr->id)->count();
            if ($count > 0) {              
              $attr->has_variation = 1;
            }
          foreach ($attr->options as $option) {
            $count = $product->attributes()->where('attribute_option_id', $option->id)->count();
          if ($count > 0) {
              $option->add_option = 1;
            }
        }
      }

      return $attributes;
    }

}