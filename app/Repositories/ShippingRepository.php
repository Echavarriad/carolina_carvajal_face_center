<?php

namespace App\Repositories;

use App\Models\Shipping;


class ShippingRepository extends BaseRepository
{
	
	public function __construct(Shipping $model)
	{
		parent::__construct($model);
	}

	public function active() {
		$object = $this->get(request()->id);
		$message = '';
		if ($object->is_active == 1) {
			$message = 'El registro NO está activo.';
			$object->is_active = 0;
			$type = 'warning';
		} else {
			$message = 'El registro está activo';
			$object->is_active = 1;
			$type = 'success';
		}
		$object->save();
		
		return ['status' => true , 'message'=> $message, 'type' => $type];  
	}

}