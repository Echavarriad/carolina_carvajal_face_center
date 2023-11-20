<?php

namespace App\Shop;

use App\Models\{ProductAttribute,Variation, Attribute};

class Variations {

		protected $message;

		public function  getAttributes($product){
        $attributes = Attribute::with('options')->orderBy('order')->get();
        if (!empty($attributes)) {
          foreach ($attributes as $key => $value) {
            $count = $product->attributes()->where('attribute_id', $value->id)->count();
            if ($count > 0) {
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
        $results = $attributes;
        return $results;
		}

    public function getVariations($data) {
      ProductAttribute::where('product_id', $data['product_id'])->delete();
      Variation::where('product_id', $data['product_id'])->delete();
      $options = [];
      foreach ($data['data'] as $key => $attr) {
        if ($attr['has_variation']) {
          $order = 1;
          foreach ($attr['options'] as  $option) {
	         	if ($option['add_option'] == 1) {
	         			$data_created = ['value' => $option['name'], 'name_attr' => $attr['name'], 'order' => $order, 'product_id' => $data['product_id'], 'is_hidden' => 0, 'attribute_id' => $attr['id'], 'attribute_option_id' => $option['id']];
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
                    $attr = ProductAttribute::where('value', $opt)->where('product_id', $product_id)->select(['id', 'attribute_option_id'])->first();
                    $values .=$attr->attribute_option_id .'_';
                    $name .= $opt . ' - ';
               }
               $name = substr($name, 0, -2);
               $values = substr($values, 0, -1);
               $insertOpts = [
                    'variation'    => $name,
                    'values'       => $values,
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
}