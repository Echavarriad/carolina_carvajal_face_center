<?php

namespace App\Shop\Helpers;

use App\Models\ProductAttribute;
use App\Models\Variation;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Variations {

		protected $message;

		public function  getAttributes($product){
        $count_attributes_checked = 0;
        $attributes = [];
        $records = Category::with('attributes')->whereIn('id', $ids)->get();
        // dd($records);
        foreach ($records as $value) {
          if ($value->attributes()->count() > 0) {
            foreach ($value->attributes as $key => $attr) {
              $attributes[] = $attr;
            }
          }
        }
        if (!empty($attributes)) {
          foreach ($attributes as $key => $value) {
            $count = $product->attributes()->where('attribute_id', $value->id)->count();
            if ($count > 0) {
              $count_attributes_checked++;
              $value->has_variation = 1;
            }
           	foreach ($value->options as $option) {
         		 	$count = $product->attributes()->where('attribute_option_id', $option->id)->count();
         		  if ($count > 0) {
		            $option->add_option = 1;
            	}
           	}
          }
        }
        $results['count_attributes_checked'] = $count_attributes_checked;
        $results['attributes'] = $attributes;
        return $results;
		}

    public function getVariations($data) {
      ProductAttribute::where('product_id', $data['product_id'])->delete();
      ;
      $this->deleteImagesVariations(Variation::where('product_id', $data['product_id'])->get());
      $options = [];
      foreach ($data['data'] as $key => $attr) {
        if ($attr['has_variation']) {
          $order = 1;
          foreach ($attr['options'] as  $option) {
	         	if ($option['add_option'] == 1) {
	         			$data_created = ['value' => $option['name'], 'name_attr' => $attr['name_filter'], 'order' => $order, 'product_id' => $data['product_id'], 'attribute_id' => $attr['id'], 'attribute_option_id' => $option['id']];
	             	ProductAttribute::create($data_created);
	             	$order++;
	         	}               
          }
        }         
      }
      foreach ($data['data'] as  $attr) {
         $records = ProductAttribute::where('attribute_id', $attr['id'])->where('product_id', $data['product_id'])->orderBy('order')->pluck('value', 'id')->toArray();
         if (count($records) > 0) {
           $options[] = $records;
         }       
      }
      if (!empty($options)) {
        $variations = $this->getCombinations($options); 
        $this->createVariations($variations, $data['product_id']);
      }
    }

    private function createVariations($variations, $product_id){
          Variation::where('product_id', $product_id)->delete();          
          $order = 1;
          foreach ($variations as $key => $var) {               
               $insertOpts = [];
               $values = '';
               $name = '';
               foreach ($var as $opt) {
                    $attr = ProductAttribute::where('value', $opt)->where('product_id', $product_id)->select('id', 'attribute_option_id')->first();
                    // var_dump($attr->id);
                    $values .=$attr->attribute_option_id.'_';
                    $name .= $opt . ' - ';
               }
               $name = substr($name, 0, -2);
               $values = substr($values, 0, -1);
               $insertOpts = [
                    'variation'    => $name,
                    'values'       => $values,
                    'price'        => 0.0,
                    'order'        => $order,
                    'is_hidden'    => 0,
                    'product_id'   => $product_id   
               ];
              Variation::create($insertOpts);
              $order++;
               
          }
    }

    private function getCombinations($arrays) { 
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $key => $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    private function deleteImagesVariations($variations){
      foreach($variations as $variation){
        if(!empty($variation->image) && Storage::disk('uploads')->exists($variation->image)){
          unlink('uploads/' . $variation->image);
        }
        $variation->delete();
      }
    }
}