<?php

namespace App\Shop\Helpers;

use App\Models\{Attribute, AttributeOption};

class ConfigVariations{

    public function getConfigurationConfig($product) {
            $query = AttributeOption::selectRaw('attribute_options.id , attribute_options.attribute_id , attribute_options.name')
                    ->orderBy('attribute_options.order')
                ->join('product_attributes', 'product_attributes.attribute_option_id', '=', 'attribute_options.id')
                // ->where('product_attributes.is_hidden', '=', 0)
                ->groupBy('attribute_options.id', 'attribute_options.attribute_id', 'attribute_options.name');
                $query->where('product_attributes.product_id', $product->id);
            $options = $query->get()->toArray();
            $attributes = Attribute::all()->toArray();
            $attrs = collect();
            foreach ($attributes as $key => $value) {
                $opts = [];
                foreach ($options as $item) {
                    if ($item['attribute_id'] == $value['id']) {
                    $opts[] = $item;
                    }
                }
                if ($opts) {
                    $attributes[$key]['options'] = $opts;
                    $attrs->push($attributes[$key]);
                }
                
            }
        return $attrs;
    }

    public function getOptions($attributes, $variations) {
        $optionsConfig = [];
        $attributes = $this->orderedAttributtes($attributes);
        foreach ($attributes as $key => $attr) {
            $opts = [];
            foreach ($attr['options'] as $k_opt => $opt) {
                // dd($opt);exit;
                if ($this->hasOptionVariation($opt['id'], $variations)) {
                    $opt['selected'] = false;
                    $opt['disabled'] = false;
                    $opts[] = $opt;
                }
            }
            $optionsConfig[] = [
                'id' => $attr['id'],
                'name' => $attr['name'],
                'type' => $attr['type'],
                'options' => $opts,
            ];
        }

        return $optionsConfig;
    }

    public function orderedAttributtes($attributes){
        $attributes_ordered = [];
         foreach ($attributes as $key => $attr) {
            $count = count($attr['options']);
            $options= [];
            for ($i=0; $i < ($count-1); $i++) {
                // dd($attr['options'][$i]);
                for ($j= ($i+1); $j < $count ; $j++) { 
                    if ($attr['options'][$j]['order'] < $attr['options'][$i]['order']) {
                        $aux = $attr['options'][$i];
                        $attr['options'][$i] = $attr['options'][$j];
                        $attr['options'][$j] = $aux;
                    }
                    
                 }
            }
            $attributes_ordered[] = $attr;
         }
        return $attributes_ordered;        
    }

    public function getConfigVariations($product) {
        $variations = $product->variations()->where('is_hidden', 0)->get();
        $index= !empty($product->image) ? 1 : 0;
        foreach ($variations as $key => $var) {
            $var->price = core()->currency($var->price);
            $var->index= $index;
            $index++;
        }
        return $variations;
    }

    private function hasOptionVariation($id_opt, $variations) {
        foreach ($variations as $key => $variation) {
            foreach ($variation['details'] as $item) {
                if ($item['attribute_option_id'] == $id_opt) {
                    return true;
                }
            }
        }
        return false;
    }
}