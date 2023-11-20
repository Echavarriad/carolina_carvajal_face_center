<?php

namespace App\Repositories;

use App\Models\Product;
/**
 * 
 */
class ProductRepository extends BaseRepository
{
	
	public function __construct(Product $model)
	{
		parent::__construct($model);
	}

	public function search(){
		$query = $this->all();
        $field_form = [];
        if (!empty(request()->get('name'))) {
            $field_form['name'] = request()->get('name');
            $query->where('name', 'LIKE', "%{$field_form['name']}%")->orWhere('sku', 'LIKE', "%{$field_form['name']}");
        } 

        return ['query' => $query, 'field_form' => $field_form]; 
	}
}